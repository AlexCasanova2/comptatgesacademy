<?php namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

//Clases para sentinel
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Database\Capsule\Manager as Capsule;
use Cartalyst\Sentinel\Laravel\Facades\Activation;

//use Mail;
use App\Http\Controllers\MailController;

class PushController extends Controller {

  public static function push_custom_message_to_all_users($title=null, $message=null){
    if(is_null($title) || is_null($message)) return;

    $headings = array(
      'en' => $title,
      'es' => $title,
    );
    $content = array(
      "en" => $message,
      "es" => $message,
    );


    $app_users = \App\User::where('push_id_onesignal','<>',null)
                                ->orderBy('id','desc')
                                ->get();
    $player_ids = array();
    $push_results = array();
    foreach ($app_users as $user){
      if($_SERVER['SERVER_NAME'] === 'oftalmoplus.bitmad.net'){
        if($user->push_id_onesignal == 'b7231a31-2717-4f8f-b2c0-bcc1a9dae29a'){
          $player_ids = array('b7231a31-2717-4f8f-b2c0-bcc1a9dae29a');
        }else{
          //$player_ids = array('invalidd-8ae1-42b2-93b7-686480af2487');
        }
      }else{
        $player_ids = array($user->push_id_onesignal);
      }
      // $player_ids = array($user->push_id_onesignal);
      //forzamos el envío sólo a mi: f74fce2f-8ae1-42b2-93b7-686480af2487
      $push_results[] = \App\Http\Controllers\Mobile\PushController::sendMessage($headings, $content, $player_ids);
    }

    $contador = 0;
    foreach($push_results as $result){
      $json_result = json_decode($result);
      if(isset($json_result->recipients) && intval($json_result->recipients)>0){
        $contador++;
      }
    }
    return $contador;
  }

 

  public static function sendMessage($headings = null,  $content = null, $player_ids = null,$attach=''){
    if(is_null($headings)){
      $headings = array (
        "en" => "Hey!",
        "es" => "Oye!",
      );
    }

    if(is_null($content)){
      $content = array(
        "en" => 'Api check',
        "es" => 'Prueba de api',
      );
    }

    if(is_null($player_ids)){
      $player_ids =  array(
           'b7231a31-2717-4f8f-b2c0-bcc1a9dae29a', //raul
         );
    }


	$fields = array(
	   	  'app_id' => "907065bd-f95b-4aec-9f57-5feff4ed3d3d", //app_id de onesignal para oftalmpplus
	      'include_player_ids' => $player_ids,
		  'contents' => $content,
	      'headings' => $headings,
	      'big_picture'=>$attach,
	      'ios_badgeType' => 'Increase',
	      'ios_badgeCount' => '1',
	);

		$fields = json_encode($fields);
    //print("\nJSON sent:\n");
    //print($fields);

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

		return $response;
	}

}
