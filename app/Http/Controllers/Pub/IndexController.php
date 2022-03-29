<?php namespace App\Http\Controllers\Pub;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

//Clases para sentinel
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Database\Capsule\Manager as Capsule;
use Cartalyst\Sentinel\Laravel\Facades\Activation;

use App\User;


//Clase Mail
//use Mail; //de momento no la necesitamos. Descomentar cuando sea necesario
use Mail;

class IndexController extends Controller {
    //
    function __construct() {
      $this->html = new \stdClass();
      $this->html->user = Sentinel::getUser();
    }


	public function index(){
		
      return view('public.index')
        ->with('html', $this->html);
    }
	
	

    public function login(Request $request, $error_fields=array(),$message=""){
    
	if($this->html->user){
		
		if ($this->html->user->inRole('admin') || $this->html->user->inRole('user')){			
			return redirect()->to('/admin');
		}

	}
	
	 
      //Si nos han pasado algun error por parametro, lo enviamos a $this->html, para que acabe en la vista.
      $this->html->error_fields = $error_fields;
      $this->html->request = $request;
	 // die(json_encode( $request->all()));

      //devolvemos la vista con $this->html
      return view('public.index')
        ->with('html', $this->html);
    }


	public function terms(){
		return view('users.terms');
	}


    public function logout(){
      	$this->html->error_fields = array('logout');    	
    	Sentinel::logout();
      	$this->html->user=null;
      	return redirect()->to('/');
    }
	
	
	// public function registerLogin($user,$type){
		// if($user->inRole('embajador') || $user->inRole('cliente')  ){
			// $logins = new Logins();
			// $logins->user = $user->id;
			// $logins->type = $type;
			// $logins->save();
		// }
// 		
	// }

    //
    public function tryLogin(Request $request){
		
      //Variable donde guardaremos los campos erroneos si los hay
      $error_fields = array();

      //Obtenemos el POST del formulario
      $input = $request->all();

      //Hacemos unas comprobaciones básicas, que haya email y passw, y que no esté vacío
      if(!isset($input['email']) || trim($input['email'])=="") $error_fields[] = 'email';
      if(!isset($input['passw']) || trim($input['passw'])=="") $error_fields[] = 'passw';

      //Si no hay errores aparentes, intentamos hacer login.
      if(count($error_fields)<=0){
	
		
        
        
        $credentials = [
          'email'    => trim($input['email']),
          'password' => trim($input['passw']),
        ];



		
        $user = Sentinel::authenticate($credentials);
        if($user===false){
          $error_fields[] = 'email';
          $error_fields[] = 'passw';
          return $this->login($request, $error_fields);
        }

        if($user->inRole('admin') || $user->inRole('user') ){
          return redirect('/admin');
        }else{
          $error_fields[] = 'email';
          $error_fields[] = 'passw';
		  
          return $this->login($request, $error_fields);
        }


      }else{ // Si hay algun error aparente, vamos a login (el del get) y añadimos los errores
        return $this->login($request, $error_fields);
      }
    }
}
