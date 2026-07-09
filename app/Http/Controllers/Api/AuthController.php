<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'member_id' => 'required|string',
            'pin' => 'required|string|digits:4',
        ]);

        $client = Client::with('branch')->where('member_id', $request->member_id)->first();

        if (! $client || ! $client->pin || ! Hash::check($request->pin, $client->pin)) {
            throw ValidationException::withMessages([
                'member_id' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($client->status !== 'active') {
            throw ValidationException::withMessages([
                'member_id' => ['This account is inactive.'],
            ]);
        }

        $token = $client->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'client' => new ClientResource($client),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json(new ClientResource($request->user()->load('branch')));
    }
}
