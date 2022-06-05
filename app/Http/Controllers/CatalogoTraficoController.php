<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CatalogoTrafico;

class CatalogoTraficoController extends Controller
{
    //

    public function ver()
    {
      $catalogo = CatalogoTrafico::get();
      return response()->json($catalogo);
    }
}
