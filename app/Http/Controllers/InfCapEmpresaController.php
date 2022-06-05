<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InfCapEmpresaController extends Controller
{
    //
    public function index(Request $request)
    {
        $infEmpresa = DB::table('empresas')
            ->select('empresas.*')
            ->get();
        return response()->json($infEmpresa);
    }

    public function show($id)
    {
        $infEmpresa = DB::table('empresas')
            ->select('empresas.*')
            ->where('empresas.id',$id)
            ->first();

        return response()->json($infEmpresa);
    }
}
