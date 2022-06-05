<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Status;
use App\Http\Helpers\Utilidades;
use App\NuevoAnalisisSeguridad;
use App\ParticipantesNuevoAnalisis;
use Barryvdh\DomPDF\Facade as PDF;
use App\PartidaAnalisisSeguridad;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Monolog\Handler\FirePHPHandler;

class NuevoSeguridadController extends Controller
{

    /**
     * Obtiene los analisis de seguridad
     */
    public function ObtenerAnalisis()
    {
        try
        {
            $analisis = DB::table("analisis_seguridad_nuevo as asn")
                ->join("empleados as e1", "e1.id", "asn.id_empleado_operaciones")
                ->join("empleados as e2", "e2.id", "asn.id_empleado_sho")
                ->where("asn.condicion", "1") // Activos
                ->select(
                    "asn.*",
                    DB::raw("CONCAT_WS(' ',e1.nombre,e1.ap_paterno,e1.ap_materno) AS operaciones_nombre"),
                    DB::raw("CONCAT_WS(' ',e2.nombre,e2.ap_paterno,e2.ap_materno) AS seguridad_nombre")
                )
                ->orderBy("fecha")->get();
            return Status::Success("analisis", $analisis);
        }
        catch (Exception $e)
        {
            return Status::Error($e, "obtener los análisis de seguridad");
        }
    }

    /**
     * Registra un nuevo analisis de seguridad
     */
    public function Guardar(Request $request)
    {
        try
        {
            if ($request->ids == null)
                return response()->json(["status" => false, "mensaje" => "Datos incompletos"]);
            // Obtener ids para guardar
            $ids = $request->ids;
            $ids = substr($ids, 0, -1); // Eliminar último '&'
            $dts = explode("&", $ids);

            if (count($dts) == 0) // Sin partidas
                return response()->json(["status" => false, "mensaje" => "Datos incompletos"]);

            DB::beginTransaction();

            $analisis = null; // Analisis seguridad
            if ($request->id == null) // Registrar
            {
                $analisis = new NuevoAnalisisSeguridad($request->all());
                Utilidades::auditar($analisis, $analisis->id);
                $analisis->save();
            }
            else // Actualizar
            {
                $analisis = NuevoAnalisisSeguridad::find($request->id);
                $analisis->fill($request->all());
                Utilidades::auditar($analisis, $analisis->id);
                $analisis->update();
            }

            // Guardar todas las partidas del analisis
            foreach ($dts as $evaluacion)
            {
                $aux_ids = explode("|", $evaluacion);

                $partida = new PartidaAnalisisSeguridad();
                $partida->an_seg_id = $analisis->id;
                $partida->analisis_id = $aux_ids[0];
                $partida->riesgo_id = $aux_ids[1];
                $partida->recomendacion_id = $aux_ids[2];
                $partida->supervisor_id = $aux_ids[3];
                Utilidades::auditar($partida, $partida->id);
                $partida->save();
            }

            DB::commit();
            return Status::Success();
        }
        catch (Exception $e)
        {
            DB::rollBack();
            Utilidades::errors($e);
            return Status::Error($e, "guardar las partidas");
        }
    }
    /**
     * Obtiene las secuencias de los analisis regitrados
     */
    public function ObtenerSecuencias()
    {
        try
        {
            $secuencias = DB::table("seguridad_analisis as sa")
                ->join("seguridad_secuencias as ss", "ss.id", "sa.secuencia_id")
                ->select("ss.id", "ss.nombre as nombre")
                ->distinct("ss.nombre")
                ->orderBy("ss.nombre")
                ->get();
            return Status::Success("secuencias", $secuencias);
        }
        catch (Exception $e)
        {
            return Status::Error($e, "obtener las secuencias");
        }
    }

    /**
     * Obtiene las recomendaciones del riesgo ingresado
     */
    public function ObtenerRecomendaciones($s_id)
    {
        try
        {
            $recomendaciones = DB::table("seguridad_analisis as sa")
                ->join("seguridad_riesgo as sr", "sr.id", "sa.riesgo_id")
                ->join("seguridad_recomendacion as sr2", "sr2.id", "sa.recomendacion_id")
                ->where("sa.secuencia_id", $s_id)
                ->select(
                    "sa.id as sa_id",
                    "sa.secuencia_id",
                    "sa.riesgo_id",
                    "sa.recomendacion_id",
                    "sr.nombre as riesgo",
                    "sr2.nombre as recomendacion",
                    DB::raw("CONCAT_WS(' - ',sr.nombre,sr2.nombre) as riesgo_recomen ")
                )
                ->orderBy("riesgo_recomen")
                ->get();
            return Status::Success("recomendaciones", $recomendaciones);
        }
        catch (Exception $e)
        {
            return Status::Error($e, "obtener las recomendaciones");
        }
    }

    /**
     * Obtiene las partidas del análisis ingresado
     */
    public function ObtenerPartidas($a_id)
    {
        try
        {
            $partidas = DB::table("seguridad_analisis as ase")
                ->join("analisis_seguridad_partida as asp", "asp.an_seg_id", "ase.id")
                ->join("seguridad_secuencias as ss", "ss.id", "asp.analisis_id")
                ->join("seguridad_riesgo as sr", "sr.id", "asp.riesgo_id")
                ->join("seguridad_recomendacion as sr2", "sr2.id", "asp.recomendacion_id")
                ->join("empleados as e", "e.id", "asp.supervisor_id")
                ->where("asp.an_seg_id", $a_id)
                ->where("asp.estado", 1)
                ->select(
                    "asp.id",
                    "asp.supervisor_id",
                    "asp.an_seg_id as analisis_seguridad_id",
                    "ss.id as secuencia_id",
                    "ss.nombre as secuencia",
                    "sr.id as riesgo_id",
                    "sr.nombre as riesgo",
                    "sr2.id as recomendacion_id",
                    "sr2.nombre as recomendacion",
                    DB::raw("CONCAT_WS(' ',e.ap_paterno,e.ap_materno,e.nombre) as supervisor_nombre")
                )->get();
            return Status::Success("partidas", $partidas);
        }
        catch (Exception $e)
        {
            return Status::Error($e, "obtener las partidas");
        }
    }

    /**
     * Elimina una partida del analisis ingresado
     */
    public function EliminarPartidas(Request $request)
    {
        try
        {
            $partida = PartidaAnalisisSeguridad::find($request->id);
            $partida->estado = 0; // Desactivar
            $partida->update();
            return Status::Success();
        }
        catch (Exception $e)
        {
            return Status::Error($e, "eliminar el eliminar la partida");
        }
    }

    /**
     * Obtiene los participantes del analisis ingresado
     */
    public function ObtenerParticipantes($a_id)
    {
        try
        {
            $participantes = ParticipantesNuevoAnalisis::join("empleados as e", "e.id", "empleado_id")
                ->where("analisis_id", $a_id)
                ->where("analisis_seguridad_participantes.estado", 1)
                ->select(
                    "analisis_seguridad_participantes.*",
                    "e.id as empleado_id",
                    DB::raw("CONCAT_WS(' ',e.ap_paterno,e.ap_materno,e.nombre) as empleado")
                )
                ->get();
            return Status::Success("participantes", $participantes);
        }
        catch (Exception $e)
        {
            return Status::Error($e, "obtener los participantes");
        }
    }
    /**
     * Registra un nuevo particpante en el analisis ingresado
     */
    public function GuardarParticipantes(Request $request)
    {
        try
        {
            $participante = new ParticipantesNuevoAnalisis($request->all());
            Utilidades::auditar($participante, 0);
            $participante->save();
            return Status::Success();
        }
        catch (Exception $e)
        {
            return Status::Error($e, "guardar el participante");
        }
    }

    /**
     * Elimina un participante del analisis ingresado
     */
    public function EliminarParticipantes(Request $request)
    {
        try
        {
            $empleado = DB::table("analisis_seguridad_participantes as asp")
                ->where("asp.analisis_id", $request->analisis_id)
                ->where("asp.empleado_id", $request->empleado_id)
                ->update([
                    "asp.estado" => 0
                ]);
            return Status::Success();
        }
        catch (Exception $e)
        {
            return Status::Error($e, "eliminar el participante");
        }
    }

    /**
     * Elimina el analisis ingresado
     */
    public function EliminarAnalisis(Request $request)
    {
        try
        {
            $analisis = NuevoAnalisisSeguridad::find($request->id);
            $analisis->condicion = 0;
            Utilidades::auditar($analisis, $analisis->id);
            $analisis->update();
            return Status::Success();
        }
        catch (Exception $e)
        {
            return Status::Error($e, "eliminar el análisis");
        }
    }

    /**
     * Descarga el formato de analisis de serguridad
     */
    public function DescargarFormato($id)
    {
        try
        {
            // Obtener analisis (encabezado)
            $analisis = DB::table('analisis_seguridad_nuevo AS ase')
                ->join('empleados AS eo', 'eo.id', 'ase.id_empleado_operaciones')
                ->join('empleados AS es', 'es.id', 'ase.id_empleado_sho')
                ->select(
                    'ase.*',
                    DB::raw("CONCAT(eo.nombre,' ',eo.ap_paterno,' ',eo.ap_materno) AS operaciones_empleado"),
                    DB::raw("CONCAT(es.nombre,' ',es.ap_paterno,' ',es.ap_materno) AS shso_empleado")
                )
                ->where('ase.id', $id)
                ->first();

            // Obtener secuencias, riesgos y recomendaciones
            $riesgos = [];

            ## BUENO
            $asd = DB::table("analisis_seguridad_nuevo as asn")
                ->join("analisis_seguridad_partida as asp", "asp.an_seg_id", "asn.id")
                ->join("seguridad_secuencias as ss", "ss.id", "asp.analisis_id")
                ->join("seguridad_riesgo as sr", "sr.id", "asp.riesgo_id")
                ->join("seguridad_recomendacion as sr2", "sr2.id", "asp.recomendacion_id")
                ->join("empleados as e", "e.id", "asp.supervisor_id")
                ->where("asn.id", $id)
                ->select(
                    "ss.nombre as secuencia",
                    "sr.nombre as riesgo",
                    "sr2.nombre  as recomendacion",
                    DB::raw("CONCAT_WS(' ',e.ap_paterno,e.ap_materno,e.nombre) as supervisor")
                )->get();
            // Secuencias
            $secuencias_final = [];
            $secuencias = [];
            $supervisores = [];
            foreach ($asd as $partida)
            {
                // Obtener todas las distintias secuencias
                $secuencias[] = $partida->secuencia;
                $supervisores[] = $partida->supervisor;
            }
            $secuencias = array_unique($secuencias); // secuencias unicas
            $supervisores = array_unique($supervisores); // supervisores unicas

            foreach ($secuencias as $i=>$secuencia)
            {
                $riesgos = [];
                foreach ($asd as $partida)
                {
                    if ($partida->secuencia == $secuencia)
                    {
                        $riesgos[] = $partida->riesgo;
                    }
                }
                $secuencias_final[] =
                    [
                        "secuencia" => $secuencia,
                        "riesgos" => $riesgos,
                        "supervisor"=> $supervisores[$i]
                    ];
            }
            // Ok arriba
            $lol = [];
            // Recorrer cada secuencia
            foreach ($secuencias_final as $secuencia_1)
            {
                // Recorrer cada riesgo de la secuencia
                $riesgos_final = [];
                $recomen_temp = [];
                foreach ($secuencia_1["riesgos"] as $riesgo)
                {
                    foreach ($asd as $partida)
                    {
                        if ($partida->secuencia == $secuencia_1["secuencia"] && $partida->riesgo == $riesgo)
                        {
                            $recomen_temp[] = $partida->recomendacion;
                        }
                    }
                }
                $secuencia_1["riesgos"] = $this->ObterNombres($secuencia_1["riesgos"]);
                $secuencia_1["recomendaciones"] = $this->ObterNombres($recomen_temp);
                $lol[] = $secuencia_1;
            }

            // Obtener los participantes
            $participantes = DB::table('analisis_seguridad_participantes AS pa')
                ->join('empleados AS e', 'e.id', 'pa.empleado_id')
                ->leftJoin('puestos AS p', 'p.id', 'e.puesto_id')
                ->select(
                    'pa.*',
                    DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado"),
                    'p.nombre AS puesto'
                )
                ->where('pa.analisis_id', $id)
                ->where("pa.estado", 1)
                ->get();

            $anio = substr($analisis->fecha, 2, 2);
            $mes = substr($analisis->fecha, 5, -3);
            $dia = substr($analisis->fecha, 8);

            $mesnombre = $this->ObtenerMes($mes);
            $fechafinal = $dia . '-' . $mesnombre . '-' . $anio;
            // dd($lol);
            // dd($array_partidas);
            $pdf = PDF::loadView('pdf.analisisseguridad_nuevo', compact("lol", 'analisis', 'riesgos', 'participantes', 'fechafinal'));
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->setPaper('letter', 'landscape');
            error_reporting(E_ALL ^ E_DEPRECATED);
            return $pdf->stream();
        }
        catch (Exception $e)
        {
            dd($e);
            Utilidades::errors($e);
            return view("errors.500");
        }
    }

    /**
     * Obtiene el nombre del mes ingresadp
     */
    private function ObtenerMes($n)
    {
        $meses = [
            "ENE", "FEB", "MAR", "ABR", "MAY", "JUN",
            "JUL", "AGO", "SEP", "OCT", "NOV", "DIC"
        ];
        return $meses[$n + 1];
    }

    public function ObterNombres($array)
    {
        $ms = "";
        foreach ($array as $r)
        {
            $ms .= $r . "\n";
        }
        return $ms;
    }
}
