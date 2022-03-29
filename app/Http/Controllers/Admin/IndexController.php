<?php namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

//Clases para sentinel
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Database\Capsule\Manager as Capsule;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use DB;

use App\User;
use App\Stands;
use App\Comptatges;

//Clase Mail
//use Mail; //de momento no la necesitamos. Descomentar cuando sea necesario

class IndexController extends Controller {
    //
    function __construct() {
      $this->html = new \stdClass();
      $this->html->user = Sentinel::getUser();
    }

    public function index(){
    
		if(!$this->html->user){
			return redirect()->to('/');
		}
		if (!$this->html->user->inRole('admin') && !$this->html->user->inRole('user')){
			return redirect()->to('/');
		
		
		}
		
		$this->seed();
		$this->html->stands = Stands::get();
      	return view('admin.index')
        ->with('html', $this->html);
		
    }
	
	
    public function products(){
    
	
      	return view('admin.products')
        ->with('html', $this->html);
    }
	
    public function general(){
    
	
      	return view('admin.general')
        ->with('html', $this->html);
    }
    public function visitesconfig(){
    
	
      	return view('admin.visitesconfig')
        ->with('html', $this->html);
    }

 
	
	public function seed(){
		$stands = Stands::get();
		foreach ($stands as $key => $stand) {
			
			$comptatge = Comptatges::where('id_stand',$stand->id)->where('created_at','=',date('Y-m-d 00:00:00'))->first();
			
			if(!$comptatge){
				$compatge = new Comptatges();
				$compatge->id_stand = $stand->id;
				$compatge->repite = 0;
				$compatge->pass = 0;
				$compatge->save();
				$compatge->update(['created_at'=>date("Y-m-d 00:00:00")]); 
			}
			

		}
	}


	public function seedNumber(){
		$stands = Stands::get();
		foreach ($stands as $key => $stand) {
			
			for ($i=0; $i <=10 ; $i++) { 
				
				$compatge = new Comptatges();
				$compatge->id_stand = $stand->id;
				$compatge->repite = 0;
				$compatge->pass = 0;
				$compatge->soci = 0;
				$compatge->edat = 12;
				$compatge->save();
				//$compatge->update(['created_at'=>date("Y-m-d 00:00:00")]); 
				
			}
			for ($i=0; $i <=10 ; $i++) { 
				
				$compatge = new Comptatges();
				$compatge->id_stand = $stand->id;
				$compatge->repite = 1;
				$compatge->pass = 0;
				$compatge->soci = 0;
				$compatge->edat = 12;
				$compatge->save();
				//$compatge->update(['created_at'=>date("Y-m-d 00:00:00")]); 
				
			}
			for ($i=0; $i <=10 ; $i++) { 
				
				$compatge = new Comptatges();
				$compatge->id_stand = $stand->id;
				$compatge->repite = 0;
				$compatge->pass = 1;
				$compatge->soci = 0;
				$compatge->edat = 12;
				$compatge->save();
				//$compatge->update(['created_at'=>date("Y-m-d 00:00:00")]); 
				
			}
			for ($i=0; $i <=10 ; $i++) { 
				
				$compatge = new Comptatges();
				$compatge->id_stand = $stand->id;
				$compatge->repite = 0;
				$compatge->pass = 0;
				$compatge->soci = 1;
				$compatge->edat = 12;
				$compatge->save();
				//$compatge->update(['created_at'=>date("Y-m-d 00:00:00")]); 
				
			}

		}
	}
	


}
