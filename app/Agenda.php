<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $fillable = ['title','date','descripcion','pagos_recurrentes_id','pagos_no_recurrentes_id'];
    protected $table = 'agenda';
    public $timestamps = false;
}
