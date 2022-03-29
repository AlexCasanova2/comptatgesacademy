<?php namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

//Clases para sentinel
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Database\Capsule\Manager as Capsule;
use Cartalyst\Sentinel\Laravel\Facades\Activation;

use App\User;
use App\Visitas;
use App\Descargas;
use App\VisualConfig;
use App\DisableDays;
use App\DisableDaysHours;
use App\TipoVisita;
use App\TipoDocumentacion;
use App\Facultativo;
use App\Centro;
use DateTime;
use DB;
use Mail;

//use Mail;
use App\Http\Controllers\MailController;

class MobileAppController extends Controller {

  public function ws(Request $request=null,$token=null,$action=null){
    if(isset($_SERVER['HTTP_ORIGIN']))
      $http_origin = $_SERVER['HTTP_ORIGIN'];
    else
      $http_origin = '*';
    $this->html->extraheader1 = 'Access-Control-Allow-Origin: '.$http_origin;
    //$this->html->extraheader1 = 'Access-Control-Allow-Origin: http://localhost:3000';
    //$this->html->extraheader1 = 'Access-Control-Allow-Origin: *';
    $this->html->extraheader2 = 'Access-Control-Allow-Credentials: true';

    $this->html->request = $request;

    //La accion no puede ser nula
    if(is_null($action)){
      $this->wsprint(-__LINE__, 'Invalid action', false);
    }
	
	

	
    //el parametro token, tiene que ser válido
    $this->html->user = \App\User::where('app_token','LIKE',$token)->count();
    if($this->html->user == 1){
      $this->html->user = \App\User::where('app_token','LIKE',$token)->first();
      if(is_null($this->html->user)){
        $this->wsprint(-__LINE__, 'Invalid token', false);
      }
    }else{
	  	
      	 switch($action){
	      	case 'centros_visita':
				$this->getCentroVisita();
				break;				
			case 'facultativos_visita':
				$this->getFacultativosVisita();
				break;				
				break;
	      	case 'tipos_visita':
				//$this->wsprint(-__LINE__, 'entra al switch',$this->html ,true);
				$this->getTiposVisita();
				break;
			case 'primera_visita_datos':
				$this->getPrimeraVisitaDatos();
				breaK;
			case 'set_primera_visita':
				$this->setPrimeraVisita();
				break;
			default:
				$this->wsprint(-37, 'Invalid token', false); //siempre -37 para poder hacer autologin en la app
				 break;
		 }
    }
    //actualizamos campo last login para saber la ultima actividad
    $this->html->user->last_login = date('Y-m-d h:i:s');//2016-11-10 11:50:48
    $this->html->user->save();





/*
    if(isset($action) && isset($this->html->user->email)){
      $clean_post = array();
     // $invalid_fields = array('photo1','photo2','photo3','photo4','file','image','images');
     // $log_message = 'user:'.$this->html->user->email.' | action:'.$action."\n";
      foreach($this->html->request->all() as $_k => $_val){
        if(in_array($_k,$invalid_fields)) continue;
        $clean_post[$_k] = $_val;
      }
      $log_message .= json_encode($clean_post)."\n";
      error_log($log_message, 3, "/tmp/ws_error_log");
    }
*/
    switch($action){
		
      case 'set_push_id':
        $this->setPushId();
        break;

      case 'logout':
        $this->logout();
        break;
	  
	  case 'set_visita':
        $this->setVisita();
        break;

	  case 'get_profile':
        $this->getProfile();
        break;		
	  
      case 'push':
        $this->pushMessageTest();
        break;
		
      default:
        $this->wsprint(-__LINE__, 'Acción no definida', false);
        break;
    }

    //Aqui no va a llegar nunca, pero por si acaso
    $this->wsprint(-__LINE__, 'Error desconocido', false);
  }

  public function setPrimeraVisita(){
  			
  		$request = $this->html->request;
		
		//solo puede tener 1 visita
		//$remvisits = Visitas::where('user','=',$this->html->user->id)->update(['visible' => false]);
		$credentials = [
		    'login' => $request->user,
		];
		$user = Sentinel::findByCredentials($credentials);
		if($user){
			$this->wsprint(-__LINE__, 'Usuario ya registrado', false);
		}
		
		//insertamos usuario porque no existe
		
		//INSERTADO CLIENTE
		$role = Sentinel::findRoleBySlug('client');
		$user = Sentinel::create([
          'email'    => $request->user,
          'password' => $request->user.'oftalmo+', //email+oftalmo+
          'first_name' => $request->first_name,
          'last_name' => '',
          
        ]);
        $role->users()->attach($user);
        $act = Activation::create($user);
        Activation::complete($user,$act->code);
		
		
		$destinationPath= base_path()."/public/images/pacients/".$user->id."/";
		$urlpath="/images/pacients/".$user->id."/";
		if(!is_dir($destinationPath)){mkdir($destinationPath, 0777, true);}
		//Añadido de imagen
		$user->save();	
		
		$visita = new Visitas();
		$visita->user =$user->id;
		$visita->note = "Primera visita para (".$request->user.')';
		$visita->tipo = $request->tipo;
		$visita->primera_visita =true;
		$visita->urgente =$request->urgente;
		$visita->horario =$request->horario;
		$visita->dia_semana =$request->dia_semana;
		$visita->centro =$request->centro;
		$visita->facultativo =$request->facultativo;
		$visita->visible =true;
		$visita->save();
		
		$this->html->visita = $visita;		
		
		
	 	$viewmail = view('emails.welcome')
          ->with('visita', $visita)
          ->with('user', $user)
		  ->with('time', $this->getStateDay())
          ->render();
	
	
     	$mailparams = array( "body" => $viewmail );

      	$params = array(
        	"subject"=> 'Oftalmoplus - Su primera visita',
        	"mailto" => $request->user,     
        	"template"=>'welcome'
     	);

	      // print_R($params);die();
	   	$email =  Mail::send('emails.blank', $mailparams , function($message) use ($params) {
	        $message->to($params['mailto'])
	          ->subject($params['subject'])
	          ->bcc('no-reply@oftalmoplus.com');
	
	    });
			
		
		error_log(json_encode(Mail::failures()));
		
		$this->html->email =$email;
		
		
		$this->wsprint(__LINE__,'ok',$this->html,false);
		

		
		//EMAIL 
		/*
		  
		Buenos días/tardes  Sr/Sra.….. Bienvenido a la App de Oftalmoplus, esta plataforma a sido creada para facilitar la relación entre el centro y el paciente. Hemos recibido su solicitud de visita, estamos procesando para adecuarnos a sus preferencias. Atentamente, 
		Equipo Oftalmoplus
		  
		  
		 */
  }

	public function getStateDay(){
		/* This sets the $time variable to the current hour in the 24 hour clock format */
	    $time = date("H");
	    /* Set the $timezone variable to become the current timezone */
	    $timezone = date("e");
	    /* If the time is less than 1200 hours, show good morning */
	    if ($time < "12") {
	        return "Buenos días";
	    } else
	    /* If the time is grater than or equal to 1200 hours, but less than 1700 hours, so good afternoon */
	    if ($time >= "12" && $time < "17") {
	        return "Buenas tardes";
	    } else
	    /* Should the time be between or equal to 1700 and 1900 hours, show good evening */
	    if ($time >= "17" && $time < "19") {
	        return "Buenas noches";
	    } else
	    /* Finally, show good night if the time is greater than or equal to 1900 hours */
	    if ($time >= "19") {
	        return "Buenas noches";
	    }
	}



   public function getPrimeraVisitaDatos(){
 	
		$centros = Centro::get();
		$this->html->centros =$centros;
		
		$facultativos = Facultativo::get();
		$this->html->facultativos =$facultativos;
		
		$tiposvisita = TipoVisita::get();
		$this->html->tiposvisita =$tiposvisita;
		
		$this->wsprint(__LINE__,'ok',$this->html,false);
   }
 
   public function getCentroVisita(){
   		$centros = Centro::get();
		$this->html->centros =$centros;
		
		$this->wsprint(__LINE__,'ok',$this->html,false);
		
   	
   }

   public function getFacultativosVisita(){
  	 	$facultativos = Facultativo::get();
		$this->html->facultativos =$facultativos;
		
		$this->wsprint(__LINE__,'ok',$this->html,false);
		
	
   }
   public function getTiposVisita(){
		
		$tiposvisita = TipoVisita::get();
		$this->html->tiposvisita =$tiposvisita;
		
		$this->wsprint(__LINE__,'ok',$this->html,false);
		
	}
	public function setVisita(){
		
		$request = $this->html->request;
		
		//solo puede tener 1 visita
		$remvisits = Visitas::where('user','=',$this->html->user->id)->update(['visible' => false]);
		
		$visita = new Visitas();
		$visita->user = $this->html->user->id;
		$visita->note = "Visita programada desde app para ".$this->html->user->first_name.' '.$this->html->user->last_name.' ('.$this->html->user->email.')';
	//	$visita->visita_date = $request->date;
		$visita->tipo = $request->tipo;
		$visita->primera_visita =false;
		$visita->urgente =$request->urgente;
		$visita->horario =$request->has('horario')?$request->horario:null;
		$visita->dia_semana =$request->has('dia_semana')?$request->dia_semana:null;
		$visita->centro =$request->has('centro')?$request->centro:null;
		$visita->facultativo =$request->has('facultativo')?$request->facultativo:null;
		$visita->visible =true;
		$visita->save();

		$this->html->visita = $visita;
		
		$this->wsprint(__LINE__,'ok',$this->html,false);
		
		//EMAIL 
		
		/*
		Buenos días/tardes  Sr/Sra.….. hemos recibido su solicitud de visita, estamos procesando para adecuarnos a sus preferencias. 
		Atentamente, 
		Equipo Oftalmoplus
		 */
		
		
		
		/*
		Buenos días/tardes  Sr/Sra.….. hemos recibido su solicitud de cambio de visita, en breve nos pondremos en contacto para ofrecer otro día y hora.
		Atentamente, 
		Equipo Oftalmoplus 
		 
		 * */
	}


	public function getProfile(){
		
		
		
		/*
		$visita = Visitas::where('user','=',$this->html->user->id)->where('visible','=',true)->whereRaw('Date(visita_date) >= CURDATE()')->orderBy('visita_date','ASC');
		$nexvisita = $visita->first();
		
		if($nexvisita){
			$nexvisita->date= strftime("%A",strtotime($nexvisita->visita_date))."&nbsp;&nbsp;".date("d",strtotime($nexvisita->visita_date)).'&nbsp;de&nbsp;'.strftime("%B",strtotime($nexvisita->visita_date));
			$nexvisita->hour= strftime("%R",strtotime($nexvisita->visita_date)).'h';
			$this->html->next_visita =$nexvisita ;
			
			
		}
		*/
		
		
		$visita = Visitas::select('visitas.id',DB::Raw('facultativo.nombre as facultativo'),DB::Raw('centro.nombre as centro'),'dia_semana','horario','note')
		->join('facultativo','visitas.facultativo','=','facultativo.id')
		->join('centro','visitas.centro','=','centro.id')
		->where('user','=',$this->html->user->id)->where('visible','=',true);
		$nexvisita = $visita->first();
		
		
		if($nexvisita){
			
				$ardays=[1=>"Lunes",2=>"Martes",3=>"Mierecoles",4=>"Jueves",5=>"Viernes"];
				$nexvisita->date=$ardays[$nexvisita->dia_semana] ;
				$nexvisita->hour= $nexvisita->horario==1?'Tarde':'Mañana';
				$this->html->next_visita =$nexvisita ;
			
			
			
			
		}
		//mañana tardes
		
		$this->html->visitas = $visita->get();		
		
		$disabledays = array();
		$disabledays_all = array();
		
		//obtenemos los dias/horas bloqueados para las visitas
		$visitasdays = Visitas::where('visible','=',true)->whereRaw('Date(visita_date) >= CURDATE()')->orderBy('visita_date','ASC')->get();
		foreach($visitasdays as $v){
			if($v->visita_date){
				$disabledays[]= $v->visita_date;				
			}
		}
		
		//obtenemos de la basde de datos los dias bloqueados
		$disabledaysdb = DisableDays::get();
		foreach ($disabledaysdb as $day) {
			if($day->allday){
				$disabledays_all[] = $day->date;
			}else{
				$disablehours = DisableDaysHours::where('disableday_id','=',$day->id)->get();
				foreach ($disablehours as $key => $hour) {							
					$createDate = new DateTime($day->date);
					$strip = $createDate->format('Y-m-d'); 
					$realdatehour = $strip.' '.$hour->hour.':00';
					$disabledays[]=$realdatehour;
				}
			}
		}
		$this->html->disabledays_all=$disabledays_all;
		$this->html->disabledays=$disabledays;
		
		$tdocu= TipoDocumentacion::get();
		
		$documents = Descargas::where('user','=',$this->html->user->id)->where('visible','=',true);
		$this->html->documents = $documents->get();		
		//echo json_encode($documents);

		$tipodocu = array();
		$tdocu= TipoDocumentacion::get();
		foreach ($tdocu as $key => $value) {
			$documentst = Descargas::where('user','=',$this->html->user->id)->where('visible','=',true)->where('tipo','=',$value->id)->get();
			$value->docs = $documentst;
			$tipodocu[]=$value;
		}
		
		$this->html->tipodocu = $tipodocu;	
		
		

	
		
		
		$this->wsprint(__LINE__,'ok',$this->html,false);
		
		
	}

	public function getHome(Request $request=null){
		
	    if(isset($_SERVER['HTTP_ORIGIN']))
	      $http_origin = $_SERVER['HTTP_ORIGIN'];
	    else
	    $http_origin = '*';
	    $this->html->extraheader1 = 'Access-Control-Allow-Origin: '.$http_origin;
	    //$this->html->extraheader1 = 'Access-Control-Allow-Origin: http://localhost:3000';
	    //$this->html->extraheader1 = 'Access-Control-Allow-Origin: *';
	    $this->html->extraheader2 = 'Access-Control-Allow-Credentials: true';
		
		
		
		$configul = VisualConfig::where('type','=','ul')->first();	
		if($configul){
			$this->html->ul = $configul;
		}
		
		$configur = VisualConfig::where('type','=','ur')->first();	
		if($configur){
			$this->html->ur = $configur;
		}
		
		$this->html->centros = Centro::get();
		$this->html->facultativo = Facultativo::get();
		$this->html->tipoVisita = TipoVisita::get();
		
		
		$this->html->box = array();
		$configbox = VisualConfig::where('type','=','box')->get();	
		if($configbox){
			$this->html->box = $configbox;
		}
		
		$configtm = VisualConfig::where('type','=','tm')->first();	
		if($configtm){
			$this->html->tm = $configtm;
		}
		
		
	 	$this->wsprint(__LINE__,'ok',$this->html,false);
	}
	

 


  //Funcion de login, donde se establece el token
  public function login(Request $request=null){
    if(isset($_SERVER['HTTP_ORIGIN']))
      $http_origin = $_SERVER['HTTP_ORIGIN'];
    else
    $http_origin = '*';
    $this->html->extraheader1 = 'Access-Control-Allow-Origin: '.$http_origin;
    //$this->html->extraheader1 = 'Access-Control-Allow-Origin: http://localhost:3000';
    //$this->html->extraheader1 = 'Access-Control-Allow-Origin: *';
    $this->html->extraheader2 = 'Access-Control-Allow-Credentials: true';



    if(is_null($request)){
      $this->wsprint(-__LINE__,'Invalid request',false);
    }

    $email = $request->input('email');
    $passw = $request->input('passw');

    if(is_null($email) || is_null($passw)){
      $this->wsprint(-__LINE__,'Usuario o contraseña no válidos',false);
    }

    //si hemos llegado hasta aqui, puede ser que haya login/pass correcto, comprobemoslo
    $credentials = [
      'email'    => trim($email),
      'password' => trim($passw),
    ];

    try{
      $user = Sentinel::authenticate($credentials);
    }catch(\Cartalyst\Sentinel\Checkpoints\NotActivatedException $e){
      $this->wsprint(-__LINE__,'Usuario no activado. Si acabas de registrarte, mira tu email para validar tu cuenta.',false);
    }

    if($user===false){
      $tmp_user = \App\User::where('email','LIKE',$email)->first();
      if(is_null($tmp_user)){
        $this->wsprint(-__LINE__,'El email que has utilizado ('.$email.') no está registrado en Oftalmoplus.',false);
      }else{
        $this->wsprint(-__LINE__,'La contraseña no es correcta, inténtalo de nuevo.',false);
      }
    }

    if(is_null($user)){
      $this->wsprint(-__LINE__,'No existe la cuenta de usuario ('.__LINE__.').',false);
    }elseif($user->inRole('client')){
      $user->app_token = md5($user->id.time().$user->email);
      $user->save();
      $output = array(
        'id' => $user->id,
        'token' => $user->app_token,
        'user' => $user->toArray()
      );
      $this->wsprint(__LINE__,'Bienvenido '.$user->email,$output);
    }else{
      $this->wsprint(-__LINE__,'El administrador no puede acceder a la app como paciente.',false);
    }


    $this->wsprint(-__LINE__, 'No has podido entrar', false);
  }


  private function base64_to_jpeg_mobile($base64_string, $output_file) {
    $ifp = fopen( $output_file, "wb" );
    fwrite( $ifp, base64_decode( $base64_string) );
    fclose( $ifp );
    return( $output_file );
  }

 	
  public function setPushId(){
    $push_id_user = (null!==$this->html->request->input('push_id_user'))?trim($this->html->request->input('push_id_user')):null;
    $push_id_onesignal = (null!==$this->html->request->input('push_id_onesignal'))?trim($this->html->request->input('push_id_onesignal')):null;
    $push_token_onesignal = (null!==$this->html->request->input('push_token_onesignal'))?trim($this->html->request->input('push_token_onesignal')):null;

    $this->html->user->push_id_user = $push_id_user;
    $this->html->user->push_id_onesignal = $push_id_onesignal;
    $this->html->user->push_token_onesignal = $push_token_onesignal;
    $this->html->user->save();

    $this->wsprint(__LINE__,'ok',$this->html->user->toArray());
  }
 


  public function logout(){
    $this->html->user->app_token = null;
    $this->html->user->push_id_user = null;
    $this->html->user->push_id_onesignal = null;
    $this->html->user->push_token_onesignal = null;
    $ret = $this->html->user->save();
    if($ret){
      $this->wsprint(__LINE__, 'La sesión se ha cerrado correctamente', true);
    }else{
      $this->wsprint(-__LINE__, 'No es posible cerrar la sesión', false);
    }
  }



	public function pushMessageTest($headings = null,  $content = null, $player_ids = null){
	    //titulo
	    if(is_null($headings)){
	      $headings = array (
	        "en" => "Hey!",
	        "es" => "Oye!",
	      );
	    }
	
	    if(is_null($content)){
	      //texto/descripcion
	      $content = array(
	        "en" => 'Have you seen our latest products?',
	        "es" => '¿Has comprobado tu próxima visita?',
	      );
	    }
	
	    if(is_null($player_ids)){
	      //recipientes que reciben el mensaje
	      $player_ids =  array(
	         'b7231a31-2717-4f8f-b2c0-bcc1a9dae29a', //raul
	       );
	    }
	
		$fields = array(
		   	  'app_id' => "907065bd-f95b-4aec-9f57-5feff4ed3d3d", //app_id de onesignal para highcloset
		      'include_player_ids' => $player_ids,
			  'contents' => $content,
		      'headings' => $headings,
		      'ios_badgeType' => 'Increase',
		      'ios_badgeCount' => '1',
		);
	
	
		$fields = json_encode($fields);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Authorization: Basic YzljMGI1MGEtNGRiMy00OGExLWFhNjItZDU3YjU5MjEzZWEy'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	
		$response = curl_exec($ch);
		curl_close($ch);
		
		$this->wsprint(__LINE__,'ok',$response);
	
		
	}

}
