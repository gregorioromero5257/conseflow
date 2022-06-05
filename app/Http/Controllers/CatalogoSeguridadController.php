<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Utilidades;
use App\SeguridadAnalisis;
use App\SeguridadRecomendacion;
use App\SeguridadRiesgo;
use App\SeguridadSecuencia;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class CatalogoSeguridadController extends Controller
{

    /**
     * Obtiene las secuencias registradas
     */
    public function ObtenerSecuencias()
    {
        try
        {
            $secuencias = SeguridadSecuencia::orderBy("nombre")->get();
            return Status::Success("secuencias", $secuencias);
        }
        catch (Exception $e)
        {
            return Status::Error($e, "obtener las secuencias");
        }
    }

    /**
     * Crea un registro de secuencia de seguridad
     */
    public function RegistrarSecuencia(Request $request)
    {
        try
        {
            if ($request->id == null)
            {
                $catalogo = new SeguridadSecuencia($request->all());
                Utilidades::auditar($catalogo, 0);
                $catalogo->save();
            }
            else
            {
                $catalogo = SeguridadSecuencia::find($request->id);
                $catalogo->fill($request->all());
                Utilidades::auditar($catalogo, $catalogo->id);
                $catalogo->update();
            }
            return Status::Success();
        }
        catch (Exception $e)
        {
            return Status::Error($e, "al guardar el catálogo");
        }
    }

    /**
     * Crea un registro de riesgo de seguridad
     */
    public function RegistrarRiesgo(Request $request)
    {
        try
        {
            if ($request->id == null)
            {
                $catalogo = new SeguridadRiesgo($request->all());
                Utilidades::auditar($catalogo, 0);
                $catalogo->save();
            }
            else
            {
                $catalogo = SeguridadRiesgo::find($request->id);
                $catalogo->fill($request->all());
                Utilidades::auditar($catalogo, $catalogo->id);
                $catalogo->update();
            }
            return Status::Success();
        }
        catch (Exception $e)
        {
            dd($e);
            return Status::Error($e, "al guardar el riesgo");
        }
    }

    /**
     * Obtener los riesgo registrados
     */
    public function ObtenerRiesgos()
    {
        try
        {
            $riesgos = SeguridadRiesgo::orderBy("nombre")->get();
            return Status::Success("riesgos", $riesgos);
        }
        catch (Exception $e)
        {
            return Status::Error($e, "obtener los riesgos");
        }
    }

    /**
     * Crea un registro de recomendacion de seguridad
     */
    public function RegistrarRecomendacion(Request $request)
    {
        try
        {
            if ($request->id == null)
            {
                $catalogo = new SeguridadRecomendacion($request->all());
                Utilidades::auditar($catalogo, 0);
                $catalogo->save();
            }
            else
            {
                $catalogo = SeguridadRecomendacion::find($request->id);
                $catalogo->fill($request->all());
                Utilidades::auditar($catalogo, $catalogo->id);
                $catalogo->update();
            }
            return Status::Success();
        }
        catch (Exception $e)
        {
            return Status::Error($e, "al guardar el catálogo");
        }
    }

    /**
     * Obtiene todas las recomendaciones de seguridad
     */
    public function ObtenerRecomendaciones()
    {
        try
        {
            $recomendaciones = SeguridadRecomendacion::orderBy("nombre")->get();
            return Status::Success("recomendaciones", $recomendaciones);
        }
        catch (Exception $e)
        {
            return Status::Error($e, "obtener las recomendaciones");
        }
    }

    /**
     * Obtiene los riesgos de la secuencia ingresada
     */
    public function RiesgosDeSecuencia($s_id)
    {
        try
        {
            $riesgos = SeguridadRiesgo::where("secuencia_id", $s_id)
                ->orderBy("nombre")->get();
            return Status::Success("riesgos", $riesgos);
        }
        catch (Exception $e)
        {
            return Status::Error($e, "obtener los riesgos");
        }
    }

    /**
     * Obtiene las recomendaciones de la secuencia ingresada
     */
    public function RecomendacionesDeRiesgo($r_id)
    {
        try
        {
            $recomendaciones = SeguridadRecomendacion::where("riesgo_id", $r_id)
                ->orderBy("nombre")->get();
            return Status::Success("recomendaciones", $recomendaciones);
        }
        catch (Exception $e)
        {
            return Status::Error($e, "obtener las recomendaciones");
        }
    }

    /**
     * Obtiene los analisis de seguridad
     */
    public function ObtenerAnalisis()
    {
        try
        {
            $analisis = DB::table("seguridad_analisis as sa")
                ->join("seguridad_secuencias as ss", "ss.id", "sa.secuencia_id")
                ->join("seguridad_riesgo as sr", "sr.id", "sa.riesgo_id")
                ->join("seguridad_recomendacion as sr2", "sr2.id", "sa.recomendacion_id")
                ->select(
                    "sa.*",
                    "ss.nombre as secuencia",
                    "sr.nombre as riesgo",
                    "sr2.nombre as recomendacion",
                )->get();
            return Status::Success("analisis", $analisis);
        }
        catch (Exception $e)
        {
            return Status::Error($e, "obtener los análisis");
        }
    }

    /**
     * Registra un nuevo analisis de seguridad
     */
    public function GuardarAnalisis(Request $request)
    {
        try
        {
            if ($request->id == null) // Nuevo
            {
                $analisis = new SeguridadAnalisis($request->all());
                Utilidades::auditar($analisis, 0);
                $analisis->save();
            }
            else // Atualizar
            {
                $analisis = SeguridadAnalisis::find($request->id);
                $analisis->fill($request->all());
                Utilidades::auditar($analisis, $analisis->id);
                $analisis->update();
            }
            return Status::Success();
        }
        catch (Exception $e)
        {
            dd($e);
            return Status::Error($e, "guardar en análisis");
        }
    }
}
