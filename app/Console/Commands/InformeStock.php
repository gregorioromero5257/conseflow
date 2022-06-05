<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;
use Illuminate\Support\Facades\DB;
use App\Empleado;

class InformeStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aviso:stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envio de correo con el informe de stock';

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
      $correos = DB::table('correos_programados')
      ->select('correos_programados.*')
      ->where('correos_programados.estado','1')
      ->where('correos_programados.tipo_correo','=','aviso:stock')
      ->get();
      foreach($correos as $value)
      {

      $resultadomin = DB::select('select * from (select id, nombre, codigo, descripcion, minimo from articulos where minimo is not null and minimo > 0 and condicion = 1) as t1 join (select articulo_id, sum(cantidad) as existencia from existencias group by articulo_id HAVING existencia > 0) as t2 on t1.id = t2.articulo_id WHERE existencia < minimo');
      $resultadomax = DB::select('select * from (select id, nombre, codigo, descripcion, maximo from articulos where maximo is not null and maximo > 0 and condicion = 1) as t1 join (select articulo_id, sum(cantidad) as existencia from existencias group by articulo_id HAVING existencia > 0) as t2 on t1.id = t2.articulo_id WHERE existencia > maximo');
      $empleado = [];
      $correo = $value->correo;
              $data = [
                  'minimo' => $resultadomin,
                  'maximo' => $resultadomax,
              ];
              Mail::send('emails.stockaviso', $data, function($message) use ($correo) {
                  $message->to($correo, 'Informe')
                          ->subject('Informe stock');
                  $message->from('webmaster@conserflow.com','Conserflow');
              });

      }
    }
}
