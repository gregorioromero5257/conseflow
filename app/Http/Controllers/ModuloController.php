<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Permiso;
use App\Modulo;
use App\ElementosMenu;
use App\ElementosSubmenu;
 
class ModuloController extends Controller
{
	/**
	 * Regresa los modulos que el usuario autenticado tiene en permisos
	 *
	 * @param  Request $request
	 * @return arreglo de los modulos
	 */
    public function getModulosByUAuthUser(Request $request)
	{
	    if (!$request->ajax()) return redirect('/');
	    $id = Auth::id();
	    $permisos = Permiso::where('user_id',$id)->distinct()->get(['modulo_id']);
	    foreach ($permisos as $key => $permiso) {
	    	$moduloshabilitados = Modulo::where('id',$permiso->modulo_id)->where('condicion','1')->get();
	    	if (count($moduloshabilitados)>0) {
	    		foreach ($moduloshabilitados as $key => $mh) {
	    			$modulos[] = $mh;
	    		}
	    	}
	    }
	    return $modulos;
	}

	public function loadModulos(Request $request)
	{	
		$regreso = ['permiso' => 0];
	    if (!$request->ajax()) return redirect('/');
	    $id = Auth::id();
	    $menu = ElementosMenu::where('page', 'like', '%' . json_decode($request->name) . '%')
	    		->first();
	    //dd($menu);
	    if (!is_null($menu)) {
		    $permisos = Permiso::where('user_id',$id)
		    			->where('modulo_id',$menu->modulo_id)
		    			->where('elementos_menu_id',$menu->id)->count();
	    }
	    if (!isset($permisos)) {
	    	$submenu = ElementosSubmenu::where('page', 'like', '%' .json_decode($request->name). '%')->first();
	    	if (!is_null($submenu)) {
		    	$permisos = Permiso::where('user_id',$id)
		    			->where('elementos_menu_id',$submenu->elementos_menu_id)
		    			->where('elementos_submenu_id',$submenu->id)->count();
		    	$regreso = ['permiso' => $permisos];
	    	}

	    }else{
	    	
	    	$regreso = ['permiso' => $permisos];

	    }
	    return $regreso;
	}

}
