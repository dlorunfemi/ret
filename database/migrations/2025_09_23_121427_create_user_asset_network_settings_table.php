<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_asset_network_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('asset_network_id')->constrained('asset_networks')->cascadeOnDelete();
            $table->decimal('min_deposit', 24, 8)->nullable();
            $table->unsignedSmallInteger('deposit_confirmations')->nullable();
            $table->unsignedSmallInteger('withdraw_confirmations')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'asset_network_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_asset_network_settings');
    }
};
