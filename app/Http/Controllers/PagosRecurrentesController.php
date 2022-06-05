<?php

namespace App\Http\Controllers;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Agenda;
use App\PagosRecurrentes;
use App\Proveedor;
use App\Compras;

class PagosRecurrentesController extends Controller
{

/**
 * [Definición de las reglas para ser utilizadas en la inserción y actualización]
 */
    protected $rules = array('nombre' => 'required|max:45',);

/**
 * [Consulta en la BD los registros de la tabla pagos_recurrentes y sus tablas 
 * relacionados proveedores, proyectos y ordenes_compras]
 * @param  Request  $request [Url del GET]
 * @return Response          [Array en formato JSON]
 */
public function index(Request $request)
{  

    $pagosRecuMen = DB::table('pagos_recurrentes')
     ->leftJoin('proveedores','proveedores.id', '=', 'pagos_recurrentes.proveedor_id')
     ->leftJoin('proyectos','proyectos.id', '=', 'pagos_recurrentes.proyecto_id')
     ->leftJoin('ordenes_compras','ordenes_compras.id', '=', 'pagos_recurrentes.ordenes_comp_id')

     ->select('pagos_recurrentes.*',
     'proveedores.nombre as NombreProveedor',
     'proveedores.limite_credito as ProveedorLimiteCredito',
     'proveedores.dia_credito as ProveedorDiasCredito',

     'proyectos.nombre as NombreProyecto',
     'proyectos.ciudad as CiudadProyecto',

     'ordenes_compras.folio as FolioOrdenCompra'  
     )
    ->get();
        
    return response()->json(
        $pagosRecuMen->toArray()
    );

}

/**
* [Guardar en BD un registro en la tabla pagos_recurrentes
* respetando las reglas definidas]
* @param  Request $request [Objeto con los datos del POST]
* @return Response         [Array con estatus true]
*/
public function store(Request $request)
{
        if (!$request->ajax()) return redirect('/');

        $validator = Validator::make($request->all(), $this->rules);

        $pagosRecuMen = new PagosRecurrentes();
        $pagosRecuMen->fill($request->all());
        Utilidades::auditar($pagosRecuMen, $pagosRecuMen->id);
        $pagosRecuMen->save();

        return response()->json(array(
            'status' => true
        ));
        return response()->json(
            $pagosRecuMen->toArray()
        );
}

/**
* [Actualizar en la BD un registro en la tabla pagos_recurrentes
* respentando las reglas definadas]
* @param  Request  $request [Objeto con los datos del PUT]
* @return Response          [Array con estatus true]
*/
public function update(Request $request)
{
        if (!$request->ajax()) return redirect('/');
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $pagosRecuMen = PagosRecurrentes::findOrFail($request->id);
        $pagosRecuMen->fill($request->all());
        Utilidades::auditar($pagosRecuMen, $pagosRecuMen->id);
        $pagosRecuMen->save();

        return response()->json(array(
            'status' => true
        ));
} 

    /**
    * [Consulta en la BD los registros de la tabla pagos_recurrentes]
    * @param  Request  $request [Url del GET]
    * @return Response          [Array en formato JSON]
    */
    public function getList(Request $request)
    {
        $pagosRecuMen = PagosRecurrentes::select('id', 'nombre')->orderBy('id', 'desc')->get()->toArray();
        return response()->json($pagosRecuMen);
    }


}