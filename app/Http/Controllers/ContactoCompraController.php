<?php

namespace App\Http\Controllers;
use App\ContactoCompra;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\DB;


class ContactoCompraController extends Controller
{
    //

    public function index(Request $request)
    {
       $contactoc = DB::table('contacto_compra')
           ->where('contacto_compra.cliente_id','=',$request->id)
           ->select('contacto_compra.*')
           ->distinct()->get();
       return response()->json($contactoc);
    }


    public function store(Request $request)
    {
        try{
        if (!$request->ajax()) return redirect('/');

        $contacto = new ContactoCompra();
        $contacto->nombre_contacto = $request->nombre_contacto;
        $contacto->telefono_oficina = $request->telefono_oficina;
        $contacto->telefono_movil = $request->telefono_movil;
        $contacto->area = $request -> area;
        $contacto->email = $request->email;
        $contacto->cliente_id = $request ->cliente_id;
        Utilidades::auditar($contacto, $contacto->id);
        $contacto->save();

        return response()->json(array('status' => true));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function update(Request $request)
    {
        try{
        if (!$request->ajax()) return redirect('/');

        $contacto =  ContactoCompra::findOrFail($request->id);
        $contacto->nombre_contacto = $request->nombre_contacto;
        $contacto->telefono_oficina = $request->telefono_oficina;
        $contacto->telefono_movil = $request->telefono_movil;
        $contacto->area = $request -> area;
        $contacto->email = $request->email;
        $contacto->cliente_id = $request ->cliente_id;
         Utilidades::auditar($contacto, $contacto->id);
        $contacto->save();

        return response()->json(array('status' => true));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }
}
