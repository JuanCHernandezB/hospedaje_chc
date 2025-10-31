<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospedaje extends Model
{
    use HasFactory;

    // Nombre de la tabla (asegúrate que coincide con la migración)
    protected $table = 'hospedaje_chc';

    // Clave primaria si usaste un nombre personalizado (opcional)
    protected $primaryKey = 'id_Solicitante';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'Fecha_Hospedaje',
        'Nombre_Solicitante',
        'Apellido_Solicitante',
        'Documento_Solicitante',
        'Nombre_Acompanante',
        'Apellido_Acompanante',
        'Documento_Acompanante',
        'Carta_Solicitud',
        'Foto_Evidencia', // asegurarse mismo nombre que en migración
    ];
}