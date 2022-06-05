<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class CatalogoConceptosViaticosController extends Controller
{
    /**
    * Obtiene el listado de todos los catalogos de la BD
    * @return \Illuminate\Http\Response
    **/

    public function listaConceptos()
    {
      $conceptos = \App\CatalogoConceptosViaticos::all();
      return response()->json($conceptos);
    }
}
