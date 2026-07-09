import React from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { useAuth } from '../services/AuthContext';
import { colors } from '../constants/theme';
import AuthScreen from '../screens/AuthScreen';
import DashboardScreen from '../screens/DashboardScreen';
import LoanListScreen from '../screens/LoanListScreen';
import LoanDetailScreen from '../screens/LoanDetailScreen';
import ScheduleScreen from '../screens/ScheduleScreen';
import PaymentsScreen from '../screens/PaymentsScreen';

export type RootStackParamList = {
  Auth: undefined;
  Dashboard: undefined;
  LoanList: undefined;
  LoanDetail: { loanId: string };
  Schedule: { loanId: string };
  Payments: { loanId: string };
};

const Stack = createNativeStackNavigator<RootStackParamList>();

export default function AppNavigator() {
  const { isAuthenticated } = useAuth();
  return (
    <NavigationContainer>
      <Stack.Navigator
        screenOptions={{
          headerStyle: { backgroundColor: colors.surface },
          headerTintColor: colors.text,
          headerTitleStyle: { fontWeight: '600' },
          headerShadowVisible: false,
          contentStyle: { backgroundColor: colors.background },
        }}>
        {isAuthenticated ? (
          <>
            <Stack.Screen
              name="Dashboard"
              component={DashboardScreen}
              options={{ headerShown: false }}
            />
            <Stack.Screen
              name="LoanList"
              component={LoanListScreen}
              options={{ title: 'My Loans' }}
            />
            <Stack.Screen
              name="LoanDetail"
              component={LoanDetailScreen}
              options={{ title: 'Loan Details' }}
            />
            <Stack.Screen
              name="Schedule"
              component={ScheduleScreen}
              options={{ title: 'Amortization Schedule' }}
            />
            <Stack.Screen
              name="Payments"
              component={PaymentsScreen}
              options={{ title: 'Payment History' }}
            />
          </>
        ) : (
          <Stack.Screen name="Auth" options={{ headerShown: false }} component={AuthScreen} />
        )}
      </Stack.Navigator>
    </NavigationContainer>
  );
}
