import React from 'react';
import { StatusBar } from 'expo-status-bar';
import { AuthProvider, useAuth } from './src/services/AuthContext';
import AppNavigator from './src/navigation/AppNavigator';
import LoadingScreen from './src/components/LoadingScreen';

function AppContent() {
  const { loading } = useAuth();

  if (loading) return <LoadingScreen />;

  return <AppNavigator />;
}

export default function App() {
  return (
    <AuthProvider>
      <StatusBar style="dark" />
      <AppContent />
    </AuthProvider>
  );
}
