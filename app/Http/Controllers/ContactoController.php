<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contacto;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;

class ContactoController extends Controller
{
    protected $rules = array(
        'tel_casa' => 'required|max:10',
        'tel_celular' => 'required|max:13',
        'correo_electronico' => 'required|max:45',
        'contacto_emergencia' => 'required|max:30',
        'tel_emergencia' => 'required|max:13',
    );

    public function index(Request $request, $id)
    {
        $contactos = Contacto::orderBy('id', 'desc')
        ->where('empleado_id', '=', $id)
        ->get()->toArray();
        return response()->json($contactos);
    }

    public function update(Request $request)
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

        $contactoemp = Contacto::findOrFail($request->id);

        if (is_null($contactoemp)) {
            $contactoemp = new Contacto();
        }
        $contactoemp->tel_celular = $request->tel_celular;
        $contactoemp->tel_casa = $request->tel_casa;
        $contactoemp->correo_electronico = $request->correo_electronico;
        $contactoemp->contacto_emergencia = $request->contacto_emergencia;
        $contactoemp->tel_emergencia = $request->tel_emergencia;
        $contactoemp->empleado_id = $request->empleado_id;
        Utilidades::auditar($contactoemp, $contactoemp->empleado_id.','.$contactoemp->id);
        $contactoemp->save();

        return response()->json(array(
            'status' => true,
        ));
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

    public function get(Request $request, $id)
    {
        try{
        $contacto = Contacto::where(
            'empleado_id', $id
        )->first();

        if (is_null($contacto))
        {
            $contacto =  new Contacto();
            $contacto['empleado_id'] = $id;
            $contacto['correo_electronico'] = '';
            $contacto['tel_celular'] = 0;
            $contacto['tel_casa'] = 0;
            $contacto['contacto_emergencia'] = '';
            $contacto['tel_emergencia'] = 0;
            Utilidades::auditar($contacto, $contacto->id);
            $contacto->save();

            $contacto = Contacto::where(
                'empleado_id', $id
            )->first();
        }

        return response()->json($contacto);
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    }

}
