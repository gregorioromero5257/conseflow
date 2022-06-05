<?php

namespace App\Http\Controllers;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;

use Validator;
use App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
// use App\UsuarioMovil;
use App\Proyecto;
// use App\Sistema;
// use App\Servicio;
// use App\Spool;
// use App\Junta;
// use App\Soldador;
use App\Response;
// use App\ServicioDeSistema;
// use App\Material;
// use App\MaterialesDeJuntas;
// use App\MaquinaSoldar;
// use App\ProcedimientoSoldadura;
// use App\ProcedimientoSoldador;
// use App\CertificadoProcedimiento;
// use App\Inspeccion;
// use App\Inspector;

use function PHPSTORM_META\type;

class SyncDbController extends Controller
{

  public function SyncDB(Request $request)
  {
     try
     {
      $i_error=0;
      $i_correcto=0;
       $yolo="Inicio";
       $yolo.=">Read:";
      //Leer archivo
      if(!$request->hasFile('SpoolsBackup'))
      {
      //   $file=$request->file('SpoolsBackup');
      //   $name=$request->file('SpoolsBackup');
      //   $pathFile=$file->getRealPath();
      //   $content=file_get_contents($pathFile);
        return response()->json(new Response(404,"Archivo no cargado",null,false));
      }

      $file=$request->file('SpoolsBackup');
      $pathFile=$file->getRealPath();
      $content=file_get_contents($pathFile);
      $db=json_decode($content,true);
      // var_dump($db);
      // ############### NO MOVER ###############

      $juntas_array=json_decode($db["Juntas"]);
      // var_dump($juntas_array);
      $yolo.=">Juntas:";
      foreach ($juntas_array as $junta)
      {
        // $x=print_r($junta);
        // $yolo.=">$id_junta";
        // $yolo.="$x";
        $id_junta=$junta->Id; //Id de junta
        $junta_model=Junta::find($id_junta);
        if($junta_model)
        {
          $soldador=Soldador::find($junta->SoldadorId);
          if($soldador)
          {
            //Actualizar datos
            // Junta::where('Id',$id)->update(['SoldadorId'=>$junta['SoldadorId']]);
            //$junta_actu=DB::table('concentrado_spools') //->get();
             Junta::where('Id',$junta->Id)
            ->update(
            [
              // 'No'              => $junta->No,
              // 'SpoolId'         => $junta->SpoolId,          //  #Id Spoo,
              'SoldadorId'      => $junta->SoldadorId,         //  # ID Soldado,
              // 'Diametro'        => str_replace("\"", "", $junta->diametro),
              'Habilitada'      => $junta->Habilitada,
              'FechaHabilitada' => $junta->FechaHabilitada,
              'Soldada'         => $junta->Soldada,
              'FechaSoldada'    => $junta->FechaSoldada,
              'StateCode'       => 0
            ]);

            $i_correcto+=1;
          }
          else
          { $i_error+=1;} //No existe soldador
        }
        else
        { $i_error+=1;}
        //No existe junta

      }
      $yolo.="Done>";
      // **** Materiales **** //
      $materiales_array=json_decode($db["Materiales"]);

      foreach ($materiales_array as $material)
      {
        $id=$material->Id; //Id de material
        $material_model=Material::find($id);
        if($material_model)
        {
            //Actualizar datos
            $material_actu=Material::find($id)
            ->where('articuloId','=',$material->ArticuloId)
            ->where('LoteId','=',$material->LoteId)
            ->update(
            [
              'Colada'       => $material->Colada,
            ]);

            $i_correcto+=1;
        }
        else
        { $i_error+=1;}
        //No existe junta

      }
      $yolo.=">Materiales";
      // **** Material de junta **** //
      $materialesdejunta_array=json_decode($db["MaterialesDeJunta"]);

      foreach ($materialesdejunta_array as $material)
      {
        $id=$material->Id; //Id de material
        $material_model=MaterialesDeJuntas::find($id);
        if($material_model)
        {
            //Actualizar datos
            $material_actu=MaterialesDeJuntas::find($id)
            // $material_actu=DB::table('materialesdejunta')
            ->update(
            [
              'Colada'    => $material->Colada,
            ]);
            $i_correcto+=1;
        }
        else
        {
          //nuevo material
          $nuevo=new MaterialesDeJuntas;

          $nuevo->JuntaId=$material->JuntaId;
          $nuevo->MaterialId=$material->MaterialId;
          $nuevo->Colada=$material->Colada;
          $nuevo->StateCode=$material->StateCode;
          $nuevo->save();
        }

      }

      $yolo.=">Materiales de junta";
      // **** Soldadores **** //
      $soldadores_array=json_decode($db["Soldadores"]);
      foreach ($soldadores_array as $soldador)
      {
        // Buscar
        $id_sol=$soldador->Id;
        $soldador_update=Soldador::find($id_sol);
        // Actualizar
        if($soldador_update)
        {
          Soldador::where('Id',$id_sol)
          ->update(
          [
            'MaquinaId'=>$soldador->MaquinaId,
            'EstadoId'=>$soldador->EstadoId,
          ]);
        }
        else
        { $i_error+=1;}
      }

      $yolo.=">Soldadores";
      // **** Inspecciones **** //
      $inspecciones_array=json_decode($db["Visual"],true);
      foreach ($inspecciones_array as $inspeccion)
      {
        // Buscar
        $id_inspeccion=$inspeccion["Id"];
        $inspec=Inspeccion::where('JuntaId','=',$inspeccion["JuntaId"])
        ->where('Tipo','=',$inspeccion["Tipo"])->first();
        // Actualizar
        if(!$inspec)
        {
          // echo "nueva $id_inspeccion";
          $insp=new Inspeccion;
          $insp->JuntaId= $inspeccion["JuntaId"];
          $insp->Tipo= $inspeccion["Tipo"];
          $insp->Fecha= $inspeccion["Fecha"];
          $insp->InspectorId= $inspeccion["InspectorId"];
          $insp->Aceptado= $inspeccion["Aceptado"];
          $insp->StateCode=0;
          $insp->save();

          // $junta=Inspeccion::where('JuntaId','=',$inspeccion["JuntaId"])
          // ->where('Tipo','=',$inspeccion["Tipo"])
          // ->update
          // ([
          //   'Fecha'=>$inspeccion["Fecha"],
          //   'InspectorId'=>$inspeccion["InspectorId"],
          //   'Aceptado'=>$inspeccion["Aceptado"],
          // ]);
        }
        // else
        // {
        //   echo "ya esiste";
          //nueva inspeccion
          // $insp=new Inspeccion;
          // $insp->JuntaId= $inspeccion["JuntaId"];
          // $insp->Tipo= $inspeccion["Tipo"];
          // $insp->Fecha= $inspeccion["Fecha"];
          // $insp->InspectorId= $inspeccion["InspectorId"];
          // $insp->Aceptado= $inspeccion["Aceptado"];
          // $insp->StateCode=0;
          // $insp->save();
        // }
      }

      $yolo.=">Inspeeciones";
      //Mensaje
      $ms="La sincronización se realizó correctamente";
      if($i_error!=0)
      {
        $ms=$ms ."\n". $i_error . " registros no fueron actualizados: ";
      }

      // return response()->json(new Response(200,"Datos sincronizados correctamente",null,true));
      $r=array
      (
        "StatusCode" => 202,
        "Message"    => "Ok",
        "IsSuccess"  => true,
      );
      return response()->json(new
      	Response(200,"$ms",null,true),
       	200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE );
    }
    catch (\Exception $e)
    {
      return response()->json(new Response(500,$e->getMessage(). "--".$yolo,null,false));
     }
   }

  public function RestoreFromServer()
  {
    try
    {
      $tiempo_inicial = microtime(true);
      //Obtener datos de DB para enviar
      $nave=DB::table('nave')->get()->toJson(JSON_UNESCAPED_UNICODE);
      $ubicacion=DB::table('ubicacion')->get()->toJson(JSON_UNESCAPED_UNICODE);
      $articulo_c=DB::table('articulo_c')->get()->toJson(JSON_UNESCAPED_UNICODE);
      $categoria=DB::table('categoria')->get()->toJson(JSON_UNESCAPED_UNICODE);

      $db=array
      (
        "nave"         =>  $nave,			//0
        "ubicacion"         =>  $ubicacion,			//0
        "articulo"         =>  $articulo_c,			//0
        "categoria"         =>  $categoria,			//0


      );

      $tiempo_final = microtime(true);
      $tiempo = $tiempo_final - $tiempo_inicial;

      return response()->json(new
      	Response(200,$tiempo,$db,true),
      	200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE );
    }
    catch (\Exception $e)
    {
      return response()->json(new Response(500,$e->getMessage(),null,false));
    }
  }

  public function TestDB()
  {
    try
    {

      $tiempo_inicial = microtime(true);
      // Obtener de las tablas
      Proyecto::first();
      // // #### Materiales #####
      // $materiales=DB::table('coladas')
      // ->join('articulos','coladas.articulo_id','=','articulos.id')
      // ->join('lote_almacen','lote_almacen.articulo_id','=','coladas.articulo_id')
      // ->select('coladas.id as a_id','coladas.id as c_id','coladas.articulo_id','coladas.numero_colada as colada','articulos.descripcion','lote_almacen.id as lote_id', 'articulos.marca')
      // ->distinct('articulos.id')
      // ->orderby('c_id')
      // ->get();

      // foreach ($materiales as $material)
      // {
      //   print_r($material);
      //   $material_existe=Material::find($material->a_id);
      //   if($material_existe)
      //   {
      //     // Actualizar
      //     Material::where('Id','=',$material->a_id)->update
      //     (
      //       [
      //         'Descripcion' => str_replace("\"", "", $material->descripcion),
      //         'Colada'      => ($material->colada==null)? "MT-$material->articulo_id/LT-$material->lote_id" : $material->colada,
      //         'LoteId'      => $material->lote_id,
      //         'Marca'       => ($material->marca==null)? "No Disponible":$material->marca,
      //         'StateCode'   => 0
      //       ]
      //     );
      //     echo "Actualizado <br>";
      //   }
      //   else
      //   {
      //     // nuevo
      //     $material_nuevo=new Material;
      //     $material_nuevo->Id           = $material->a_id;
      //     $material_nuevo->Descripcion  = str_replace("\"", "", $material->descripcion);
      //     $material_nuevo->Colada       = ($material->colada==null)? "MT-$material->articulo_id/LT-$material->lote_id" : $material->colada;
      //     $material_nuevo->LoteId       = $material->lote_id;
      //     $material_nuevo->StateCode    = 0;
      //     $material_nuevo->Marca        = ($material->marca==null)? "No Disponible":$material->marca;
      //     $material_nuevo->save();
      //     echo "creado<br>";
      //   }
      // }

      //echo count($materiales);

      // where id=646;

      $tiempo_final = microtime(true);
      $tiempo = $tiempo_final - $tiempo_inicial;

      return response()->json(new Response(200,$tiempo,"Ok",true));
    }
    catch (\Exception $e)
    {
      echo $e->getMessage();
      // return response()->json(new Response(500,$e->getMessage(),null,false));
    }
  }

  public function ConfirmPass($contra)
  {
    try
      {
        $valido=UsuarioMovil::where('Contra',$contra)->first();
        if($valido)
        {
          // Válido
          return response()->json(new Response(200,"Ok",true,true));
        }
        // No valido
        return response()->json(
          new Response
          (
            404,"Contraseña incorrecta",false,true), 200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
          );
      }
      catch (\Exception $e)
      {
        // Error
        return response()->json(new Response(500,$e->getMessage(),false,false));
      }
  }

  public function Load()
  {
    try
    {
      $tiempo_inicial = microtime(true);

      // ##### Proyectos ######
      $proyectos=DB::table('proyectos')
      // ->where('fecha_fin','>=',date('Y-m-d'))
      ->where('proyectos.id','!=',1)
      ->where('proyectos.id','>',50)
      //->orWhere('proyectos.id','=',72)
      //->orWhere('proyectos.id','=',85)
      ->join('clientes','clientes.id','=','proyectos.cliente_id')
      ->select('proyectos.id','proyectos.nombre_corto','clientes.nombre as cliente','proyectos.ciudad','proyectos.condicion')
      ->distinct('id')
      ->orderby('id')
      ->get();
      foreach ($proyectos as $proyecto)
      {
        $proyecto_existe=Proyecto::find($proyecto->id);
        if($proyecto_existe)
        {
          // Actualizar
          Proyecto::where('Id',$proyecto->id)
          ->update(
          [
            'Nombre'    =>  $proyecto->nombre_corto,
            'Ubicacion' =>  $proyecto->ciudad,
            'Cliente'   =>  $proyecto->cliente,
            'NoContrato'=>  "NC-000",
            'StateCode' =>  0
          ]);
        }
        else
        {
          $proyecto_nuevo=new Proyecto;
          $proyecto_nuevo->Id         = $proyecto->id;
          $proyecto_nuevo->Nombre     = $proyecto->nombre_corto;
          $proyecto_nuevo->Ubicacion  = $proyecto->ciudad;
          $proyecto_nuevo->Cliente    = $proyecto->cliente;
          $proyecto_nuevo->NoContrato = "NC-000";
          $proyecto_nuevo->StateCode  = 0;
          $proyecto_nuevo->save();
        }
      }
      echo "Proyectos cargados <br>";
      // ###### Sistemas ######
      $sistemas=DB::table('sistemas_spool')->get();
      foreach ($sistemas as $sistema)
      {
        $sistema_existe=Sistema::find($sistema->Id);
        if($sistema_existe)
        {
          Sistema::where('Id',$sistema->Id)
          ->update(
          [
            'Nombre'    =>  $sistema->Nombre,
            'ProyectoId' =>  $sistema->ProyectoId
          ]);
        }
        else
        {
          $sistema_nuevo = new Sistema;
          $sistema_nuevo->Id         = $sistema->Id;
          $sistema_nuevo->Nombre     = $sistema->Nombre;
          $sistema_nuevo->ProyectoId = $sistema->ProyectoId;
          $sistema_nuevo->StateCode  = 0;
          $sistema_nuevo->save();
        }
      }
      echo "Sistemas cargados<br>";
      // ###### Servicios ######
      $servicios=DB::table('servicios_spools')->get();
      foreach ($servicios as $servicio)
      {
        $servicio_existe=Servicio::find($servicio->Id);
        if($servicio_existe)
        {
          // Actualizar
          Servicio::where('Id',$servicio->Id)
          ->update(
          [
            'Nombre'    =>  $servicio->Nombre,
            'StateCode' =>  0
          ]);
        }
        else
        {
          $servicio_nuevo = new Servicio;
          $servicio_nuevo->Id         = $servicio->Id;
          $servicio_nuevo->Nombre         = $servicio->Nombre;
          $servicio_nuevo->StateCode  = 0;
          $servicio_nuevo->save();
        }
      }
      echo "servicio cargados <br>";
      // ###### Servicio de sistema ######

      $servicios_de_sistema=DB::table('serviciosdesistema_spools')->get();
      foreach ($servicios_de_sistema as $servicio_sistema)
      {
        $servicio_sistema_exisite=ServicioDeSistema::find($servicio_sistema->Id);
        if($servicio_sistema_exisite)
        {
          // Actualizar
          ServicioDeSistema::where('Id',$servicio_sistema->Id)
          ->update(
          [
            'SistemaId'     =>  $servicio_sistema->SistemaId,
            'ServicioId'    =>  $servicio_sistema->ServicioId,
          ]);
        }
        else
        {
          $servicio_sistema_nuevo = new ServicioDeSistema;
          $servicio_sistema_nuevo->Id          = $servicio_sistema->Id;
          $servicio_sistema_nuevo->SistemaId   = $servicio_sistema->SistemaId;
          $servicio_sistema_nuevo->ServicioId  = $servicio_sistema->ServicioId;
          $servicio_sistema_nuevo->save();
        }
      }

      echo "servicio_sistema cargados <br>";
      // ###### Spools ######
      $spools=DB::table('spool_spools')->get();
      foreach ($spools as $spool)
      {
        $spool_existe=Spool::find($spool->Id);
        if($spool_existe)
        {
          // Actualizar
          Spool::where('Id',$spool->Id)
          ->update(
          [
            'Nombre'     =>  $spool->Nombre,
            'SistemaId'  =>  $spool->SistemaId,
            'ServicioId' =>  $spool->ServicioId,
            'StateCode'  =>  0
          ]);
        }
        else
        {
          $spool_nuevo = new Spool;
          $spool_nuevo->Id          = $spool->Id;
          $spool_nuevo->Nombre      = $spool->Nombre;
          $spool_nuevo->SistemaId   = $spool->SistemaId;
          $spool_nuevo->ServicioId  = $spool->ServicioId;
          $spool_nuevo->StateCode  = 0;
          $spool_nuevo->save();
        }
      }

      echo "spools cargados <br>";
      // #### Soldadores ###
      //FAltan las maquinas de soldar
      // Obtener de las tablas
      $soldadores=DB::table('empleados')->where('puesto_id','=',51)
      ->select('id','nombre','ap_paterno','ap_materno','condicion')->get();
      foreach ($soldadores as $soldador)
      {
        $nombres=explode(' ',$soldador->nombre);
        $aux_clave="";
        if(count($nombres)==1)
        {
          $aux_clave=$nombres[0][0];
        }
        else
        {
          $aux_clave=$nombres[0][0];
          $aux_clave.=$nombres[1][0];
        }
        $aux_clave.=$soldador->ap_paterno[0];
        $aux_clave.=$soldador->ap_materno[0];

        // Guardar/actualizar
        $soldador_existe=Soldador::find($soldador->id);
        if($soldador_existe)
        {
          // Actualizar
          Soldador::where('Id',$soldador->id)
          ->update(
          [
            'Nombre'=>$soldador->nombre,
            'ApellidoP'=>$soldador->ap_paterno,
            'ApellidoM'=>$soldador->ap_materno,
            'Clave'=>$aux_clave,
            'PQR'=>1  ,                     //
            'MaquinaId'=>1,                 //
            'EstadoId'=>$soldador->condicion,
            'StateCode'=>0
          ]);
        }
        else
        {
          // Nuevo
          $nuevo_soldador=new Soldador;
          // $nuevo_soldador->Id=$soldador->id;
          $nuevo_soldador->Nombre=$soldador->nombre;
          $nuevo_soldador->ApellidoP=$soldador->ap_paterno;
          $nuevo_soldador->ApellidoM=$soldador->ap_materno;
          $nuevo_soldador->Clave=$aux_clave;
          $nuevo_soldador->PQR=1;               //
          $nuevo_soldador->MaquinaId=1;         //
          $nuevo_soldador->EstadoId=$soldador->condicion;
          $nuevo_soldador->StateCode=0;
          $asd=$nuevo_soldador->save();
        }
      }
      echo "soldadores cargados<br>";

      // #### Juntas ####
      $juntas=DB::table('concentrado_spools')->get();
      foreach ($juntas as $junta)
      {
        $junta_existe=Junta::find($junta->id);
        if($junta_existe)
        {
          // Actualizar
          Junta::where('Id',$junta->id)
          ->update(
          [
            'No'              => $junta->juntas,
            'SpoolId'         => 1,         //  #Id Spoo,
            'SoldadorId'      => 1,         //  # ID Soldado,
            'Diametro'        => str_replace("\"", "In", $junta->diametro),
            'Habilitada'      => (strcasecmp($junta->habilitadas,"")==0)?0:1,
            'FechaHabilitada' => ($junta->fecha_uno==null)? "01/01/01":$junta->fecha_uno,
            'Soldada'         => (strcasecmp($junta->soldadas,"")==0)?0:1,
            'FechaSoldada'    => ($junta->fecha_dos==null)? "01/01/01":$junta->fecha_dos,
            'ProcedimientoId' => 1,     // procedimeinto de soldadiura
            'StateCode'       => 0
          ]);
        }
        else
        {
          // nuevo
          $junta_nuevo=new Junta;
          // $junta_nuevo->Id              = $junta->id;
          $junta_nuevo->No              = "N-X";
          $junta_nuevo->SpoolId         = 1; //# Id Spools
          $junta_nuevo->SoldadorId      = 1; //Id Soldador
          $junta_nuevo->Diametro        = str_replace("\"", "In", $junta->diametro);
          $junta_nuevo->Habilitada      = (strcasecmp($junta->habilitadas,"")==0)?0:1;
          $junta_nuevo->FechaHabilitada = ($junta->fecha_uno==null)? "01/01/01":$junta->fecha_uno;
          $junta_nuevo->Soldada         = (strcasecmp($junta->soldadas,"")==0)?0:1;
          $junta_nuevo->FechaSoldada    = ($junta->fecha_dos==null)? "01/01/01":$junta->fecha_dos;
          $junta_nuevo->StateCode       = 0;
          $junta_nuevo->ProcedimientoId = 1;
          $junta_nuevo->save();
        }
      }
      echo "Juntas cargados<br>";

      ################################################################
      ###########  lote_id & articulo_id NO DEBEN SER NULL ###########
      ################################################################

      // #### Materiales #####
      $materiales=DB::table('coladas')
      ->join('articulos','coladas.articulo_id','=','articulos.id')
      ->join('lote_almacen','lote_almacen.articulo_id','=','coladas.articulo_id')
      ->select('coladas.id as a_id','coladas.id as c_id','coladas.articulo_id','coladas.numero_colada as colada','articulos.descripcion','lote_almacen.id as lote_id', 'articulos.marca')
      ->distinct('articulos.id')
      ->orderby('c_id')
      ->get();

      foreach ($materiales as $material)
      {
        $material_existe=Material::find($material->a_id);
        if($material_existe)
        {
          echo "Existe<br>";
          // Actualizar
          Material::where('Id','=',$material->a_id)->update
          (
            [
              'Descripcion' => str_replace("\"", "", $material->descripcion),
              'Colada'      => ($material->colada==null)? "MT-$material->articulo_id/LT-$material->lote_id" : $material->colada,
              'LoteId'      => $material->lote_id,
              'Marca'       => ($material->marca==null)? "No Disponible":$material->marca,
              'StateCode'   => 0,
              'ArticuloId'  => $material->articulo_id,
            ]
          );
        }
        else
        {
          // nuevo
          $material_nuevo=new Material;
          // $material_nuevo->Id           = $material->a_id;
          $material_nuevo->Descripcion  = str_replace("\"", "", $material->descripcion);
          $material_nuevo->Colada       = ($material->colada==null)? "MT-$material->articulo_id/LT-$material->lote_id" : $material->colada;
          $material_nuevo->LoteId       = $material->lote_id;
          $material_nuevo->StateCode    = 0;
          $material_nuevo->ArticuloId   = $material->articulo_id;
          $material_nuevo->Marca        = ($material->marca==null)? "No Disponible":$material->marca;
          $material_nuevo->save();
          echo "Nuevo<br>";
        }
      }
      echo "Materiales cargados<br>";
      $tiempo_final = microtime(true);
      $tiempo = $tiempo_final - $tiempo_inicial;
      echo $tiempo_final;

      // return response()->json(new Response(200,$tiempo,'Registros cargados',true));
    }
    catch (\Exception $e)
    {
      echo $e->getMessage();
      // return response()->json(new Response(500,$e->getMessage(),null,false));
    }
  }

  public function barras()
  {
    $barcode = new BarcodeGenerator();
    $barcode->setText("0123456789");
    $barcode->setType(BarcodeGenerator::Code128);
    $barcode->setScale(2);
    $barcode->setThickness(25);
    $barcode->setFontSize(10);
    $code = $barcode->generate();

    echo '<img src="data:image/png;base64,'.$code.'" />';

  }

  public function naves()
  {

$d = new DNS1D();
echo DNS1D::getBarcodePNGPath('4445645656', 'PHARMA2T');

$d->getBarcodeHTML('9780691147727', 'EAN13');
$d->setStorPath(public_path('barras/'));
    // $naves = DB::table('nave')->get();
    // return response()->json();
  }
}
