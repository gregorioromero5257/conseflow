<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatalogoConceptosViaticosDBP extends Model
{
    //
    protected $connection = 'csct';
    protected $fillable = ['nombre'];
    protected $table = 'catalogo_conceptos_viaticos';
    public $timestamps = false;
}
