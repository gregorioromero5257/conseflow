<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatConceptosA extends Model
{
    protected $fillable = ['nombre','porcentaje_pago'];
    protected $table = 'cat_conceptos_a';
    public $timestamps = false;
}
