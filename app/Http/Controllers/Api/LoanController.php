<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoanResource;
use App\Http\Resources\LoanScheduleResource;
use App\Http\Resources\LoanTransactionResource;
use App\Models\Loan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $loans = $request->user()
            ->loans()
            ->with(['product', 'branch'])
            ->latest()
            ->get();

        return response()->json(LoanResource::collection($loans));
    }

    public function show(Request $request, Loan $loan): JsonResponse
    {
        if ($loan->client_id !== $request->user()->id) {
            abort(404);
        }

        $loan->load('product', 'branch', 'amortizationSchedule');

        return response()->json(new LoanResource($loan));
    }

    public function schedule(Request $request, Loan $loan): JsonResponse
    {
        if ($loan->client_id !== $request->user()->id) {
            abort(404);
        }

        $schedule = $loan->amortizationSchedule()->orderBy('installment_number')->get();

        return response()->json(LoanScheduleResource::collection($schedule));
    }

    public function payments(Request $request, Loan $loan): JsonResponse
    {
        if ($loan->client_id !== $request->user()->id) {
            abort(404);
        }

        $payments = $loan->transactions()->latest()->get();

        return response()->json(LoanTransactionResource::collection($payments));
    }
}
