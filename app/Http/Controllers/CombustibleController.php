<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Combustible;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\Storage;


class CombustibleController extends Controller
{
    //

    public function get()
    {
      $data = Combustible::orderBy('fecha','DESC')->get();
      return response()->json($data);
    }

    public function guardar(Request $request)
    {
      try {
        $new = new Combustible();
        $new->proveedor = $request->proveedor;
        $new->folio = $request->folio;
        $new->fecha = $request->fecha;
        $new->proyecto_id = $request->proyecto_id;
        $new->proyecto = $request->proyecto;
        $new->operador = $request->operador;
        $new->factura = $request->factura;
        $new->placas = $request->placas;
        $new->unidad = $request->unidad;
        $new->producto = $request->producto;
        $new->kilometraje = $request->kilometraje;
        $new->cantidad = $request->cantidad;
        $new->precio = $request->precio;
        $new->subtotal = $request->subtotal;
        $new->iva = $request->iva;
        $new->total = $request->total;
        if (count($request->adjunto) != 0) {
          $new->adjunto = $this->gua($request->adjunto[0]['name']);
        }
        Utilidades::auditar($new, $new->id);
        $new->save();

        return response()->json(['status  ' => true]);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function actualizar(Request $request)
    {
      try {
        $act = Combustible::where('id',$request->id)->first();
        $act->proveedor = $request->proveedor;
        $act->folio = $request->folio;
        $act->fecha = $request->fecha;
        $act->proyecto_id = $request->proyecto_id;
        $act->proyecto = $request->proyecto;
        $act->operador = $request->operador;
        $act->factura = $request->factura;
        $act->placas = $request->placas;
        $act->unidad = $request->unidad;
        $act->producto = $request->producto;
        $act->kilometraje = $request->kilometraje;
        $act->cantidad = $request->cantidad;
        $act->precio = $request->precio;
        $act->subtotal = $request->subtotal;
        $act->iva = $request->iva;
        $act->total = $request->total;
        if (count($request->adjunto) != 0) {
          if ($request->adjunto[0]['id'] == 0) {
            $act->adjunto = $this->gua($request->adjunto[0]['name']);
          }
        }
        Utilidades::auditar($act, $act->id);
        $act->save();

        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function eliminar($id)
    {
      $act = Combustible::where('id',$id)->delete();
      return response()->json(['status' => true]);
    }

    public function gua($image)
    {
      try {
        // return response()->json($request);

        $image_64 = $image; //your base64 encoded data

        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf

        $replace = substr($image_64, 0, strpos($image_64, ',')+1);

        // find substring fro replace here eg: data:image/png;base64,

        $image = str_replace($replace, '', $image_64);

        $image = str_replace(' ', '+', $image);

        $imageName = uniqid().'.'.$extension;


        Storage::disk('local')->put('Trafico/'.$imageName, base64_decode($image));

        // return response()->json(['status' => true]);
        return $imageName;

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }

    }

    public function deleteImg($id)
    {
      try {
        $img = Combustible::where('id',$id)->first();
        $img->adjunto = '';

        $img_u = Storage::disk('local')->delete('Trafico/'.$img->adjunto);

        $img->save();
        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function getImg($id)
    {
      try {
        $img = Combustible::where('id',$id)->first();
        $arreglo = [];
        // foreach ($img as $key => $value) {
        if (isset($img) == true ) {
          if ($img->adjunto != '') {
            // code...
          $img_u = Storage::disk('local')->get('Trafico/'.$img->adjunto);
          $type = explode('.',$img->adjunto);
          $base64 = 'data:image/' . $type[1] . ';base64,' .base64_encode($img_u);

          $arreglo = [
            'id' => $id,
            'img' => $base64,
          ];
          }
        }

        return response()->json($arreglo);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }
}
