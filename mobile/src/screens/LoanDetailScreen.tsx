import React, { useEffect, useState } from 'react';
import {
  View,
  Text,
  ScrollView,
  StyleSheet,
  TouchableOpacity,
} from 'react-native';
import type { NativeStackScreenProps } from '@react-navigation/native-stack';
import type { RootStackParamList } from '../navigation/AppNavigator';
import { colors, spacing, borderRadius } from '../constants/theme';
import { Loan, ScheduleItem, getLoan } from '../services/loans';
import LoadingScreen from '../components/LoadingScreen';

type Props = NativeStackScreenProps<RootStackParamList, 'LoanDetail'>;

export default function LoanDetailScreen({ route, navigation }: Props) {
  const { loanId } = route.params;
  const [loan, setLoan] = useState<Loan | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    (async () => {
      try {
        const data = await getLoan(loanId);
        setLoan(data);
      } catch (err) {
        //
      } finally {
        setLoading(false);
      }
    })();
  }, [loanId]);

  if (loading) return <LoadingScreen />;
  if (!loan) return null;

  const schedule = loan.schedule ?? [];
  const paidInstallments = schedule.filter(s => s.status === 'paid');
  const totalPaid = paidInstallments.reduce((sum, s) => sum + s.paid_amount, 0);
  const totalDue = schedule.reduce((sum, s) => sum + s.total_due, 0);
  const progress = totalDue > 0 ? (totalPaid / totalDue) * 100 : 0;

  const nextDue = schedule.find(s => s.status === 'pending');

  return (
    <ScrollView style={styles.container}>
      <View style={styles.headerCard}>
        <Text style={styles.productName}>{loan.product_name}</Text>
        <View
          style={[
            styles.badge,
            loan.status === 'active' && styles.statusActive,
            loan.status === 'approved' && styles.statusApproved,
            loan.status === 'pending' && styles.statusPending,
            loan.status === 'closed' && styles.statusClosed,
          ]}>
          <Text style={styles.badgeText}>
            {loan.status.charAt(0).toUpperCase() + loan.status.slice(1)}
          </Text>
        </View>
      </View>

      <View style={styles.amountCard}>
        <Text style={styles.amountLabel}>Principal Amount</Text>
        <Text style={styles.amount}>₱{loan.principal_amount.toLocaleString()}</Text>
      </View>

      <View style={styles.progressCard}>
        <View style={styles.progressBar}>
          <View style={[styles.progressFill, { width: `${Math.min(progress, 100)}%` }]} />
        </View>
        <Text style={styles.progressText}>
          {paidInstallments.length} of {schedule.length} installments paid
        </Text>
      </View>

      <View style={styles.detailsGrid}>
        <View style={styles.detailItem}>
          <Text style={styles.detailLabel}>Interest Rate</Text>
          <Text style={styles.detailValue}>{loan.interest_rate}% {loan.interest_type}</Text>
        </View>
        <View style={styles.detailItem}>
          <Text style={styles.detailLabel}>Term</Text>
          <Text style={styles.detailValue}>{loan.term_months} months</Text>
        </View>
        <View style={styles.detailItem}>
          <Text style={styles.detailLabel}>Payment</Text>
          <Text style={styles.detailValue}>{loan.payment_frequency}</Text>
        </View>
        <View style={styles.detailItem}>
          <Text style={styles.detailLabel}>Branch</Text>
          <Text style={styles.detailValue}>{loan.branch?.name ?? '—'}</Text>
        </View>
      </View>

      {loan.release_date && (
        <View style={styles.infoRow}>
          <Text style={styles.infoLabel}>Release Date:</Text>
          <Text style={styles.infoValue}>
            {new Date(loan.release_date).toLocaleDateString('en-PH', {
              year: 'numeric',
              month: 'long',
              day: 'numeric',
            })}
          </Text>
        </View>
      )}

      {loan.maturity_date && (
        <View style={styles.infoRow}>
          <Text style={styles.infoLabel}>Maturity Date:</Text>
          <Text style={styles.infoValue}>
            {new Date(loan.maturity_date).toLocaleDateString('en-PH', {
              year: 'numeric',
              month: 'long',
              day: 'numeric',
            })}
          </Text>
        </View>
      )}

      {nextDue && (
        <View style={styles.nextDueCard}>
          <Text style={styles.nextDueLabel}>Next Payment</Text>
          <Text style={styles.nextDueAmount}>₱{nextDue.total_due.toLocaleString()}</Text>
          <Text style={styles.nextDueDate}>
            Due: {new Date(nextDue.due_date).toLocaleDateString('en-PH', {
              year: 'numeric',
              month: 'long',
              day: 'numeric',
            })}
          </Text>
        </View>
      )}

      <View style={styles.actions}>
        <TouchableOpacity
          style={styles.actionButton}
          onPress={() => navigation.navigate('Schedule', { loanId: loan.id })}>
          <Text style={styles.actionText}>View Schedule</Text>
        </TouchableOpacity>
        <TouchableOpacity
          style={[styles.actionButton, styles.actionButtonSecondary]}
          onPress={() => navigation.navigate('Payments', { loanId: loan.id })}>
          <Text style={[styles.actionText, styles.actionTextSecondary]}>Payment History</Text>
        </TouchableOpacity>
      </View>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: colors.background,
  },
  headerCard: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: spacing.lg,
    backgroundColor: colors.surface,
    borderBottomWidth: 1,
    borderBottomColor: colors.border,
  },
  productName: {
    fontSize: 18,
    fontWeight: 'bold',
    color: colors.text,
  },
  badge: {
    paddingHorizontal: spacing.sm,
    paddingVertical: 4,
    borderRadius: borderRadius.sm,
  },
  badgeText: {
    fontSize: 12,
    fontWeight: '600',
    color: colors.white,
  },
  statusActive: { backgroundColor: colors.success },
  statusApproved: { backgroundColor: colors.success },
  statusPending: { backgroundColor: colors.warning },
  statusClosed: { backgroundColor: colors.textMuted },
  statusRejected: { backgroundColor: colors.error },
  amountCard: {
    padding: spacing.lg,
    backgroundColor: colors.surface,
    borderBottomWidth: 1,
    borderBottomColor: colors.border,
  },
  amountLabel: {
    fontSize: 12,
    color: colors.textSecondary,
  },
  amount: {
    fontSize: 32,
    fontWeight: 'bold',
    color: colors.text,
    marginTop: spacing.xs,
  },
  progressCard: {
    padding: spacing.lg,
    backgroundColor: colors.surface,
    borderBottomWidth: 1,
    borderBottomColor: colors.border,
  },
  progressBar: {
    height: 6,
    backgroundColor: colors.border,
    borderRadius: 3,
    overflow: 'hidden',
  },
  progressFill: {
    height: '100%',
    backgroundColor: colors.success,
    borderRadius: 3,
  },
  progressText: {
    fontSize: 12,
    color: colors.textSecondary,
    marginTop: spacing.sm,
  },
  detailsGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    padding: spacing.lg,
    backgroundColor: colors.surface,
    borderBottomWidth: 1,
    borderBottomColor: colors.border,
    gap: spacing.md,
  },
  detailItem: {
    width: '46%',
  },
  detailLabel: {
    fontSize: 12,
    color: colors.textSecondary,
  },
  detailValue: {
    fontSize: 14,
    fontWeight: '600',
    color: colors.text,
    marginTop: 2,
  },
  infoRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    paddingHorizontal: spacing.lg,
    paddingVertical: spacing.sm,
    backgroundColor: colors.surface,
    borderBottomWidth: 1,
    borderBottomColor: colors.border,
  },
  infoLabel: {
    fontSize: 13,
    color: colors.textSecondary,
  },
  infoValue: {
    fontSize: 13,
    color: colors.text,
    fontWeight: '500',
  },
  nextDueCard: {
    margin: spacing.lg,
    backgroundColor: colors.primary,
    borderRadius: borderRadius.lg,
    padding: spacing.lg,
  },
  nextDueLabel: {
    fontSize: 12,
    color: colors.text,
    fontWeight: '600',
  },
  nextDueAmount: {
    fontSize: 28,
    fontWeight: 'bold',
    color: colors.text,
    marginTop: spacing.xs,
  },
  nextDueDate: {
    fontSize: 13,
    color: colors.text,
    marginTop: spacing.xs,
    opacity: 0.8,
  },
  actions: {
    flexDirection: 'row',
    gap: spacing.md,
    padding: spacing.lg,
    paddingBottom: spacing.xl,
  },
  actionButton: {
    flex: 1,
    backgroundColor: colors.primary,
    borderRadius: borderRadius.sm,
    padding: spacing.md,
    alignItems: 'center',
  },
  actionButtonSecondary: {
    backgroundColor: colors.surface,
    borderWidth: 1,
    borderColor: colors.border,
  },
  actionText: {
    fontSize: 14,
    fontWeight: '600',
    color: colors.text,
  },
  actionTextSecondary: {
    color: colors.text,
  },
});
