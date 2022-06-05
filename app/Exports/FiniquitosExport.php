<?php
namespace App\Exports;

use App\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FiniquitosExport implements FromView, ShouldAutoSize
{
    protected $fecha_inicial;
    protected $fecha_final;
    protected $proyecto_id;

    public function __construct($fecha_inicial, $fecha_final, $proyecto_id)
    {
        $this->fecha_inicial = $fecha_inicial;
        $this->fecha_final = $fecha_final;
        $this->proyecto_id = $proyecto_id;
    }

    public function view(): View
    {
        $finiquitos = DB::table('finiquito')
        ->join('antiguedad', 'finiquito.antiguedad_id', '=', 'antiguedad.id')
        ->join('empresas', 'antiguedad.empresa_id', '=', 'empresas.id')
        ->join('empleados', 'antiguedad.empleado_id', '=', 'empleados.id')
        ->join('contratos', 'finiquito.contrato_id', '=', 'contratos.id')
        ->join('proyectos', 'contratos.proyecto_id', '=', 'proyectos.id')
        ->select('finiquito.id', 'finiquito.formato', 'antiguedad.fecha_inicial', 'antiguedad.fecha_final', 'empresas.nombre AS empresa', 'contratos.antiguedad_id', 'proyectos.nombre AS proyecto', DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno) AS empleado"), 'finiquito.total', 'proyectos.nombre AS proyecto' )
        ->where([
            ['antiguedad.condicion',  '=',  0],
            ['antiguedad.fecha_final', '>=', $this->fecha_inicial],
            ['antiguedad.fecha_final', '<=', $this->fecha_final],
            ['contratos.proyecto_id', $this->proyecto_id == 0 ? '>' : '=', $this->proyecto_id]
        ])
        ->get();

        return view('excel.finiquitos', compact('finiquitos'));
    }
}
