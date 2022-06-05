<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class CostosNodosController extends Controller
{
	public $json_list = array();

	/**
	 * [Construye el arbol de las partidas_costos_nodos]
	 * @param  Int      $proyecto_id [id del proyecto]
	 * @return Response              [Array anidado]
	 */
	public function arbol($proyecto_id)
	{
		$arreglo = [];
		$nodos_hijos = \DB::table('partidas_costos_nodos')->select('padre_id')
			->where('ultimo', '1')->where('proyecto_id', $proyecto_id)->distinct('padre_id')->get();

		foreach ($nodos_hijos as $key => $value) {
			$cadena = $this->buscarRastro($value->padre_id);

			$nodo_hijo = \DB::table('partidas_costos_nodos')
				->join('partidas_costos_nodos AS PCN', 'PCN.id', '=', 'partidas_costos_nodos.padre_id')
				->select('partidas_costos_nodos.*', 'PCN.concepto AS padre_concepto')
				->where('partidas_costos_nodos.padre_id', $value->padre_id)->get();

			$nodos_principal = \DB::table('partidas_costos_nodos')->select('partidas_costos_nodos.*')
				->where('padre_id', '0')->where('proyecto_id', $proyecto_id)->first();

			$nodo_suma = \DB::table('partidas_costos_nodos')->select(\DB::raw("SUM(partidas_costos_nodos.importe) AS Total"))
				->where('padre_id', $value->padre_id)->get();

			$arreglo[] = [
				'suma' => $nodo_suma,
				'principal' => $nodos_principal,
				'cade' => $cadena,
				'nodos' => $nodo_hijo,
			];
		}

		return response()->json($arreglo);
	}

	/**
	 * [Revisa los conceptos de las registros padres que le pertenencen para ser presentados]
	 * @param  Int     $id     [id de tipo entero]
	 * @return String  $cadena [cadena concatenada]
	 * 
	 */
	public function buscarRastro($id)
	{
		$cadena = '';
		$nodos_padres = \DB::table('partidas_costos_nodos')->select('id', 'codigo', 'padre_id', 'concepto')
			->where('id', $id)->first();
		if ($nodos_padres->padre_id == 0) {
			$cadena .= '';
		} else {
			$cadena .= $this->buscarRastro($nodos_padres->padre_id) . "/ " . $nodos_padres->codigo . " " . $nodos_padres->concepto;
		}

		return $cadena;
	}
}
