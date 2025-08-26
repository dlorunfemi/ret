<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserBalanceRequest;
use App\Http\Requests\Admin\UpdateUserBalanceRequest;
use App\Models\UserBalance;
use App\Notifications\AdminActionOccurred;
use App\Notifications\UserActionOccurred;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;

class UserBalanceController extends Controller
{
    public function index(): View
    {
        $balances = UserBalance::query()->with(['user', 'asset'])->latest()->paginate(25);

        return view('admin.balances.index', compact('balances'));
    }

    public function create(): View
    {
        return view('admin.balances.create');
    }

    public function store(StoreUserBalanceRequest $request): RedirectResponse
    {
        $balance = UserBalance::query()->create($request->validated());

        if ($balance->user) {
            $balance->user->notify(new UserActionOccurred('Balance Created', 'A balance was created for '.($balance->asset->symbol ?? 'asset')));
        }

        Notification::route('mail', config('mail.from.address'))
            ->notify(new AdminActionOccurred('Balance Created', 'ID: '.$balance->id));

        return redirect()->route('admin.balances.index')->with('status', 'Balance created');
    }

    public function edit(UserBalance $balance): View
    {
        return view('admin.balances.edit', ['balance' => $balance]);
    }

    public function update(UpdateUserBalanceRequest $request, UserBalance $balance): RedirectResponse
    {
        $balance->update($request->validated());

        if ($balance->user) {
            $balance->user->notify(new UserActionOccurred('Balance Updated', 'Your balance was updated for '.($balance->asset->symbol ?? 'asset')));
        }

        Notification::route('mail', config('mail.from.address'))
            ->notify(new AdminActionOccurred('Balance Updated', 'ID: '.$balance->id));

        return redirect()->route('admin.balances.index')->with('status', 'Balance updated');
    }

    public function destroy(UserBalance $balance): RedirectResponse
    {
        $id = $balance->id;
        $user = $balance->user;
        $assetSymbol = $balance->asset->symbol ?? 'asset';
        $balance->delete();

        if ($user) {
            $user->notify(new UserActionOccurred('Balance Deleted', 'A balance for '.$assetSymbol.' was deleted'));
        }

        Notification::route('mail', config('mail.from.address'))
            ->notify(new AdminActionOccurred('Balance Deleted', 'ID: '.$id));

        return back()->with('status', 'Balance deleted');
    }
}
