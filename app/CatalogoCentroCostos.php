<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatalogoCentroCostos extends Model
{
    protected $fillable = ['nombre','catalogo_centro_costos_id','sub','estado'];
    protected $table = 'catalogo_centro_costos';
    public $timestamps = false;
}
