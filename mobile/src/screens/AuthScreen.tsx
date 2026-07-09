import React, { useState } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  KeyboardAvoidingView,
  Platform,
  Alert,
  ActivityIndicator,
} from 'react-native';
import { colors, spacing, borderRadius } from '../constants/theme';
import { login } from '../services/auth';
import { useAuth } from '../services/AuthContext';

export default function AuthScreen() {
  const { onLoginSuccess } = useAuth();
  const [memberId, setMemberId] = useState('');
  const [pin, setPin] = useState('');
  const [loading, setLoading] = useState(false);

  const handleLogin = async () => {
    if (!memberId.trim() || !pin.trim()) {
      Alert.alert('Error', 'Please enter your Member ID and PIN.');
      return;
    }

    setLoading(true);
    try {
      await login(memberId.trim(), pin.trim());
      onLoginSuccess();
    } catch (err: any) {
      const message =
        err.response?.data?.errors?.member_id?.[0] ||
        err.response?.data?.message ||
        'Login failed. Please check your credentials.';
      Alert.alert('Login Failed', message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <KeyboardAvoidingView
      style={styles.container}
      behavior={Platform.OS === 'ios' ? 'padding' : 'height'}>
      <View style={styles.content}>
        <View style={styles.logoContainer}>
          <View style={styles.logo}>
            <Text style={styles.logoText}>M</Text>
          </View>
          <Text style={styles.title}>Microfin</Text>
          <Text style={styles.subtitle}>Community Microfinance System</Text>
        </View>

        <View style={styles.form}>
          <Text style={styles.label}>Member ID</Text>
          <TextInput
            style={styles.input}
            value={memberId}
            onChangeText={setMemberId}
            placeholder="e.g. MF-2026-0001"
            placeholderTextColor={colors.textMuted}
            autoCapitalize="characters"
            autoCorrect={false}
          />

          <Text style={styles.label}>PIN</Text>
          <TextInput
            style={styles.input}
            value={pin}
            onChangeText={setPin}
            placeholder="Enter your 4-digit PIN"
            placeholderTextColor={colors.textMuted}
            secureTextEntry
            keyboardType="number-pad"
            maxLength={4}
          />

          <TouchableOpacity
            style={[styles.button, loading && styles.buttonDisabled]}
            onPress={handleLogin}
            disabled={loading}>
            {loading ? (
              <ActivityIndicator color={colors.text} />
            ) : (
              <Text style={styles.buttonText}>Log In</Text>
            )}
          </TouchableOpacity>
        </View>

        <Text style={styles.footer}>
          Empowering communities through inclusive financial services.
        </Text>
      </View>
    </KeyboardAvoidingView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: colors.background,
  },
  content: {
    flex: 1,
    justifyContent: 'center',
    paddingHorizontal: spacing.lg,
  },
  logoContainer: {
    alignItems: 'center',
    marginBottom: spacing.xl,
  },
  logo: {
    width: 64,
    height: 64,
    borderRadius: borderRadius.lg,
    backgroundColor: colors.primary,
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: spacing.md,
  },
  logoText: {
    fontSize: 32,
    fontWeight: 'bold',
    color: colors.text,
  },
  title: {
    fontSize: 28,
    fontWeight: 'bold',
    color: colors.text,
  },
  subtitle: {
    fontSize: 14,
    color: colors.textSecondary,
    marginTop: spacing.xs,
  },
  form: {
    backgroundColor: colors.surface,
    borderRadius: borderRadius.lg,
    padding: spacing.lg,
    borderWidth: 1,
    borderColor: colors.border,
  },
  label: {
    fontSize: 14,
    fontWeight: '600',
    color: colors.text,
    marginBottom: spacing.xs,
    marginTop: spacing.md,
  },
  input: {
    borderWidth: 1,
    borderColor: colors.border,
    borderRadius: borderRadius.sm,
    padding: spacing.md,
    fontSize: 16,
    color: colors.text,
    backgroundColor: colors.background,
  },
  button: {
    backgroundColor: colors.primary,
    borderRadius: borderRadius.sm,
    padding: spacing.md,
    alignItems: 'center',
    marginTop: spacing.lg,
  },
  buttonDisabled: {
    opacity: 0.6,
  },
  buttonText: {
    fontSize: 16,
    fontWeight: '600',
    color: colors.text,
  },
  footer: {
    textAlign: 'center',
    fontSize: 12,
    color: colors.textMuted,
    marginTop: spacing.xl,
  },
});
