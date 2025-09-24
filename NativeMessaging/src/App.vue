<template>
  <Page @loaded="onPageLoaded" class="page">
    <ActionBar title="WebSocket Echo Test" class="action-bar" />

    <ScrollView>
      <StackLayout class="container">

        <!-- Connection Status -->
        <GridLayout columns="*, auto" class="status-card" :class="connectionStatusClass">
          <StackLayout col="0">
            <Label :text="connectionStatusText" class="status-title" />
            <Label v-if="lastMessage" :text="`Last Message: ${lastMessage}`" class="status-subtitle" />
          </StackLayout>
          <Label col="1" :text="isConnected ? '🟢' : '🔴'" class="status-icon" />
        </GridLayout>

        <!-- Server Health Controls -->
        <StackLayout class="websocket-section">
          <Button text="🌐 Check Server Health" @tap="checkServerHealth" class="btn btn-connect" />
        </StackLayout>

        <!-- WebSocket Controls -->
        <StackLayout class="websocket-section">
          <Button text="🔗 Connect WebSocket" @tap="connectWebSocket" :isEnabled="!isConnected" class="btn btn-connect" />
          <Button text="🔌 Disconnect WebSocket" @tap="disconnectWebSocket" :isEnabled="isConnected" class="btn btn-disconnect" />
        </StackLayout>

        <!-- Message Display -->
        <StackLayout class="message-section">
          <Label text="📨 Received Message" class="section-title" />
          <Label :text="lastMessage || 'Menunggu mesej...'" class="message-box" textWrap="true" />
        </StackLayout>
        <!-- Error Display -->
        <StackLayout class="message-section">
          <Label text="📨 Received Error" class="section-title" />
          <Label :text="lastError || 'Menunggu error...'" class="message-box" textWrap="true" />
        </StackLayout>

      </StackLayout>
    </ScrollView>
  </Page>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import Echo from 'laravel-echo';
// @ts-ignore
import * as Pusher from 'nativescript-pusher-ns';

const lastMessage = ref('');
const isConnected = ref(false);
const lastError = ref('');

const connectionStatusText = computed(() =>
  isConnected.value ? 'Connected to Laravel Echo' : 'Disconnected'
);

const connectionStatusClass = computed(() =>
  isConnected.value ? 'connected' : 'disconnected'
);

let echo: Echo<any> | null = null;

function connectWebSocket() {
  try {
    const pusher = new Pusher('local', {
      wsHost: '172.20.10.6',
      wsPort: 8080,
      wssPort: 8080,
      forceTLS: false,
      enabledTransports: ['ws'],
      disableStats: true,
    });

    (Pusher as any).logToConsole = true;

    // 👇 letak kat sini untuk monitor state connection
    pusher.connection.bind('state_change', (states: any) => {
      console.log(`🔄 State changed: ${states.previous} -> ${states.previous} -> ${states.current}`);
    });

    // listen connection state
    pusher.connection.bind('connected', () => {
      console.log('✅ Connected to Reverb');
      isConnected.value = true;
    });

    pusher.connection.bind('disconnected', () => {
      console.log('❌ Disconnected from Reverb');
      isConnected.value = false;
    });

    pusher.connection.bind('error', (err: any) => {
      console.log('⚠️ Connection error:', err);
      lastError.value = JSON.stringify(err);
      isConnected.value = false;
    });

    // pass client into Echo
    echo = new Echo({
      broadcaster: 'reverb',
      key: 'local',
      wsHost: '172.20.10.6',
      wsPort: 8080,
      wssPort: 8080,
      forceTLS: false,
      enabledTransports: ['ws'],
      client: pusher,
    });

    echo.channel('public-channel').listen('MessageSent', (data: any) => {
      console.log('📡 Event received:', data);
      lastMessage.value = JSON.stringify(data);
    });

    console.log('🔗 WebSocket status: ', isConnected.value);
  } catch (error) {
    lastError.value = String(error);
    isConnected.value = false;
  }
}


function disconnectWebSocket() {
  if (echo) {
    echo.disconnect();
    echo = null;
    isConnected.value = false;
  }
}

async function checkServerHealth() {
  try {
    console.log("🌐 Checking server health...");

    const response = await fetch("http://172.20.10.6:8000/api/health", {
      method: "GET",
      headers: {
        "Accept": "application/json",
      },
    });

    if (!response.ok) {
      console.log(`❌ Server responded with status: ${response.status}`);
      return;
    }

    const data = await response.json();
    console.log("✅ Server health response:", data);
  } catch (error) {
    console.log("⚠️ Error checking server health:", error);
  }
}


function onPageLoaded() {
  checkServerHealth();
  console.log('📱 Page loaded');
}
</script>

<style scoped lang="scss">
@import '@nativescript/theme/scss/variables/blue';

.page {
  background-color: #fff;
}

.container {
  padding: 16;
}

.status-card {
  margin-bottom: 12;
  padding: 12;
  border-radius: 8;
  background-color: #e0e0e0;
}

.status-title {
  font-size: 18;
  font-weight: bold;
}

.status-subtitle {
  font-size: 14;
  color: #666;
}

.status-icon {
  font-size: 24;
  vertical-align: center;
}

.connected {
  border-color: green;
}

.disconnected {
  border-color: red;
}

.websocket-section {
  margin-top: 16;
}

.message-section {
  margin-top: 24;
}

.message-box {
  font-size: 16;
  padding: 12;
  background-color: #f9f9f9;
  border-radius: 8;
  border-width: 1;
  border-color: #ccc;
  text-align: center;
}

.btn {
  margin: 8 0;
  padding: 12;
  font-size: 16;
  @include colorize($color: accent);
}
</style>
