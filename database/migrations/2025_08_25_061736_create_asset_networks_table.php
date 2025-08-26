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
        Schema::create('asset_networks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
            $table->string('network_name');
            $table->string('deposit_address')->nullable();
            $table->string('qr_path')->nullable();
            $table->decimal('min_deposit', 24, 8)->nullable();
            $table->unsignedSmallInteger('deposit_confirmations')->nullable();
            $table->unsignedSmallInteger('withdraw_confirmations')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_networks');
    }
};
