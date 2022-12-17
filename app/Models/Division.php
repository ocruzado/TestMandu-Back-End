<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $table = 'division';

    protected $primaryKey = 'divi_IdDivision';

    protected $fillable = [
        'disu_IdDivisionSuperior', // 0 : NO TIENE DIVISÓN SUPERIOR | #: ID DE DIVISIÓN SUPERIOR

        'divi_Nombre', // UNICÓ, NO SUPERIOR A 45 CARACTERES

        'divi_Nivel', // ENTERO POSITIVO ALEATORIO
        'divi_Colaborador_Cantidad', // ENTERO POSITIVO ALEATORIO

        'divi_Embajador_Nombre'
    ];

    public $timestamps = false;
}
