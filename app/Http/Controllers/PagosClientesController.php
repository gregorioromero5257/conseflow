<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PagosClientes;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;

class PagosClientesController extends Controller
{
    //
    public function index()
    {
      $pagos_clientes = PagosClientes::join('proyectos AS p','p.id','pagos_cliente.proyecto_id')
      ->join('clientes AS c','c.id','pagos_cliente.cliente_id')
      ->select('pagos_cliente.*','p.nombre_corto','c.nombre AS nombre_cliente')->get();
      return response()->json($pagos_clientes);
    }

    public function store(Request $request)
    {
      $pagos_clientes = new PagosClientes();
      $pagos_clientes->fill($request->all());
      $pagos_clientes->save();

      return response()->json(['status' => true]);
    }

    public function update(Request $request)
    {
      $pagos_clientes = PagosClientes::where('id',$request->id)->first();
      $pagos_clientes->fill($request->all());
      $pagos_clientes->update();

      return response()->json(['status' => true]);
    }

    public function show()
    {

    }

    public function delete($id)
    {
      $pagos_clientes = PagosClientes::where('id',$id)->delete();
      return response()->json(['status' => true]);
    }

}
