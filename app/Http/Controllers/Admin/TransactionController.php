<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTransactionRequest;
use App\Http\Requests\Admin\UpdateTransactionRequest;
use App\Models\Asset;
use App\Models\AssetNetwork;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\AdminActionOccurred;
use App\Notifications\UserActionOccurred;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;

class TransactionController extends Controller
{
    public function index(): View
    {
        $transactions = Transaction::query()->with(['user', 'asset', 'network'])->latest()->paginate(25);

        return view('admin.transactions.index', compact('transactions'));
    }

    public function create(): View
    {
        $users = User::query()->orderBy('email')->get(['id', 'name', 'email']);
        $assets = Asset::query()->orderBy('symbol')->get(['id', 'symbol', 'name']);
        $networks = AssetNetwork::query()->orderBy('network_name')->get(['id', 'network_name']);

        return view('admin.transactions.create', [
            'users' => $users,
            'assets' => $assets,
            'networks' => $networks,
        ]);
    }

    public function store(StoreTransactionRequest $request): RedirectResponse
    {
        $tx = Transaction::query()->create($request->validated());

        // Notify the affected user
        $user = User::query()->find($tx->user_id);
        if ($user) {
            $user->notify(new UserActionOccurred(
                'Transaction Created',
                'A transaction was created on your account. Type: '.$tx->type.', Amount: '.$tx->amount
            ));
        }

        // Notify admins
        Notification::route('mail', config('mail.from.address'))
            ->notify(new AdminActionOccurred('Transaction Created', 'ID: '.$tx->id));

        return redirect()->route('admin.transactions.index')->with('status', 'Transaction created');
    }

    public function edit(Transaction $transaction): View
    {
        $users = User::query()->orderBy('email')->get(['id', 'name', 'email']);
        $assets = Asset::query()->orderBy('symbol')->get(['id', 'symbol', 'name']);
        $networks = AssetNetwork::query()->orderBy('network_name')->get(['id', 'network_name']);

        return view('admin.transactions.edit', [
            'transaction' => $transaction,
            'users' => $users,
            'assets' => $assets,
            'networks' => $networks,
        ]);
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction): RedirectResponse
    {
        $transaction->update($request->validated());

        // Notify user
        $user = $transaction->user;
        if ($user) {
            $user->notify(new UserActionOccurred(
                'Transaction Updated',
                'Your transaction has been updated. Status: '.$transaction->status
            ));
        }

        Notification::route('mail', config('mail.from.address'))
            ->notify(new AdminActionOccurred('Transaction Updated', 'ID: '.$transaction->id));

        return redirect()->route('admin.transactions.index')->with('status', 'Transaction updated');
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        $id = $transaction->id;
        $user = $transaction->user;
        $transaction->delete();

        if ($user) {
            $user->notify(new UserActionOccurred(
                'Transaction Deleted',
                'A transaction on your account was deleted. ID: '.$id
            ));
        }

        Notification::route('mail', config('mail.from.address'))
            ->notify(new AdminActionOccurred('Transaction Deleted', 'ID: '.$id));

        return back()->with('status', 'Transaction deleted');
    }
}
