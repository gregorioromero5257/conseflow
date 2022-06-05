<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EdoCivilController extends Controller
{
    public function index()
    {

        $estados = DB::table('estados_civiles')
            ->select('estados_civiles.*')        
            ->orderBy('estados_civiles.id', 'ASC') 
            ->get()->toArray();
        
        
        return response()->json($estados);
    }
}
