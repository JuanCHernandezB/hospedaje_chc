<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ResetHospedajes extends Command
{
    /**
     * El nombre y firma del comando de Artisan.
     */
    protected $signature = 'reset:hospedajes';

    /**
     * La descripción del comando.
     */
    protected $description = 'Elimina todos los registros de hospedaje_chc, reinicia el autoincrement y limpia archivos en storage';

    /**
     * Ejecuta el comando.
     */
    public function handle()
    {
        $this->info(' Limpiando hospedajes...');

        try {
            //  Eliminar registros de la tabla
            DB::statement('DELETE FROM hospedaje_chc');

            //  Reiniciar autoincrement
            DB::statement('ALTER TABLE hospedaje_chc AUTO_INCREMENT = 1');

            //  Borrar archivos de cartas y fotos
            $cartasPath = storage_path('app/public/cartas');
            $fotosPath = storage_path('app/public/fotos');

            if (File::exists($cartasPath)) {
                File::cleanDirectory($cartasPath);
            }

            if (File::exists($fotosPath)) {
                File::cleanDirectory($fotosPath);
            }

            $this->info('Tabla limpiada y autoincrement reiniciado.');
            $this->info('Archivos de cartas y fotos eliminados correctamente.');
        } catch (\Exception $e) {
            $this->error(' Error: ' . $e->getMessage());
        }
    }
}
