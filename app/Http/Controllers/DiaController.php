<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use App\Dia;
use Illuminate\Validation\Rule;

class DiaController extends Controller
{

    /**
     * [index Consulta en la BD de todos los registro de la tabla dias]
     * @return [type] [description]
     */
    public function index()
    {
      $dias = DB::table('dias')
      ->select('dias.*')
      ->get();
      return response()->json(
          $dias->toArray()
      );
    }

    public function store(Request $request)
    {

    }

    public function update(Request $request)
    {

    }

    public function edit($id)
    {

    }

    public function show($id)
    {

    }



}
