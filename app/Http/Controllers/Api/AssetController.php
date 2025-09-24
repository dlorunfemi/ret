<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\UserAssetNetworkSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assets = Asset::query()->where('is_active', true)->with('networks')->get();
        $user = Auth::user();
        $overridesByNetworkId = collect();
        if ($user) {
            $overridesByNetworkId = UserAssetNetworkSetting::query()
                ->where('user_id', $user->id)
                ->get()
                ->keyBy('asset_network_id');
        }
        $symbols = $assets->pluck('symbol')->values()->all();
        $prices = App::make('price.service')->getUsdPrices($symbols);

        return response()->json([
            'data' => $assets->map(function ($a) use ($prices, $overridesByNetworkId) {
                return [
                    'id' => $a->id,
                    'symbol' => $a->symbol,
                    'name' => $a->name,
                    'precision' => $a->precision,
                    'price_usd' => $prices[$a->symbol] ?? null,
                    'networks' => $a->networks->map(function ($n) use ($overridesByNetworkId) {
                        $ov = $overridesByNetworkId->get($n->id);
                        return [
                            'id' => $n->id,
                            'name' => $n->network_name,
                            'deposit_address' => $n->deposit_address,
                            'qr_path' => $n->qr_path,
                            'min_deposit' => $ov?->min_deposit ?? $n->min_deposit,
                            'deposit_confirmations' => $ov?->deposit_confirmations ?? $n->deposit_confirmations,
                            'withdraw_confirmations' => $ov?->withdraw_confirmations ?? $n->withdraw_confirmations,
                        ];
                    })->values(),
                ];
            })->values(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
