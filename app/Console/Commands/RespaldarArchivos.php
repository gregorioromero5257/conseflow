<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RespaldarArchivos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'respaldar:archivos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        //
        $output = shell_exec("cp -r /opt/lampp/htdocs/conserflow-sistema2018/storage/app/Archivos/ /home/vsftpuser/ftp;rm -r /opt/lampp/htdocs/conserflow-sistema2018/storage/app/Archivos/");
    }
}
