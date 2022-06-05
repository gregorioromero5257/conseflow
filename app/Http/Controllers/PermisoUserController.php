<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\PermisoUser;
use Illuminate\Support\Facades\DB;

class PermisoUserController extends Controller
{
    //

    public function index(Request $request,$id)
    {
        $permisou = DB::table('permisos')
        ->select('permisos.*')
        ->where('permisos.user_id','=',$id)
        ->get();
        return response()->json(
            $permisou->toArray()

        );

        }

        public function create()
    {
        $modulos = DB::table('modulos')
           ->select('modulos.*')
           ->orderBy('modulos.id', 'ASC')
           ->get();
        return response()->json($modulos);
    }

    /**
     * [allPermisos Asigna todos los permisos de menus y submenus]
     * @param  Int $id [description]
     * @return Response     [description]
     */
    public function allPermisos($id)
    {
     $permisos = DB::table('permisos')->select('permisos.*')->where('permisos.user_id','=',$id)->delete();
      $menu = DB::table('elementos_menus')->select('elementos_menus.*')->orderBy('elementos_menus.id','ASC')->get();
      for ($i=0; $i < count($menu) ; $i++) {
        $permisos = new \App\PermisoUser();
        $permisos->modulo_id = $menu[$i]->modulo_id;
        $permisos->elementos_menu_id = $menu[$i]->id;
        $permisos->elementos_submenu_id = null;
        $permisos->user_id = $id;
        $permisos->C = 1;
        $permisos->R = 1;
        $permisos->U = 1;
        $permisos->D = 1;
        $permisos->save();
      }
      $submenus = DB::table('elementos_submenus')
      ->leftJoin('elementos_menus AS EM','EM.id','=','elementos_submenus.elementos_menu_id')
      ->select('elementos_submenus.*','EM.modulo_id')->orderBy('elementos_submenus.id','ASC')->get();
      for ($j=0; $j < count($submenus) ; $j++) {
        $permiso = new \App\PermisoUser();
        $permiso->modulo_id =  $submenus[$j]->modulo_id;
        $permiso->elementos_menu_id = $submenus[$j]->elementos_menu_id;
        $permiso->elementos_submenu_id = $submenus[$j]->id;
        $permiso->user_id = $id;
        $permiso->C = 1;
        $permiso->R = 1;
        $permiso->U = 1;
        $permiso->D = 1;
        $permiso->save();
      }
      return response()->json(array('status' => true));
    }

    public function show($id)
    {

        $elementos_menu = DB::table('elementos_menus')
           ->select('elementos_menus.*')
           ->orderBy('elementos_menus.id', 'ASC')
           ->get();
           foreach ($elementos_menu as $key => $value) {
               $id_menu = $value->id;
               if (!is_null($id_menu)) {
                $elementos_submenu = DB::table('elementos_submenus')
                 ->select('elementos_submenus.*')->where('elementos_submenus.elementos_menu_id','=',$id_menu)->get();
               }
           $arreglo [] =[
            'm' => $value,
           's'=> $elementos_submenu
           ];
           }
        return response()->json($arreglo);
    }

    public function menusidemp($id)
    {
        $elementos_menu = DB::table('permisos')

           ->select('permisos.*')
           ->where('permisos.user_id','=',$id)
           ->orderBy('permisos.user_id', 'ASC')
           ->get();
        return response()->json($elementos_menu);
    }

    public function edit($id)
    {
        //
        $elementos_submenus=DB::table('elementos_submenus')
        ->select('elementos_submenus.*')
        ->orderBy('elementos_submenus.id','ASC')
        ->get();
        return response ()->json($elementos_submenus);
    }

    public function actualizar(Request $request){

        if (!$request->ajax()) return redirect('/');

        $permisos = DB::table('permisos')->select('permisos.*')
        ->where('permisos.modulo_id','=',$request->modulo)
        ->where('permisos.elementos_menu_id','=',$request->id_menu)
        ->where('permisos.user_id','=',$request->id)
        ->first();

        if (is_null($permisos)) {
            $permiso = new \App\PermisoUser();
            $permiso->modulo_id = $request->modulo;
            $permiso->elementos_menu_id = $request->id_menu;
            $permiso->elementos_submenu_id = null;
            $permiso->user_id = $request->id;
            $permiso->C = 1;
            $permiso->R = 1;
            $permiso->U = 1;
            $permiso->D = 1;
            $permiso->save();
        }
        else{
            $permisos = DB::table('permisos')->select('permisos.*')
        ->where('permisos.modulo_id','=',$request->modulo)
        ->where('permisos.elementos_menu_id','=',$request->id_menu)
        ->where('permisos.user_id','=',$request->id)
        ->delete();


        }

        return response()->json(array(
            'status' => true
        ));
    }

    public function actualizarsub(Request $request){

        $permisos = DB::table('permisos')
        ->leftJoin('modulos','modulos.id','=','permisos.modulo_id')
        ->select('permisos.*','modulos.id AS idmod')
        ->where('permisos.elementos_menu_id','=',$request->menu_id)
        ->where('permisos.elementos_submenu_id','=',$request->id_submenu)
        ->where('permisos.user_id','=',$request->id)
        ->first();
        $modulo = DB::table('elementos_menus')->select('elementos_menus.*')->where('elementos_menus.id','=',$request->menu_id)->first();
        if (is_null($permisos)) {
            $permiso = new \App\PermisoUser();
            $permiso->modulo_id =  $modulo->modulo_id;
            $permiso->elementos_menu_id = $request->menu_id;
            $permiso->elementos_submenu_id = $request->id_submenu;
            $permiso->user_id = $request->id;
            $permiso->C = 1;
            $permiso->R = 1;
            $permiso->U = 1;
            $permiso->D = 1;
            $permiso->save();
        }
        else{
            $permisos = DB::table('permisos')->select('permisos.*')
            ->where('permisos.elementos_menu_id','=',$request->menu_id)
            ->where('permisos.elementos_submenu_id','=',$request->id_submenu)
            ->where('permisos.user_id','=',$request->id)
        ->delete();


        }

        return response()->json(array(
            'status' => true
        ));
    }
    public function update(Request $request, $id)
    {    if (!$request->ajax()) return redirect('/');




return response()->json($response);
}



}
