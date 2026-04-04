<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gift_code_redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gift_code_id')->constrained('gift_codes');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamp('redeemed_at')->nullable(); // Cuándo se canjeó
            $table->timestamp('vip_starts_at')->nullable(); // Cuándo comienza el VIP
            $table->timestamp('vip_ends_at')->nullable(); // Cuándo termina el VIP
            $table->string('ip_address')->nullable(); // IP desde donde se canjeó
            $table->timestamps();
            
            // Un usuario no puede canjear el mismo código más de una vez
            $table->unique(['gift_code_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gift_code_redemptions');
    }
};