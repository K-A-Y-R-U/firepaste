<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Agrega índice ÚNICO (post_id, ip_address) de forma segura.
     */
    public function up(): void
    {
        // 1. Eliminar duplicados existentes (importante antes del índice único)
        DB::statement('
            DELETE pv1 FROM post_views pv1
            INNER JOIN post_views pv2 
            WHERE pv1.id > pv2.id 
              AND pv1.post_id = pv2.post_id 
              AND pv1.ip_address = pv2.ip_address
        ');

        // 2. Cambiar índice normal → índice ÚNICO (en una sola consulta)
        // Esto evita el error 1553 con la foreign key
        DB::statement('
            ALTER TABLE post_views 
            DROP INDEX IF EXISTS post_views_post_id_ip_address_index,
            ADD UNIQUE KEY post_views_post_ip_unique (post_id, ip_address)
        ');
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        DB::statement('
            ALTER TABLE post_views 
            DROP INDEX post_views_post_ip_unique,
            ADD INDEX post_views_post_id_ip_address_index (post_id, ip_address)
        ');
    }
};