<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartidasRetorno extends Model
{
    protected $table = 'partidas_retorno';
    protected $fillable = ['articulo_id','cantidad_entrada','proyecto_id','tipo_salida','salida_id','cantidad_defectuoso','salida_retorno_id','partida_id'];
    public $timestamps = false;
}
