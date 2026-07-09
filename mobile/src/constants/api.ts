import { Platform } from 'react-native';

const DEV_API_URL = Platform.select({
  android: 'http://192.168.1.26:8000/api',
  ios: 'http://192.168.1.26:8000/api',
  default: 'http://192.168.1.26:8000/api',
});

export const API_BASE_URL = __DEV__ ? DEV_API_URL : 'https://your-production-url.com/api';
