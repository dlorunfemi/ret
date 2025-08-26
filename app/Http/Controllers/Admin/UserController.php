<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()->latest()->paginate(20);
        $pendingWithdrawals = Transaction::query()->where('type', 'withdrawal')->where('status', 'pending')->latest()->paginate(20);

        return view('admin.dashboard', compact('users', 'pendingWithdrawals'));
    }

    public function updateRole(UpdateUserRoleRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();
        $user->update(['role' => $validated['role']]);

        return back()->with('status', 'User role updated');
    }

    public function approveWithdrawal(Request $request, Transaction $transaction): RedirectResponse
    {
        if ($transaction->type !== 'withdrawal' || $transaction->status !== 'pending') {
            return back()->withErrors(['tx' => 'Invalid transaction state']);
        }
        $transaction->update(['status' => 'approved']);

        return back()->with('status', 'Withdrawal approved');
    }
}
