import AsyncStorage from '@react-native-async-storage/async-storage';
import api, { TOKEN_KEY } from './api';

export interface Client {
  id: string;
  member_id: string;
  full_name: string;
  first_name: string;
  middle_name: string | null;
  last_name: string;
  suffix: string | null;
  phone: string | null;
  email: string | null;
  address: string | null;
  branch: { name: string } | null;
  status: string;
}

export interface LoginResponse {
  token: string;
  client: Client;
}

export async function login(memberId: string, pin: string): Promise<LoginResponse> {
  const response = await api.post<LoginResponse>('/auth/login', {
    member_id: memberId,
    pin,
  });
  const data = response.data;
  await AsyncStorage.setItem(TOKEN_KEY, data.token);
  return data;
}

export async function logout(): Promise<void> {
  try {
    await api.post('/auth/logout');
  } finally {
    await AsyncStorage.removeItem(TOKEN_KEY);
  }
}

export async function getProfile(): Promise<Client> {
  const response = await api.get<Client>('/me');
  return response.data;
}

export async function getStoredToken(): Promise<string | null> {
  return AsyncStorage.getItem(TOKEN_KEY);
}
