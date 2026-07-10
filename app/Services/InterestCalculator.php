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
        $termMonths = $loan->term_months;
        $frequency = $loan->payment_frequency;

        $numInstallments = $this->getNumInstallments($termMonths, $frequency);
        $totalInterest = ($principal * $annualRate) * ($termMonths / 12);

        $installmentPrincipal = $principal / $numInstallments;
        $installmentInterest = $totalInterest / $numInstallments;

        return $this->buildSchedule($loan, $numInstallments, $installmentPrincipal, $installmentInterest);
    }

    public function decliningBalance(Loan $loan): Collection
    {
        $principal = (float) $loan->principal_amount;
        $annualRate = (float) $loan->interest_rate / 100;
        $frequency = $loan->payment_frequency;
        $termMonths = $loan->term_months;

        $periodsPerYear = $this->getPeriodsPerYear($frequency);
        $numInstallments = $this->getNumInstallments($termMonths, $frequency);
        $ratePerPeriod = $annualRate / $periodsPerYear;

        $payment = $this->pmt($ratePerPeriod, $numInstallments, $principal);
        $balance = $principal;

        $schedules = collect();
        $paymentDate = $this->getFirstPaymentDate($loan);

        for ($i = 1; $i <= $numInstallments; $i++) {
            $interest = round($balance * $ratePerPeriod, 2);
            $principalPart = round($payment - $interest, 2);

            if ($i === $numInstallments) {
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
            $this->advanceDate($paymentDate, $frequency);
        }

        return $schedules;
    }

    public function addOnRate(Loan $loan): Collection
    {
        $principal = (float) $loan->principal_amount;
        $annualRate = (float) $loan->interest_rate / 100;
        $termMonths = $loan->term_months;
        $frequency = $loan->payment_frequency;

        $numInstallments = $this->getNumInstallments($termMonths, $frequency);
        $totalInterest = ($principal * $annualRate) * ($termMonths / 12);
        $totalAmount = $principal + $totalInterest;

        $installmentPrincipal = $principal / $numInstallments;
        $installmentInterest = $totalInterest / $numInstallments;

        return $this->buildSchedule($loan, $numInstallments, $installmentPrincipal, $installmentInterest);
    }

    private function buildSchedule(Loan $loan, int $numInstallments, float $installmentPrincipal, float $installmentInterest): Collection
    {
        $schedules = collect();
        $paymentDate = $this->getFirstPaymentDate($loan);
        $balance = (float) $loan->principal_amount;

        for ($i = 1; $i <= $numInstallments; $i++) {
            $principalPart = round($installmentPrincipal, 2);
            $interestPart = round($installmentInterest, 2);

            if ($i === $numInstallments) {
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
            $this->advanceDate($paymentDate, $loan->payment_frequency);
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
        $frequency = $loan->payment_frequency;
        $termMonths = $loan->term_months;

        $periodsPerYear = $this->getPeriodsPerYear($frequency);
        $numInstallments = $this->getNumInstallments($termMonths, $frequency);
        $ratePerPeriod = ((float) $loan->interest_rate / 100) / $periodsPerYear;

        $payment = $this->pmt($ratePerPeriod, $numInstallments, $principal);

        return round($payment * $numInstallments - $principal, 2);
    }

    private function getPeriodsPerYear(string $frequency): int
    {
        return match ($frequency) {
            'weekly' => 52,
            'bi-monthly' => 24,
            'daily' => 365,
            default => 12,
        };
    }

    private function getNumInstallments(int $termMonths, string $frequency): int
    {
        return match ($frequency) {
            'weekly' => (int) round($termMonths * 52 / 12),
            'bi-monthly' => $termMonths * 2,
            'daily' => (int) round($termMonths * 365 / 12),
            default => $termMonths,
        };
    }

    private function advanceDate(Carbon $date, string $frequency): void
    {
        match ($frequency) {
            'weekly' => $date->addWeek(),
            'bi-monthly' => $date->addDays(15),
            'daily' => $date->addDay(),
            default => $date->addMonth(),
        };
    }

    private function pmt(float $rate, int $nper, float $pv): float
    {
        if ($rate == 0) {
            return $pv / $nper;
        }
        $factor = (1 + $rate) ** $nper;
        return round($pv * ($rate * $factor) / ($factor - 1), 2);
    }

    public function getFirstPaymentDate(Loan $loan): Carbon
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
