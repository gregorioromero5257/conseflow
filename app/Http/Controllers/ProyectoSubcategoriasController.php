<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProyectoSubcategoria;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ProyectoSubcategoriasController extends Controller
{
    protected $rules = array(
        'nombre' => 'required|max:45',
    );

    public function index(Request $request)
    {
        // $proyectoSubcategoria = ProyectoSubcategoria::orderBy('id', 'desc')->get()->toArray();

        $proyectoSubcategoria = DB::table('proyecto_subcategorias')
            ->join('proyecto_categorias', 'proyecto_subcategorias.proyecto_categoria_id', '=', 'proyecto_categorias.id')
            ->select('proyecto_subcategorias.*', 'proyecto_categorias.nombre AS categoria')
            ->get();

        return response()->json($proyectoSubcategoria);
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }

        $proyectoSubcategoria = new ProyectoSubcategoria();
        $proyectoSubcategoria->nombre = $request->nombre;
        $proyectoSubcategoria->proyecto_categoria_id = $request->proyecto_categoria_id;
        $proyectoSubcategoria->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $proyectoSubcategoria = ProyectoSubcategoria::findOrFail($request->id);
        $proyectoSubcategoria->nombre = $request->nombre;
        $proyectoSubcategoria->proyecto_categoria_id = $request->proyecto_categoria_id;
        $proyectoSubcategoria->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function getList()
    {
        $proyectoSubcategoria = DB::table('proyecto_subcategorias')
        ->join('proyecto_categorias','proyecto_subcategorias.proyecto_categoria_id','=','proyecto_categorias.id')
            ->select('proyecto_subcategorias.id', DB::raw("CONCAT(proyecto_categorias.nombre,' - ',proyecto_subcategorias.nombre) AS nombre"))->orderBy('nombre', 'asc')->get()->toArray();
        return response()->json($proyectoSubcategoria);
    }

    public function getListByCategoria($id)
    {
        $proyectoSubcategoria = DB::table('proyecto_subcategorias')
            ->where('proyecto_categoria_id', $id)->orderBy('nombre', 'asc')->get()->toArray();
        return response()->json($proyectoSubcategoria);
    }

}
