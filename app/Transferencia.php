<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transferencia extends Model
{
    protected $fillable = ['codigo','articulos','precio_unitario','cantidad','importe','total'];
    protected $table = 'transferencia';
    public $timestamps = false;
}
