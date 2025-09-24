<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAssetNetworkSetting extends Model
{
    /** @use HasFactory<\Database\Factories\UserAssetNetworkSettingFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'asset_network_id',
        'min_deposit',
        'deposit_confirmations',
        'withdraw_confirmations',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function assetNetwork(): BelongsTo
    {
        return $this->belongsTo(AssetNetwork::class);
    }
}

