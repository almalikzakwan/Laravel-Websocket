import { NativeScriptConfig } from '@nativescript/core';

export default {
  id: 'org.nativescript.NativeMessaging',
  appPath: 'src',
  appResourcesPath: 'apps',
  android: {
    v8Flags: '--expose_gc',
    markingMode: 'none'
  }
} as NativeScriptConfig;
