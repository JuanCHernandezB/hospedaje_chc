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
    Schema::create('hospedaje_chc', function (Blueprint $table) {
        $table->id('id_Solicitante');
        $table->date('Fecha_Hospedaje');
        $table->string('Nombre_Solicitante', 100);
        $table->string('Apellido_Solicitante', 100);
        $table->string('Documento_Solicitante', 50);
        $table->string('Nombre_Acompanante', 100)->nullable();
        $table->string('Apellido_Acompanante', 100)->nullable();
        $table->string('Documento_Acompanante', 50)->nullable();
        $table->string('Carta_Solicitud', 255)->nullable();   // ruta archivo PDF/JPG
        $table->string('Foto_Evidencia', 255)->nullable();    // ruta archivo imagen
        $table->timestamps();
    });
}
  

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospedaje_chc');
    }
};
