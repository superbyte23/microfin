import api from './api';

export interface Loan {
  id: string;
  product_name: string;
  principal_amount: number;
  interest_rate: number;
  interest_type: string;
  term_months: number;
  payment_frequency: string;
  release_date: string | null;
  maturity_date: string | null;
  first_payment_date: string | null;
  status: string;
  purpose: string | null;
  branch: { name: string } | null;
  schedule?: ScheduleItem[];
  created_at: string;
}

export interface ScheduleItem {
  installment_number: number;
  due_date: string;
  principal: number;
  interest: number;
  total_due: number;
  paid_amount: number;
  paid_date: string | null;
  status: string;
}

export interface Payment {
  id: string;
  amount: number;
  payment_method: string;
  reference_number: string | null;
  notes: string | null;
  collected_by: string | null;
  created_at: string;
}

export async function getLoans(): Promise<Loan[]> {
  const response = await api.get<Loan[]>('/loans');
  return response.data;
}

export async function getLoan(id: string): Promise<Loan> {
  const response = await api.get<Loan>(`/loans/${id}`);
  return response.data;
}

export async function getSchedule(loanId: string): Promise<ScheduleItem[]> {
  const response = await api.get<ScheduleItem[]>(`/loans/${loanId}/schedule`);
  return response.data;
}

export async function getPayments(loanId: string): Promise<Payment[]> {
  const response = await api.get<Payment[]>(`/loans/${loanId}/payments`);
  return response.data;
}
