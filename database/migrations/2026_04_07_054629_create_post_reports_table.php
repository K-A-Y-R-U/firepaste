<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('reason', ['link_caido', 'contenido_incorrecto', 'otro']);
            $table->text('description')->nullable();
            $table->string('ip_address', 45);
            $table->enum('status', ['pendiente', 'revisado', 'resuelto'])->default('pendiente');
            $table->timestamps();

            // Un usuario/IP solo puede reportar el mismo post una vez
            $table->unique(['post_id', 'ip_address']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_reports');
    }
};