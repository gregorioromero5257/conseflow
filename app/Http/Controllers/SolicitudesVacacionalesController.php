<?php

namespace App\Http\Controllers;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
Use App\Contrato;
Use App\TipoContrato;
use App\SolicitudVacacional;
use App\Empleado;
use App\Antiguedad;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use File;
use Illuminate\Support\Facades\Storage;
use DateTime;

class SolicitudesVacacionalesController extends Controller
{
    protected $rules = array(

        'fecha_solicitud' => 'required|max:45',
    );

    public function index(Request $request, $id)
    {
        $solicitudvacacional = DB::table('solicitudes_vacacionales')
        ->leftJoin('empleados AS autoriza', 'autoriza.id', '=', 'solicitudes_vacacionales.autoriza_empleado_id')
        ->leftJoin('empleados AS solicita', 'solicita.id', '=', 'solicitudes_vacacionales.solicita_empleado_id')
        ->select("solicitudes_vacacionales.*",
        DB::raw("CONCAT(autoriza.nombre ,' ',autoriza.ap_paterno,' ',autoriza.ap_materno) AS e_autoriza"),
        DB::raw("CONCAT(solicita.nombre ,' ',solicita.ap_paterno,' ',solicita.ap_materno) AS e_solicita"))
        ->where('solicitudes_vacacionales.solicita_empleado_id', '=' , $id)

        ->get();
    return response()->json(
        $solicitudvacacional->toArray()
    );
    }


public function diasganados($id)
{
  $dias_vacacionales = 0;

  $dias_vacacionaless = DB::table('antiguedad')
  ->select("antiguedad.*")
  ->where('antiguedad.empleado_id', '=' , $id)
  ->where('antiguedad.condicion','=','1')
  ->first();
  $fecha = date("Y-m-d");
  if (!is_null($dias_vacacionaless)) {
    $fechainicial = $dias_vacacionaless->fecha_inicial;
  $datetime1 = new \DateTime($fecha);
  $datetime2 = new \DateTime($fechainicial);
  $diferencia = $datetime1->diff($datetime2);
  $año_diferencia = $diferencia->format('%y');

  if ($año_diferencia == 1) {   $dias_vacacionales = 6; }
  if ($año_diferencia == 2) { $dias_vacacionales = 8; }
  if ($año_diferencia == 3) { $dias_vacacionales = 10; }
  if ($año_diferencia == 4) { $dias_vacacionales = 12;  }
  if ($año_diferencia >= 5 && $año_diferencia <= 9) { $dias_vacacionales = 14;  }
  if ($año_diferencia >= 10 && $año_diferencia <= 14) { $dias_vacacionales = 16;  }
  if ($año_diferencia >= 15 && $año_diferencia <= 19) { $dias_vacacionales = 18;  }
  if ($año_diferencia >= 20 && $año_diferencia <= 24) { $dias_vacacionales = 20;  }
  if ($año_diferencia >= 25 && $año_diferencia <= 29) { $dias_vacacionales = 22;  }
  if ($año_diferencia >= 30 && $año_diferencia <= 34) { $dias_vacacionales = 24;  }
  }
  return $dias_vacacionales;
}
public function diastomados($id)
{
  $total = 0;
  $solicitudvacacional = DB::table('solicitudes_vacacionales')
  ->select('solicitudes_vacacionales.*')
  ->where('solicitudes_vacacionales.solicita_empleado_id','=', $id)->get();

  if (count($solicitudvacacional) != 0) {
    for ($i=0; $i < count($solicitudvacacional) ; $i++) {
      $total += $solicitudvacacional[$i]->total_dias;
      // code...
    }
  }

  return $total;
}
public function empleadosconvacaciones(){

  $puestos = 0;
  $departamentos = 0;
  $estados = 0;
  $antiguedadid = 0;
  $antiguedad_dias = 0;
  $time = '';
  $diff = '';
  $fechainicial = '';
  $arreglo = [];
  $empleados = DB::table('empleados')
  ->leftJoin('contratos AS c', 'c.empleado_id', '=', 'empleados.id')
  ->select('empleados.*')
  ->where('c.tipo_contrato_id', '!=', '1')->distinct()->get();
  foreach ($empleados as $key => $empleado) {
      $puestoid = $empleado->puesto_id;
      $estadosid = $empleado->edo_civil_id;
      // if ($empleado->antiguedad_id != null) {
      //   $antiguedadid = $empleado->antiguedad_id;
      // }
      if (!is_null($puestoid)) {
          //$puestos = Puesto::find($puestoid)->get();
          $puestos = DB::table('puestos')
              ->select('puestos.*')
              ->where('puestos.id', '=', $puestoid)
              ->get();
          $departamentoid = $puestos[0]->departamento_id;
          if (!is_null($departamentoid)) {
              //$departamentos = Departamento::find($departamentoid)->get();
              $departamentos = DB::table('departamentos')
                  ->select('departamentos.*')
                  ->where('departamentos.id', '=', $departamentoid)
                  ->get();
          }
      }
      if (!is_null($estadosid)) {
           $estados = DB::table('estados_civiles')
              ->select('estados_civiles.*')
              ->where('estados_civiles.id', '=', $estadosid)
              ->get();
      }

        $antiguedades = DB::table('antiguedad')->select('antiguedad.*')->where('empleado_id','=',$empleado->id)->where('condicion','=','1')->first();
        if ($antiguedades != null) {
          $hoy = date("Y-m-d");
          $antiguedad_dias = (strtotime($antiguedades->fecha_inicial)) - (strtotime($antiguedades->fecha_final));
          if ($hoy < $antiguedades->fecha_final) {
            $date2 = new DateTime($hoy);
          }
          else{
            $date2 = new DateTime($antiguedades->fecha_final);
          }
           $date1 = new DateTime($antiguedades->fecha_inicial);
           //$date2 = new DateTime($antiguedades->fecha_final);
           $diff = $date1->diff($date2);
           $fechainicial = $antiguedades->fecha_inicial;
           $anios = ($diff->y) > 1 ? ' años,' : ' año,';
           $time = ($diff->y).$anios.$diff->m.' meses y '.$diff->d.' dias.';
        }

        $date = new DateTime('2000-01-01');
        $dias_g = $this->diasganados($empleado->id);

        if ($dias_g > 0) {
          // code...
     $diasrestantes = ($this->diasganados($empleado->id) - $this->diastomados($empleado->id) );

      $arreglo[] = [
          'empleado' => $empleado,
          'puesto' => $puestos[0],
          'departamento' => $departamentos[0],
          'estados' =>$estados[0],
          'diasganados' => $this->diasganados($empleado->id),
          'diastomados' => $this->diastomados($empleado->id),
          'diasrestantes' => $diasrestantes,
          'antiguedad' => $time,
          'fecha_inicial' => $fechainicial,
      ];
    }
  }
  //dd($arreglo);
  return response()->json($arreglo);


}




    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        if ($request->metodo == 'Nuevo') {
          $solicitudvacacional = new SolicitudVacacional();
          $solicitudvacacional->fill($request->all());
          Utilidades::auditar($solicitudvacacional, $solicitudvacacional->id);
          $solicitudvacacional->save();
          return response()->json(array(
              'status' => true
          ));
        }

        if ($request->metodo == 'Subir') {
          $FormatoStore = NULL;

            if(!$request->hasFile('formato_vacacion'))
            {
              $solicitudvacacional = SolicitudVacacional::findOrFail($request->id);
              $solicitudvacacional->formato_vacacion = $FormatoStore;
              Utilidades::auditar($solicitudvacacional, $solicitudvacacional->id);
              $solicitudvacacional->save();

              return response()->json(array(
                  'status' => true
              ));
            }

            if($request->hasFile('formato_vacacion'))
            {
              //obtiene el nombre del archivo y su extension
              $FormatoNE = $request->file('formato_vacacion')->getClientOriginalName();
              //Obtiene el nombre del archivo
              $FormatoNombre = pathinfo($FormatoNE, PATHINFO_FILENAME);
              //obtiene la extension
              $FormatoExt = $request->file('formato_vacacion')->getClientOriginalExtension();
              //nombre que se guarad en BD
              $FormatoStore = $FormatoNombre.'_'.uniqid().'.'.$FormatoExt;
              //Subida del archivo al servidor ftp
              Storage::disk('local')->put('Archivos/'.$FormatoStore, fopen($request->file('formato_vacacion'), 'r+'));

              $solicitudvacacional = SolicitudVacacional::findOrFail($request->id);
              $solicitudvacacional->formato_vacacion = $FormatoStore;
              $solicitudvacacional->save();

              return response()->json(array(
                  'status' => true
              ));
            }
        }
        /*$solicitudvacacional = new SolicitudVacacional();
        $solicitudvacacional->fill($request->all());
        $solicitudvacacional->save();
        return response()->json(array(
            'status' => true
        ));*/
    }


    public function update(Request $request, $id)
    {
        if (!$request->ajax()) return redirect('/');

        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $solicitudvacacional = SolicitudVacacional::findOrFail($request->id);
        $solicitudvacacional->fill($request->all());
        Utilidades::auditar($solicitudvacacional, $solicitudvacacional->id);
        $solicitudvacacional->save();

        return response()->json(array(
            'status' => true
        ));
    }
    public function autoriza($id){
      $empleados = DB::table('empleados')->select('empleados.*')->where('empleados.id','=',$id)->first();
      $empleado_puesto = $empleados->puesto_id;
      $puestos = DB::table('puestos')->select('puestos.*')->where('puestos.id','=',$empleado_puesto)->first();
      $puesto_nivel = $puestos->nivel_o;
      $puesto = DB::table('puestos')->select('puestos.*')->where('puestos.id','=',$puesto_nivel)->first();
      $puesto_id = $puesto->id;
      $empleados_autoriza = DB::table('empleados')->select('empleados.*')->where('empleados.puesto_id','=',$puesto_id)->first();
      return response()->json($empleados_autoriza);

    }

    public function edit($id)
    {
      // Se obtiene el archivo del FTP serve
      $archivo = Utilidades::ftpSolucion($id);
      // Se coloca el archivo en una ruta local
      Storage::disk('descarga')->put($id, $archivo);
      //--Devuelve la respuesta y descarga el archivo--//
      return response()->download(storage_path().'/app/descargas/'.$id);
    }

    public function show($id)
    {
        $solicitudvacacional = DB::table('solicitudes_vacacionales')
        ->leftJoin('empleados AS autoriza', 'autoriza.id', '=', 'solicitudes_vacacionales.autoriza_empleado_id')
        ->leftJoin('empleados AS solicita', 'solicita.id', '=', 'solicitudes_vacacionales.solicita_empleado_id')
        ->select("solicitudes_vacacionales.*",
        DB::raw("CONCAT(autoriza.nombre ,' ',autoriza.ap_paterno,' ',autoriza.ap_materno) AS e_autoriza"),
        DB::raw("CONCAT(solicita.nombre ,' ',solicita.ap_paterno,' ',solicita.ap_materno) AS e_solicita"))


        ->get();
    return response()->json(
        $solicitudvacacional->toArray()
    );
    }



    public function activar(Request $request)
    {

    }

    public function NoAutorizado(Request $request)
     {
         if (!$request->ajax()) return redirect('/');
         $solicitudvacacional = SolicitudVacacional::findOrFail($request->id);
         $solicitudvacacional->estado = '2';
         Utilidades::auditar($solicitudvacacional, $solicitudvacacional->id);
         $solicitudvacacional->save();
     }

     public function Autorizado(Request $request)
     {
         if (!$request->ajax()) return redirect('/');
         $solicitudvacacional = SolicitudVacacional::findOrFail($request->id);
         $solicitudvacacional->estado = '1';
         Utilidades::auditar($solicitudvacacional, $solicitudvacacional->id);
         $solicitudvacacional->save();
     }
     public function EnEspera(Request $request)
     {
         if (!$request->ajax()) return redirect('/');
         $solicitudvacacional = SolicitudVacacional::findOrFail($request->id);
         $solicitudvacacional->estado = '0';
         Utilidades::auditar($solicitudvacacional, $solicitudvacacional->id);
         $solicitudvacacional->save();
     }

     public function getList(Request $request)
    {
        $empleados = SolicitudVacacional::select('id', 'nombre')->where('solicita_empleado_id', $id)->orderBy('id', 'desc')->get()->toArray();
        return response()->json($empleados);
    }

    public function getListaEnEsperaPorAutorizar()
    {
        $user = Auth::user();
        $solicitudvacacional = DB::table('solicitudes_vacacionales')
            ->leftJoin('empleados AS autoriza', 'autoriza.id', '=', 'solicitudes_vacacionales.autoriza_empleado_id')
            ->leftJoin('empleados AS solicita', 'solicita.id', '=', 'solicitudes_vacacionales.solicita_empleado_id')
            ->select("solicitudes_vacacionales.*",
            DB::raw("CONCAT(autoriza.nombre ,' ',autoriza.ap_paterno,' ',autoriza.ap_materno) AS e_autoriza"),
            DB::raw("CONCAT(solicita.nombre ,' ',solicita.ap_paterno,' ',solicita.ap_materno) AS e_solicita"))
            ->where('solicitudes_vacacionales.autoriza_empleado_id', '=' , $user->empleado_id)
            ->where('solicitudes_vacacionales.estado', '=' , 0)
            ->get();

        return response()->json([
            'solicitudes' => $solicitudvacacional->toArray(),
        ]);
    }

    public function destroy($id)
    {
      //elimina de la ruta local el archivo descargado
        Storage::disk('descarga')->delete($id);
    }

}
