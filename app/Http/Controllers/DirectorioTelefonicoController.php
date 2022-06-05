<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\DirectorioTelefonico;
use Validator;
use \App\Http\Helpers\Utilidades;

class DirectorioTelefonicoController extends Controller
{
    protected $rules = array(
        'numero_telefonico' => 'required|max:250',
        //'proveedor_telefonia' => 'required|max:250',
        //'ubicacion' => 'required|max:250',
    );
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $directorio = DirectorioTelefonico::orderBy('directorio.id', 'ASC')
            ->leftjoin('empleados AS Empleado', 'Empleado.id', '=', 'directorio.empleado_encargado_id')
            ->select('directorio.*',
                DB::raw("CONCAT(Empleado.nombre,' ',Empleado.ap_paterno,' ',Empleado.ap_materno) AS nombrec"))
            ->get();

        return response()->json($directorio);
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
        try{
        if (!$request->ajax()) return redirect('/');
            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                return response()->json(array(
                    'status' => false,
                    'errors' => $validator->errors()->all()
                ));
            }

            $directorios = new DirectorioTelefonico();
            $directorios->numero_telefonico = $request->numero_telefonico;
            $directorios->proveedor_telefonia = $request->proveedor_telefonia;
            $directorios->ubicacion = $request->ubicacion;
            $directorios->comentario = $request->comentario;
            $directorios->empleado_encargado_id = $request->empleado_encargado_id;
            Utilidades::auditar($directorios, $directorios->id);
            $directorios->save();

        return response()->json(array(
            'status' => true
        ));
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        try{
        if (!$request->ajax()) return redirect('/');

        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $directorios = DirectorioTelefonico::findOrFail($request->id);
        $directorios->numero_telefonico = $request->numero_telefonico;
        $directorios->proveedor_telefonia = $request->proveedor_telefonia;
        $directorios->ubicacion = $request->ubicacion;
        $directorios->comentario = $request->comentario;
        $directorios->empleado_encargado_id = $request->empleado_encargado_id;
        Utilidades::auditar($directorios, $directorios->id);
        $directorios->save();

        return response()->json(array(
            'status' => true
        ));
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
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
