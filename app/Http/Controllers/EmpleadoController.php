<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Empleado;
use App\EmpleadoDBP;
use App\Puesto;
use App\Departamento;
use App\EstadoCivil;
use App\Contrato;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use \App\Http\Helpers\Utilidades;
use Exception;
use Validator;
use Illuminate\Validation\Rule;

class EmpleadoController extends Controller
{
  protected $rules = array(
    'curp' => 'required|max:18',
    'rfc' => 'required|max:13',
    'nss_imss' => 'required|max:15',
  );
  public function index()
  {
    $ubicacion = Utilidades::ubicacion();
    if ($ubicacion == null){
      $puestos = 0;
      $departamentos = 0;
      $estados = 0;
      $ubicaciones = 0;
      $empleados = Empleado::select('empleados.*','empleados.ubicacion_id', 'tipo_ubicacion.nombre AS ubicacion')
      ->leftJoin('tipo_ubicacion','empleados.ubicacion_id', '=', 'tipo_ubicacion.id')
      // ->where('empleados.ubicacion_id', '=',$ubicacion)
      ->orderBy('id', 'asc')->get();
      foreach ($empleados as $key => $empleado) {
        $puestoid = $empleado->puesto_id;
        $estadosid = $empleado->edo_civil_id;
        $ubicacionesid = $empleado->tipo_ubicacion_id;

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
        if (!is_null($ubicacionesid)) {
          $ubicaciones = DB::table('tipo_ubicacion')
          ->select('tipo_ubicacion.*')
          ->where('tipo_ubicacion.id', '=', $ubicacionesid)
          ->get();
        }

        $arreglo[] = [
          'empleado' => $empleado,
          'puesto' => $puestos[0],
          'departamento' => $departamentos[0],
          'estados' =>$estados[0],
          'ubicaciones' =>$ubicaciones[0],
        ];
      }}
      elseif($ubicacion != null){
        $puestos = 0;
        $departamentos = 0;
        $estados = 0;
        $ubicaciones = 0;
        $empleados = Empleado::select('empleados.*','empleados.ubicacion_id', 'tipo_ubicacion.nombre AS ubicacion')
        ->leftJoin('tipo_ubicacion','empleados.ubicacion_id', '=', 'tipo_ubicacion.id')
        ->where('empleados.ubicacion_id', '=',$ubicacion)
        ->orderBy('id', 'asc')->get();
        foreach ($empleados as $key => $empleado) {
          $puestoid = $empleado->puesto_id;
          $estadosid = $empleado->edo_civil_id;
          $ubicacionesid = $empleado->tipo_ubicacion_id;

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
          if (!is_null($ubicacionesid)) {
            $ubicaciones = DB::table('tipo_ubicacion')
            ->select('tipo_ubicacion.*')
            ->where('tipo_ubicacion.id', '=', $ubicacionesid)
            ->get();
          }

          $arreglo[] = [
            'empleado' => $empleado,
            'puesto' => $puestos[0],
            'departamento' => $departamentos[0],
            'estados' =>$estados[0],
            'ubicaciones' =>$ubicaciones[0],
          ];
        }
      }


      //dd($arreglo);
      return response()->json($arreglo);
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
      //
    }

    /**
    * Store a newly created resource in storage.
    * CSCT SEMANAL = 3
    * CSCT QUINCENAL = 4
    * CONSERFLOW SEMANAL = 1
    * CONSERFLOW QUINCENAL = 2
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
      try{
        if (!$request->ajax()) return redirect('/');
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
          return response()->json(array(
            'status' => false,
            'errors' => $validator->errors()->all()
          ));
        }
        DB::beginTransaction();
        $empleado = new Empleado();
        $empleado->nombre =$request->nombre;
        $empleado->ap_paterno = $request->ap_paterno;
        $empleado->ap_materno = $request->ap_materno;
        $empleado->sexo = $request->sexo;
        $empleado->lug_nac = $request->lug_nac;
        $empleado->fech_nac = $request->fech_nac;
        $empleado->ine = $request->ine;
        $empleado->curp = $request->curp;
        $empleado->rfc = $request->rfc;
        $empleado->nss_imss = $request->nss_imss;
        $empleado->fech_alta_imss = $request->fech_alta_imss;
        $empleado->fech_ing = $request->fech_ing;
        $empleado->tipo_sangre = $request->tipo_sangre;
        $empleado->talla_overol = $request->talla_overol;
        $empleado->talla_botas = $request->talla_botas;
        $empleado->amortizacion = $request->amortizacion;
        $empleado->numero_credito = $request->numero_credito;
        $empleado->edo_civil_id = $request->edo_civil_id;
        $empleado->ubicacion_id = $request->ubicacion_id;
        $empleado->puesto_id = $request->puesto_id;
        $empleado->id_checador = $request->id_checador;
        $empleado->condicion = '1';
        $empleado->created_at = $request->created_at;
        $empleado->updated_at = $request->updated_at;
        $empleado->deleted_at = $request->deleted_at;
        Utilidades::auditar($empleado, $empleado->id);
        $empleado->save();

        //SE EL REGISTRO ES EMPLEADO DE CSCT SE CREA LA COPIA EN CSCT
        if ($request->id_checador == 3 || $request->id_checador == 4) {
          $empleado = new EmpleadoDBP();
          $empleado->nombre = $request->nombre;
          $empleado->ap_paterno = $request->ap_paterno;
          $empleado->ap_materno = $request->ap_materno;
          $empleado->sexo = $request->sexo;
          $empleado->lug_nac = $request->lug_nac;
          $empleado->fech_nac = $request->fech_nac;
          $empleado->ine = $request->ine;
          $empleado->curp = $request->curp;
          $empleado->rfc = $request->rfc;
          $empleado->nss_imss = $request->nss_imss;
          $empleado->fech_alta_imss = $request->fech_alta_imss;
          $empleado->fech_ing = $request->fech_ing;
          $empleado->tipo_sangre = $request->tipo_sangre;
          $empleado->talla_overol = $request->talla_overol;
          $empleado->talla_botas = $request->talla_botas;
          $empleado->amortizacion = $request->amortizacion;
          $empleado->numero_credito = $request->numero_credito;
          $empleado->edo_civil_id = $request->edo_civil_id;
          $empleado->ubicacion_id = $request->ubicacion_id;
          $empleado->puesto_id = $request->puesto_id;
          $empleado->id_checador = $request->id_checador;
          $empleado->condicion = '1';
          $empleado->created_at = $request->created_at;
          $empleado->updated_at = $request->updated_at;
          $empleado->deleted_at = $request->deleted_at;
          Utilidades::auditar($empleado, $empleado->id);
          $empleado->save();
        }

        DB::commit();
        return response()->json($empleado);
      } catch (\Throwable $e) {
        DB::rollBack();
        Utilidades::errors($e);
      }
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
      return response()->json(Empleado::findOrFail($id));
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
      try{
        $empleado = Empleado::findOrFail($id);
        if ($empleado->condicion==0) {
          $empleado->condicion = 1;
        }else{
          $empleado->condicion = 0;
          $empleado->id_checador = 0;
        }
        Utilidades::auditar($empleado, $empleado->id);
        $empleado->update();
        return $empleado;
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
      try{
        $empleado = Empleado::findOrFail($id);
        $empleado->nombre =$request->nombre;
        $empleado->ap_paterno = $request->ap_paterno;
        $empleado->ap_materno = $request->ap_materno;
        $empleado->sexo = $request->sexo;
        $empleado->lug_nac = $request->lug_nac;
        $empleado->fech_nac = $request->fech_nac;
        $empleado->ine = $request->ine;
        $empleado->curp = $request->curp;
        $empleado->rfc = $request->rfc;
        $empleado->nss_imss = $request->nss_imss;
        $empleado->fech_alta_imss = $request->fech_alta_imss;
        $empleado->fech_ing = $request->fech_ing;
        $empleado->tipo_sangre = $request->tipo_sangre;
        $empleado->talla_overol = $request->talla_overol;
        $empleado->talla_botas = $request->talla_botas;
        $empleado->amortizacion = $request->amortizacion;
        $empleado->numero_credito = $request->numero_credito;
        $empleado->edo_civil_id = $request->edo_civil_id;
        $empleado->ubicacion_id = $request->ubicacion_id;
        $empleado->id_checador = $request->id_checador;
        $empleado->puesto_id = $request->puesto_id;
        $empleado->condicion = '1';
        $empleado->created_at = $request->created_at;
        $empleado->updated_at = $request->updated_at;
        $empleado->deleted_at = $request->deleted_at;
        Utilidades::auditar($empleado, $empleado->id);
        $empleado->update();
        return response()->json($empleado);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
      //
    }

    public function pdf($id)
    {
      Utilidades::permiso_submenu('/rh/empleados/cv/');

      $empleado = Empleado::where('empleados.id', '=',$id)
      ->leftJoin('puestos', 'puestos.id', '=', 'empleados.puesto_id')
      ->select('empleados.*', 'puestos.nombre AS puesto' )
      ->first();
      $contacto = DB::table('contacto_empleados')
      // ->where('condicion', '=', 1)
      ->where('empleado_id', '=', $id)->first();
      $telefonos = DB::table('telefonos_corporativos')
      ->where('condicion', '=', 1)
      ->where('empleado_id', '=', $id)->get();
      $correos = DB::table('correos_corporativos')
      ->where('condicion', '=', 1)
      ->where('empleado_id', '=', $id)->get();
      $explabs = \App\ExperienciasLaborale::where('empleado_id', '=', $id)->where('condicion', '=', 1)->get();
      $escolaridades = \App\Escolaridade::where('empleado_id', '=', $id)
      ->where('condicion', '=', 1)
      ->join('grados', 'grados.id', '=', 'escolaridades.grado_id')
      ->select('escolaridades.*', 'grados.nombre AS grado' )
      ->orderBy('escolaridades.fecha_inicio','ASC')
      ->get();
      // return response()->json(compact('empleado', 'explabs', 'escolaridades', 'telefonos', 'correos','contacto'));
      $pdf = PDF::loadView('pdf.cv-interno', compact('empleado', 'explabs', 'escolaridades', 'telefonos', 'correos','contacto'));
      // return $pdf->download('cv-interno.pdf');
      return $pdf->stream('cv-interno.pdf');
    }

    public function historico(Request $request, $id)
    {
      if (!$request->ajax()) return redirect('/');

      $empleado = Empleado::where('empleados.id', '=',$id)
      ->leftJoin('puestos', 'puestos.id', '=', 'empleados.puesto_id')
      ->select('empleados.*', 'puestos.nombre AS puesto' )
      ->get()->first();

      $contratos = DB::table('contratos')
      ->leftJoin('proyectos', 'contratos.proyecto_id', '=', 'proyectos.id')
      ->join('tipo_contratos', 'contratos.tipo_contrato_id', '=', 'tipo_contratos.id')
      ->join('tipo_nomina', 'contratos.tipo_nomina_id', '=', 'tipo_nomina.id')
      ->join('tipo_horario', 'contratos.horario_id', '=', 'tipo_horario.id')
      ->leftJoin('sueldos', 'contratos.id', '=', 'sueldos.contrato_id')
      ->leftJoin('bajas', 'contratos.id', '=', 'bajas.contrato_id')
      ->where([
        'empleado_id' => $id
      ])
      ->select(
        'contratos.*',
        'proyectos.*',
        'tipo_contratos.nombre AS tipo_contrato',
        'tipo_nomina.nombre AS tipo_nomina',
        'tipo_horario.nombre AS horario',
        'sueldos.*',
        'bajas.*'
        )
        ->get();

        return response()->json([
          'empleado'  => $empleado->toArray(),
          'contratos' => $contratos->toArray()
        ]);
      }
      public function pdfalt($id)
      {

        $fecha_final_ingreso = '';
        $empleado = Empleado::where('empleados.id', '=',$id)
        ->leftJoin('puestos', 'puestos.id', '=', 'empleados.puesto_id')
        ->leftJoin('departamentos', 'departamentos.id', '=', 'puestos.departamento_id')
        ->leftJoin('estados_civiles AS edo_civil', 'edo_civil.id', '=', 'empleados.edo_civil_id')
        ->select(
          'empleados.*', 'puestos.nombre AS puesto', 'departamentos.nombre as dep', 'empleados.talla_overol as overol', 'empleados.talla_botas as botas', 'empleados.rfc AS rfc', 'empleados.nss_imss AS nss','empleados.lug_nac AS lug_nac','empleados.fech_nac AS fech_nac', 'empleados.curp AS curp','edo_civil.nombre as civil',
          DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno) AS empleado"))
          ->get()->first();

          $sueldos = DB::table('contratos')
          ->leftJoin('sueldos', 'contratos.id', '=', 'sueldos.contrato_id')
          ->where(['empleado_id' => $id])
          ->select(DB::raw('sueldos.*'))
          ->get()->first();

          $quincenal_semanal = DB::table('contratos')
          ->select('contratos.*')
          ->where('contratos.empleado_id','=',$id)
          ->first();

          $mescumpleaños = DB::table('empleados')
          ->where('empleados.id', '=',$id)
          ->select(DB::raw('*, TIMESTAMPDIFF(YEAR,fech_nac,CURDATE()) AS edad'))
          ->get()->first();
          $telefonos = DB::table('contacto_empleados')->where('empleado_id', '=', $id)->get();

          $domicilios = DB::table('direcciones_empleados')->where('empleado_id', '=', $id)->get();
          $contratos = DB::table('contratos')->where('empleado_id', '=', $id)->get()->first();
          $beneficiarios = \App\Beneficiario::where('empleado_id', '=', $id)->where('condicion', '=', 1)->get();
          if (!is_null($contratos)) {
            // code...
            $fecha_ingreso=$contratos->fecha_ingreso;
            $fecha_fin=$contratos->fecha_fin;


            $today = date("j, m");
            $t_y=date("Y");
            $fecha_final_ingreso='';

            $dia_hoy = substr($today,0,2);
            $mes_hoy = substr($today,4,2);
            $año_hoy = substr($t_y,0,4);

            $mes_ingreso=substr($fecha_ingreso,5,2);
            $mes_fin=substr($fecha_fin,5,2);

            $fecha_final_ingreso= substr($fecha_ingreso,8,2).' de '.$this->meses($mes_ingreso).' del '.substr($fecha_ingreso,0,4);
            $fecha_final_temino= substr($fecha_fin,8,2).' de '.$this->meses($mes_fin).' del '.\substr($fecha_fin,0,4);
            $fecha_hoy=substr($dia_hoy,0,2) .' de '.$this->meses($mes_hoy).' del '.substr($año_hoy,0,4);
          }

          $count = 1;

          $dias = array("DOMINGO","LUNES","MARTES","MIERCOLES","JUEVES","VIERNES","SABADO");
          $meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
          $hoy = $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;

          $pdfalt = PDF::loadView('pdf.alt', compact('empleado','mescumpleaños','hoy','telefonos','count','domicilios','fecha_final_ingreso', 'beneficiarios','sueldos','quincenal_semanal'));
          $pdfalt->setPaper("letter","portrait");

          return $pdfalt->stream();

        }

        public function meses($mes){
          $resultado='';
          switch ($mes) {
            case '01': $resultado='Enero'; break;
            case '02': $resultado='Febrero'; break;
            case '03': $resultado='Marzo'; break;
            case '04': $resultado='Abril'; break;
            case '05': $resultado='Mayo'; break;
            case '06': $resultado='Junio'; break;
            case '07': $resultado='Julio'; break;
            case '08': $resultado='Agosto'; break;
            case '09': $resultado='Septiembre'; break;
            case '10': $resultado='Octubre'; break;
            case '11': $resultado='Noviembre'; break;
            case '12': $resultado='Diciembre'; break;

          }
          return $resultado;
        }

        public function vertodosempleados()
        {
          $empleados = Empleado::select('empleados.*','empleados.ubicacion_id', 'tipo_ubicacion.nombre AS ubicacion')
          ->leftJoin('tipo_ubicacion','empleados.ubicacion_id', '=', 'tipo_ubicacion.id')
          // ->where('empleados.ubicacion_id', '=',$ubicacion)
          ->orderBy('id', 'asc')->get();

          return response()->json($empleados);
        }

        public function AsignarChecador(Request $request)
        {
          try
          {
            $ms=$request->empleado_id." - ".$request->id_checador;
            $empleado=Empleado::findOrFail($request->empleado_id);
            $empleado->id_checador=$request->id_checador;
            $empleado->update();
            return response()->json(["status"=>true, "ms"=>$ms]);
          }
          catch(Exception $e)
          {
            return response()->json(["status"=>false,"mensaje"=> $e->getMessage(),"ms"=>$ms]);
          }
        }

        public function getCFW(){
          $puestos = 0;
          $departamentos = 0;
          $estados = 0;
          $ubicaciones = 0;
          $empleados = \App\Empleado::
          leftJoin('tipo_ubicacion','empleados.ubicacion_id', '=', 'tipo_ubicacion.id')
          ->select('empleados.*','empleados.ubicacion_id', 'tipo_ubicacion.nombre AS ubicacion')
          ->whereIn('empleados.id_checador',['1','2'])
          ->orderBy('id', 'asc')->get();

          foreach ($empleados as $key => $empleado) {
            $puestoid = $empleado->puesto_id;
            $estadosid = $empleado->edo_civil_id;
            $ubicacionesid = $empleado->tipo_ubicacion_id;

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
            if (!is_null($ubicacionesid)) {
              $ubicaciones = DB::table('tipo_ubicacion')
              ->select('tipo_ubicacion.*')
              ->where('tipo_ubicacion.id', '=', $ubicacionesid)
              ->get();
            }

            $arreglo[] = [
              'empleado' => $empleado,
              'puesto' => $puestos[0],
              'departamento' => $departamentos[0],
              'estados' =>$estados[0],
              'ubicaciones' =>$ubicaciones[0],
            ];
          }
          return response()->json($arreglo);

        }

        public function getCSCT()
        {

          $puestos = 0;
          $departamentos = 0;
          $estados = 0;
          $ubicaciones = 0;
          $empleados = EmpleadoDBP::select('empleados.*','empleados.ubicacion_id', 'tipo_ubicacion.nombre AS ubicacion')
          ->leftJoin('tipo_ubicacion','empleados.ubicacion_id', '=', 'tipo_ubicacion.id')
          // ->where('empleados.ubicacion_id', '=',$ubicacion)
          ->orderBy('id', 'asc')->get();
          foreach ($empleados as $key => $empleado) {
            $puestoid = $empleado->puesto_id;
            $estadosid = $empleado->edo_civil_id;
            $ubicacionesid = $empleado->tipo_ubicacion_id;

            if (!is_null($puestoid)) {
              //$puestos = Puesto::find($puestoid)->get();
              $puestos = \App\PuestoDBP::
              select('puestos.*')
              ->where('puestos.id', '=', $puestoid)
              ->get();
              $departamentoid = $puestos[0]->departamento_id;
              if (!is_null($departamentoid)) {
                //$departamentos = Departamento::find($departamentoid)->get();
                $departamentos = \App\DepartamentoDBP::
                select('departamentos.*')
                ->where('departamentos.id', '=', $departamentoid)
                ->get();
              }
            }
            if (!is_null($estadosid)) {
              $estados = \App\EstadoCivilDBP::
              select('estados_civiles.*')
              ->where('estados_civiles.id', '=', $estadosid)
              ->get();
            }
            if (!is_null($ubicacionesid)) {
              $ubicaciones = \App\TipoUbicacionDBP::
              select('tipo_ubicacion.*')
              ->where('tipo_ubicacion.id', '=', $ubicacionesid)
              ->get();
            }

            $arreglo[] = [
              'empleado' => $empleado,
              'puesto' => $puestos[0],
              'departamento' => $departamentos[0],
              'estados' =>$estados[0],
              'ubicaciones' =>$ubicaciones[0],
            ];
          }


          return response()->json($arreglo);
        }

      }
