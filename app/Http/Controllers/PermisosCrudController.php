<?php

  namespace App\Http\Controllers;

  use Illuminate\Http\Request;
  use Validator;
  use App\PermisoUser;
  use Illuminate\Support\Facades\DB;

  class PermisosCrudController extends Controller
  {
    //

    
    public function create()
    {
      //se carga la tabla modulos y se genera el json qque visualiza lo que contiene la tabla
      $modulos = DB::table('modulos')
      ->select('modulos.*')
      ->orderBy('modulos.id', 'ASC')
      ->get();
      return response()->json($modulos);

    }

    public function Permisocrud(Request $request)

    {//genera un json con los datos de esta tabla y se llama en la vista PermisosCrud.vue generando primero la ruta en web con /permisocrud/ con el verbo get
      $permisocrud = DB::table('controles')
      ->select('controles.*')
      ->get();
      return response()->json($permisocrud);
    }

    public function elementospormodulos(Request $request, $id)
    {
      //genera un array que carga los modulos con elementos menu y su submenu si los hay
      $elementos_menu = DB::table('elementos_menus')
      ->select(
        DB::raw(" id AS elementos_menu_id"),
        'elementos_menus.modulo_id',
        'elementos_menus.id',
        'elementos_menus.clase',
        'elementos_menus.name',
        DB::raw(" '' AS submenu"),
        'elementos_menus.page',
        DB::raw(" 1 AS es_menu"),
        DB::raw(" 1 AS pk")
        )
        ->where('modulo_id','=',$id)
        ->where('page','!=','null')//descarta los submenus o menus que en su propiedad page sean nulos
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
          DB::raw(" 0 AS es_menu"),
            DB::raw(" 1 AS pk")
          )
          ->join('elementos_menus', 'elementos_submenus.elementos_menu_id', '=', 'elementos_menus.id')
          ->where('elementos_menus.modulo_id','=',$id)
          ->orderBy('elementos_menus.modulo_id')
          ->get();

          $resultado =  array_merge($elementos_menu->toArray(), $elementos_submenu->toArray() );
          $i = 0;
          foreach ($resultado as $key=> $item){

                $item->pk=$i;
                $i++;

          }
          $result = array_multisort( $resultado);

          return response()->json(
            $resultado
          );

        }


        public function menusidempc(Request $request)
        {//se muestra el contenido  de la tabla permisos controles y se envia en el parametro post para que se llene y se haga una consulta posterior de los permisos que ya estan asignados
          $elementos_menu = DB::table('permisos_controles')
          ->select('permisos_controles.*')
          ->where('permisos_controles.usuario_id','=',$request->usuario_id)
          ->where('permisos_controles.ruta','=',$request->ruta)
          ->orderBy('permisos_controles.usuario_id', 'ASC')
          ->get();
          return response()->json($elementos_menu);
        }



        public function actualizar(Request $request) {

          if (!$request->ajax()) return redirect('/');
          // return response()->json($request);

          $permisos = DB::table('permisos_controles')-> select('permisos_controles.*')
          ->where ('permisos_controles.usuario_id','=', $request->usuario_id)
          ->where('permisos_controles.ruta', '=', $request->ruta)
          ->delete();

          for ($i=0; $i < count($request->control) ; $i++) {


            $permisos = DB::table('permisos_controles')-> select('permisos_controles.*')
            ->where ('permisos_controles.usuario_id','=', $request->usuario_id)
            ->where('permisos_controles.control_id', '=', $request->control[$i])
            ->where('permisos_controles.ruta', '=', $request->ruta)
            ->first();

            if (is_null($permisos)) {
              $permiso = new \App\PermisosCrud();
              $permiso->usuario_id = $request->usuario_id;
              $permiso->control_id = $request->control[$i];
              $permiso->ruta = $request->ruta;
              $permiso->save();
            }


          }
          return response()->json(array(
            'status' => true
          ));
        }


    public function todos(Request $request)
    {
      if (!$request->ajax()) return redirect('/');

      $user_id = (int)$request->usuario_id;
      $resultDelete = DB::select(DB::raw('DELETE FROM permisos_controles where usuario_id='.$user_id.';' ));
      $resultInsert = DB::select(DB::raw('INSERT INTO  permisos_controles(usuario_id, control_id, ruta) SELECT '.$user_id.', controles.id, page FROM controles join elementos_submenus;'));
      $resultInsert = DB::select(DB::raw('INSERT INTO  permisos_controles(usuario_id, control_id, ruta) SELECT '.$user_id.', controles.id, page FROM controles join elementos_menus WHERE page IS NOT NULL;'));

      return response()->json(array(
          'status' => true
      ));
    }

}
