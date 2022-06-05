<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RenunciaDBP extends Model
{
    protected $connection = 'csct';
    protected $fillable = ['formato', 'bajas_id'];
    protected $table = 'renuncias';
    public $timestamps = false;
}
