<?php

namespace App\Imports;

use App\Asistencia;
use App\Registro;
use App\prueba;
use App\Empleado;
use App\Contrato;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use \App\Http\Helpers\Utilidades;
use App\CatConceptosA;
use Carbon\Carbon;


// class EdenRedUserImport implements ToCollection

HeadingRowFormatter::default('none');

class EquiposImport implements ToCollection, WithHeadingRow
{


  /**
  * @param array $row
  *
  * @return \Illuminate\Database\Eloquent\Model|null
  */
  public function collection(Collection $rows)
  {

    try {

      DB::beginTransaction();

     ///Crear entrada para los equipos de calibración
         $entrada = new \App\Entrada();
         $entrada->fecha = date('Y-m-d');
         $entrada->comentarios = "Entrada de herramienta inventario";
         $entrada->tipo_entrada_id = 1;
         $entrada->empleado_calidad_id = 147;
         $entrada->empleado_almacen_id = 147;
         $entrada->empleado_usuario_id = 147;
         Utilidades::auditar($entrada, $entrada->id);
         $entrada->save();

         foreach ($rows as $key => $value) {

           $articulo = \App\Articulo::where('nombre',$value['DESCRIPCION'])
           ->where('codigo',$value['CODIGO'])
           ->where('descripcion',$value['DESCRIPCION'])
           ->where('marca',$value['MARCA'])
           ->first();

           if (isset($articulo) == false) {
             $articulo = new \App\Articulo();
             $articulo->nombre = $value['DESCRIPCION'];
             $articulo->codigo = $value['CODIGO'];
             $articulo->descripcion = $value['DESCRIPCION'];
             $articulo->marca = $value['MARCA'];
             $articulo->unidad = $value['UM'];
             // $articulo->comentarios = $value['COMENTARIO '];
             $articulo->grupo_id = $value['GRUPO'];
             $articulo->calidad_id = 1;
             $articulo->tipo_resguardo_id = $value['TIPO DE RESGUARDO'];
             Utilidades::auditar($articulo, $articulo->id);
             $articulo->save();
           }



           $st = null;$ni = null;
           $s_b = DB::table('stands')->where('almacene_id','1')->where('nombre', $value['STAND'])->first();
           if (isset($s_b) == true) {
             $st = $s_b->id;
             $n_b = DB::table('niveles')->where('stande_id',$s_b->id)->where('nombre', $value['NIVEL'])->first();
           if (isset($n_b) == true) {
           $ni = $n_b->id;
            }
           }

           $rhoc = new \App\requisicionhasordencompras();
          $rhoc->requisicione_id = 1;
          $rhoc->orden_compra_id = 1;
          $rhoc->articulo_id = $articulo->id;
          $rhoc->cantidad = $value['CANTIDAD'];
          $rhoc->precio_unitario = 0;
          $rhoc->tipo_entrada = 'INV';
          $rhoc->condicion = 0;
          $rhoc->antigua = 0;
          $rhoc->cantidad_entrada = 0;
          Utilidades::auditar($rhoc,$rhoc->id);
          $rhoc->save();

          // PArtidaEntrada
      $partidaEntrada = new \App\PartidaEntrada();
      $partidaEntrada->entrada_id = $entrada->id;
      $partidaEntrada->req_com_id = $rhoc->id;
      $partidaEntrada->articulo_id = $articulo->id;
      $partidaEntrada->validacion_calidad = 0;
      $partidaEntrada->cantidad = $value['CANTIDAD'];
      $partidaEntrada->almacene_id = 1;
      $partidaEntrada->stand_id = $st;
      $partidaEntrada->nivel_id = $ni;
      $partidaEntrada->pendiente = 0;
      $partidaEntrada->status = 0;
      $partidaEntrada->precio_unitario = 0;
      $partidaEntrada->stocke_id = 1;
      $partidaEntrada->save();

      // Lote
          $lote = new \App\Lote();
          $lote->nombre = "lote 0001-".$articulo->id;
          $lote->entrada_id = $partidaEntrada->id;
          $lote->articulo_id = $articulo->id;
          $lote->cantidad = $value['CANTIDAD'];
          $lote->save();
          $lote->nombre = ("lote 0001-" . $articulo->id . "-" . $lote->id);
          $lote->save();

          // LoteAlmacen
       $lote_almacen = new \App\LoteAlmacen();
       $lote_almacen->lote_id = $lote->id;
       $lote_almacen->almacene_id = 1;
       $lote_almacen->stand_id = $st;
       $lote_almacen->nivel_id = $ni;
       $lote_almacen->cantidad = $value['CANTIDAD'];
       $lote_almacen->stocke_id = 1;
       $lote_almacen->articulo_id = $articulo->id;
       $lote_almacen->condicion = 1;
       $lote_almacen->codigo_barras = 'MCF 0001 '.$articulo->id.' '.$lote->id;
       $lote_almacen->save();

       // Existencia ??? Crear nueva existencia para cada articulo? (Repetidos?)
         $existencia = new \App\Existencia();
         $existencia->articulo_id = $articulo->id;
         $existencia->almacene_id = 1;
         $existencia->stand_id = $st;
         $existencia->nivel_id = $ni;
         $existencia->id_lote = $lote->id;
         $existencia->cantidad = $value['CANTIDAD'];
         $existencia->save();

         // Movimiento
         $movimiento = new \App\Movimiento();
         $movimiento->cantidad = $value['CANTIDAD'];
         $movimiento->fecha = date("y-m-d");
         $movimiento->hora = date("H:i:s");
         $movimiento->tipo_movimiento = "INV";
         $movimiento->folio = "Entrada-" . 1 . 1;
         $movimiento->proyecto_id = 1;
         $movimiento->lote_id = $lote_almacen->id;
         $movimiento->stocke_id = 1;
         $movimiento->almacene_id = 1;
         $movimiento->stand_id = $st;
         $movimiento->nivel_id = $ni;
         $movimiento->articulo_id = $articulo->id;
         $movimiento->save();

         // StockArticulos
       $stock_articulo = \App\StockArticulo::where("articulo_id", "=", $articulo->id)
           ->where("stocke_id", "=", 1)->first();
       if ($stock_articulo == null)
       {
           // Registrar nuevo
           $nuevo_stock = new \App\StockArticulo();
           $nuevo_stock->cantidad = $value['CANTIDAD'];
           $nuevo_stock->articulo_id = $articulo->id;
           $nuevo_stock->stocke_id = 1;
           $nuevo_stock->save();
       }else
         {
             // Sumar cantidad
             $n = $stock_articulo->cantidad + $value['CANTIDAD'];
             $stk = \App\StockArticulo::where("articulo_id", "=", $articulo->id)
                 ->where("stocke_id", "=", 1)->first();
             $stk->cantidad = $n;
             $stk->update();
         }




         }



      DB::commit();

      return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      DB::rollBack();
      Utilidades::errors($e);
    }
    // echo "".var_dump($rows);


        // $prueba = new prueba();
        // $prueba->nombre =  $value['Nombre'];
        // $prueba->fecha =  $value['Fecha'];
        // $prueba->entrada_uno =  $value['Primer Horario'] == 'Entrada' ? NULL : $value['Primer Horario'];
        // $prueba->salida_uno =  $value[0] == 'Salida' ? NULL : $value[0];
        // $prueba->entrada_dos =  $value['Segundo Horario']== 'Entrada' ? NULL : $value['Segundo Horario'];
        // $prueba->salida_dos =  $value[1]== 'Salida' ? NULL : $value[1];
        // $prueba->save();




    // $prueba = prueba::where('nombre','=',null)->delete();
  }



  public function headingRow(): int
  {
    return 1;
  }

  public function getProyecto($value)
  {
    $pro = 0;

    switch ($value) {
      case 'TOPOLOBAMPO':  $pro = 105;     break;
      case 'GARANTIA TUMUT':  $pro = 144;     break;
      case 'SM-400':  $pro = 87;     break;
      case 'DELTAV':  $pro = 109;     break;
      case 'ULTRASONICOS BRASKEM':  $pro = 131;     break;
    }
    return $pro;
  }



}
