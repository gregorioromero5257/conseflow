<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Expediente;
use App\Contrato;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Empleado;
use App\EstadoCivil;
use App\DireccionEmpleado;
use App\Contacto;
use App\Identificacion;
use App\DatosBancariosEmpleado;
use App\Puesto;
use App\DireccionesAdministrativas;
use App\Departamento;

class ExpedienteController extends Controller
{
    protected $rules = array(
        'folio' => 'required|max:30',
    );

    public function index(Request $request)
    {
        $expedientes = Expediente::orderBy('id', 'desc')->get()->toArray();
        return response()->json($expedientes);
    }

    public function update(Request $request)
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

        $expediente = Expediente::findOrFail($request->id);

        if (is_null($expediente)) {
            $expediente = new Expediente();
        }
        $expediente->folio = $request->folio;
        $expediente->solicitud = $request->solicitud;
        $expediente->evaluacion_psicolaboral = $request->evaluacion_psicolaboral;
        $expediente->evaluacion_hab_tecnicas = $request->evaluacion_hab_tecnicas;
        $expediente->foto = $request->foto;
        $expediente->acta_nacimiento = $request->acta_nacimiento;
        $expediente->credencial_elector = $request->credencial_elector;
        $expediente->curp = $request->curp;
        $expediente->rfc = $request->rfc;
        $expediente->nss_imss = $request->nss_imss;
        $expediente->comprobante_domicilio = $request->comprobante_domicilio;
        $expediente->cartilla_servicio_militar = $request->cartilla_servicio_militar;
        $expediente->licencia_manejo = $request->licencia_manejo;
        $expediente->acta_matrimonio = $request->acta_matrimonio;
        $expediente->carta_infonavit = $request->carta_infonavit;
        $expediente->certificado_medico = $request->certificado_medico;
        $expediente->carta_no_enfermedades = $request->carta_no_enfermedades;
        $expediente->vales_resguardo = $request->vales_resguardo;
        $expediente->comprobante_estudios = $request->comprobante_estudios;
        $expediente->carta_recomendacion = $request->carta_recomendacion;
        $expediente->retencion_credito_infonavit = $request->retencion_credito_infonavit;
        Utilidades::auditar($expediente, $expediente->empleado_id.','.$expediente->id);
        $expediente->save();

        return response()->json(array(
            'status' => true,
            'solicitud' => $request->solicitud
        ));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function get(Request $request, $id)
    {
        $expediente = Expediente::where(
            'empleado_id', $id
        )->first();

        if (is_null($expediente))
        {
            $expediente =  new Expediente();
            $expediente['empleado_id'] = $id;
            $expediente['folio'] = '';
            $expediente->save();

            $expediente = Expediente::where(
                'empleado_id', $id
            )->first();
        }

        return response()->json($expediente);
    }

    /**
     * Cargar los empleados en las tablas:
     * Empleados, Direcciones_empleados, Contacto_empleados, Telefonos_corporativos, Coreeos_corporativos, datos_bancarios_empleados
     * Recorrer los registros del archivo con FastExcel
     * Si no se encuentra el Puesto se agrega, si el departamento no se encuentra en el catalogo se agrega.
     * @return array Regresa el numero de los registros nuevos, repetidos, la fecha y hora de inicio y fin del script
     */
    public function uploadEmpleados(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        if($request->hasFile('import_file')){

        ini_set('max_execution_time', 300);

        $collection = (new FastExcel)->import($request->file('import_file')->getRealPath());

        $nuevos = 0;
        $repetidos = 0;
        $descripcionRepetidos = array();
        $inicio = date('Y-m-d H:i:s');
        $fecha = null;

        foreach($collection as $item) {

            if ( empty($item['NOMBRES']) ) continue;

            $empleado = Empleado::where('nombre', $item['NOMBRES'])
                ->where('ap_paterno', $item['AP PATERNO'])
                ->where('ap_materno', $item['AP MATERNO'])
                ->first();

            if (empty($empleado)) {
            $empleado = new Empleado();
            $empleado->nombre = $item['NOMBRES'];
            $empleado->ap_paterno = $item['AP PATERNO'];
            $empleado->ap_materno = $item['AP MATERNO'];
            $empleado->sexo = $item['SEXO'] == '' ? 0 : 1;
            $empleado->lug_nac = $item['LUGAR DE NACIMIENTO'];
            $empleado->fech_nac = date_format($item['FECHA NACIMIENTO'], 'Y-m-d');
            $empleado->tipo_sangre = $item['TIPO DE SANGRE'];

            $puesto = Puesto::where('nombre', $item['PUESTO'])->first();
            $idPuesto = 0;
            if (empty($puesto)) {

                $direccion = DireccionesAdministrativas::where('nombre', $item['DIRECCIÓN'])->first();
                $idDireccion = 0;
                if (empty($direccion)) {
                    $newDireccion = new Direccion();
                    $newDireccion->nombre = $item['DIRECCIÓN'];
                    $newDireccion->save();
                    $idDireccion = $newDireccion->id;
                } else {
                    $idDireccion = $direccion->id;
                }

                $depto = Departamento::where('nombre', $item['DEPARTAMENTO'])->first();
                $idDepto = 0;
                if (empty($depto)) {
                    $newDepto = new Departamento();
                    $newDepto->nombre = $item['DEPARTAMENTO'];
                    $newDepto->direccion_administrativa_id = $idDireccion;
                    $newDepto->save();
                    $idDepto = $newDepto->id;
                } else {
                    $idDepto = $depto->id;
                }

                $newPuesto = new Puesto();
                $newPuesto->nombre = $item['PUESTO'];
                $newPuesto->area = $item['AREA'];
                $newPuesto->departamento_id = $idDepto;
                $newPuesto->save();
                $idPuesto = $newPuesto->id;
            } else {
                $idPuesto = $puesto->id;
            }
            $empleado->puesto_id = $idPuesto;

            $estadoCivil = EstadoCivil::where('nombre', $item['ESTADO CIVIL'])->first();
            $empleado->edo_civil_id = empty($estadoCivil) ? null : $estadoCivil->id;
            $empleado->save();
            $nuevos++;

            $direccion = new DireccionEmpleado();
            $direccion->calle = $item['CALLE'];
            $direccion->numero_exterior = $item['NO EXT'];
            $direccion->numero_interior = $item['NO INT'];
            $direccion->colonia = $item['COLONIA'];
            $direccion->codigo_postal = $item['CP'];
            $direccion->localidad = $item['LOCALIDAD'];
            $direccion->municipio = $item['MUNICIPIO'];
            $direccion->entidad_federativa = $item['ESTADO'];
            $direccion->empleado_id = $empleado->id;
            $direccion->save();

            $contacto = new Contacto();
            $contacto->tel_celular = $item['TELEFONO CELULAR'];
            $contacto->tel_casa = $item['TELEFONO PART'];
            $contacto->correo_electronico = $item['EMAIL PERSONAL'];
            $contacto->contacto_emergencia = '';
            $contacto->tel_emergencia = '';
            $contacto->empleado_id = $empleado->id;
            $contacto->save();

            DB::table('correos_corporativos')->insert(
                ['correo' => $item['EMAIL CORPORATIVO'], 'empleado_id' => $empleado->id]
            );

            DB::table('telefonos_corporativos')->insert(
                ['telefono' => $item['TELEFONO ASIGNADO'], 'empleado_id' => $empleado->id]
            );

            $identificacion = new Identificacion();
            $identificacion->cartilla = $item['CARTILLA MILITAR'];
            $identificacion->curp = $item['CURP'];
            $identificacion->nss_imms = $item['NSS'];
            $identificacion->rfc = $item['RFC'];
            $identificacion->empleado_id = $empleado->id;
            $identificacion->save();

            try {
                $datbanemp = new DatosBancariosEmpleado();
                $datbanemp->banco = $item['BANCO'];
                $datbanemp->numero_tarjeta = $item['NO. TARJETA'];
                $datbanemp->numero_cuenta = $item['NO. CUENTA'];
                $datbanemp->clabe = $item['CLABE'];
                $datbanemp->empleado_id = $empleado->id;
                $datbanemp->save();
            } catch (\Illuminate\Database\QueryException $e) { }

            } else {
                $repetidos++;
                array_push($descripcionRepetidos, $item['NOMBRES']);
            }

        }

        $fin = date('Y-m-d H:i:s');

        return response()->json(
            array(
                'nuevos'=> $nuevos,
                'repetidos' => $repetidos,
                'inicio' => $inicio,
                'fin'=> $fin,
                'descripcionrepetidos' => $descripcionRepetidos,
            ));

        }
    }

    public function expedientePdf($id){

      $expediente = Expediente::where('empleado_id', '=' ,$id)
           ->leftJoin('empleados AS EE', 'EE.id', '=', 'expedientes.empleado_id')
           ->leftJoin('puestos AS puesto', 'puesto.id', '=', 'EE.puesto_id')
           ->select('expedientes.*','expedientes.solicitud','expedientes.evaluacion_psicolaboral','expedientes.evaluacion_hab_tecnicas',
           'expedientes.foto','expedientes.acta_nacimiento','expedientes.credencial_elector','expedientes.curp','expedientes.rfc',
           'expedientes.nss_imss','expedientes.comprobante_estudios','expedientes.cartilla_servicio_militar','expedientes.licencia_manejo',
           'expedientes.carta_recomendacion','expedientes.acta_matrimonio','expedientes.carta_infonavit','expedientes.certificado_medico',
           'expedientes.carta_no_enfermedades','expedientes.retencion_credito_infonavit','expedientes.vales_resguardo','expedientes.comprobante_domicilio',
           'expedientes.folio','puesto.nombre as areadmin','puesto.area as area',
            DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS entrega"))
           ->get()->first();

           $ubicacion = Contrato::where('empleado_id', '=' ,$id)
            ->leftJoin('tipo_ubicacion', 'tipo_ubicacion.id', '=', 'contratos.tipo_contrato_id')
            ->select('contratos.*','tipo_ubicacion.nombre as tun') ->get()->first();

            $sueldo = Contrato::where('empleado_id','=',$id)
             ->leftJoin('sueldos AS S','S.contrato_id','=','contratos.id')
             ->select('contratos.*','S.sueldo_mensual as ssd')
             ->first();

           $pdfcom = PDF::loadView('pdf.exp', compact('expediente','ubicacion','sueldo'));
           $pdfcom->setPaper("letter","portrait");

          return  $pdfcom->stream();


    }

}
