<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;
use Illuminate\Http\Request;
use App\DatosBancariosEmpleado;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Rap2hpoutre\FastExcel\FastExcel;
use Barryvdh\DomPDF\Facade as PDF;

class DatosBancariosEmpleadoController extends Controller
{
    /**
     * [protected Reglas definidas para el guardado y la actualizacion en la BD]
     * @var [type]
     */
    protected $rules = array(
        'numero_tarjeta' => 'required|max:16',
        'numero_cuenta' => 'required|max:15',
        'clabe' => 'max:18',
        'banco_id' => 'required|max:50',
    );

    /**
     * [index Consulta en la base de datos de todos los registros de la tabla datos_bancarios_empleados]
     * @param  Request $request [description]
     * @param  Int  $id      [description]
     * @return Response           [description]
     */
    public function index(Request $request, $id)
    {
        $DatosBancEmpleado = DB::table('datos_bancarios_empleados')
            ->leftJoin('catalogo_bancos AS CB', 'CB.id', '=', 'datos_bancarios_empleados.banco_id')
            ->select('datos_bancarios_empleados.*', 'CB.nombre AS bnombre')->get();
        return response()->json($DatosBancEmpleado);
    }

    /**
     * [store Guarado de un registro en la BD]
     * @param  Request $request [description]
     * @return Response           [description]
     */
    public function store(Request $request)
    {
        try
        {
            if (!$request->ajax()) return redirect('/');
            $validator = Validator::make($request->all(), $this->rules);

            if ($validator->fails())
            {
                return response()->json(array(
                    'status' => false,
                    'errors' => $validator->errors()->all()
                ));
            }
            $DatosBancEmpleado = new DatosBancariosEmpleado();
            $DatosBancEmpleado->fill($request->all());
            Utilidades::auditar($DatosBancEmpleado, $DatosBancEmpleado->id);
            $DatosBancEmpleado->save();
            return response()->json(array(
                'status' => true
            ));
        }
        catch (\Throwable $e)
        {
            Utilidades::errors($e);
            return response()->json(['status' => false]);
        }
    }

    /**
     * [edit actualizacion del campo condicion dek modelo DatosBancariosEmpleado]
     * @param  Int $id [description]
     * @return Response     [description]
     */
    public function edit($id)
    {
        $DatosBancEmpleado = DatosBancariosEmpleado::findOrFail($id);
        if ($DatosBancEmpleado->condicion == 0)
        {
            $DatosBancEmpleado->condicion = 1;
        }
        else
        {
            $DatosBancEmpleado->condicion = 0;
        }
        $DatosBancEmpleado->update();
        return $DatosBancEmpleado;
    }

    /**
     * [update Actualizacion de un registro en la BD]
     * @param  Request $request [description]
     * @return Response           [description]
     */
    public function update(Request $request)
    {
        try
        {
            if (!$request->ajax()) return redirect('/');
            $validator = Validator::make($request->all(), $this->rules);

            if ($validator->fails())
            {
                return response()->json(array(
                    'status' => false,
                    'errors' => $validator->errors()->all()
                ));
            }
            $DatosBancEmpleado = DatosBancariosEmpleado::findOrFail($request->id);
            $DatosBancEmpleado->fill($request->all());
            Utilidades::auditar($DatosBancEmpleado, $DatosBancEmpleado->id);
            $DatosBancEmpleado->save();

            return response()->json(array(
                'status' => true,
            ));
        }
        catch (\Throwable $e)
        {
            Utilidades::errors($e);
        }
    }

    /**
     * [bancos Consulta del modelo CatalogoBanco]
     * @return Response [description]
     */
    public function bancos()
    {
        $bancos = \App\CatalogoBanco::orderBy('id', 'desc')->get();
        return response()->json($bancos);
    }

    /**
     * [get Obtencion del un registro especifico en la BD por id recibido]
     * @param  Request $request [description]
     * @param  Int  $id      [description]
     * @return Response           [description]
     */
    public function get($id)
    {
        $valores = explode('&', $id);
        array_push($valores,1); // 1. Conser 2. CSCT
        // return dd($valores);
        $tarjetas = [];
        if ($valores[1] == 1)
        {
            //conserflow
            $tarjetas = [];
            $datos_bancarios = \App\DatosBancariosEmpleado::leftJoin('catalogo_bancos AS CB', 'CB.id', '=', 'datos_bancarios_empleados.banco_id')
                ->select('datos_bancarios_empleados.*', 'CB.nombre AS bnombre')
                ->where('empleado_id', '=', $valores[0])
                ->where('datos_bancarios_empleados.condicion',1)
                ->get()->toArray();

            $tarjetas_endenred = \App\EdenRedUser::where('empleado_id', $valores[0])
                ->select(
                    'tarjetas_edenred.*',
                    DB::raw("('N/D') AS clabe"),
                    DB::raw("('EDENRED CONSERFLOW') as bnombre")
                )
                ->get()->toArray();

            $tarjetas = array_merge($datos_bancarios, $tarjetas_endenred);
        }
        elseif ($valores[1] == 2)
        {
            // Constructora
            $tarjetas = [];
            $empleado_cfw = \App\Empleado::where('id', $valores[0])->select('nombre', 'ap_paterno', 'ap_materno')->first();
            $empleado_csct = \App\EmpleadoDBP::where('nombre', $empleado_cfw->nombre)->where('ap_paterno', $empleado_cfw->ap_paterno)->where('ap_materno', $empleado_cfw->ap_materno)->first();

            $datos_bancarios = \App\DatosBancariosEmpleadoDBP::leftJoin('catalogo_bancos AS CB', 'CB.id', '=', 'datos_bancarios_empleados.banco_id')
                ->select('datos_bancarios_empleados.*', 'CB.nombre AS bnombre')
                ->where('empleado_id', '=', $empleado_csct->id)
                ->get()->toArray();

            $tarjetas_endenred = \App\EdenRedUserDBP::where('empleado_id', $empleado_csct->id)
                ->select(
                    'tarjetas_edenred.*',
                    DB::raw("('N/D') AS clabe"),
                    DB::raw("('EDENRED CSCT') as bnombre")
                )
                ->get()->toArray();

            $tarjetas = array_merge($datos_bancarios, $tarjetas_endenred);
        }

        return response()->json($tarjetas);
    }

    /**
     * [datosBancariosReporte ]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function datosBancariosReporte(Request $request)
    {
        $empleados = DB::table('empleados AS e')
            ->join('contratos AS c', 'e.id', '=', 'c.empleado_id')
            ->join('empresas AS m', 'c.empresa_id', '=', 'm.id')
            ->join('proyectos AS p', 'c.proyecto_id', '=', 'p.id')
            ->join('datos_bancarios_empleados AS d', 'd.empleado_id', '=', 'e.id')
            ->join('catalogo_bancos AS b', 'd.banco_id', '=', 'b.id')
            ->select('m.nombre AS empresa', 'p.nombre AS proyecto', DB::raw("CONCAT(e.ap_paterno,' ',e.ap_materno, ' ', e.nombre) AS empleado"), 'd.numero_cuenta', 'd.numero_tarjeta', 'd.clabe', 'b.nombre AS banco')
            ->where([
                ['e.condicion', '=', 1],
                ['c.condicion', '=', 1],
                ['d.condicion', '=', 1],
                ['c.proyecto_id', $request->proyecto_id == 0 ? '>' : '=', $request->proyecto_id]
            ])
            ->orderBy('proyecto', 'ASC')
            ->orderBy('empleado', 'ASC')
            ->orderBy('empresa', 'ASC')
            ->get()->toArray();
        return response()->json($empleados);
    }

    /**
     * [pdfDatosBancarios Crecion en formato pdf de la vista retornada]
     * @param  Request $request [description]
     * @param  Int  $id      [description]
     * @return Stream           [description]
     */
    public function pdfDatosBancarios(Request $request, $id)
    {
        $empleados = DB::table('empleados AS e')
            ->join('contratos AS c', 'e.id', '=', 'c.empleado_id')
            ->join('empresas AS m', 'c.empresa_id', '=', 'm.id')
            ->join('proyectos AS p', 'c.proyecto_id', '=', 'p.id')
            ->join('datos_bancarios_empleados AS d', 'd.empleado_id', '=', 'e.id')
            ->join('catalogo_bancos AS b', 'd.banco_id', '=', 'b.id')
            ->select('m.nombre AS empresa', 'p.nombre AS proyecto', DB::raw("CONCAT(e.ap_paterno,' ',e.ap_materno, ' ', e.nombre) AS empleado"), 'd.numero_cuenta', 'd.numero_tarjeta', 'd.clabe', 'b.nombre AS banco')
            ->where([
                ['e.condicion', '=', 1],
                ['c.condicion', '=', 1],
                ['d.condicion', '=', 1],
                ['c.proyecto_id', $id == 0 ? '>' : '=', $id]
            ])
            ->orderBy('proyecto', 'ASC')
            ->orderBy('empleado', 'ASC')
            ->orderBy('empresa', 'ASC')
            ->get();

        $pdfdbem = PDF::loadView('pdf.dbemproyecto', compact('empleados'));
        $pdfdbem->setPaper("letter", "landscape");

        return $pdfdbem->stream();
    }
}
