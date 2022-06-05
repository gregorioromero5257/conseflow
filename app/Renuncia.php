<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Renuncia extends Model
{
    protected $fillable = ['formato', 'bajas_id'];
    protected $table = 'renuncias';
    public $timestamps = false;
}
