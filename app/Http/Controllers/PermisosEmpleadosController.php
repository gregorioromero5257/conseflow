<?php
namespace App\Http\Controllers;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use App\PermisosEmpleados;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use File;
use Illuminate\Support\Facades\Storage;
use App\Empleado;
use Mail;

class PermisosEmpleadosController extends Controller
{
  protected $rules = array(
    'fecha_solicitud' => 'required|max:45',
  );

  public function index(Request $request,$id)
  {
    $tipoPermiso = DB::table('solicitud_permisos')
    ->join('empleados AS SolicitaId', 'SolicitaId.id','=', 'solicitud_permisos.solicita_empleado_id')
    ->join('empleados AS AutorizaId', 'AutorizaId.id','=', 'solicitud_permisos.autoriza_empleado_id')
    ->select('solicitud_permisos.*',
    DB::raw("CONCAT(AutorizaId.nombre ,' ',AutorizaId.ap_paterno,' ',AutorizaId.ap_materno) AS NombreAutoriza"),
    DB::raw("CONCAT(SolicitaId.nombre ,' ',SolicitaId.ap_paterno,' ',SolicitaId.ap_materno) AS NombreSolicita"))
    ->where('solicitud_permisos.solicita_empleado_id', '=', $id)
    ->get();
    return response()->json(
      $tipoPermiso->toArray()
    );
  }
  public function grv($id)
  {
    $fecha = date("Y-m");
    $tipoPermiso = DB::table('solicitud_permisos')
    ->join('empleados AS SolicitaId', 'SolicitaId.id','=', 'solicitud_permisos.solicita_empleado_id')
    ->join('empleados AS AutorizaId', 'AutorizaId.id','=', 'solicitud_permisos.autoriza_empleado_id')
    ->select('solicitud_permisos.*',
    DB::raw("CONCAT(AutorizaId.nombre ,' ',AutorizaId.ap_paterno,' ',AutorizaId.ap_materno) AS NombreAutoriza"),
    DB::raw("CONCAT(SolicitaId.nombre ,' ',SolicitaId.ap_paterno,' ',SolicitaId.ap_materno) AS NombreSolicita"))
    ->where('solicitud_permisos.solicita_empleado_id', '=', $id)
    ->Where('fecha_solicitud', 'like', $fecha.'%')
    ->get();
    return response ()->json($tipoPermiso);
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
    if ($request->metodo == 'Nuevo'){
      $tipoPermiso = new PermisosEmpleados();
      $tipoPermiso->fill($request->all());
      Utilidades::auditar($tipoPermiso, $tipoPermiso->id);
      $tipoPermiso->save();

      /****************************************************/
      //envio de email al jefe directo informando el perimiso
      //descomentar esta linea para el envio de correos
      /*
      if ($tipoPermiso->autoriza_empleado_id != null) {
        $empleado = DB::table('empleados')
        ->select('empleados.nombre', 'empleados.ap_paterno', 'empleados.ap_materno', 'contacto_empleados.correo_electronico')
        ->join('contacto_empleados', 'empleados.id', '=', 'contacto_empleados.empleado_id')
        ->where('empleados.id','=', $tipoPermiso->autoriza_empleado_id)
        ->first();
        $empleado_s = DB::table('empleados')
        ->select('empleados.nombre', 'empleados.ap_paterno', 'empleados.ap_materno', 'contacto_empleados.correo_electronico')
        ->join('contacto_empleados', 'empleados.id', '=', 'contacto_empleados.empleado_id')
        ->where('empleados.id','=', $tipoPermiso->solicita_empleado_id)
        ->first();
        if (strlen($empleado->correo_electronico) > 0)
        {
        $data = [
          'nombre_autoriza' => $empleado->nombre.' '.$empleado->ap_paterno.' '.$empleado->ap_materno,
          'nombre_solicita' => $empleado_s->nombre.' '.$empleado_s->ap_paterno.' '.$empleado_s->ap_materno,
          'motivo' => $tipoPermiso->motivo,
          'fecha_solicitud' => $tipoPermiso->fecha_solicitud,
        ];
        Mail::send('emails.permisos', $data, function($message) use ($empleado) {
          $message->to($empleado->correo_electronico, $empleado->nombre.' '.$empleado->ap_paterno)
          ->subject('Aviso de solicitud de permiso');
          $message->from('cesar.abad@conservct.com','Conserflow');

        });
      }
      }
      /******************************************************/


      return response()->json(array(
        'status' => true
      ));
    }
    if ($request->metodo == 'Subir'){
      $FormatoStore = Null;

      if(!$request->hasFile('formato_permiso'))
      {
        $tipoPermiso = PermisosEmpleados::findOrFail($request->id);
        $tipoPermiso->formato_permiso = $FormatoStore;
        Utilidades::auditar($tipoPermiso, $tipoPermiso->id);
        $tipoPermiso->save();

        return response()->json(array(
          'status' => true
        ));
      }

      if($request->hasFile('formato_permiso'))
      {
        //obtiene el nombre del archivo y su extension
        $FormatoNE = $request->file('formato_permiso')->getClientOriginalName();
        //Obtiene el nombre del archivo
        $FormatoNombre = pathinfo($FormatoNE, PATHINFO_FILENAME);
        //obtiene la extension
        $FormatoExt = $request->file('formato_permiso')->getClientOriginalExtension();
        //nombre que se guarad en BD
        $FormatoStore = $FormatoNombre.'_'.uniqid().'.'.$FormatoExt;
        //Subida del archivo al servidor ftp
        Storage::disk('local')->put('Archivos/'.$FormatoStore, fopen($request->file('formato_permiso'), 'r+'));

        $tipoPermiso = PermisosEmpleados::findOrFail($request->id);
        $tipoPermiso->formato_permiso = $FormatoStore;
        $tipoPermiso->save();

        return response()->json(array(
          'status' => true
        ));
      }
    }

  }
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

    $tipoPermiso = PermisosEmpleados::findOrFail($request->id);
    $tipoPermiso->fill($request->all());
    Utilidades::auditar($tipoPermiso, $tipoPermiso->id);
    $tipoPermiso->save();

    return response()->json(array(
      'status' => true
    ));
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
    $beneficiarios = DB::table('solicitud_permisos')
    ->join('empleados', 'empleados.id', '=', 'solicitud_permisos.solicita_empleado_id')
    ->select("solicitud_permisos.*"
    ,DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno) as empleado"))
    ->get();
    return response()->json(
      $beneficiarios->toArray()
    );
  }
  public function EnEspera(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $tipoPermiso = PermisosEmpleados::findOrFail($request->id);
    $tipoPermiso->condicion = '0';
    Utilidades::auditar($tipoPermiso, $tipoPermiso->id);
    $tipoPermiso->save();
  }

  public function Autorizado(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $tipoPermiso = PermisosEmpleados::findOrFail($request->id);
    $tipoPermiso->condicion = '1';
    Utilidades::auditar($tipoPermiso, $tipoPermiso->id);
    $tipoPermiso->save();
  }
  public function NoAutorizado(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $tipoPermiso = PermisosEmpleados::findOrFail($request->id);
    $tipoPermiso->condicion = '2';
    Utilidades::auditar($tipoPermiso, $tipoPermiso->id);
    $tipoPermiso->save();
  }

  public function ConGoce(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $tipoPermiso = PermisosEmpleados::findOrFail($request->id);
    $tipoPermiso->goce = '1';
    Utilidades::auditar($tipoPermiso, $tipoPermiso->id);
    $tipoPermiso->save();
  }
  public function SinGoce(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $tipoPermiso = PermisosEmpleados::findOrFail($request->id);
    $tipoPermiso->goce = '0';
    Utilidades::auditar($tipoPermiso, $tipoPermiso->id);
    $tipoPermiso->save();
  }

  public function getList(Request $request)
  {
    $tiposPermiso = PermisosEmpleados::select('id', 'fecha_solicitud')->orderBy('id', 'desc')->get()->toArray();
    return response()->json($tiposPermiso);
  }
  public function getListaEnEsperaPorAutorizar()
  {
    $user = Auth::user();
    $permisos = DB::table('solicitud_permisos')
    ->leftJoin('empleados AS autoriza', 'autoriza.id', '=', 'solicitud_permisos.autoriza_empleado_id')
    ->leftJoin('empleados AS solicita', 'solicita.id', '=', 'solicitud_permisos.solicita_empleado_id')
    ->select("solicitud_permisos.*",
    DB::raw("CONCAT(autoriza.nombre ,' ',autoriza.ap_paterno,' ',autoriza.ap_materno) AS e_autoriza"),
    DB::raw("CONCAT(solicita.nombre ,' ',solicita.ap_paterno,' ',solicita.ap_materno) AS e_solicita"))
    ->where('solicitud_permisos.autoriza_empleado_id', '=' , $user->empleado_id)
    ->where('solicitud_permisos.condicion', '=' , 0)
    ->get();

    return response()->json([
      'solicitudes' => $permisos->toArray(),
    ]);
  }

}
