<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Permiso;
use App\Empleado;
use App\Contrato;
use App\Puesto;
use App\ElementosMenu;
use App\ElementosSubmenu;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Validation\Rule;


class ElementosMenuController extends Controller
{
	protected $rulesMenu = array(
        'name' => 'required|max:50',
	);
	
	protected $rulesSubmenu = array(
        'submenu' => 'required|max:100',
    );

	/**
	* Esta funcion regresa los permisos de los menus y submenus del usuario autenticado
	*
	* A *description*, that can span multiple lines, to go _in-depth_ into the details of this element
	* and to provide some background information or textual references.
	*
	* @param string $myArgument With a *description* of this argument, these may also
	*    span multiple lines.
	*
	* @return void
	*/
    public function index(Request $request)
	{
	    $id = Auth::id();
	    $modulo_id = $request->id;
	    $sisubmenu = $request->submenu;
	    $menus = Permiso::where('user_id',$id)->distinct()->where('modulo_id',$modulo_id)->get(['elementos_menu_id','C','R','U','D']);
	    if ($sisubmenu) {
	    	$submenus = Permiso::where('user_id',$id)
	    						->where('modulo_id',$modulo_id)
	    						->where('elementos_submenu_id','!=','NULL')
							    ->get(['elementos_menu_id','elementos_submenu_id','C','R','U','D']);
	    }
		if (count($menus)>0) {
	    	foreach ($menus as $key => $menu) {
		    	$elementosmenu = ElementosMenu::where('id',$menu->elementos_menu_id)->get();
		    	$elementosmenus[] = [
					'id' => $elementosmenu[0]->id,
					'clase' => $elementosmenu[0]->clase,
					'name' => $elementosmenu[0]->name,
					'page' => $elementosmenu[0]->page,
					'modulo_id' => $elementosmenu[0]->modulo_id,
					'C' => $menu->C,
					'R' => $menu->R,
					'U' => $menu->U,
					'D' => $menu->D,
		    	];
		    }
		}	    
		$elementossubmenuarray = [];
	    if ($sisubmenu) {
	    	foreach ($submenus as $key => $submenu) {
		    	$elementossubmenus = ElementosSubmenu::where('id',$submenu->elementos_submenu_id)
			    	->get();
		    	foreach ($elementossubmenus as $jey => $elementossubmenu) {
			    	$elementossubmenuarray[] = [
						'id' => $elementossubmenu->id,
						'clase' => $elementossubmenu->clase,
						'name' => $elementossubmenu->name,
						'submenu' => $elementossubmenu->submenu,
						'page' => $elementossubmenu->page,
						'elementos_menu_id' => $elementossubmenu->elementos_menu_id,
						'C' => $submenus[$key]->C,
						'R' => $submenus[$key]->R,
						'U' => $submenus[$key]->U,
						'D' => $submenus[$key]->D,
			    	];
		    	}
		    }
	    }

	    if ($sisubmenu) {
	    	return [
	    		'menu' => $elementosmenus,
	    		'submenu' => $elementossubmenuarray,
	    	];	    
	    }else{
	    	return [
	    		'menu' => $elementosmenus
	      	];
	    }

	}

	public function elementosMenuPorModulo(Request $request, $id)
    {
        $elementos_menu = DB::table('elementos_menus')
		->where('modulo_id','=',$id)
		->orderBy('id')
        ->get();

        return response()->json(
			$elementos_menu
        );
	}

	public function elementosPorModulo(Request $request, $id)
    {
        $elementos_menu = DB::table('elementos_menus')
        ->select(
            DB::raw(" id AS elementos_menu_id"),
			'elementos_menus.modulo_id',
			'elementos_menus.id',
            'elementos_menus.clase',
            'elementos_menus.name', 
            DB::raw(" '' AS submenu"),
            'elementos_menus.page',
            DB::raw(" 1 AS es_menu")
        )
		->where('modulo_id','=',$id)
		->orderBy('id')
        ->get();

        $elementos_submenu = DB::table('elementos_submenus')
        ->select(
			'elementos_submenus.elementos_menu_id',
			'elementos_menus.modulo_id', 
            'elementos_submenus.id',
            'elementos_submenus.clase',
            'elementos_menus.name',
            'elementos_submenus.submenu',
            'elementos_submenus.page',
            DB::raw(" 0 AS es_menu")
        )
        ->join('elementos_menus', 'elementos_submenus.elementos_menu_id', '=', 'elementos_menus.id')
		->where('elementos_menus.modulo_id','=',$id)
		->orderBy('elementos_menus.modulo_id')
        ->get();

        $resultado =  array_merge($elementos_menu->toArray(), $elementos_submenu->toArray() );
        $result = array_multisort( $resultado);

        return response()->json(
           $resultado
        );
	}
	
	public function eliminarMenu(Request $request)
	{
		// Eliminar en submenus todos los hijos
		// Elimnar en permisos
		if (!$request->ajax()) return redirect('/');
		$menu = ElementosMenu::findOrFail($request->id);
		$menu->delete();

		DB::table('elementos_submenus')->where('elementos_menu_id', '=', $request->id)->delete();

		DB::table('permisos')->where('elementos_menu_id', '=', $request->id)->delete();

		return response()->json(array(
            'status' => true
        ));
	}

	public function eliminarSubmenu(Request $request)
	{
		// Elimnar en la tabla permisos
		if (!$request->ajax()) return redirect('/');
		$submenu = ElementosSubmenu::findOrFail($request->id);
		$submenu->delete();

		DB::table('permisos')->where('elementos_submenu_id', '=', $request->id)->delete();

		return response()->json(array(
            'status' => true
        ));
	}

	public function agregarMenu(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $validator = Validator::make($request->all(), $this->rulesMenu);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $menu = new ElementosMenu();
        $menu->clase = $request->clase;
        $menu->name = $request->name;
        $menu->page = $request->page;
        $menu->modulo_id = $request->modulo_id;
        $menu->save();

        return response()->json(array(
            'status' => true
        ));
	}
	
	public function actualizarMenu(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $validator = Validator::make($request->all(), $this->rulesMenu);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $menu = ElementosMenu::findOrFail($request->id);
        $menu->clase = $request->clase;
        $menu->name = $request->name;
        $menu->page = $request->page;
        $menu->modulo_id = $request->modulo_id;
		$menu->save();
		
		// Actualizar submenus
		DB::table('elementos_submenus')
            ->where('elementos_menu_id', $request->id)
            ->update(['name' => $request->name]);

        return response()->json(array(
            'status' => true
        ));
	}
	
	public function agregarSubmenu(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $validator = Validator::make($request->all(), $this->rulesSubmenu);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $submenu = new ElementosSubmenu();
        $submenu->clase = $request->clase;
        $submenu->name = $request->name;
		$submenu->page = $request->page;
		$submenu->submenu = $request->submenu;
        $submenu->elementos_menu_id = $request->elementos_menu_id;
		$submenu->save();	

        return response()->json(array(
            'status' => true
        ));
	}
	
	public function actualizarSubmenu(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $validator = Validator::make($request->all(), $this->rulesSubmenu);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $submenu = ElementosSubmenu::findOrFail($request->id);
        $submenu->clase = $request->clase;
        $submenu->name = $request->name;
		$submenu->page = $request->page;
		$submenu->submenu = $request->submenu;
        $submenu->elementos_menu_id = $request->elementos_menu_id;
        $submenu->save();

        return response()->json(array(
            'status' => true
        ));
    }
}
