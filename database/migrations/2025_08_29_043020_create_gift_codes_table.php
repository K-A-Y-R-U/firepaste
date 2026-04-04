<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gift_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique(); // Código único
            $table->integer('vip_days'); // Días de VIP que otorga
            $table->timestamp('expires_at')->nullable(); // Cuándo expira el código
            $table->boolean('is_active')->default(true); // Si está activo
            $table->integer('max_uses')->default(1); // Máximo de usos permitidos
            $table->integer('used_count')->default(0); // Veces que se ha usado
            $table->foreignId('created_by')->constrained('hexa_admins'); // Admin que lo creó
            $table->text('description')->nullable(); // Descripción del código
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gift_codes');
    }
};