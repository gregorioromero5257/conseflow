<?php

namespace App\Http\Controllers\Calidad;

use App\CalidadModels\InspeccionSoldaduraJunta;
use App\CalidadModels\MaterialJunta;
use App\CalidadModels\Junta;
use App\ColadaInspeccion;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Utilidades;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JuntasController  extends Controller
{
    /**
     * Obtiene las juntas del spool ingresado
     */
    public function Obtener($spool_id)
    {
        // try
        // {
        $juntas = DB::table("calidad_juntas as cj")
            ->join("calidad_juntas_materiales as cjm1", "cjm1.id", "cj.material_uno_id")
            ->join("calidad_juntas_materiales as cjm2", "cjm2.id", "cj.material_dos_id")
            ->join("calidad_soldador_proyecto as csp", "csp.id", "cj.soldador_proyecto_id")
            ->join("certificaciones_procedimiento as cp", "csp.certificacion_procedimiento_id", "cp.id")
            ->join("coladas_inspeccion as ci1", "ci1.id", "cjm1.colada_inspeccion_id")
            ->join("coladas_inspeccion as ci2", "ci2.id", "cjm2.colada_inspeccion_id")
            ->join("calidad_proced_soldadura as cps", "cps.id", "cp.procedimiento_id")
            ->join("calidad_soldadores as cs", "cs.id", "cp.soldador_id")
            ->join("empleados as e", "e.id", "cs.empleado_id")
            ->where("cj.spool_id", $spool_id)
            ->select(
                "cj.id",
                "cj.nombre",
                "cj.diametro",
                "cj.espesor",
                "cj.estado",
                "cj.hoja",
                "cj.fecha_habilitado",
                "cj.fecha_soldado",
                "cj.fecha_inspeccion",
                "cjm1.id as yolo1",
                "cjm1.nombre_corto as nombre_material1",
                "cjm2.id as cci2_id",
                "cjm2.nombre_corto as nombre_material2",
                "csp.id as csp_id",
                "cps.clave as procedimiento",
                "cps.id as procedimiento_id",
                "cs.clave as clave_soldador",
                "ci1.colada as colada_uno",
                "ci2.colada as colada_dos",
                DB::raw("concat(cs.clave,' - ',e.nombre, ' ',e.ap_paterno,' ',e.ap_materno) as clave_nombre"),
                DB::raw("CONCAT_WS(' -  ',cps.clave,cps.descripcion ) as nombre_aux")
            )->get();


        return $this->Success("juntas", $juntas);
        // }
        // catch (Exception $e)
        // {
        //     return $this->Error($e, "obtener las juntas");
        // }
    }

    /**
     * Obtiene los datos de los materiales de la junta ingresada
     */
    public function ObtenerMaterialesJutna($j_id)
    {
        try
        {
            $materiales = DB::table("calidad_juntas as cj")
                ->join("calidad_juntas_materiales as cci1", "cci1.id", "cj.material_uno_id")
                ->join("coladas_inspeccion as ci1", "ci1.id", "cci1.colada_inspeccion_id")
                ->join("inspecciones_calidad as ic1", "ic1.id", "ci1.inspeccion_id")
                ->join("articulos as a1", "a1.id", "ic1.articulo_id")
                ->join("calidad_juntas_materiales as cci2", "cci2.id", "cj.material_dos_id")
                ->join("coladas_inspeccion as ci2", "ci2.id", "cci2.colada_inspeccion_id")
                ->join("inspecciones_calidad as ic2", "ic2.id", "ci2.inspeccion_id")
                ->join("articulos as a2", "a2.id", "ic2.articulo_id")
                ->where("cj.id", $j_id)
                ->select(
                    "ic1.id as ic1_id",
                    "a1.nombre as articulo1_nombre",
                    "cci1.nombre_corto as nombre_corto1",
                    "cci1.id as yolo1", // id de colada_junta
                    "ic2.id as ic2_id",
                    "ci1.id as material_uno_id",
                    "ci2.id as material_dos_id",
                    "ci1.colada as colada1",
                    "a2.nombre as articulo2_nombre",
                    "cci2.nombre_corto as nombre_corto2",
                    "cci2.id as yolo2", // id de colada_junta
                    "ci2.colada as colada2"
                )->first();

            return $this->Success("materiales", $materiales);
        }
        catch (Exception $e)
        {
            return $this->Error($e, "obtener los materiales");
        }
    }

    public function ObtenerMateriales($ss_id)
    {
        try
        {
            // Obtener proyecto
            $cp = $this->ObtenerProyecto($ss_id);
            if ($cp == null) return $this->Error(null, "obtener los materiales. Proyecto no encontrado");

            // Materiales de rime del proyecto
            $materiales = DB::table("rime_calidad as rc")
                ->join("inspecciones_calidad as ic", "ic.rime_calidad_id", "rc.id")
                ->join("articulos as a", "a.id", "ic.articulo_id")
                ->where("rc.proyecto_id", $cp->proyecto_id)
                ->select(
                    "ic.id as ic_id",
                    "ic.articulo_id",
                    "a.nombre"
                )
                ->orderBy("descripcion")
                ->get();
            return $this->Success("materiales", $materiales);
        }
        catch (Exception $e)
        {
            return $this->Error($e, "obtener los materiales");
        }
    }

    /**
     * Obtiene los soldadores con el procedimiento del proyecto ingresado
     */
    public function ObtenerSoldadores($data)
    {
        try
        {
            $dts = explode("&", $data);
            if (count($dts) != 2) return $this->Error(null, "obtener los soldadores. Datos incompletos");
            $ss_id = $dts[0];
            $procedimiento_id = $dts[1];

            $proyecto = $this->ObtenerProyecto($ss_id);
            $soldadores = DB::table("calidad_soldador_proyecto as csp")
                ->join("certificaciones_procedimiento as cp", "csp.certificacion_procedimiento_id", "cp.id")
                ->join("calidad_soldadores as cs", "cs.id", "cp.soldador_id")
                ->join("calidad_proced_soldadura as cps", "cps.id", "cp.procedimiento_id")
                ->join("empleados as e", "e.id", "cs.empleado_id")
                ->where("csp.proyecto_id", $proyecto->id)
                ->where("cps.id", $procedimiento_id)
                ->select(
                    "csp.id as csp_id", // calidad soldador proyecto
                    "cs.id as cs_id",   // calidad soldador
                    DB::raw("concat(cs.clave,' - ',e.nombre, ' ',e.ap_paterno,' ',e.ap_materno) as clave_nombre")
                )->get();
            return $this->Success("soldadores", $soldadores);
        }
        catch (Exception $e)
        {
            return $this->Error($e, "obtener los soldadores");
        }
    }

    /**
     * Obtiene todas las coladas de la inspección ingresada
     */
    public function ObtenerColada($inspeccion_id)
    {
        try
        {
            $coladas = ColadaInspeccion::where("inspeccion_id", $inspeccion_id)
                ->select("id as ci_id", "colada")
                ->get();
            return $this->Success("coladas", $coladas);
        }
        catch (Exception $e)
        {
            return $this->Error($e, "obtener colada");
        }
    }

    /**
     * Guarda la junta
     */
    public function Guardar(Request $request)
    {
        try
        {

            // return response()->json(
            //     [
            //         "status" => false,
            //         "1" => $request->material1_id,
            //         "2" => $request->material2_id,
            //         "id1"=>MaterialJunta::find(1)->id,
            //         "id2"=>MaterialJunta::find(2)->id,
            //     ]
            // );
            DB::beginTransaction();
            if ($request->id == null) // nuevo
            {
                // Crear Coladas
                $colada1 = new MaterialJunta();
                $colada1->nombre_corto = $request->nombre_material_1;
                $colada1->colada_inspeccion_id = $request->colada_1;
                $colada1->save();

                $colada2 = new MaterialJunta();
                $colada2->nombre_corto = $request->nombre_material_2;
                $colada2->colada_inspeccion_id = $request->colada_2;
                $colada2->save();

                // Guardar junta
                $junta = new Junta($request->all());
                $junta->material_uno_id = $colada1->id;
                $junta->material_dos_id = $colada2->id;
                $junta->save();
            }
            else
            {
                // Colada1 
                $colada1 = MaterialJunta::find($request->material1_id);
                $colada1->nombre_corto = $request->nombre_material_1;
                $colada1->colada_inspeccion_id = $request->colada_1;
                $colada1->update();
                // Colada 2
                $colada2 = MaterialJunta::find($request->material2_id);
                $colada2->nombre_corto = $request->nombre_material_2;
                $colada2->colada_inspeccion_id = $request->colada_2;
                $colada2->update();

                // Datos de la junta
                $junta = Junta::find($request->id);
                $junta->fill($request->all());
                $junta->update();
            }
            DB::commit();
            return $this->Success();
        }
        catch (Exception $e)
        {
            DB::rollBack();
            return $this->Error($e, "guardar la junta");
        }
    }

    /**
     * Obtiene el proyecto del sds ingresado
     */
    private function ObtenerProyecto($ss_id)
    {
        // Obtener proyecto
        $cp = DB::table("servicios_sistema as ss")
            ->join("calidad_sistemas as cs", "cs.id", "ss.sistema_id")
            ->join("calidad_proyectos as cp", "cp.id", "cs.proyecto_id")
            ->where("ss.id", $ss_id)
            ->select("*")->first();
        return $cp;
    }

    /**
     * Habilita la junta ingresada
     */
    public function Habilitar(Request $request)
    {
        try
        {
            $junta = Junta::find($request->id);
            $junta->fecha_habilitado = $request->fecha;
            $junta->estado = 1; // habilitado
            $junta->update();
            return $this->Success();
        }
        catch (Exception $e)
        {
            return $this->Error($e, "habilitar la junta");;
        }
    }

    /**
     * Suelda la junta ingresada
     */
    public function Soldar(Request $request)
    {
        try
        {
            DB::beginTransaction();
            // Registrar inspeccion historial
            $inspec = new InspeccionSoldaduraJunta($request->all());
            $inspec->save();
            if ($request->aceptado == 1) // Registrar fecha de soldadura
            {
                $junta = Junta::find($request->junta_id);
                $junta->fecha_soldado = $request->fecha;
                $junta->estado = 2; // Soldar
                $junta->update();
            }
            DB::commit();
            return $this->Success();
        }
        catch (Exception $e)
        {
            DB::rollBack();
            return $this->Error($e, "soldar la junta");;
        }
    }
    /**
     * REgistra la fecha la inspección visual de la junta
     */
    public function Inspeccion(Request $request)
    {
        try
        {
            $junta = Junta::find($request->id);
            $junta->fecha_inspeccion = $request->fecha;
            $junta->estado = 3; // inspeccionado
            $junta->update();
            return $this->Success();
        }
        catch (Exception $e)
        {
            return $this->Error($e, "registrar inspección la junta");;
        }
    }

    public function Success($nombre = "", $data = [])
    {
        return response()->json(["status" => true, $nombre => $data]);
    }

    public function Error($e, $mensaje = "obtener los datos")
    {
        if ($e != null)
            Utilidades::errors($e);
        return response()->json(["status" => false, "mensaje" => "Error al " . $mensaje]);
    }
}
