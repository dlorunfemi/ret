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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
            $table->foreignId('asset_network_id')->nullable()->constrained('asset_networks')->nullOnDelete();
            $table->enum('type', ['deposit', 'withdrawal']);
            $table->decimal('amount', 30, 12);
            $table->decimal('fee', 30, 12)->default(0);
            $table->string('address')->nullable();
            $table->string('tx_hash')->nullable()->index();
            $table->enum('status', ['pending', 'confirmed', 'failed', 'cancelled', 'rejected', 'approved'])->default('pending');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
