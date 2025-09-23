# Laravel 12 WebSocket + NativeScript Vue TS (Live Messaging)

This branch is a **real-time chat demo project** using **Laravel Reverb** (Laravel’s official WebSocket server), **Laravel Echo**, and **NativeScript Vue + TypeScript** as the mobile client for **public, private, and group chat**.  

---

## ✨ Features

- ✅ **Laravel 12.x** – Backend API & event broadcasting  
- ✅ **Laravel Reverb** – Official WebSocket server for Laravel  
- ✅ **Laravel Echo** – Frontend listener for real-time events  
- ✅ **NativeScript Vue + TS** – Mobile client (Android/iOS) for chat  
- ✅ **Public Chat** – Open public channel  
- ✅ **Private Chat (1-to-1)** – Direct messaging between two users  
- ✅ **Private Channels** – Authenticated & authorized channels  
- ✅ **Group Chat** – Real-time communication in groups  
- ✅ **Jobs & Events** – Fire events through queued jobs  

---

## 📦 Requirements

### Backend (Laravel + Reverb)
- PHP **^8.2**  
- Composer **^2.7**  
- Node.js **^20.19.0 or >=22.12.0**  
- NPM or Yarn  
- Laravel 12.x  

### Mobile Client (NativeScript)
- NativeScript CLI **>= 9.x**  
- Node.js **^20.19.0 or >=22.12.0**  
- Android Studio / Xcode (to build emulator/device)  

---

## ⚡ Installation

### Backend (Laravel Reverb)

```bash
# Clone repository
git clone https://github.com/almalikzakwan/Laravel-Websocket.git

# Checkout live messaging branch
cd Laravel-Websocket
git checkout sandbox/native-script/live-messaging

# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Copy environment
cp .env.example .env

# Generate app key
php artisan key:generate
```

### Mobile Client (NativeScript Vue TS)

```bash
# Go to native client project
cd native

# Install dependencies
npm install

# Run on Android
ns run android

# Run on iOS
ns run ios
```

---

## 🔧 Environment Setup

### Laravel `.env` (backend)

```env
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=local
REVERB_APP_KEY=local
REVERB_APP_SECRET=local
REVERB_HOST=127.0.0.1
REVERB_PORT=8080

QUEUE_CONNECTION=database
```

---

## 🚀 Running the Project

Run these in separate terminals:

```bash
# 1. Start Laravel API
php artisan serve --host=0.0.0.0 --port=8000

# 2. Start Reverb WebSocket server
php artisan reverb:start

# 3. Start Vite for web frontend assets
npm run dev

# 4. Start NativeScript app
cd native
ns run android
```

---

## 📡 Example Events

### Event Class (`app/Events/MessageSent.php`)

```php
class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $user;

    public function __construct($message, $user)
    {
        $this->message = $message;
        $this->user = $user;
    }

    public function broadcastOn()
    {
        return new Channel('public-chat');
    }
}
```

### Trigger Event (Controller)

```php
event(new MessageSent("Hello from Laravel + NativeScript!", auth()->user()));
```

---

## 🎧 Client Listener

### Web Frontend (`resources/js/app.js`)

```js
window.Echo.channel('public-chat')
    .listen('MessageSent', (e) => {
        console.log("📡 Web Event received:", e.message);
    });
```

### NativeScript Vue TS (`native/src/App.vue`)

```vue
<script lang="ts">
import { ref, onMounted } from 'nativescript-vue'
import Echo from 'laravel-echo'

export default {
  setup() {
    const messages = ref<string[]>([])

    onMounted(() => {
      const echo = new Echo({
        broadcaster: 'reverb',
        key: 'local',
        wsHost: '127.0.0.1',
        wsPort: 8080,
        forceTLS: false,
        disableStats: true,
      })

      echo.channel('public-chat')
        .listen('MessageSent', (e: any) => {
          messages.value.push(`${e.user.name}: ${e.message}`)
        })
    })

    return { messages }
  }
}
</script>
```

---

## ✅ Testing

1. Run the backend + Reverb server.  
2. Run the NativeScript app in emulator/device.  
3. Trigger an event from Laravel controller/route.  
4. The message should appear in real-time in the mobile app.  

---

## 📖 References

- [Laravel Reverb Documentation](https://laravel.com/docs/12.x/reverb)  
- [Laravel Broadcasting](https://laravel.com/docs/12.x/broadcasting)  
- [Laravel Echo](https://laravel.com/docs/12.x/broadcasting#client-side-installation)  
- [NativeScript Docs](https://docs.nativescript.org/)  

---

👉 Branch `sandbox/native-script/live-messaging` is intended for **demo & learning purposes** on building real-time chat (Web + Mobile).
