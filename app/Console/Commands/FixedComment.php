<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Requisicionhasordencompras;
use App\Compras;


class FixedComment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fixed:comment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando y Archivo para solucionar problemas de insercion en base datos !Ejecutar con Precaución!';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // $partidas_entradas = DB::select('select * from partidas_entradas');
        // foreach ($partidas_entradas as $key => $value) {
        //   if ($value->almacene_id == '') {
        //     $par_ent = \App\PartidaEntrada::where('id',$value->id)->first();
        //     $par_ent->status = 0;
        //     $par_ent->save();
        //   }else {
        //     $par_ent = \App\PartidaEntrada::where('id',$value->id)->first();
        //     $par_ent->status = 1;
        //     $par_ent->save();
        //   }
        // }

       // $rhoc = DB::select('select * from requisicion_has_ordencompras');
      //  foreach ($rhoc as $key => $value) {
        //  $rh = \App\requisicionhasordencompras::where('id',$value->id)->first();
         // $rh->cantidad_entrada = $value->cantidad;
         // $rh->save();
       // }
         // $proveedores = DB::select('select * from proveedores');
       // foreach ($proveedores as $key => $value) {
         // $credito = \App\Credito::where('proveedor_id',$value->id)->first();
         // if (isset($credito) == true) {
          //  $credito->proveedor_id = $value->id;
          //  $credito->monto_credito = $value->limite_credito;
          //  $credito->save();
        //  }else {
         //   $credito_new = new \App\Credito();
         //   $credito_new->proveedor_id = $value->id;
         //   $credito_new->monto_credito = $value->limite_credito;
         //   $credito_new->save();
         // }
       // }

	$compras = Compras::where('proyecto_id','98')->orderBy('folio','ASC')->get();

foreach ($compras as $key => $value) {
  $valores = explode('-',$value->folio);
  $oc = Compras::where('id',$value->id)->first();
  $oc->folio = $valores[0].'-'.$valores[1].'-'.$valores[2].'-'.$valores[3].'-'.str_pad(($key + 1), 3, "0", STR_PAD_LEFT);
  // $oc->folio = 'OC-CONSERFLOW-020-TI-'.str_pad(($key + 1), 3, "0", STR_PAD_LEFT);
  $oc->save();

}
    }
}

