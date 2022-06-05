<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empleado;
use App\Puesto;


class OrganigramaController extends Controller
{
	public $json_list = array();

    public function index()
    {
    	$json_list = array();

        $json_list[] = $this->hasChilden();
		return response()->json($json_list);
    }

    public function hasChilden($id=0){
	    $inicio = '';
	    $empleados = $this->getEmpleados($id);
	    foreach ($empleados as $k => $e) {
	    	if ($this->isLast($e->id)) {
	    		$json_list[] =	[
									'id' => $e->id,
									'name' => $e->nombre,
									'title' => $e->puesto
								];
	    	}else{
	    		$json_list[] =
	    		[
					'id' => $e->id,
					'name' => $e->nombre,
					'title' => $e->puesto,
	    			'children' => $this->hasChilden($e->id)
				];
	    	}
	    }
	    return $json_list;
	}
	
	public function islast($id)
	{
		$empleados = \DB::table('puestos')->where('nivel_o', $id)->count();
		if ($empleados==0) {
			return true;
		}
		return false;
	}
	public function getEmpleados($id=0)
	{
		$empleados = \DB::table('puestos')
		            ->join('empleados', 'empleados.puesto_id', '=', 'puestos.id')
					->select('puestos.id', 'puestos.nivel_o', 'puestos.nombre as puesto',
					\DB::raw("CONCAT(empleados.nombre ,' ',empleados.ap_paterno ,' ',empleados.ap_materno) AS nombre"))
		            ->where('empleados.condicion' , 1)
		            ->where('puestos.nivel_o' , $id)
		            ->get();
		return $empleados;
	}
    public function store(Request $request)
    {

    }

    public function update(Request $request)
    {

    }

    public function edit($id)
    {

    }

    public function show($id)
    {

    }



    public function activar(Request $request)
    {

	}

}
