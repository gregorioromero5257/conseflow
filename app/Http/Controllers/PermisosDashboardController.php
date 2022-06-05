<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\ElementosDashboard;
use App\PermisosDashboard;
use Validator;
use Illuminate\Support\Facades\Auth;

class PermisosDashboardController extends Controller
{
    protected $rules = array(
        'elemento_dashboard_id' => 'required',
    );

    public function index()
    {
        $permisosDashboard = DB::table('permisos_dashboards')
            ->join('elementos_dashboards', 'permisos_dashboards.elemento_dashboard_id', '=', 'elementos_dashboards.id')
            ->join('modulos', 'elementos_dashboards.modulo_id', '=', 'modulos.id')
            ->join('users', 'permisos_dashboards.user_id', '=', 'users.id')
            ->select('permisos_dashboards.*', 'modulos.nombre AS modulo', 'elementos_dashboards.nombre AS elemento', 'users.name AS usuario')
            ->get();

        return response()->json(
            $permisosDashboard->toArray()
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

        $permisoDashboard = new PermisosDashboard();
        $permisoDashboard->user_id = $request->user_id;
        $permisoDashboard->elemento_dashboard_id = $request->elemento_dashboard_id;
        $permisoDashboard->save();

        return response()->json(array(
            'status' => true
        ));
    }

    public function eliminar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $permisoDashboard = PermisosDashboard::findOrFail($request->id);
        $permisoDashboard->delete();
    }

    public function permisosDashboardPorUsuarioModulo(Request $request)
    {
        $user = Auth::user();
        $permisosDashboard = DB::table('permisos_dashboards')
            ->join('elementos_dashboards', 'permisos_dashboards.elemento_dashboard_id', '=', 'elementos_dashboards.id')
            ->where('permisos_dashboards.user_id', '=', $user->id)
            ->where('elementos_dashboards.modulo_id', '=', $request->modulo_id)
            ->select('elementos_dashboards.nombre')
            ->get();

        return response()->json(
            array_column( $permisosDashboard->toArray(), 'nombre' )
        );
    }

}
