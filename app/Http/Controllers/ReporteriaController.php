<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\RequisitadoCompradoExport;
use App\Exports\EstatusMaterialExport;
use App\Exports\EstatusMaterialRequisitadoExport;
use Maatwebsite\Excel\Facades\Excel;


class ReporteriaController extends Controller
{
    //

    public function requisitadocomprado($id)
    {
      return Excel::download(new RequisitadoCompradoExport($id), 'RequisitadoComprado.xlsx' );
    }

    public function estatusmaterial($id)
    {
      return Excel::download(new EstatusMaterialExport($id), 'RequisitadoComprado.xlsx' );
    }

    public function estatusmaterialrequisitado($id)
    {
      return Excel::download(new EstatusMaterialRequisitadoExport($id), 'RequisitadoComprado.xlsx' );
    }
}
