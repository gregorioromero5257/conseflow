<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Contrato;
use Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException\Encrypter;
use \App\Http\Helpers\Utilidades;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*Obtiene todos los registros de usuarios del sistema*/
        $iduser = Auth::user();
        $usuarios = User::select('users.*',\DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS nombre_empleado"))
        ->leftJoin('empleados as e','e.id','=','users.empleado_id')->orderBy('id', 'asc')->get();
        foreach ($usuarios as $key => $usuario)
        {
            $tipo_ubicacion = \App\TipoUbicacion::where('id', '=', $usuario->tipo_ubicacion_id)->first();
            $arreglo[] =
            [
                'usuario' => $usuario,
                'miusuario' => $iduser,
                'ubicacion' => $tipo_ubicacion,
            ];
        }

        return response()->json($arreglo);
        /***************************************************/
        /***************************************************/
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*Inserta un nuevo usuario en la BD*/
        //Obtiene los datos del navegador utilizado
        $browser = $_SERVER['HTTP_USER_AGENT'];

        $usuario = new User();
        $usuario->name = $request->name;
        $usuario->name_user = $request->name_user;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->password);
        $usuario->tipo_ubicacion_id = $request->tipo_ubicacion_id;
        $usuario->condicion = '1';
        $usuario->session_id = '0';
        $usuario->navegador = $browser;
        // $usuario->validar_nav = '0';
        $usuario->save();
        /***********************************************/
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /*Cambia el campo session_id del registro en la BD*/
        $usuario = User::findOrFail($id);
        $usuario->session_id = '0';
        $usuario->save();
        /*************************************************/
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /*Habilita o Inhabilita un usuario del sistema*/
        $usuario = User::findOrFail($id);
        if ($usuario->condicion==0) {
            $usuario->condicion = 1;
        }else{
            $usuario->condicion = 0;
        }
        $usuario->update();
        return $usuario;
        /**********************************************/
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /*Actualiza los registros del usuario indicado*/
        $usuario = User::findOrFail($id);
        $usuario->name = $request->name;
        $usuario->name_user = $request->name_user;
        $usuario->email = $request->email;
        if (isset($request->password))
            $usuario->password = bcrypt($request->password);
        $usuario->tipo_ubicacion_id = $request->tipo_ubicacion_id;
        $usuario->empleado_id = $request->empleado_id == 0 ? null : $request->empleado_id;
        $usuario->save();

        return response()->json(array(
            'status' => (isset($request->password))
        ));
        /********************************************/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
