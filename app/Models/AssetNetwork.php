<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetNetwork extends Model
{
    /** @use HasFactory<\Database\Factories\AssetNetworkFactory> */
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'network_name',
        'deposit_address',
        'qr_path',
        'min_deposit',
        'deposit_confirmations',
        'withdraw_confirmations',
        'is_active',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
