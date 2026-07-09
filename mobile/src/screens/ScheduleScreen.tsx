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
import { ScheduleItem, getSchedule } from '../services/loans';
import LoadingScreen from '../components/LoadingScreen';

type Props = NativeStackScreenProps<RootStackParamList, 'Schedule'>;

export default function ScheduleScreen({ route }: Props) {
  const { loanId } = route.params;
  const [schedule, setSchedule] = useState<ScheduleItem[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    (async () => {
      try {
        const data = await getSchedule(loanId);
        setSchedule(data);
      } catch (err) {
        //
      } finally {
        setLoading(false);
      }
    })();
  }, [loanId]);

  if (loading) return <LoadingScreen />;

  const statusStyle = (status: string) => {
    switch (status) {
      case 'paid': return { backgroundColor: colors.success };
      case 'overdue': return { backgroundColor: colors.error };
      case 'partial': return { backgroundColor: colors.warning };
      default: return { backgroundColor: colors.border };
    }
  };

  const statusLabel = (status: string) => {
    switch (status) {
      case 'paid': return 'Paid';
      case 'overdue': return 'Overdue';
      case 'partial': return 'Partial';
      default: return 'Pending';
    }
  };

  const renderItem = ({ item }: { item: ScheduleItem }) => (
    <View style={[styles.row, item.status === 'overdue' && styles.rowOverdue]}>
      <View style={styles.left}>
        <View style={[styles.dot, statusStyle(item.status)]} />
        <View>
          <Text style={styles.installment}>Installment {item.installment_number}</Text>
          <Text style={styles.dueDate}>
            Due: {new Date(item.due_date).toLocaleDateString('en-PH')}
          </Text>
        </View>
      </View>
      <View style={styles.right}>
        <Text style={styles.amount}>₱{item.total_due.toLocaleString()}</Text>
        <Text style={[styles.status, item.status === 'overdue' && styles.statusOverdue]}>
          {statusLabel(item.status)}
        </Text>
      </View>
    </View>
  );

  return (
    <View style={styles.container}>
      <FlatList
        data={schedule}
        renderItem={renderItem}
        keyExtractor={item => `${item.installment_number}`}
        contentContainerStyle={styles.list}
      />
    </View>
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
  row: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: colors.surface,
    borderRadius: borderRadius.md,
    padding: spacing.md,
    marginBottom: spacing.sm,
    borderWidth: 1,
    borderColor: colors.border,
  },
  rowOverdue: {
    borderColor: colors.error,
    borderWidth: 1.5,
  },
  left: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: spacing.sm,
  },
  dot: {
    width: 10,
    height: 10,
    borderRadius: 5,
  },
  installment: {
    fontSize: 14,
    fontWeight: '600',
    color: colors.text,
  },
  dueDate: {
    fontSize: 12,
    color: colors.textSecondary,
    marginTop: 2,
  },
  right: {
    alignItems: 'flex-end',
  },
  amount: {
    fontSize: 16,
    fontWeight: 'bold',
    color: colors.text,
  },
  status: {
    fontSize: 11,
    color: colors.textMuted,
    marginTop: 2,
  },
  statusOverdue: {
    color: colors.error,
    fontWeight: '600',
  },
});
