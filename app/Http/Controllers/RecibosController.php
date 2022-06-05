<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Recibos;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;

class RecibosController extends Controller
{
   protected $rules = array(
        'tipo_nomina_id' => 'required|max:45',
    );

    public function index(Request $request, $id)
    {
        $recibos = Recibos::orderBy('id', 'asc')
            ->leftJoin('contrato_recibos AS ContrRec', 'ContrRec.recibo_id', '=', 'recibos.id')
            ->leftJoin('contratos', 'contratos.id', '=', 'ContrRec.contrato_id')
            ->leftJoin('empleados', 'empleados.id', '=', 'contratos.empleado_id')
            ->select('recibos.*',
                //contratos
                'ContrRec.recibo_id AS recibo_idC',
                'ContrRec.contrato_id',
                'contratos.empleado_id',
                'empleados.nombre',
                'empleados.ap_paterno',
                'empleados.ap_materno'
                //Fin
                )
            ->where('ContrRec.contrato_id', '=', $id)
            ->get();

        //dd($arreglo);
        return response()->json($recibos);
    }

    public function show($id)
    {
        $recibos = Recibos::find($id);

                $deducciones = DB::table('deducciones')
                    ->leftJoin('tipo_deducciones AS TipoDeduc', 'TipoDeduc.id', '=', 'deducciones.tipo_deduccione_id')
                    ->select('deducciones.*', 'TipoDeduc.nombre')
                    ->where('deducciones.recibo_id', '=', $id)
                    ->get();
                        
                $percepciones = DB::table('percepciones')
                    ->leftJoin('tipo_percepciones AS TipoPerc', 'TipoPerc.id', '=', 'percepciones.tipo_percepcione_id')
                    ->select('percepciones.*', 'TipoPerc.nombre')
                    ->where('percepciones.id', '=', $id)
                    ->get();

                $arreglo[] = [
                    'deduccion' => $deducciones,
                    'percepcion' => $percepciones,
                ];

        return response()->json($arreglo);
    }

    public function pdf($id)
    {
        $recibos = Recibos::where('recibos.id', '=', $id)
            ->leftJoin('contrato_recibos AS ContrRec', 'ContrRec.recibo_id', '=', 'recibos.id')
            ->leftJoin('contratos', 'contratos.id', '=', 'ContrRec.contrato_id')
            ->leftJoin('empleados', 'empleados.id', '=', 'contratos.empleado_id')
            ->leftJoin('puestos', 'puestos.id', '=', 'empleados.puesto_id')
            ->leftJoin('departamentos', 'departamentos.id', '=', 'puestos.departamento_id')
            ->select('recibos.*',
                //contratos
                'ContrRec.recibo_id AS recibo_idC',
                'ContrRec.contrato_id',
                'contratos.empleado_id',
                'empleados.nombre',
                'empleados.ap_paterno',
                'empleados.ap_materno',
                'empleados.puesto_id',
                'puestos.nombre AS puesto',
                'puestos.departamento_id',
                'departamentos.nombre AS departamento'
                //Fin
                )
            ->get()->first();

        $deducciones = DB::table('deducciones')
                ->leftJoin('tipo_deducciones AS TipoDeduc', 'TipoDeduc.id', '=', 'deducciones.tipo_deduccione_id')
                ->select('deducciones.*', 'TipoDeduc.nombre')
                ->where('deducciones.recibo_id', '=', $id)
                ->get();
                        
        $percepciones = DB::table('percepciones')
            ->leftJoin('tipo_percepciones AS TipoPerc', 'TipoPerc.id', '=', 'percepciones.tipo_percepcione_id')
            ->select('percepciones.*', 'TipoPerc.nombre')
            ->where('percepciones.id', '=', $id)
            ->get();


        //return response()->json($recibos);
        $pdf = PDF::loadView('pdf.recibo', compact('recibos', 'percepciones', 'deducciones'));

        // return $pdf->download('cv-interno.pdf');
        return $pdf->stream();
    }
}