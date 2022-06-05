<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Juntas;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class JuntasController extends Controller
{
    //


    public function uploadJuntas(Request $request){

        if (!$request->ajax()) return redirect('/');
        if($request->hasFile('import_file')){

        ini_set('max_execution_time', 300);

        $collection = (new FastExcel)->import($request->file('import_file')->getRealPath());

        $nuevos = 0;
        $repetidos = 0;
        $descripcionRepetidos = array();
        $inicio = date('Y-m-d H:i:s');
        $fecha = null;

        foreach($collection as $item) {



            $juntaExcel = Juntas::where('juntas', $item['JUNTAS'])
                ->first();

            if (empty($juntaExcel)) {
                $juntaExcel = new Juntas();
                $juntaExcel->juntas = $item['JUNTAS'];
                $juntaExcel->diametro = $item['Ǿ'];
                $juntaExcel->sistema = $item['SISTEMA'];
                $juntaExcel->habilitadas = $item['HABILITADAS'];
                $fechaUno = $item['FECHAUNO'];
                $dia = substr($fechaUno,0,2);
                $mes = substr($fechaUno,2,1);
                $año = substr($fechaUno,4,4);
                $nuevafecha = $año . $mes . $dia;
                $juntaExcel->fecha_uno = date('Y-m-d',strtotime($nuevafecha));
                $juntaExcel->soldadas = $item['SOLDADAS'];
                $fechaDos = $item ['FECHADOS'];
                $diaDos = substr($fechaDos,0,2);
                $mesDos = substr($fechaDos,2,1);
                $añoDos = substr($fechaDos,4,4);
                $nuevafechaDos = $añoDos . $mesDos . $diaDos;
                $juntaExcel->fecha_dos = date('Y-m-d',strtotime($nuevafechaDos));
                $juntaExcel->w = $item['W'];

            $juntaExcel->save();
            $nuevos++;

            } else {
                $repetidos++;
                array_push($descripcionRepetidos, $item['JUNTAS']);
            }

        }

        $fin = date('Y-m-d H:i:s');

        return response()->json(
            array(
                'nuevos'=> $nuevos,
                'repetidos' => $repetidos,
                'inicio' => $inicio,
                'fin'=> $fin,
                'descripcionrepetidos' => $descripcionRepetidos,
            ));

        }

    }

    public function index(Request $request){

            $listaJuntas = DB::table('concentrado_spools')
            ->select('concentrado_spools.*')
            ->get();

            return response()->json($listaJuntas);
    }

    public function store (Request $request){

      if (!$request->ajax()) return redirect('/');

        $nuevaJunta = new Juntas();
        $nuevaJunta->id = $request->id;
        $nuevaJunta->juntas = $request->juntas;
        $nuevaJunta->diametro = $request->diametro;
        $nuevaJunta->sistema = $request->sistema;
        $nuevaJunta->habilitadas = $request->habilitadas;
        $nuevaJunta->fecha_uno = $request->fecha_uno;
        $nuevaJunta->soldadas = $request->soldadas;
        $nuevaJunta->fecha_dos = $request->fecha_dos;
        $nuevaJunta->w = $request->w;

        $nuevaJunta->save();
        return response()->json(array('status'=>true));

    }

    public function update (Request $request,$id){

      if (!$request->ajax()) return redirect('/');

        $nuevaJunta = Juntas :: where ('id',$id)->first();
        $nuevaJunta->id = $request->id;
        $nuevaJunta->juntas = $request->juntas;
        $nuevaJunta->diametro = $request->diametro;
        $nuevaJunta->sistema = $request->sistema;
        $nuevaJunta->habilitadas = $request->habilitadas;
        $nuevaJunta->fecha_uno = $request->fecha_uno;
        $nuevaJunta->soldadas = $request->soldadas;
        $nuevaJunta->fecha_dos = $request->fecha_dos;
        $nuevaJunta->w = $request->w;
        $nuevaJunta->save();

        return response()->json(array('status'=>true));

    }

}
