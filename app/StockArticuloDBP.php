<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockArticuloDBP extends Model
{
    protected $connection = 'csct';
    protected $fillable = ['cantidad', 'articulo_id', 'stocke_id'];
    protected $table = 'stock_articulos';
    public $timestamps = false;
}
