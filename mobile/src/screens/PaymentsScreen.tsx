import React, { useEffect, useState } from 'react';
import {
  View,
  Text,
  FlatList,
  StyleSheet,
} from 'react-native';
import type { NativeStackScreenProps } from '@react-navigation/native-stack';
import type { RootStackParamList } from '../navigation/AppNavigator';
import { colors, spacing, borderRadius } from '../constants/theme';
import { Payment, getPayments } from '../services/loans';
import LoadingScreen from '../components/LoadingScreen';

type Props = NativeStackScreenProps<RootStackParamList, 'Payments'>;

export default function PaymentsScreen({ route }: Props) {
  const { loanId } = route.params;
  const [payments, setPayments] = useState<Payment[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    (async () => {
      try {
        const data = await getPayments(loanId);
        setPayments(data);
      } catch (err) {
        //
      } finally {
        setLoading(false);
      }
    })();
  }, [loanId]);

  if (loading) return <LoadingScreen />;

  const formatDate = (dateStr: string) => {
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-PH', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
    });
  };

  const formatTime = (dateStr: string) => {
    const d = new Date(dateStr);
    return d.toLocaleTimeString('en-PH', {
      hour: '2-digit',
      minute: '2-digit',
    });
  };

  const methodLabel = (method: string) => {
    switch (method) {
      case 'cash': return 'Cash';
      case 'bank_transfer': return 'Bank Transfer';
      case 'check': return 'Check';
      case 'mobile_money': return 'Mobile Money';
      default: return method.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
    }
  };

  const renderItem = ({ item }: { item: Payment }) => (
    <View style={styles.card}>
      <View style={styles.cardHeader}>
        <Text style={styles.method}>{methodLabel(item.payment_method)}</Text>
        <Text style={styles.amount}>₱{item.amount.toLocaleString()}</Text>
      </View>
      <View style={styles.meta}>
        <Text style={styles.date}>{formatDate(item.created_at)}</Text>
        <Text style={styles.time}>{formatTime(item.created_at)}</Text>
      </View>
      {item.reference_number && (
        <Text style={styles.ref}>Ref: {item.reference_number}</Text>
      )}
      {item.collected_by && (
        <Text style={styles.collector}>Collected by: {item.collected_by}</Text>
      )}
    </View>
  );

  return (
    <FlatList
      style={styles.container}
      data={payments}
      renderItem={renderItem}
      keyExtractor={item => item.id}
      contentContainerStyle={styles.list}
      ListEmptyComponent={
        <View style={styles.empty}>
          <Text style={styles.emptyText}>No payments recorded yet</Text>
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
  method: {
    fontSize: 14,
    fontWeight: '600',
    color: colors.textSecondary,
  },
  amount: {
    fontSize: 20,
    fontWeight: 'bold',
    color: colors.text,
  },
  meta: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginTop: spacing.sm,
    paddingTop: spacing.sm,
    borderTopWidth: 1,
    borderTopColor: colors.border,
  },
  date: {
    fontSize: 13,
    color: colors.textSecondary,
  },
  time: {
    fontSize: 13,
    color: colors.textMuted,
  },
  ref: {
    fontSize: 12,
    color: colors.textMuted,
    marginTop: spacing.xs,
  },
  collector: {
    fontSize: 12,
    color: colors.textMuted,
    marginTop: 2,
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
