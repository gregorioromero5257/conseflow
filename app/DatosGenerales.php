<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatosGenerales extends Model
{
    //
    protected $fillable = ['rfc','razon_social','nombre','regimenfiscal','calle','numero_interior','numero_exterior','codigo_postal','colonia','municipio','entidad_federativa'];
    protected $table = 'datosgenerales';
    public $timestamps = false;
}
