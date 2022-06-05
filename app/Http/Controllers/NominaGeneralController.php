<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
Use App\Nomina;
Use App\Contrato;
Use App\Empleado;
Use App\Sueldo;
Use App\MovimientosNomina;
Use Validator;
use Illuminate\Validation\Rule;
use App\Exports\QuincenaExport;
use App\Exports\QuincenaExportFin;
use App\Exports\QuincenaExportCon;
use App\Exports\NominaGExport;
use Maatwebsite\Excel\Facades\Excel;

class NominaGeneralController extends Controller
{
	public function nominaexcel($id)
	{
		return Excel::download(new NominaGExport($id), 'ReporteNominaGeneral.xlsx' );
	}
}
