<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockArticulo extends Model
{

    protected $fillable = ['cantidad', 'articulo_id', 'stocke_id'];
    protected $table = 'stock_articulos';
    public $timestamps = false;
}
