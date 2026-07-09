import React, { useEffect, useState, useCallback } from 'react';
import {
  View,
  Text,
  FlatList,
  StyleSheet,
  TouchableOpacity,
  RefreshControl,
} from 'react-native';
import { useFocusEffect } from '@react-navigation/native';
import type { NativeStackScreenProps } from '@react-navigation/native-stack';
import type { RootStackParamList } from '../navigation/AppNavigator';
import { colors, spacing, borderRadius } from '../constants/theme';
import { Loan, getLoans } from '../services/loans';
import LoadingScreen from '../components/LoadingScreen';

type Props = NativeStackScreenProps<RootStackParamList, 'LoanList'>;

export default function LoanListScreen({ navigation }: Props) {
  const [loans, setLoans] = useState<Loan[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  const loadLoans = useCallback(async () => {
    try {
      const data = await getLoans();
      setLoans(data);
    } catch (err) {
      //
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  }, []);

  useFocusEffect(
    useCallback(() => {
      loadLoans();
    }, [loadLoans]),
  );

  if (loading) return <LoadingScreen />;

  const statusStyle = (status: string) => {
    switch (status) {
      case 'active': return styles.statusActive;
      case 'approved': return styles.statusApproved;
      case 'pending': return styles.statusPending;
      case 'closed': return styles.statusClosed;
      default: return styles.statusRejected;
    }
  };

  const renderLoan = ({ item }: { item: Loan }) => (
    <TouchableOpacity
      style={styles.card}
      onPress={() => navigation.navigate('LoanDetail', { loanId: item.id })}>
      <View style={styles.cardHeader}>
        <Text style={styles.productName}>{item.product_name}</Text>
        <View style={[styles.badge, statusStyle(item.status)]}>
          <Text style={styles.badgeText}>
            {item.status.charAt(0).toUpperCase() + item.status.slice(1)}
          </Text>
        </View>
      </View>
      <Text style={styles.amount}>₱{item.principal_amount.toLocaleString()}</Text>
      <View style={styles.metaRow}>
        <Text style={styles.meta}>{item.term_months} months</Text>
        <Text style={styles.meta}>{item.payment_frequency}</Text>
        <Text style={styles.meta}>{item.interest_rate}% {item.interest_type}</Text>
      </View>
      {item.release_date && (
        <Text style={styles.releaseDate}>
          Released: {new Date(item.release_date).toLocaleDateString('en-PH')}
        </Text>
      )}
    </TouchableOpacity>
  );

  return (
    <FlatList
      style={styles.container}
      data={loans}
      renderItem={renderLoan}
      keyExtractor={item => item.id}
      contentContainerStyle={styles.list}
      refreshControl={
        <RefreshControl refreshing={refreshing} onRefresh={() => { setRefreshing(true); loadLoans(); }} />
      }
      ListEmptyComponent={
        <View style={styles.empty}>
          <Text style={styles.emptyText}>No loans found</Text>
        </View>
      }
    />
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: colors.background,
  },
  list: {
    padding: spacing.lg,
  },
  card: {
    backgroundColor: colors.surface,
    borderRadius: borderRadius.lg,
    padding: spacing.md,
    marginBottom: spacing.md,
    borderWidth: 1,
    borderColor: colors.border,
  },
  cardHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  productName: {
    fontSize: 15,
    fontWeight: '600',
    color: colors.text,
  },
  badge: {
    paddingHorizontal: spacing.sm,
    paddingVertical: 2,
    borderRadius: borderRadius.sm,
  },
  badgeText: {
    fontSize: 11,
    fontWeight: '600',
    color: colors.white,
  },
  statusActive: { backgroundColor: colors.success },
  statusApproved: { backgroundColor: colors.success },
  statusPending: { backgroundColor: colors.warning },
  statusClosed: { backgroundColor: colors.textMuted },
  statusRejected: { backgroundColor: colors.error },
  amount: {
    fontSize: 22,
    fontWeight: 'bold',
    color: colors.text,
    marginTop: spacing.sm,
  },
  metaRow: {
    flexDirection: 'row',
    gap: spacing.md,
    marginTop: spacing.sm,
  },
  meta: {
    fontSize: 12,
    color: colors.textSecondary,
  },
  releaseDate: {
    fontSize: 12,
    color: colors.textMuted,
    marginTop: spacing.xs,
  },
  empty: {
    padding: spacing.xl,
    alignItems: 'center',
  },
  emptyText: {
    color: colors.textMuted,
    fontSize: 14,
  },
});
