<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\UsuarioCategoria;
use App\ProyectoCategoria;
use App\ProyectoSubcategoria;
use Validator;
use Illuminate\Support\Facades\Auth;

class UsuarioCategoriaController extends Controller
{
    protected $rules = array(
        'proyecto_categoria_id' => 'required',
        'proyecto_subcategoria_id' => 'required',
    );

    public function index()
    {
        $usuarioCategorias = DB::table('usuario_categoria')
            ->join('proyecto_categorias', 'usuario_categoria.proyecto_categoria_id', '=', 'proyecto_categorias.id')
            ->join('proyecto_subcategorias', 'usuario_categoria.proyecto_subcategoria_id', '=', 'proyecto_subcategorias.id')
            ->join('users', 'usuario_categoria.user_id', '=', 'users.id')
            ->select('usuario_categoria.*', 'proyecto_categorias.nombre AS categoria', 'proyecto_subcategorias.nombre AS subcategoria', 'users.name AS usuario')
            ->get();

        return response()->json(
            $usuarioCategorias->toArray()
        );
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

        $usuarioCategoria = new UsuarioCategoria();
        $usuarioCategoria->user_id = $request->user_id;
        $usuarioCategoria->proyecto_categoria_id = $request->proyecto_categoria_id;
        $usuarioCategoria->proyecto_subcategoria_id = $request->proyecto_subcategoria_id;
        $usuarioCategoria->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function eliminar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $usuarioCategoria = UsuarioCategoria::findOrFail($request->id);
        $usuarioCategoria->delete();
    }

    public function todos(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $user_id = (int)$request->user_id;
        $resultDelete = DB::select(DB::raw("DELETE FROM usuario_categoria where user_id=".$user_id.";" ));
        $resultInsert = DB::select(DB::raw("INSERT INTO usuario_categoria (user_id, proyecto_categoria_id, proyecto_subcategoria_id) SELECT ".$user_id.", proyecto_categoria_id, s.id AS proyecto_subcategoria_id FROM proyecto_categorias AS c JOIN proyecto_subcategorias AS s ON c.id = s.proyecto_categoria_id ;" ));

        return response()->json(array(
            'status' => true
        ));
    }

}
