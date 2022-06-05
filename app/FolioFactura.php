<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FolioFactura extends Model
{
    protected $fillable = ['serie','consecutivo','tipo_factura','rfc'];
    protected $table = 'folio_factura';
    public $timestamps = false;
}
