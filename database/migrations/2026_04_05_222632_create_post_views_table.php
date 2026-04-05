<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->string('ip_address', 45); // IPv4 e IPv6
            $table->timestamp('viewed_at');

            // Índice compuesto para búsquedas rápidas post + IP
            $table->index(['post_id', 'ip_address']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_views');
    }
};