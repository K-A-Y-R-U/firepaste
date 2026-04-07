<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanPostViews extends Command
{
    /**
     * Elimina registros de post_views con más de 24 horas.
     *
     * 1. Mantiene la tabla pequeña (evita acumulación infinita).
     * 2. Permite que la misma IP cuente como visita nueva después de 24h.
     *
     * Programado en routes/console.php para correr diariamente.
     */
    protected $signature   = 'app:clean-post-views';
    protected $description = 'Elimina registros de visitas de posts con más de 24 horas';

    public function handle(): int
    {
        $deleted = DB::table('post_views')
            ->where('viewed_at', '<', now()->subHours(24))
            ->delete();

        $this->info("✅ Eliminados {$deleted} registros de post_views.");

        return self::SUCCESS;
    }
}