<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use Mail;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Utilidades
{

  /**
   * Metodo para auditar los cambios de valores de las propiedades de los objetos.
   * Se comparan los valores de los atributos con sus valores originales
   * Los campos que tiene un nuevo valor se registrar en el Log auditoria
   * @param objeto extiende de la clase Model
   * @param var el identificador de la entidad fuerte
   */

  public static function auditar($objeto, $id, $aux_user = 1)
  {
    if ($aux_user == 2){
     $nombre = "SISTEMA";}
   else{
     $nombre = Auth::user()->name;}

    $modelo =  substr(strrchr(get_class($objeto), "\\"), 1);
    $tabla = $objeto->getTable();
    if ($aux_user == 2)
      $nombre = "SISTEMA";
    else
      $nombre = Auth::user()->name;
    $resultado = array_diff_assoc($objeto->getAttributes(), $objeto->getoriginal());
    foreach ($resultado as $key => $item)
    {
      Log::channel('auditoria')->debug(json_encode([
        'id' => $id,
        'u' => $nombre,
        'm' => $modelo,
        'c' => $key,
        'o' => $objeto->getOriginal($key) . '',
        'n' => $item . ''
      ]));
    }
  }

  /**
   * Metodo que verifica si el usuario autenticado tiene asignado el permiso.
   * Se busca el permiso en la tabla de submenus
   * Compara el id del usuario y el nombre de la pagina
   * Si no fue encontrado se lanza la pagina 403, que indica que no tiene autorizadion
   * @param string Nombre de la pagina
   */
  public static function permiso_submenu($pagina)
  {
    $id = Auth::id();
    $permiso = DB::table('elementos_submenus')
      ->join('permisos', 'elementos_submenus.id', '=', 'permisos.elementos_submenu_id')
      ->where([
        'elementos_submenus.page' => $pagina,
        'permisos.user_id' => $id
      ])->get();

    if (count($permiso) == 0)
      abort(403);
  }

  /**
   * Metodo que verifica si el usuario autenticado tiene asignado el permiso.
   * Se busca el permiso en la tabla de menus
   * Compara el id del usuario y el nombre de la pagina
   * Si no fue encontrado se lanza la pagina 403, que indica que no tiene autorizadion
   * @param string Nombre de la pagina
   */
  public static function permiso_menu($pagina)
  {
    $id = Auth::id();
    $permiso = DB::table('elementos_menus')
      ->join('permisos', 'elementos_menus.id', '=', 'permisos.elementos_menu_id')
      ->where([
        'elementos_menus.page' => $pagina,
        'permisos.user_id' => $id
      ])->get();

    if (count($permiso) == 0)
      abort(403);
  }

  /**
   * Metodo que regresa el valor en letras
   * @param var Valor
   * @return string Valor en letras
   */
  public static function valorEnLetras($x)
  {
    if ($x < 0)
    {
      $signo = "menos ";
    }
    else
    {
      $signo = "";
    }
    $x = abs($x);
    $C1 = $x;

    $G6 = floor($x / (1000000));  // 7 y mas

    $E7 = floor($x / (100000));
    $G7 = $E7 - $G6 * 10;   // 6

    $E8 = floor($x / 1000);
    $G8 = $E8 - $E7 * 100;   // 5 y 4

    $E9 = floor($x / 100);
    $G9 = $E9 - $E8 * 10;  //  3

    $E10 = floor($x);
    $G10 = $E10 - $E9 * 100;  // 2 y 1


    $G11 = round(($x - $E10) * 100, 0);  // Decimales
    //////////////////////

    $H6 = self::unidades($G6);

    if ($G7 == 1 and $G8 == 0)
    {
      $H7 = "Cien ";
    }
    else
    {
      $H7 = self::decenas($G7);
    }

    $H8 = self::unidades($G8);

    if ($G9 == 1 and $G10 == 0)
    {
      $H9 = "Cien ";
    }
    else
    {
      $H9 = self::decenas($G9);
    }

    $H10 = self::unidades($G10);

    if ($G11 < 10)
    {
      $H11 = "0" . $G11;
    }
    else
    {
      $H11 = $G11;
    }

    /////////////////////////////
    if ($G6 == 0)
    {
      $I6 = " ";
    }
    elseif ($G6 == 1)
    {
      $I6 = "Millón ";
    }
    else
    {
      $I6 = "Millones ";
    }

    if ($G8 == 0 and $G7 == 0)
    {
      $I8 = " ";
    }
    else
    {
      $I8 = "Mil ";
    }

    $I10 = "Pesos ";
    $I11 = "/100 M.N. ";

    $C3 = $signo . $H6 . $I6 . $H7 . $H8 . $I8 . $H9 . $H10 . $I10 . $H11 . $I11;

    return $C3; //Retornar el resultado

  }

  /**
   * Metodo que regresa el valor en letras
   * @param var Valor
   * @return string Valor en letras
   */
  public static function valorEnLetrasFactura($x, $moneda)
  {
    if ($x < 0)
    {
      $signo = "menos ";
    }
    else
    {
      $signo = "";
    }
    $x = abs($x);
    $C1 = $x;

    $G6 = floor($x / (1000000));  // 7 y mas

    $E7 = floor($x / (100000));
    $G7 = $E7 - $G6 * 10;   // 6

    $E8 = floor($x / 1000);
    $G8 = $E8 - $E7 * 100;   // 5 y 4

    $E9 = floor($x / 100);
    $G9 = $E9 - $E8 * 10;  //  3

    $E10 = floor($x);
    $G10 = $E10 - $E9 * 100;  // 2 y 1


    $G11 = round(($x - $E10) * 100, 0);  // Decimales
    //////////////////////

    $H6 = self::unidades($G6);

    if ($G7 == 1 and $G8 == 0)
    {
      $H7 = "Cien ";
    }
    else
    {
      $H7 = self::decenas($G7);
    }

    $H8 = self::unidades($G8);

    if ($G9 == 1 and $G10 == 0)
    {
      $H9 = "Cien ";
    }
    else
    {
      $H9 = self::decenas($G9);
    }

    $H10 = self::unidades($G10);

    if ($G11 < 10)
    {
      $H11 = "0" . $G11;
    }
    else
    {
      $H11 = $G11;
    }

    /////////////////////////////
    if ($G6 == 0)
    {
      $I6 = " ";
    }
    elseif ($G6 == 1)
    {
      $I6 = "Millón ";
    }
    else
    {
      $I6 = "Millones ";
    }

    if ($G8 == 0 and $G7 == 0)
    {
      $I8 = " ";
    }
    else
    {
      $I8 = "Mil ";
    }

    $I10 = $moneda == 1 ? "Pesos " : "Dólares ";
    $I11 = $moneda == 1 ? "/100 M.N. " : "/100 USD";

    $C3 = $signo . $H6 . $I6 . $H7 . $H8 . $I8 . $H9 . $H10 . $I10 . $H11 . $I11;

    return $C3; //Retornar el resultado

  }

  public static function unidades($u)
  {
    if ($u == 0)
    {
      $ru = " ";
    }
    elseif ($u == 1)
    {
      $ru = "Un ";
    }
    elseif ($u == 2)
    {
      $ru = "Dos ";
    }
    elseif ($u == 3)
    {
      $ru = "Tres ";
    }
    elseif ($u == 4)
    {
      $ru = "Cuatro ";
    }
    elseif ($u == 5)
    {
      $ru = "Cinco ";
    }
    elseif ($u == 6)
    {
      $ru = "Seis ";
    }
    elseif ($u == 7)
    {
      $ru = "Siete ";
    }
    elseif ($u == 8)
    {
      $ru = "Ocho ";
    }
    elseif ($u == 9)
    {
      $ru = "Nueve ";
    }
    elseif ($u == 10)
    {
      $ru = "Diez ";
    }

    elseif ($u == 11)
    {
      $ru = "Once ";
    }
    elseif ($u == 12)
    {
      $ru = "Doce ";
    }
    elseif ($u == 13)
    {
      $ru = "Trece ";
    }
    elseif ($u == 14)
    {
      $ru = "Catorce ";
    }
    elseif ($u == 15)
    {
      $ru = "Quince ";
    }
    elseif ($u == 16)
    {
      $ru = "Dieciseis ";
    }
    elseif ($u == 17)
    {
      $ru = "Decisiete ";
    }
    elseif ($u == 18)
    {
      $ru = "Dieciocho ";
    }
    elseif ($u == 19)
    {
      $ru = "Diecinueve ";
    }
    elseif ($u == 20)
    {
      $ru = "Veinte ";
    }

    elseif ($u == 21)
    {
      $ru = "Veintiun ";
    }
    elseif ($u == 22)
    {
      $ru = "Veintidos ";
    }
    elseif ($u == 23)
    {
      $ru = "Veintitres ";
    }
    elseif ($u == 24)
    {
      $ru = "Veinticuatro ";
    }
    elseif ($u == 25)
    {
      $ru = "Veinticinco ";
    }
    elseif ($u == 26)
    {
      $ru = "Veintiseis ";
    }
    elseif ($u == 27)
    {
      $ru = "Veintisiente ";
    }
    elseif ($u == 28)
    {
      $ru = "Veintiocho ";
    }
    elseif ($u == 29)
    {
      $ru = "Veintinueve ";
    }
    elseif ($u == 30)
    {
      $ru = "Treinta ";
    }

    elseif ($u == 31)
    {
      $ru = "Treinta y un ";
    }
    elseif ($u == 32)
    {
      $ru = "Treinta y dos ";
    }
    elseif ($u == 33)
    {
      $ru = "Treinta y tres ";
    }
    elseif ($u == 34)
    {
      $ru = "Treinta y cuatro ";
    }
    elseif ($u == 35)
    {
      $ru = "Treinta y cinco ";
    }
    elseif ($u == 36)
    {
      $ru = "Treinta y seis ";
    }
    elseif ($u == 37)
    {
      $ru = "Treinta y siete ";
    }
    elseif ($u == 38)
    {
      $ru = "Treinta y ocho ";
    }
    elseif ($u == 39)
    {
      $ru = "Treinta y nueve ";
    }
    elseif ($u == 40)
    {
      $ru = "Cuarenta ";
    }

    elseif ($u == 41)
    {
      $ru = "Cuarenta y un ";
    }
    elseif ($u == 42)
    {
      $ru = "Cuarenta y dos ";
    }
    elseif ($u == 43)
    {
      $ru = "Cuarenta y tres ";
    }
    elseif ($u == 44)
    {
      $ru = "Cuarenta y cuatro ";
    }
    elseif ($u == 45)
    {
      $ru = "Cuarenta y cinco ";
    }
    elseif ($u == 46)
    {
      $ru = "Cuarenta y seis ";
    }
    elseif ($u == 47)
    {
      $ru = "Cuarenta y siete ";
    }
    elseif ($u == 48)
    {
      $ru = "Cuarenta y ocho ";
    }
    elseif ($u == 49)
    {
      $ru = "Cuarenta y nueve ";
    }
    elseif ($u == 50)
    {
      $ru = "Cincuenta ";
    }

    elseif ($u == 51)
    {
      $ru = "Cincuenta y un ";
    }
    elseif ($u == 52)
    {
      $ru = "Cincuenta y dos ";
    }
    elseif ($u == 53)
    {
      $ru = "Cincuenta y tres ";
    }
    elseif ($u == 54)
    {
      $ru = "Cincuenta y cuatro ";
    }
    elseif ($u == 55)
    {
      $ru = "Cincuenta y cinco ";
    }
    elseif ($u == 56)
    {
      $ru = "Cincuenta y seis ";
    }
    elseif ($u == 57)
    {
      $ru = "Cincuenta y siete ";
    }
    elseif ($u == 58)
    {
      $ru = "Cincuenta y ocho ";
    }
    elseif ($u == 59)
    {
      $ru = "Cincuenta y nueve ";
    }
    elseif ($u == 60)
    {
      $ru = "Sesenta ";
    }

    elseif ($u == 61)
    {
      $ru = "Sesenta y un ";
    }
    elseif ($u == 62)
    {
      $ru = "Sesenta y dos ";
    }
    elseif ($u == 63)
    {
      $ru = "Sesenta y tres ";
    }
    elseif ($u == 64)
    {
      $ru = "Sesenta y cuatro ";
    }
    elseif ($u == 65)
    {
      $ru = "Sesenta y cinco ";
    }
    elseif ($u == 66)
    {
      $ru = "Sesenta y seis ";
    }
    elseif ($u == 67)
    {
      $ru = "Sesenta y siete ";
    }
    elseif ($u == 68)
    {
      $ru = "Sesenta y ocho ";
    }
    elseif ($u == 69)
    {
      $ru = "Sesenta y nueve ";
    }
    elseif ($u == 70)
    {
      $ru = "Setenta ";
    }

    elseif ($u == 71)
    {
      $ru = "Setenta y un ";
    }
    elseif ($u == 72)
    {
      $ru = "Setenta y dos ";
    }
    elseif ($u == 73)
    {
      $ru = "Setenta y tres ";
    }
    elseif ($u == 74)
    {
      $ru = "Setenta y cuatro ";
    }
    elseif ($u == 75)
    {
      $ru = "Setenta y cinco ";
    }
    elseif ($u == 76)
    {
      $ru = "Setenta y seis ";
    }
    elseif ($u == 77)
    {
      $ru = "Setenta y siete ";
    }
    elseif ($u == 78)
    {
      $ru = "Setenta y ocho ";
    }
    elseif ($u == 79)
    {
      $ru = "Setenta y nueve ";
    }
    elseif ($u == 80)
    {
      $ru = "Ochenta ";
    }

    elseif ($u == 81)
    {
      $ru = "Ochenta y un ";
    }
    elseif ($u == 82)
    {
      $ru = "Ochenta y dos ";
    }
    elseif ($u == 83)
    {
      $ru = "Ochenta y tres ";
    }
    elseif ($u == 84)
    {
      $ru = "Ochenta y cuatro ";
    }
    elseif ($u == 85)
    {
      $ru = "Ochenta y cinco ";
    }
    elseif ($u == 86)
    {
      $ru = "Ochenta y seis ";
    }
    elseif ($u == 87)
    {
      $ru = "Ochenta y siete ";
    }
    elseif ($u == 88)
    {
      $ru = "Ochenta y ocho ";
    }
    elseif ($u == 89)
    {
      $ru = "Ochenta y nueve ";
    }
    elseif ($u == 90)
    {
      $ru = "Noventa ";
    }

    elseif ($u == 91)
    {
      $ru = "Noventa y un ";
    }
    elseif ($u == 92)
    {
      $ru = "Noventa y dos ";
    }
    elseif ($u == 93)
    {
      $ru = "Noventa y tres ";
    }
    elseif ($u == 94)
    {
      $ru = "Noventa y cuatro ";
    }
    elseif ($u == 95)
    {
      $ru = "Noventa y cinco ";
    }
    elseif ($u == 96)
    {
      $ru = "Noventa y seis ";
    }
    elseif ($u == 97)
    {
      $ru = "Noventa y siete ";
    }
    elseif ($u == 98)
    {
      $ru = "Noventa y ocho ";
    }
    else
    {
      $ru = "Noventa y nueve ";
    }

    return $ru; //Retornar el resultado
  }

  public static function decenas($d)
  {
    if ($d == 0)
    {
      $rd = "";
    }
    elseif ($d == 1)
    {
      $rd = "Ciento ";
    }
    elseif ($d == 2)
    {
      $rd = "Doscientos ";
    }
    elseif ($d == 3)
    {
      $rd = "Trescientos ";
    }
    elseif ($d == 4)
    {
      $rd = "Cuatrocientos ";
    }
    elseif ($d == 5)
    {
      $rd = "Quinientos ";
    }
    elseif ($d == 6)
    {
      $rd = "Seiscientos ";
    }
    elseif ($d == 7)
    {
      $rd = "Setecientos ";
    }
    elseif ($d == 8)
    {
      $rd = "Ochocientos ";
    }
    else
    {
      $rd = "Novecientos ";
    }
    return $rd; //Retornar el resultado
  }

  /**
   * Metodo que regresa el nombre del mes
   * @param string numero del mes
   * @return string nombre del mes
   */
  public static function meses($mes)
  {
    $resultado = '';
    $mes = $mes . '';
    switch ($mes)
    {
      case '01';
      case '1';
        $resultado = 'Enero';
        break;
      case '02';
      case '2';
        $resultado = 'Febrero';
        break;
      case '03';
      case '3';
        $resultado = 'Marzo';
        break;
      case '04';
      case '4';
        $resultado = 'Abril';
        break;
      case '05';
      case '5';
        $resultado = 'Mayo';
        break;
      case '06';
      case '6';
        $resultado = 'Junio';
        break;
      case '07';
      case '7';
        $resultado = 'Julio';
        break;
      case '08';
      case '8';
        $resultado = 'Agosto';
        break;
      case '09';
      case '9';
        $resultado = 'Septiembre';
        break;
      case '10':
        $resultado = 'Octubre';
        break;
      case '11':
        $resultado = 'Noviembre';
        break;
      case '12':
        $resultado = 'Diciembre';
        break;
    }
    return $resultado;
  }
  public static function ubicacion()
  {
    $ids = Auth::id();
    $users = DB::table('users')->select('users.*')->where('users.id', '=', $ids)->first();

    return $users->tipo_ubicacion_id;
  }

  // public static function getFolioFactura($A,$B)
  // {
  //   $foliofactura = DB::table('folio_factura')->first();
  //   DB::table('folio_factura')
  //     ->where('id', $foliofactura->id)
  //     ->update(['consecutivo' => $foliofactura->consecutivo+1]);
  //
  //   return [
  //     'serie' => $foliofactura->serie,
  //     'folio' => $foliofactura->consecutivo
  //   ];
  // }

  public static function getFolioFactura($emisor, $tipo_factura)
  {
    $dg = \App\DatosGenerales::where('id', $emisor)->first();
    $tf = DB::table('sat_cat_tipofactura')->select('sat_cat_tipofactura.*')->where('id', '=', $tipo_factura)->first();

    // return $tf;
    $se = '';
    $fo = '';

    if ($tf->c_tipofactura == 'P')
    {
      $ff = \App\FolioFactura::where('rfc', '=', $dg->rfc)->where('tipo_factura', '=', 'P')->first();
      if (is_null($ff))
      {
        $ffn = new \App\FolioFactura();
        $ffn->serie = 'RPA';
        $ffn->consecutivo = 1;
        $ffn->tipo_factura = 'P';
        $ffn->rfc = $dg->rfc;
        $ffn->save();

        $se = $ffn->serie;
        $fo = $ffn->consecutivo;
      }
      else
      {
        $ffv = \App\FolioFactura::where('rfc', '=', $dg->rfc)->where('tipo_factura', '=', 'P')->first();
        $ffv->consecutivo = $ff->consecutivo + 1;
        $ffv->save();

        $se = $ffv->serie;
        $fo = $ffv->consecutivo;
      }
    }

    elseif ($tf->c_tipofactura == 'I')
    {
      $ff = \App\FolioFactura::where('rfc', '=', $dg->rfc)->where('tipo_factura', '=', 'I')->first();
      if (is_null($ff))
      {
        $ffn = new \App\FolioFactura();
        $ffn->serie = 'A';
        $ffn->consecutivo = 1;
        $ffn->tipo_factura = 'I';
        $ffn->rfc = $dg->rfc;
        $ffn->save();

        $se = $ffn->serie;
        $fo = $ffn->consecutivo;
      }
      else
      {
        $ffv = \App\FolioFactura::where('rfc', '=', $dg->rfc)->where('tipo_factura', '=', 'I')->first();
        $ffv->consecutivo = $ff->consecutivo + 1;
        $ffv->save();
        $se = $ffv->serie;
        $fo = $ffv->consecutivo;
      }
    }
    elseif ($tf->c_tipofactura == 'E')
    {
      $ff = \App\FolioFactura::where('rfc', '=', $dg->rfc)->where('tipo_factura', '=', 'E')->first();
      if (is_null($ff))
      {
        $ffn = new \App\FolioFactura();
        $ffn->serie = 'NCA';
        $ffn->consecutivo = 1;
        $ffn->tipo_factura = 'E';
        $ffn->rfc = $dg->rfc;
        $ffn->save();

        $se = $ffn->serie;
        $fo = $ffn->consecutivo;
      }
      else
      {
        $ffv = \App\FolioFactura::where('rfc', '=', $dg->rfc)->where('tipo_factura', '=', 'E')->first();
        $ffv->consecutivo = $ff->consecutivo + 1;
        $ffv->save();
        $se = $ffv->serie;
        $fo = $ffv->consecutivo;
      }
    }
    elseif ($tf->c_tipofactura == 'T')
    {
      $ff = \App\FolioFactura::where('rfc', '=', $dg->rfc)->where('tipo_factura', '=', 'T')->first();
      if (is_null($ff))
      {
        $ffn = new \App\FolioFactura();
        $ffn->serie = 'TA';
        $ffn->consecutivo = 1;
        $ffn->tipo_factura = 'T';
        $ffn->rfc = $dg->rfc;
        $ffn->save();

        $se = $ffn->serie;
        $fo = $ffn->consecutivo;
      }
      else
      {
        $ffv = \App\FolioFactura::where('rfc', '=', $dg->rfc)->where('tipo_factura', '=', 'T')->first();
        $ffv->consecutivo = $ff->consecutivo + 1;
        $ffv->save();
        $se = $ffv->serie;
        $fo = $ffv->consecutivo;
      }
    }

    return [
      'serie' => $se,
      'folio' => $fo,
    ];

    //
    //
    //
    // $foliofactura = DB::table('folio_factura')->first();
    // DB::table('folio_factura')
    //   ->where('id', $foliofactura->id)
    //   ->update(['consecutivo' => $foliofactura->consecutivo+1]);
    //
    // return [
    //   'serie' => $foliofactura->serie,
    //   'folio' => $foliofactura->consecutivo
    // ];
  }

  public static function getFolioOrdenVenta()
  {
    $foliofactura = DB::table('folio_factura')->where('id', '=', '2')->first();
    DB::table('folio_factura')
      ->where('id', $foliofactura->id)
      ->update(['consecutivo' => $foliofactura->consecutivo + 1]);

    return [
      'serie' => $foliofactura->serie,
      'folio' => $foliofactura->consecutivo
    ];
  }

  public static function getFolio($tipo_doc, $proyecto_id)
  {
    $proyecto = DB::table('proyectos')->select('id', 'nombre_corto')->where('id', '=', $proyecto_id)->first();

    $folio = \App\Folio::where([
      ['tipo_doc', '=', $tipo_doc],
      ['proyecto_id', '=', $proyecto_id]
    ])->first();

    if (is_null($folio))
    {
      $folio = new \App\Folio();
      $folio->tipo_doc = $tipo_doc;
      $folio->proyecto_id = $proyecto_id;
      $folio->consecutivo = 1;
      $folio->save();
    }

    $nuevoFolio = '';
    switch ($tipo_doc)
    {
      case 'REQ':
        $nuevoFolio = 'REQ-' . strtoupper($proyecto->nombre_corto) . '-' . str_pad($folio->consecutivo, 3, "0", STR_PAD_LEFT);
        break;

      case 'OC-CONSERFLOW':
        $nuevoFolio = 'OC-CONSERFLOW-' . substr(date("Y"), 1, 3) . '-' . strtoupper($proyecto->nombre_corto) . '-' . str_pad($folio->consecutivo, 3, "0", STR_PAD_LEFT);
        break;
    }

    $folio->consecutivo = $folio->consecutivo + 1;
    $folio->save();

    return $nuevoFolio;
  }

  public static function crearPda($requisicion_id)
  {
    $requisicion_id = DB::table('partidas_requisiciones')->select('pda')
      ->where('requisicione_id', '=', $requisicion_id)->max('pda');
    $valor = $requisicion_id == null ? (0 + 1) : ($requisicion_id + 1);
    return $valor;
  }

  public static function pdaDoc($requisicion_id)
  {
    $req_doc = DB::table('partidarequisicion_documentos')->select('pda')
      ->where('partidarequisicione_req', '=', $requisicion_id)->max('pda');
    return $req_doc;
  }

  public static function crearFolioViaticos($proyecto_id)
  {
    $folio = "";
    $numero = 0;
    $proyecto = DB::table('proyectos')->select('id', 'nombre_corto')->where('id', '=', $proyecto_id)->first();

    $numero_solicitudes = DB::table('solicitud_viaticos')->select(DB::raw("COUNT(solicitud_viaticos.id) AS tamanio"))
      ->where('proyecto_id', '=', $proyecto_id)
      // ->where('empleado_elabora_id','=',$empleado_id)
      ->first();
    $numero = $numero_solicitudes == null ? 1 : ($numero_solicitudes->tamanio + 1);
    $folio = strtoupper($proyecto->nombre_corto) . '-' . str_pad($numero, 3, "0", STR_PAD_LEFT);
    return $folio;
  }


  public static function pdaReporteViaticos($id)
  {
    $pda = 0;
    $reporte = DB::table('reporte_gastos_viaticos')->select(DB::raw("COUNT(reporte_gastos_viaticos.solicitud_viaticos_id) AS tamanio"))
      ->where('solicitud_viaticos_id', '=', $id)->first();
    $pda = $reporte == null ? 1 : ($reporte->tamanio + 1);
    return $pda;
  }


  /**
   *Metodo que asgina el codigo de cada nodo de partida en costos tomando en cuenta
   * la relacion padre_id
   * @param request
   * @return string codigo
   */
  public static function codigoPartidaCosto($request)
  {
    $codigo = '';
    if ($request->padre_id == 0)
    {
      $codigo = '';
    }
    elseif ($request->padre_id != 0)
    {
      $partidas = DB::table('partidas_costos_nodos')->select('partidas_costos_nodos.*')->where('id', '=', $request->padre_id)->first();
      $cadena = $partidas->codigo;
      $total = DB::table('partidas_costos_nodos')->select(DB::raw("COUNT(partidas_costos_nodos.id) AS total"))
        ->where('padre_id', '=', $partidas->id)->first();
      if ($partidas->codigo == '')
      {
        $codigo = $total == null ? '1.0' : (string)($total->total + 1) . '.0';
      }
      else
      {
        $valores = explode('.', $cadena);
        if ($valores[1] == 0)
        {
          $array = explode('.', $cadena);
          $ultimo = array_pop($array);
          $ultimo = $total == null ? 1 : ($total->total + 1);
          array_push($array, $ultimo);
          for ($i = 0; $i < (count($array) - 1); $i++)
          {
            $codigo .= (string)($array[$i]) . '.' . (string)($array[count($array) - 1]);
          }
        }
        elseif ($valores[1] != 0)
        {
          $codigo .= $partidas->codigo . '.' . (string)($total == null ? 1 : ($total->total + 1));
        }
      }
    }
    return $codigo;
  }

  /**
   * [enviarCorreoPR funcion abreviada para enviar correo en el proceso de requisicion envia a la persona
   * encargada de validar,calidad,autorizar,almacen o compras]
   */
  public static function enviarCorreoPR($empleado, $mensaje, $requisicion)
  {
    //revisar si se envia adjunto
    $hoy = date("Y-m-d");
    $data = [
      'nombre' => $empleado->nombre . ' ' . $empleado->ap_paterno . ' ' . $empleado->ap_materno,
      'correo_electronico' => $empleado->correo_electronico,
      'fecha' => $hoy,
      'folio' => $requisicion->folio,
      'mensaje' => $mensaje,
      'empleado_solicita' => $requisicion->emp_sol,

    ];
    Mail::send('emails.procesorequisicion', $data, function ($message) use ($data, $empleado, $mensaje)
    {
      $core = $empleado->correo_electronico;

      // $pdf = PDF::loadView('emails.antiguedadadjunto',$data);
      $message->to($core, $mensaje)
        ->subject($mensaje);
      $message->from('romerovelascogregorio@gmail.com', 'Conserflow');
      $message->attach(public_path('img/c.png'));
    });
  }

  public static function ftpSolucion($id)
  {
    // Se obtiene el archivo del local serve
    //Se busca en disk o carpeta -----
    if (Storage::exists('Archivos/' . $id))
    {
      // Se coloca el archivo en una ruta local
      $archivo = Storage::disk('local')->get('Archivos/' . $id);
    }
    else
    {
      $output = shell_exec("mkdir /var/www/html/conserflow2020/conserflow-sistema2018/storage/app/Archivos/ 2> errormk.txt;cp /home/vsftpuser/ftp/Archivos/$id /var/www/html/conserflow2020/conserflow-sistema2018/storage/app/Archivos/ 2> error.txt;");
      $archivo = Storage::disk('local')->get('Archivos/' . $id);
    }
    return $archivo;
  }

  public static function ftpSolucionEliminar($var)
  {
    if (Storage::exists('Archivos/' . $var))
    {
      Storage::disk('local')->delete('Archivos/' . $var);
    }
    else
    {
      $output = shell_exec("rm /home/vsftpuser/ftp/Archivos/$var");
    }
    return true;
  }

  public static function busqueda_filterByColumn($data, $queries)
  {
    $queries = json_decode($queries, true);
    // dd($queries);
    return $data->where(function ($q) use ($queries)
    {
      foreach ($queries as $field => $query)
      {
        $_field = $field;

        if (is_string($query))
        {
          $q->where($_field, 'LIKE', "%{$query}%");
        }
        else
        {
          $start = Carbon::createFromFormat('Y-m-d', substr($query['start'], 0, 10))->startOfDay();
          $end = Carbon::createFromFormat('Y-m-d', substr($query['end'], 0, 10))->endOfDay();

          $q->whereBetween($_field, [$start, $end]);
        }
      }
    });
  }

  public static function busqueda_filter($data, $query, $fields)
  {
    return $data->where(function ($q) use ($query, $fields)
    {
      foreach ($fields as $index => $field)
      {
        $method = $index ? 'orWhere' : 'where';
        $q->{$method}($field, 'LIKE', "%{$query}%");
      }
    });
  }

  /**
   * Metodo para auditar los cambios de valores de las propiedades de los objetos.
   * Se comparan los valores de los atributos con sus valores originales
   * Los campos que tiene un nuevo valor se registrar en el Log auditoria
   * @param objeto extiende de la clase Model
   * @param var el identificador de la entidad fuerte
   */
  public static function errors($e, $aux_user = 1, $usuario = "")
  {
    if ($aux_user == 2)
    {
      $nombre = $usuario == "" ? "SISTEMA" : $usuario;
    }
    else
    {
      $nombre =  Auth::user()->name;
    }
    $m = $e->getMessage();
    $f = $e->getFile();
    $l = $e->getLine();
    Log::channel('errors')->debug(json_encode([
      'mensaje' => $m,
      'file' => $f,
      'line' => $l,
      'user' => $nombre,
    ]));
  }
}
