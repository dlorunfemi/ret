<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChangeAdminPasswordRequest;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index(): View
    {
        $users = User::query()->latest()->paginate(20);
        $pendingWithdrawals = Transaction::query()
            ->with(['user', 'asset'])
            ->where('type', 'withdrawal')
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('admin.dashboard', compact('users', 'pendingWithdrawals'));
    }

    public function editPassword(): View
    {
        return view('admin.profile.password');
    }

    public function updatePassword(ChangeAdminPasswordRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $validated = $request->validated();

        $user->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        return back()->with('status', 'Password updated');
    }
}
