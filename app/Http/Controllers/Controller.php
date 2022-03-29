<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

use Illuminate\Http\Request;

//Clases para sentinel
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Database\Capsule\Manager as Capsule;
use Cartalyst\Sentinel\Laravel\Facades\Activation;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	
	
	 function __construct(Request $request=null) {
	 	
		
	 	$this->html = new \stdClass();
	    try{
	        $this->html->user = Sentinel::getUser();
	      }catch(\Cartalyst\Sentinel\Checkpoints\NotActivatedException $e){
	        $this->html->user = null;
	      }
	
	      $this->html->request = $request;
	 }
	 
	/**
    * Generates the json output.
    *
    * @return string/json
    */
    public function wsprint($status=-1, $message=null, $output=false, $debug=false) {
      //

      header('Content-Type: application/json');
      if(isset($this->html->extraheader1)){
        header($this->html->extraheader1);
      }
      if(isset($this->html->extraheader2)){
        header($this->html->extraheader2);
      }

     

      echo json_encode( array(
                            'STATUS' => $status,
                            'MESSAGE' => $message,
                            // 'DEBUG' => $debug?$debug:null,
                            'OUTPUT' => $output,
                            )
                      );
      die();
    }
	
	  function base64_to_jpeg($base64_string, $output_file) {
	    $ifp = fopen($output_file, "wb"); 	
	    $data = explode(',', $base64_string);	
	    fwrite($ifp, base64_decode($data[1])); 
	    fclose($ifp); 	
	    return $output_file; 
	}	
	  
}
