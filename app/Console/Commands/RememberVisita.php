<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;
use App\User;
use App\Visitas;

class RememberVisita extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RememberVisita:sendnotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification remember visita';

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
    	//php artisan RememberVisita:sendnotification
		// $visitas = Visitas::where('visible','=',true)->whereRaw('DATE(visita_date) = DATE(NOW()) + INTERVAL 1 DAY')->get();			
		// if($visitas){
// 			
			// //pasamos por todas las visitas que hayan al dia siguiente
			// foreach ($visitas as $key => $visita) {
				// //recogemos usuario de esta visita				
				// $userto = User::find($visita->user);					
				// if($userto){
					// $headings = array(
				        // 'en' => 'Remember de cita previa',
				        // 'es' => 'Recordatorio cita previa',
				      // );
				      // $content = array(
				        // 'en' => 'New visit '.$visita->visita_date.' - '.$visita->note,
				        // 'es' => 'Tiene cita el '.$visita->visita_date.' - '.$visita->note,
				      // );
// 					  
					// $player_ids = array($visita->push_id_onesignal);						 
			      	// $result =  \App\Http\Controllers\Mobile\PushController::sendMessage($headings, $content, $player_ids);						
// 					
				// }
// 			
			// }
		// }
		
    }
}
