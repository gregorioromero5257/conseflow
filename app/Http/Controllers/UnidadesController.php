<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Unidades;
use Validator;
use Illuminate\Validation\Rule;
use File;
use Illuminate\Support\Facades\Storage;
use \App\Http\Helpers\Utilidades;
use App\UnidadPropio;
use App\UnidadRenta;
use Illuminate\Support\Facades\DB;



class UnidadesController extends Controller
{
    protected $rules = array(
        'modelo' => 'required|max:45',
    );

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $unidades = Unidades::leftJoin('unidad_propio AS UP','UP.unidad_id','=','unidades.id')
        ->leftJoin('unidad_renta AS UR','UR.unidad_id','=','unidades.id')
        ->select('unidades.*','UP.id AS unidad_propio_id','UP.propietario','UR.id AS unidad_renta_id','UR.proveedor','UR.tiempo','UR.costo_renta')
        ->orderBy('unidades.id', 'ASC')->get()->toArray();

        return response()->json($unidades);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $unidades = Unidades::orderBy('id', 'ASC')->get();

        return response()->json($unidades);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      try {



        if($request->metodo == "Nuevo"){
            /*NUEVO REGISTRO*/
            //Variables de archivo
            $FacturaStore='';
            $TarjetaStore = '';


            if (!$request->ajax()) return redirect('/');
            //--Si el request no contiene archivos, se guardan los campos listados--//
            if(!$request->hasFile('factura') && !$request->hasFile('tarjeta')) {
                $unidad = new Unidades();
                $unidad->unidad = $request->unidad;
                $unidad->marca = $request->marca;
                $unidad->modelo = $request->modelo;
                $unidad->anio = $request->anio;
                $unidad->placas = $request->placas;
                $unidad->estado = $request->estado;
                $unidad->comentarios = $request->comentarios;
                $unidad->tipo = $request->tipo;
                $unidad->factura = '';
                $unidad->clase_tipo = $request->clase_tipo;
                $unidad->combustible = $request->combustible;
                $unidad->numero_tarjeta_circulacion = $request->numero_tarjeta_circulacion;
                $unidad->tarjeta = '';
                $unidad->excento = $request->excento == 'false'  ? 0 : 1;
                $unidad->primer_semestre = $request->primer_semestre;
                $unidad->segundo_semestre = $request->segundo_semestre;
                $unidad->numero_serie = $request->numero_serie;
                $unidad->empresa = $request->empresa;
                $unidad->color = $request->color;
                $unidad->no_motor = $request->no_motor;
                $unidad->capacidad = $request->capacidad;
                $unidad->cilindros = $request->cilindros;
                Utilidades::auditar($unidad,$unidad->id);
                $unidad->save();
                $this->completarLLenado($request,$unidad->id);
            }

            //--valida que campos del request contienen archivos y realiza el guardado--//
            if($request->hasFile('factura') || $request->hasFile('tarjeta'))
            {
                if ($request->hasFile('factura')) {
                    //obtiene el nombre del archivo y su extension
                    $FacturaNE = $request->file('factura')->getClientOriginalName();
                    //Obtiene el nombre del archivo
                    $FacturaNombre = pathinfo($FacturaNE, PATHINFO_FILENAME);
                    //obtiene la extension
                    $FacturaExt = $request->file('factura')->getClientOriginalExtension();
                    //nombre que se guarad en BD
                    $FacturaStore = 'Factura_'.uniqid().'.'.$FacturaExt;
                    //Subida del archivo al servidor ftp
                    Storage::disk('local')->put('Archivos/'.$FacturaStore, fopen($request->file('factura'), 'r+'));
                }
                if ($request->hasFile('tarjeta')) {
                    //obtiene el nombre del archivo y su extension
                    $TarjetaNE = $request->file('tarjeta')->getClientOriginalName();
                    //Obtiene el nombre del archivo
                    $TarjetaNombre = pathinfo($TarjetaNE, PATHINFO_FILENAME);
                    //obtiene la extension
                    $TarjetaExt = $request->file('tarjeta')->getClientOriginalExtension();
                    //nombre que se guarad en BD
                    $TarjetaStore = 'Tarjeta_'.uniqid().'.'.$TarjetaExt;
                    //Subida del archivo al servidor ftp
                    Storage::disk('local')->put('Archivos/'.$TarjetaStore, fopen($request->file('tarjeta'), 'r+'));
                }
                $unidad = new Unidades();
                $unidad->unidad = $request->unidad;
                $unidad->marca = $request->marca;
                $unidad->modelo = $request->modelo;
                $unidad->anio = $request->anio;
                $unidad->placas = $request->placas;
                $unidad->estado = $request->estado;
                $unidad->comentarios = $request->comentarios;
                $unidad->tipo = $request->tipo;
                $unidad->factura = $FacturaStore;
                $unidad->clase_tipo = $request->clase_tipo;
                $unidad->combustible = $request->combustible;
                $unidad->numero_tarjeta_circulacion = $request->numero_tarjeta_circulacion;
                $unidad->tarjeta = $TarjetaStore;
                $unidad->excento = $request->excento == 'false'  ? 0 : 1;
                $unidad->primer_semestre = $request->primer_semestre;
                $unidad->segundo_semestre = $request->segundo_semestre;
                $unidad->numero_serie = $request->numero_serie;
                $unidad->empresa = $request->empresa;
                $unidad->color = $request->color;
                $unidad->no_motor = $request->no_motor;
                $unidad->capacidad = $request->capacidad;
                $unidad->cilindros = $request->cilindros;
                Utilidades::auditar($unidad, $unidad->id);
                $unidad->save();
                $this->completarLLenado($request,$unidad->id);
            }

            return response()->json(array(
                'status' => true,
            ));
            /*FIN NUEVO REGISTRO*/
        }

        if($request->metodo == "Actualizar"){
            /*ACTUALIZAR REGISTRO*/
            //Variables de archivo
            $FacturaStore='';
            $TarjetaStore = '';
            //*Variables para actualizar nuevos archivos y eliminar existentes
            $ValorPoliza = '';
            $ValorPoliz = '';

            $unidades = Unidades::where('id',$request->id)->get();

            foreach ($unidades as $key => $item) {
                $ValorPoliza = $item->factura;
                $ValorPoliz = $item->tarjeta;

                $FacturaStore=$item->factura;
                $TarjetaStore=$item->tarjeta;

            }
            //*FIN

            if (!$request->ajax()) return redirect('/');

            //--Si el request no contiene archivos, solo se actualizan los campos listados--//
            if(!$request->hasFile('factura') && !$request->hasFile('tarjeta'))
            {

                $unidad = Unidades::findOrFail($request->id);
                $unidad->unidad = $request->unidad;
                $unidad->marca = $request->marca;
                $unidad->modelo = $request->modelo;
                $unidad->anio = $request->anio;
                $unidad->placas = $request->placas;
                $unidad->estado = $request->estado;
                $unidad->comentarios = $request->comentarios;
                $unidad->tipo = $request->tipo;
                $unidad->clase_tipo = $request->clase_tipo;
                $unidad->combustible = $request->combustible;
                $unidad->numero_tarjeta_circulacion = $request->numero_tarjeta_circulacion;
                $unidad->excento = $request->excento == 'false'  ? 0 : 1;
                $unidad->primer_semestre = $request->primer_semestre;
                $unidad->segundo_semestre = $request->segundo_semestre;
                $unidad->numero_serie = $request->numero_serie;
                $unidad->empresa = $request->empresa;
                $unidad->color = $request->color;
                $unidad->no_motor = $request->no_motor;
                $unidad->capacidad = $request->capacidad;
                $unidad->cilindros = $request->cilindros;
                Utilidades::auditar($unidad, $unidad->id);
                $unidad->save();
                $this->completarLLenado($request,$unidad->id);
            }

            //--valida que campos del request contienen archivos y realiza el guardado--//
            if($request->hasFile('factura') || $request->hasFile('tarjeta') )
            {
                if ($request->hasFile('factura')) {
                    //obtiene el nombre del archivo y su extension
                    $FacturaNE = $request->file('factura')->getClientOriginalName();
                    //Obtiene el nombre del archivo
                    $FacturaNombre = pathinfo($FacturaNE, PATHINFO_FILENAME);
                    //obtiene la extension
                    $FacturaExt = $request->file('factura')->getClientOriginalExtension();
                    //nombre que se guarad en BD
                    $FacturaStore = 'Factura_'.uniqid().'.'.$FacturaExt;
                    //Subida del archivo al servidor ftp
                    Storage::disk('local')->put('Archivos/'.$FacturaStore, fopen($request->file('factura'), 'r+'));
                    if ($ValorPoliza != '') {
                        //Elimina el archivo en el servidor si requiere ser actualizado
                        Utilidades::ftpSolucionEliminar($ValorPoliza);
                    }

                }
                if ($request->hasFile('tarjeta')) {
                    //obtiene el nombre del archivo y su extensionfactura
                    $TarjetaNE = $request->file('tarjeta')->getClientOriginalName();
                    //Obtiene el nombre del archivo
                    $TarjetaNombre = pathinfo($TarjetaNE, PATHINFO_FILENAME);
                    //obtiene la extension
                    $TarjetaExt = $request->file('tarjeta')->getClientOriginalExtension();
                    //nombre que se guarad en BD
                    $TarjetaStore = 'Tarjeta_'.uniqid().'.'.$TarjetaExt;
                    //Subida del archivo al servidor ftp
                    Storage::disk('local')->put('Archivos/'.$TarjetaStore, fopen($request->file('tarjeta'), 'r+'));
                    if ($ValorPoliz != '') {
                        //Elimina el archivo en el servidor si requiere ser actualizado
                        Utilidades::ftpSolucionEliminar($ValorPoliz);
                    }

                }

                $unidad = Unidades::findOrFail($request->id);
                $unidad->unidad = $request->unidad;
                $unidad->marca = $request->marca;
                $unidad->modelo = $request->modelo;
                $unidad->anio = $request->anio;
                $unidad->placas = $request->placas;
                $unidad->estado = $request->estado;
                $unidad->comentarios = $request->comentarios;
                $unidad->tipo = $request->tipo;
                $unidad->factura = $FacturaStore;
                $unidad->clase_tipo = $request->clase_tipo;
                $unidad->combustible = $request->combustible;
                $unidad->numero_tarjeta_circulacion = $request->numero_tarjeta_circulacion;
                $unidad->tarjeta = $TarjetaStore;
                $unidad->excento = $request->excento == 'false'  ? 0 : 1;
                $unidad->primer_semestre = $request->primer_semestre;
                $unidad->segundo_semestre = $request->segundo_semestre;
                $unidad->numero_serie = $request->numero_serie;
                $unidad->empresa = $request->empresa;
                $unidad->color = $request->color;
                $unidad->no_motor = $request->no_motor;
                $unidad->capacidad = $request->capacidad;
                $unidad->cilindros = $request->cilindros;
                Utilidades::auditar($unidad, $unidad->id);
                $unidad->save();
                $this->completarLLenado($request,$unidad->id);
            }

            return response()->json(array(
                'status' => true
            ));
            /*FIN ACTUALIZAR REGISTRO*/
        }
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function completarLLenado($request,$id)
    {
      if($request->tipo == 1){
        $unidad_propio_buscar = UnidadPropio::where('unidad_id',$id)->first();
        if (isset($unidad_propio_buscar) == true) {
          $unidad_propio_buscar->unidad_id = $id;
          $unidad_propio_buscar->propietario = $request->propietario;
          $unidad_propio_buscar->save();
          return true;

        }
        else {
          $unidad_renta_buscar = UnidadRenta::where('unidad_id',$id)->first();
          if (isset($unidad_renta_buscar) == true) {
            $unidad_renta_eliminar = UnidadRenta::where('unidad_id',$id)->delete();
          }
          $unidad_propio = new UnidadPropio();
          $unidad_propio->unidad_id = $id;
          $unidad_propio->propietario = $request->propietario;
          $unidad_propio->save();
          return true;

        }

      }elseif ($request->tipo == 2) {
        $unidad_renta_buscar = UnidadRenta::where('unidad_id',$id)->first();
        if (isset($unidad_renta_buscar) == true) {
          $unidad_renta_buscar->unidad_id = $id;
          $unidad_renta_buscar->proveedor = $request->proveedor;
          $unidad_renta_buscar->tiempo = $request->tiempo;
          $unidad_renta_buscar->costo_renta = $request->costo_renta;
          $unidad_renta_buscar->save();
          return true;

        }else {
          $unidad_propio_buscar = UnidadPropio::where('unidad_id',$id)->first();
          if (isset($unidad_propio_buscar) == true) {
            $unidad_propio_eliminar = UnidadPropio::where('unidad_id',$id)->delete();
          }
          $unidad_renta = new UnidadRenta();
          $unidad_renta->unidad_id = $id;
          $unidad_renta->proveedor = $request->proveedor;
          $unidad_renta->tiempo = $request->tiempo;
          $unidad_renta->costo_renta = $request->costo_renta;
          $unidad_renta->save();
          return true;
        }
      }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Se obtiene el archivo del FTP serve
        $archivo = Utilidades::ftpSolucion($id);
        // Se coloca el archivo en una ruta local
        Storage::disk('descarga')->put($id, $archivo);
        //--Devuelve la respuesta y descarga el archivo--//
        return response()->download(storage_path().'/app/descargas/'.$id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	//elimina de la ruta local el archivo descargado
        Storage::disk('descarga')->delete($id);
        Storage::disk('local')->delete($id);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function catalogoclasetipo()
    {
      $catalogo = DB::table('catalogo_tipo_vehiculo')->get();
      return response()->json($catalogo);
    }

    public function catalogocombustible()
    {
      $catalogo = DB::table('catalogo_tipo_gasolina')->get()->toArray();
      return response()->json($catalogo);
    }

    public function propietariosUnidades()
    {
      $arreglo = [];
      $unidades = Unidades::leftJoin('unidad_propio AS UP','UP.unidad_id','=','unidades.id')
      ->leftJoin('unidad_renta AS UR','UR.unidad_id','=','unidades.id')
      ->select('unidades.*','UP.id AS unidad_propio_id','UP.propietario','UR.id AS unidad_renta_id','UR.proveedor','UR.tiempo','UR.costo_renta')
      ->get();
      foreach ($unidades as $key => $value) {
        if ($value->propietario == null) {
          $arreglo [] = [
            'nombre' => $value->proveedor,
          ];
          // array_push($arreglo,$value->proveedor);
        }elseif ($value->proveedor == null) {
          // array_push($arreglo,$value->propietario);
          $arreglo [] = [
            'nombre' => $value->propietario,
          ];
        }
      }
      return response()->json($arreglo);

    }

    public function unidadesbuscarusr($id)
    {
      if($id == '0'){
        $unidades = Unidades::leftJoin('unidad_propio AS UP','UP.unidad_id','=','unidades.id')
        ->leftJoin('unidad_renta AS UR','UR.unidad_id','=','unidades.id')
        ->select('unidades.*','UP.id AS unidad_propio_id','UP.propietario','UR.id AS unidad_renta_id','UR.proveedor',
        'UR.tiempo','UR.costo_renta')->get();
      }else {
        $unidades = Unidades::leftJoin('unidad_propio AS UP','UP.unidad_id','=','unidades.id')
        ->leftJoin('unidad_renta AS UR','UR.unidad_id','=','unidades.id')
        ->select('unidades.*','UP.id AS unidad_propio_id','UP.propietario','UR.id AS unidad_renta_id','UR.proveedor',
        'UR.tiempo','UR.costo_renta')->where('propietario','=',$id)->orWhere('proveedor','=',$id)
        ->get();
      }

      // $array = array_merge($unidad_propio,$unidad_renta);
      return response()->json($unidades);

    }
}
