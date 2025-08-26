<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetNetwork;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assets = [
            ['symbol' => 'USDT', 'name' => 'Tether', 'precision' => 6],
            ['symbol' => 'USDC', 'name' => 'USD Coin', 'precision' => 6],
            ['symbol' => 'BTC', 'name' => 'Bitcoin', 'precision' => 8],
            ['symbol' => 'ETH', 'name' => 'Ethereum', 'precision' => 18],
        ];

        foreach ($assets as $data) {
            $asset = Asset::query()->updateOrCreate(
                ['symbol' => $data['symbol']],
                [
                    'name' => $data['name'],
                    'precision' => $data['precision'],
                    'is_active' => true,
                ]
            );

            $networks = match ($data['symbol']) {
                'USDT' => ['BSC (BEP20)', 'TRON (TRC20)', 'Ethereum (ERC20)', 'Solana'],
                'USDC' => ['Ethereum Mainnet', 'Polygon', 'Arbitrum'],
                'BTC' => ['Bitcoin Mainnet', 'Lightning Network'],
                'ETH' => ['Ethereum Mainnet', 'Polygon', 'Arbitrum'],
                default => [],
            };

            foreach ($networks as $networkName) {
                AssetNetwork::query()->updateOrCreate(
                    [
                        'asset_id' => $asset->id,
                        'network_name' => $networkName,
                    ],
                    [
                        'deposit_address' => null,
                        'qr_path' => 'img/qr/qr-code.png',
                        'min_deposit' => null,
                        'deposit_confirmations' => null,
                        'withdraw_confirmations' => null,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
