<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatalogoConceptosViaticos extends Model
{
    //
    protected $fillable = ['nombre'];
    protected $table = 'catalogo_conceptos_viaticos';
    public $timestamps = false;
}
