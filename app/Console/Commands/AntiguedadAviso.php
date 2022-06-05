<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Mail;
use Illuminate\Support\Facades\DB;
use App\Empleado;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;



class AntiguedadAviso extends Command
{
  /**
  * The name and signature of the console command.
  *
  * @var string
  */
  protected $signature = 'aviso:antiguedad';

  /**
  * The console command description.
  *
  * @var string
  */
  protected $description = 'Envio de correo para el aviso de antiguedad.';

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

  public function mandarCorreo($empleado)
  {
    $hoy = date("Y-m-d");
    $data = [
     'nombre' => $empleado->nombre.' '.$empleado->ap_paterno.' '.$empleado->ap_materno,
     'correo_electronico' => $empleado->correo_electronico,
     'fecha' => $hoy,
     ];
    Mail::send('emails.antiguedad', $data, function($message) use ($data, $empleado) {
      $core= $empleado->correo_electronico;

      $pdf = PDF::loadView('emails.antiguedadadjunto',$data);
        $message->to($core, 'Aviso de antiguedad')
                ->subject('Aviso de antiguedad');
        $message->from('cesar.abad@conservct.com','Conserflow');
        $message->attachData($pdf->output(), 'Antiguedad.pdf');
      });

      return  true;
  }

  public function handle()
  {
    $antiguedad = DB::table('antiguedad')
    ->leftJoin('empleados AS E','E.id','=','antiguedad.empleado_id')
    ->join('contacto_empleados AS CE', 'E.id', '=', 'CE.empleado_id')
    ->select('antiguedad.*','E.nombre', 'E.ap_paterno', 'E.ap_materno','CE.correo_electronico')
    ->where('antiguedad.condicion', '1')
    ->whereRaw("DATE_FORMAT(antiguedad.fecha_inicial, '%m-%d') = ?", [date('m-d')])
    ->get();

    foreach ($antiguedad as $empleado) {
      $this->mandarCorreo($empleado);
    }

  }

}
