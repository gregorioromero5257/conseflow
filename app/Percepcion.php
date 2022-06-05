<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Percepcion extends Model
{
    protected $fillable = ['unidad', 'costo', 'importe', 'tipo_percepcione_id'];
    protected $table = 'percepciones';
    public $timestamps = false;
}
