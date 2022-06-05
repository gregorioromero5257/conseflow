<?php

namespace App\Imports;

use App\EdenRedUser;
use App\Empleado;
use App\DatosBancariosEmpleado;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
// class EdenRedUserImport implements ToCollection
class EdenRedUserImport implements ToCollection, WithHeadingRow
{
  /**
  * @param array $row
  *
  * @return \Illuminate\Database\Eloquent\Model|null
  */
  public function collection(Collection $rows)
  {

    foreach ($rows as $key => $value) {
      $empleados = EdenRedUser::where('numero_nomina','=',$value['numero_de_nomina'])
      ->where('nombre_empleado','=',$value['nombre_de_empleado'])
      ->where('numero_cuenta','=',$value['numero_de_cuenta'])
      ->where('numero_tarjeta','=',$value['numero_de_tarjeta'])
      ->where('status','=',$value['status_tarjeta'])
      ->where('correo','=',$value['correo_electronico'])
      ->where('puesto','=',$value['puesto'])
      ->where('telefono','=',$value['telefono'])
      ->where('direccion_entrega','=',$value['direccion_de_entrega'])
      ->where('buzon_usuario','=',$value['buzon_de_usuario'])->first();

      if (isset($empleados) == false) {
        $id = 0;
        $asignado =0;
        $empleado = Empleado::select('empleados.*',\DB::raw("CONCAT(empleados.nombre,' ',empleados.ap_paterno,' ',empleados.ap_materno) AS nombre_empleado"))->get();
        foreach ($empleado as $key => $val) {
          if($val->nombre_empleado === $value['nombre_de_empleado']){
            $id = $val->id;
            $asignado = 1;
          }
        }
        EdenRedUser::create([
          'numero_nomina' => $value['numero_de_nomina'],
          'nombre_empleado' => $value['nombre_de_empleado'],
          'empleado_id' => $id,
          'numero_cuenta' => $value['numero_de_cuenta'],
          'numero_tarjeta' => $value['numero_de_tarjeta'],
          'status' => $value['status_tarjeta'],
          'correo' => $value['correo_electronico'],
          'puesto' => $value['puesto'],
          'telefono' => $value['telefono'],
          'direccion_entrega' => $value['direccion_de_entrega'],
          'buzon_usuario' => $value['buzon_de_usuario'],
          'asignado' => $asignado,
        ]);

        if ($id != 0) {
          $datos_banc = DatosBancariosEmpleado::join('catalogo_bancos AS CB','CB.id','=','datos_bancarios_empleados.banco_id')
          ->where('nombre','=','EDENRED')->where('empleado_id','=',$id)->first();
          if(isset($datos_banc) != true){
            $bancos = \App\CatalogoBanco::where('nombre','EDENRED')->first();
            $dato_banc = new DatosBancariosEmpleado();
            $dato_banc->numero_tarjeta = $value['numero_de_tarjeta'];
            $dato_banc->numero_cuenta = $value['numero_de_cuenta'];
            $dato_banc->banco_id = $bancos->id;
            $dato_banc->empleado_id = $id;
            $dato_banc->save();
          }
        }

      }
    }
  }

  public function headingRow(): int
  {
    return 13;
  }
}
