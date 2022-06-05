<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];



    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
      return;
      //usuario-> correo-> periocidad;
      $correos = DB::table('correos_programados')
      ->select('correos_programados.*')
      ->where('correos_programados.estado','1')
      ->get();
      foreach ($correos as $key => $value) {
        switch ($value->frecuencia) {
          case 1:
          $schedule->command($value->tipo_correo)->everyMinute();
          break;
          case 2:
          $schedule->command($value->tipo_correo)->everyFiveMinutes();
          break;
          case 3:
          $schedule->command($value->tipo_correo)->everyTenMinutes();
          break;
          case 4:
          $schedule->command($value->tipo_correo)->everyThirtyMinutes();
          break;
          case 5:
          $schedule->command($value->tipo_correo)->hourly();
          break;
          case 6:
          $valor = intval($value->tiempo_rango);
          $schedule->command($value->tipo_correo)->hourlyAt($valor);
          break;
          case 7:
          $schedule->command($value->tipo_correo)->daily();
          break;
          case 8:
          $schedule->command($value->tipo_correo)->dailyAt($value->tiempo_rango);
          break;
          case 9:
          // $valores = explode('&', $value->tiempo_rango);
          // $valor_uno = intval($valores[0]);
          // $valor_dos = intval($valores[1]);
        //  $schedule->command($value->tipo_correo)->twiceDaily(11, 12);
          break;
          case 10:
          $schedule->command($value->tipo_correo)->weekly();
          break;
          case 11:
           $valores = explode('&', $value->tiempo_rango);
           $valor_uno = intval($valores[0]);
           $valor_dos = $valores[1];
           $schedule->command($value->tipo_correo)->weeklyOn($valor_uno, $valor_dos);
          break;
          case 12:
          $schedule->command($value->tipo_correo)->monthly();
          break;
          case 13:
          $valores = explode('&', $value->tiempo_rango);
          $valor_uno = intval($valores[0]);
          $valor_dos = $valores[1];
          $schedule->command($value->tipo_correo)->monthlyOn($valor_uno, $valor_dos);
          break;
        }
      }
   //   $schedule->command('aviso:cumpleaños')->dailyAt('07:00');
  //    $schedule->command('aviso:antiguedad')->dailyAt('07:00');

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
