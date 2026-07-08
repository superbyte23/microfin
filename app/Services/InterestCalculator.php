<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\LoanAmortizationSchedule;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class InterestCalculator
{
    public function generateSchedule(Loan $loan): Collection
    {
        return match ($loan->interest_type) {
            'flat' => $this->flatRate($loan),
            'declining' => $this->decliningBalance($loan),
            'add-on' => $this->addOnRate($loan),
            default => throw new \InvalidArgumentException("Unknown interest type: {$loan->interest_type}"),
        };
    }

    public function flatRate(Loan $loan): Collection
    {
        $principal = (float) $loan->principal_amount;
        $annualRate = (float) $loan->interest_rate / 100;
        $term = $loan->term_months;

        $monthlyPrincipal = $principal / $term;
        $monthlyInterest = ($principal * $annualRate) / 12;

        return $this->buildSchedule($loan, $term, $monthlyPrincipal, $monthlyInterest);
    }

    public function decliningBalance(Loan $loan): Collection
    {
        $principal = (float) $loan->principal_amount;
        $annualRate = (float) $loan->interest_rate / 100;
        $monthlyRate = $annualRate / 12;
        $term = $loan->term_months;

        $payment = $this->pmt($monthlyRate, $term, $principal);
        $balance = $principal;

        $schedules = collect();
        $paymentDate = $this->getFirstPaymentDate($loan);

        for ($i = 1; $i <= $term; $i++) {
            $interest = round($balance * $monthlyRate, 2);
            $principalPart = round($payment - $interest, 2);

            if ($i === $term) {
                $principalPart = $balance;
            }

            $balanceBefore = round($balance, 2);
            $balance -= $principalPart;

            $schedule = new LoanAmortizationSchedule([
                'installment_number' => $i,
                'due_date' => $paymentDate->copy(),
                'principal' => $principalPart,
                'interest' => $interest,
                'total_due' => round($principalPart + $interest, 2),
                'paid_amount' => 0,
                'status' => 'pending',
            ]);

            $schedule->balance_before = $balanceBefore;
            $schedule->balance_after = round(max($balance, 0), 2);

            $schedules->push($schedule);
            $paymentDate->addMonth();
        }

        return $schedules;
    }

    public function addOnRate(Loan $loan): Collection
    {
        $principal = (float) $loan->principal_amount;
        $annualRate = (float) $loan->interest_rate / 100;
        $term = $loan->term_months;

        $totalInterest = ($principal * $annualRate) * ($term / 12);
        $totalAmount = $principal + $totalInterest;

        $monthlyPrincipal = $principal / $term;
        $monthlyInterest = $totalInterest / $term;

        return $this->buildSchedule($loan, $term, $monthlyPrincipal, $monthlyInterest);
    }

    private function buildSchedule(Loan $loan, int $term, float $monthlyPrincipal, float $monthlyInterest): Collection
    {
        $schedules = collect();
        $paymentDate = $this->getFirstPaymentDate($loan);
        $balance = (float) $loan->principal_amount;

        for ($i = 1; $i <= $term; $i++) {
            $principalPart = round($monthlyPrincipal, 2);
            $interestPart = round($monthlyInterest, 2);

            if ($i === $term) {
                $principalPart = round($balance, 2);
            }

            $balanceBefore = round($balance, 2);
            $balance -= $principalPart;

            $schedule = new LoanAmortizationSchedule([
                'installment_number' => $i,
                'due_date' => $paymentDate->copy(),
                'principal' => $principalPart,
                'interest' => $interestPart,
                'total_due' => round($principalPart + $interestPart, 2),
                'paid_amount' => 0,
                'status' => 'pending',
            ]);

            $schedule->balance_before = $balanceBefore;
            $schedule->balance_after = round(max($balance, 0), 2);

            $schedules->push($schedule);
            $paymentDate->addMonth();
        }

        return $schedules;
    }

    public function getTotalInterest(Loan $loan): float
    {
        return match ($loan->interest_type) {
            'flat' => (float) $loan->principal_amount * ((float) $loan->interest_rate / 100) * ($loan->term_months / 12),
            'declining' => $this->getDecliningTotalInterest($loan),
            'add-on' => (float) $loan->principal_amount * ((float) $loan->interest_rate / 100) * ($loan->term_months / 12),
            default => 0,
        };
    }

    private function getDecliningTotalInterest(Loan $loan): float
    {
        $principal = (float) $loan->principal_amount;
        $monthlyRate = ((float) $loan->interest_rate / 100) / 12;
        $term = $loan->term_months;
        $payment = $this->pmt($monthlyRate, $term, $principal);

        return round($payment * $term - $principal, 2);
    }

    private function pmt(float $rate, int $nper, float $pv): float
    {
        if ($rate == 0) {
            return $pv / $nper;
        }
        $factor = (1 + $rate) ** $nper;
        return round($pv * ($rate * $factor) / ($factor - 1), 2);
    }

    private function getFirstPaymentDate(Loan $loan): Carbon
    {
        $releaseDate = $loan->release_date
            ? Carbon::parse($loan->release_date)
            : Carbon::now();

        return match ($loan->payment_frequency) {
            'weekly' => $releaseDate->addWeek(),
            'bi-monthly' => $releaseDate->addDays(15),
            'daily' => $releaseDate->addDay(),
            default => $releaseDate->addMonth(),
        };
    }
}
