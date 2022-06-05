<?php

namespace App\CalidadModels;

use Illuminate\Database\Eloquent\Model;

class NomenclaturaVisual  extends Model
{
    protected $fillable = [
        "inspec_visual_id",
        "nomen_id"
    ];
    protected $table = "calidad_nomens_visual";
    public $timestamps = false;
}
