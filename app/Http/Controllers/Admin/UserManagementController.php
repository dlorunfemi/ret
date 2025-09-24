<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserBalancesRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\Admin\UpdateUserNetworkSettingsRequest;
use App\Models\Asset;
use App\Models\User;
use App\Models\UserBalance;
use App\Models\UserAssetNetworkSetting;
use App\Notifications\AdminActionOccurred;
use App\Notifications\UserActionOccurred;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;

class UserManagementController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->with(['balances.asset'])
            ->latest()
            ->paginate(20);

        // Collect symbols for visible users' assets and fetch USD prices
        $symbols = $users->getCollection()
            ->flatMap(function (User $user) {
                return $user->balances->map(function (UserBalance $balance) {
                    return $balance->asset?->symbol;
                });
            })
            ->filter()
            ->unique()
            ->values()
            ->all();

        /** @var object{getUsdPrices: callable} $priceService */
        $priceService = app('price.service');
        $prices = $symbols ? $priceService->getUsdPrices($symbols) : [];

        return view('admin.users.index', compact('users', 'prices'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = User::query()->create($data);

        // Send email verification to the newly created user
        event(new Registered($user));

        // Notify user
        $user->notify(new UserActionOccurred('Account Created', 'An admin created your account.'));

        // Notify admins
        Notification::route('mail', config('mail.from.address'))
            ->notify(new AdminActionOccurred('User Created', 'Email: ' . $user->email));

        return redirect()->route('admin.users.index')->with('status', 'User created');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();
        if (empty($data['password'])) {
            unset($data['password']);
        }
        $user->update($data);

        $user->notify(new UserActionOccurred('Account Updated', 'Your account was updated by an admin.'));

        Notification::route('mail', config('mail.from.address'))
            ->notify(new AdminActionOccurred('User Updated', 'Email: ' . $user->email));

        return redirect()->route('admin.users.index')->with('status', 'User updated');
    }

    public function destroy(User $user): RedirectResponse
    {
        $email = $user->email;
        $user->delete();

        Notification::route('mail', config('mail.from.address'))
            ->notify(new AdminActionOccurred('User Deleted', 'Email: ' . $email));

        return back()->with('status', 'User deleted');
    }

    /**
     * Return all balances for a user across all assets.
     */
    public function balances(User $user): JsonResponse
    {
        $assets = Asset::query()->orderBy('symbol')->get(['id', 'symbol', 'name', 'precision']);
        $existing = UserBalance::query()
            ->where('user_id', $user->id)
            ->get()
            ->keyBy('asset_id');

        $data = $assets->map(function ($asset) use ($existing) {
            $balance = $existing->get($asset->id);

            return [
                'asset_id' => $asset->id,
                'asset_symbol' => $asset->symbol,
                'asset_name' => $asset->name,
                'precision' => $asset->precision,
                'available' => $balance?->available ?? 0,
                'frozen' => $balance?->frozen ?? 0,
            ];
        })->values();

        return response()->json(['data' => $data]);
    }

    /**
     * Bulk update balances for a user.
     */
    public function updateBalances(UpdateUserBalancesRequest $request, User $user): JsonResponse
    {
        $items = $request->validated('balances');

        foreach ($items as $item) {
            UserBalance::query()->updateOrCreate(
                [
                    'user_id' => $user->id,
                    'asset_id' => $item['asset_id'],
                ],
                [
                    'available' => $item['available'],
                    'frozen' => $item['frozen'] ?? 0,
                ]
            );
        }

        $user->notify(new UserActionOccurred('Balances Updated', 'Your asset balances were updated by an admin.'));
        Notification::route('mail', config('mail.from.address'))
            ->notify(new AdminActionOccurred('User Balances Updated', 'Email: ' . $user->email));

        return response()->json(['status' => 'ok']);
    }

    /**
     * Return all asset networks with the user's overrides merged.
     */
    public function networkSettings(User $user): JsonResponse
    {
        $assets = Asset::query()->with('networks')->orderBy('symbol')->get();
        $overrides = UserAssetNetworkSetting::query()->where('user_id', $user->id)->get()->keyBy('asset_network_id');

        $data = $assets->flatMap(function ($asset) use ($overrides) {
            return $asset->networks->map(function ($n) use ($asset, $overrides) {
                $ov = $overrides->get($n->id);
                return [
                    'asset_symbol' => $asset->symbol,
                    'asset_name' => $asset->name,
                    'asset_network_id' => $n->id,
                    'network_name' => $n->network_name,
                    'min_deposit' => $ov?->min_deposit,
                    'deposit_confirmations' => $ov?->deposit_confirmations,
                    'withdraw_confirmations' => $ov?->withdraw_confirmations,
                    'defaults' => [
                        'min_deposit' => $n->min_deposit,
                        'deposit_confirmations' => $n->deposit_confirmations,
                        'withdraw_confirmations' => $n->withdraw_confirmations,
                    ],
                ];
            });
        })->values();

        return response()->json(['data' => $data]);
    }

    /**
     * Update per-user network settings (overrides).
     */
    public function updateNetworkSettings(UpdateUserNetworkSettingsRequest $request, User $user): JsonResponse
    {
        $items = $request->validated('network_settings');

        foreach ($items as $item) {
            UserAssetNetworkSetting::query()->updateOrCreate(
                [
                    'user_id' => $user->id,
                    'asset_network_id' => $item['asset_network_id'],
                ],
                [
                    'min_deposit' => $item['min_deposit'] ?? null,
                    'deposit_confirmations' => $item['deposit_confirmations'] ?? null,
                    'withdraw_confirmations' => $item['withdraw_confirmations'] ?? null,
                ]
            );
        }

        return response()->json(['status' => 'ok']);
    }
}
