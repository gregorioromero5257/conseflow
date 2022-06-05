<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Mail;
use Illuminate\Support\Facades\DB;
use App\Empleado;

class CumpleañosAviso extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aviso:cumpleaños';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envio de correo a los que cumplen años.';

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
        // Log::info('Envio de correo cumpleaneros: '.date('Y-d-m H:i:s'));
        $empleados = DB::table('empleados')
        ->select('empleados.nombre', 'empleados.ap_paterno', 'empleados.ap_materno', 'contacto_empleados.correo_electronico')
        ->join('contacto_empleados', 'empleados.id', '=', 'contacto_empleados.empleado_id')
        ->whereRaw("DATE_FORMAT(empleados.fech_nac, '%m-%d') = ?", [date('m-d')])
        ->where('empleados.condicion', '1')
        ->get();

        foreach($empleados as $empleado)
        {
            if (strlen($empleado->correo_electronico) > 0)
            {
                $data = [
                    'nombre' => $empleado->nombre.' '.$empleado->ap_paterno.' '.$empleado->ap_materno,
                ];
                Mail::send('emails.cumpleaños', $data, function($message) use ($empleado) {
                    $message->to($empleado->correo_electronico, $empleado->nombre.' '.$empleado->ap_paterno)
                            ->subject('Feliz cumpleaños');
                    $message->from('cesar.abad@conservct.com','Conserflow');
                });
            }
        }        
    }
}
