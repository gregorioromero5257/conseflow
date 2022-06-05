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


class AgendaController extends Controller
{

    public $json_list = array();
    public function index()
    {
    	$json_list = array();

        $json_list[] = $this->principal();
		return response()->json($json_list);
    }
    public function principal($id=0)
    {
      $json_list = [];
        $agendaPagosRec = DB::table('agenda')
        ->leftJoin('pagos_recurrentes AS Nomb', 'Nomb.id', '=', 'agenda.pagos_recurrentes_id')
        ->select("agenda.*")
        ->get();


        $inicio = '';
	    foreach ($agendaPagosRec as $k => $e) {

	    		$json_list[] =	[
									'id' => $e->id,
									'date' => substr($e->date,0,4).'/'.substr($e->date,5,2).'/'.substr($e->date,8,2),
                                    'title' => $e->title,
                                    'desc' => $e->descripcion,
                                    'pagos_recurrentes_id' => $e->pagos_recurrentes_id
                                    //'customClass' => $e->customClass
								];

	    }
        return $json_list;

    }
    public function rangodias ()
    {
        $operaciodiasrangos = PagosRecurrentes ::orderBy('id', 'desc')->get()->toArray();
        return response()->json($operaciodiasrangos);

        $arreglo[] =[
            'fecha_inicial' => $fechaIni,
            'rango' => $rangos,
        ];
        //que se guarde 200 veces
        if ($rangos <= 200) {
        $fechaIni - $rangos;

        }
        else {



        }
    }
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $agendaPagosRec = new Agenda();
        $agendaPagosRec->fill($request->all());
        Utilidades::auditar($agendaPagosRec, $agendaPagosRec->id);
        $agendaPagosRec->save();

        return response()->json(array(
            'status' => true
        ));
        return response()->json(
            $agendaPagosRec->toArray()
        );
    }
    public function update(Request $request)
    {
    if(!$request->ajax()) return redirect('/');

    $agendaPagosRec = Agenda::findOrFail($request->id);
    $agendaPagosRec->fill($request->all());
    Utilidades::auditar($agendaPagosRec, $agendaPagosRec->id);
    $agendaPagosRec->save();

    return response()->json(array(
        'status' => true
    ));
    }

    public function GuardarAgendaPagoNR(Request $request)
    {
      // $fecha = explode(',',$request->fecha);
      $pagosNoRecurrentes = DB::table('pagos_no_recurrentes')
      ->leftJoin('proveedores AS P', 'P.id', '=', 'pagos_no_recurrentes.proveedor_id')
      ->leftJoin('ordenes_compras AS OC', 'OC.id', '=', 'pagos_no_recurrentes.ordenes_comp_id')
      ->leftJoin('condicion_pago AS CP','CP.id','=','OC.condicion_pago_id')
      ->leftJoin('proyectos AS PR', 'PR.id', '=', 'pagos_no_recurrentes.proyecto_id')
      ->select('pagos_no_recurrentes.*','P.razon_social','P.id AS proveedor_id',
      'PR.nombre AS nombre_proyecto','CP.id AS condicion_pago_id','CP.nombre AS nombre_condicion_pago','OC.folio','OC.id AS orden_compra_id')
      ->where('pagos_no_recurrentes.id','=',$request->id)
      ->first();

      for ($i=0; $i < count($request->fecha) ; $i++) {
        $agendaPagosNRec = new Agenda();
        $agendaPagosNRec->title = 'Pago compra con folio '.$pagosNoRecurrentes->folio;
        $agendaPagosNRec->date = $request->fecha[$i];
        $agendaPagosNRec->descripcion = 'Pago '.($i + 1).' de la compra con folio: '.$pagosNoRecurrentes->folio.' del proveedor '.$pagosNoRecurrentes->razon_social;
        $agendaPagosNRec->pagos_no_recurrentes_id = $pagosNoRecurrentes->id;
        $agendaPagosNRec->save();
      }
      $pagosNoRecurrentesd = \App\PagosNoRecurrentes::where('id','=',$request->id)->first();
      $pagosNoRecurrentesd->condicion = 3;
      $pagosNoRecurrentesd->save();

      $ordencompra = \App\Compras::where('id','=',$request->orden_compra_id)->first();
      $ordencompra->estado_id = 2;
      $ordencompra->save();

      return response()->json(array('status' => true));
    }

   public function getList(Request $request)
   {
    $agendaPagosRec = Agenda::select('id','title')->orderBy('id','desc')->get()->toArray();
    return response()->json($agendaPagosRec);

   }
    }
