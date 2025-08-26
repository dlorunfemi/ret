<?php

namespace App\Providers;

use App\Models\Asset;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserBalance;
use App\Notifications\UserActionOccurred;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Price service binding
        $this->app->singleton('price.service', function () {
            return new class
            {
                public function getUsdPrices(array $symbols): array
                {
                    $cacheKey = 'prices:'.implode(',', $symbols);

                    return Cache::remember($cacheKey, now()->addMinutes(2), function () use ($symbols) {
                        // Map common symbols to CoinGecko IDs
                        $symbolToId = [
                            'BTC' => 'bitcoin',
                            'ETH' => 'ethereum',
                            'USDT' => 'tether',
                            'USDC' => 'usd-coin',
                        ];
                        $ids = implode(',', array_map(function ($s) use ($symbolToId) {
                            return $symbolToId[$s] ?? strtolower($s);
                        }, $symbols));
                        $response = Http::timeout(8)->retry(2, 200)->get('https://api.coingecko.com/api/v3/simple/price', [
                            'ids' => $ids,
                            'vs_currencies' => 'usd',
                        ]);
                        if ($response->failed()) {
                            return [];
                        }
                        $data = $response->json();
                        $prices = [];
                        foreach ($symbols as $symbol) {
                            $id = $symbolToId[$symbol] ?? strtolower($symbol);
                            $prices[$symbol] = $data[$id]['usd'] ?? null;
                        }

                        return $prices;
                    });
                }
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Transaction notifications
        Transaction::created(function (Transaction $transaction): void {
            $asset = $transaction->asset()->first();
            $symbol = $asset?->symbol ?? 'ASSET';
            $user = $transaction->user()->first();

            if ($transaction->type === 'deposit') {
                $title = 'Deposit Created';
                $message = "Your deposit of {$transaction->amount} {$symbol} has been created.";
            } else {
                $title = 'Withdrawal Submitted';
                $message = "Your withdrawal of {$transaction->amount} {$symbol} is pending review.";
            }

            if ($user) {
                $user->notify(new UserActionOccurred(
                    title: $title,
                    message: $message,
                    actionUrl: route('withdrawal.records')
                ));
            }

            $admins = User::query()->where('role', 'admin')->get();
            if ($admins->isNotEmpty()) {
                Notification::send($admins, new UserActionOccurred(
                    title: "{$title} (User)",
                    message: ($user?->email ? $user->email.' - ' : '').$message,
                    actionUrl: route('admin.dashboard')
                ));
            }
        });

        Transaction::updated(function (Transaction $transaction): void {
            if (! $transaction->wasChanged('status')) {
                return;
            }

            $asset = $transaction->asset()->first();
            $symbol = $asset?->symbol ?? 'ASSET';
            $user = $transaction->user()->first();
            $status = $transaction->status;

            $title = 'Transaction Update';
            $message = "Your {$transaction->type} of {$transaction->amount} {$symbol} status is now {$status}.";

            if ($user) {
                $user->notify(new UserActionOccurred(
                    title: $title,
                    message: $message,
                    actionUrl: route($transaction->type === 'withdrawal' ? 'withdrawal.records' : 'deposit.records')
                ));
            }
        });

        // Create zero balances for all assets when a new user is created
        User::created(function (User $user): void {
            $assets = Asset::query()->get(['id']);
            foreach ($assets as $asset) {
                UserBalance::query()->firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'asset_id' => $asset->id,
                    ],
                    [
                        'available' => 0,
                        'frozen' => 0,
                    ]
                );
            }
        });
    }
}
