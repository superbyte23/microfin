import React, { useEffect, useState, useCallback } from 'react';
import {
  View,
  Text,
  ScrollView,
  StyleSheet,
  RefreshControl,
  TouchableOpacity,
} from 'react-native';
import { useFocusEffect } from '@react-navigation/native';
import type { NativeStackScreenProps } from '@react-navigation/native-stack';
import type { RootStackParamList } from '../navigation/AppNavigator';
import { colors, spacing, borderRadius } from '../constants/theme';
import { Loan, getLoans } from '../services/loans';
import { Client, getProfile } from '../services/auth';
import { useAuth } from '../services/AuthContext';
import LoadingScreen from '../components/LoadingScreen';

type Props = NativeStackScreenProps<RootStackParamList, 'Dashboard'>;

export default function DashboardScreen({ navigation }: Props) {
  const { onLogout } = useAuth();
  const [client, setClient] = useState<Client | null>(null);
  const [loans, setLoans] = useState<Loan[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  const loadData = useCallback(async () => {
    try {
      const [profile, loanData] = await Promise.all([getProfile(), getLoans()]);
      setClient(profile);
      setLoans(loanData);
    } catch (err) {
      //
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  }, []);

  useFocusEffect(
    useCallback(() => {
      loadData();
    }, [loadData]),
  );

  const onRefresh = () => {
    setRefreshing(true);
    loadData();
  };

  if (loading) return <LoadingScreen />;

  const activeLoans = loans.filter(l => ['pending', 'approved', 'active'].includes(l.status));
  const nextPayment = loans
    .flatMap(l => l.schedule ?? [])
    .filter(s => s.status === 'pending')
    .sort((a, b) => new Date(a.due_date).getTime() - new Date(b.due_date).getTime())[0];

  const totalOutstanding = loans
    .filter(l => ['approved', 'active'].includes(l.status))
    .reduce((sum, l) => sum + l.principal_amount, 0);

  return (
    <ScrollView
      style={styles.container}
      refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} />}>
      <View style={styles.header}>
        <View>
          <Text style={styles.greeting}>Welcome,</Text>
          <Text style={styles.name}>{client?.full_name}</Text>
          <Text style={styles.memberId}>{client?.member_id}</Text>
        </View>
        <TouchableOpacity onPress={onLogout} style={styles.logoutButton}>
          <Text style={styles.logoutText}>Logout</Text>
        </TouchableOpacity>
      </View>

      <View style={styles.statsRow}>
        <View style={styles.statCard}>
          <Text style={styles.statValue}>{activeLoans.length}</Text>
          <Text style={styles.statLabel}>Active Loans</Text>
        </View>
        <View style={styles.statCard}>
          <Text style={styles.statValue}>₱{(totalOutstanding).toLocaleString()}</Text>
          <Text style={styles.statLabel}>Outstanding</Text>
        </View>
      </View>

      {nextPayment && (
        <View style={styles.nextPaymentCard}>
          <Text style={styles.nextPaymentLabel}>Next Payment Due</Text>
          <Text style={styles.nextPaymentAmount}>
            ₱{nextPayment.total_due.toLocaleString()}
          </Text>
          <Text style={styles.nextPaymentDate}>
            Due: {new Date(nextPayment.due_date).toLocaleDateString('en-PH', {
              year: 'numeric',
              month: 'long',
              day: 'numeric',
            })}
          </Text>
        </View>
      )}

      <View style={styles.sectionHeader}>
        <Text style={styles.sectionTitle}>My Loans</Text>
        <TouchableOpacity onPress={() => navigation.navigate('LoanList')}>
          <Text style={styles.seeAll}>See All</Text>
        </TouchableOpacity>
      </View>

      {loans.length === 0 ? (
        <View style={styles.emptyState}>
          <Text style={styles.emptyText}>No loans yet</Text>
        </View>
      ) : (
        loans.slice(0, 3).map(loan => (
          <TouchableOpacity
            key={loan.id}
            style={styles.loanCard}
            onPress={() => navigation.navigate('LoanDetail', { loanId: loan.id })}>
            <View style={styles.loanCardHeader}>
              <Text style={styles.loanProduct}>{loan.product_name}</Text>
              <View
                style={[
                  styles.statusBadge,
                  loan.status === 'active' && styles.statusActive,
                  loan.status === 'approved' && styles.statusApproved,
                  loan.status === 'pending' && styles.statusPending,
                  loan.status === 'closed' && styles.statusClosed,
                  (loan.status === 'rejected' || loan.status === 'written-off') && styles.statusRejected,
                ]}>
                <Text style={styles.statusText}>
                  {loan.status.charAt(0).toUpperCase() + loan.status.slice(1)}
                </Text>
              </View>
            </View>
            <Text style={styles.loanAmount}>₱{loan.principal_amount.toLocaleString()}</Text>
            <Text style={styles.loanMeta}>
              {loan.term_months} months · {loan.payment_frequency}
            </Text>
          </TouchableOpacity>
        ))
      )}
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: colors.background,
  },
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'flex-start',
    padding: spacing.lg,
    paddingTop: spacing.xl,
  },
  logoutButton: {
    paddingHorizontal: spacing.sm,
    paddingVertical: spacing.xs,
  },
  logoutText: {
    fontSize: 14,
    color: colors.textMuted,
    fontWeight: '500',
  },
  greeting: {
    fontSize: 14,
    color: colors.textSecondary,
  },
  name: {
    fontSize: 24,
    fontWeight: 'bold',
    color: colors.text,
    marginTop: spacing.xs,
  },
  memberId: {
    fontSize: 12,
    color: colors.textMuted,
    marginTop: spacing.xs,
  },
  statsRow: {
    flexDirection: 'row',
    paddingHorizontal: spacing.lg,
    gap: spacing.md,
    marginBottom: spacing.md,
  },
  statCard: {
    flex: 1,
    backgroundColor: colors.surface,
    borderRadius: borderRadius.lg,
    padding: spacing.md,
    borderWidth: 1,
    borderColor: colors.border,
  },
  statValue: {
    fontSize: 20,
    fontWeight: 'bold',
    color: colors.text,
  },
  statLabel: {
    fontSize: 12,
    color: colors.textSecondary,
    marginTop: spacing.xs,
  },
  nextPaymentCard: {
    marginHorizontal: spacing.lg,
    backgroundColor: colors.primary,
    borderRadius: borderRadius.lg,
    padding: spacing.lg,
    marginBottom: spacing.lg,
  },
  nextPaymentLabel: {
    fontSize: 12,
    color: colors.text,
    fontWeight: '600',
  },
  nextPaymentAmount: {
    fontSize: 28,
    fontWeight: 'bold',
    color: colors.text,
    marginTop: spacing.xs,
  },
  nextPaymentDate: {
    fontSize: 13,
    color: colors.text,
    marginTop: spacing.xs,
    opacity: 0.8,
  },
  sectionHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: spacing.lg,
    marginBottom: spacing.sm,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: colors.text,
  },
  seeAll: {
    fontSize: 14,
    color: colors.primaryDark,
    fontWeight: '600',
  },
  loanCard: {
    backgroundColor: colors.surface,
    marginHorizontal: spacing.lg,
    marginBottom: spacing.sm,
    borderRadius: borderRadius.md,
    padding: spacing.md,
    borderWidth: 1,
    borderColor: colors.border,
  },
  loanCardHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  loanProduct: {
    fontSize: 14,
    fontWeight: '600',
    color: colors.text,
  },
  statusBadge: {
    paddingHorizontal: spacing.sm,
    paddingVertical: 2,
    borderRadius: borderRadius.sm,
    backgroundColor: colors.pending,
  },
  statusActive: { backgroundColor: colors.success },
  statusApproved: { backgroundColor: colors.success },
  statusPending: { backgroundColor: colors.warning },
  statusClosed: { backgroundColor: colors.textMuted },
  statusRejected: { backgroundColor: colors.error },
  statusText: {
    fontSize: 11,
    fontWeight: '600',
    color: colors.white,
  },
  loanAmount: {
    fontSize: 20,
    fontWeight: 'bold',
    color: colors.text,
    marginTop: spacing.sm,
  },
  loanMeta: {
    fontSize: 12,
    color: colors.textSecondary,
    marginTop: spacing.xs,
  },
  emptyState: {
    padding: spacing.xl,
    alignItems: 'center',
  },
  emptyText: {
    color: colors.textMuted,
    fontSize: 14,
  },
});
