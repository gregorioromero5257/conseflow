<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deduccion extends Model
{
    protected $fillable = ['unidad', 'costo', 'importe', 'tipo_deduccione_id'];
    protected $table = 'deducciones';
    public $timestamps = false;
}
