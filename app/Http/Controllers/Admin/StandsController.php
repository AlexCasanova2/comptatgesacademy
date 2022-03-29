<?php namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

//Clases para sentinel
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Database\Capsule\Manager as Capsule;
use Cartalyst\Sentinel\Laravel\Facades\Activation;

use Yajra\Datatables\Facades\Datatables;

use DB;

use App\User;
use App\Stands;
use App\Comptatges;

//Clase Mail
//use Mail; //de momento no la necesitamos. Descomentar cuando sea necesario

class StandsController extends Controller {
  

    public function index(){
    	
		
		if(!$this->html->user){
			return redirect()->to('/');
		}
		if (!$this->html->user->inRole('admin')){
			return redirect()->to('/admin');
		}
	 	$this->html->stands = Stands::get();
	
	
      	return view('admin.stands')
        ->with('html', $this->html);
    }
	
	
	
	public function statics(){
		
		if(!$this->html->user){
			return redirect()->to('/');
		}
		if (!$this->html->user->inRole('admin')){
			return redirect()->to('/admin');
		}
		
		return view('admin.statics')
        ->with('html', $this->html);
	}

	public function getStatistics(){
		
		$this->html->stands = Stands::get();
		$daysExcel = array();
		$horas = array(11, 12, 13, 14, 15, 16 , 17, 18);
		

		$data = json_decode(json_encode(Stands::getGraella(), true), true);

		foreach($data as $pos => $properties){
			$daysExcel[$properties['dia']][$properties['id'].'_'.$properties['nom']][$properties['hora']] = array(
				'news' => $properties['news']
				, 'repeat' => $properties['repeat']
				, 'pass' => $properties['pass']
				, 'soci' => $properties['soci']
			);
		}


		foreach($daysExcel as $date => $properties){
			foreach($properties as $stand => $hours){
				foreach ($hours as $hour => $comptatges){
					foreach ($horas as $hora){
						if (!isset($daysExcel[$date][$stand][$hora])){
							$daysExcel[$date][$stand][$hora] = array(
								'news' => 0
								, 'repeat' => 0
								, 'pass' => 0
								, 'soci' => 0
							);
						}
					}
				}
			}
			
		}
		return json_encode($daysExcel);
	}

	public function getGraphs(){
		$graphTotal = $getGrapPerhour = $getGraphPerAge = array();
		$standsTotal = json_decode(json_encode(Stands::getStandTotal(), true), true);
		$standsPerHour = json_decode(json_encode(Stands::getStandPerHour(), true), true);
		$standsPerAge = json_decode(json_encode(Stands::getStandPerAge(), true), true);
		foreach($standsTotal as $pos=>$array){
			$graphTotal[$array['nom']] = $array['total'];
		}
		foreach($standsPerHour as $pos=>$array){
			$getGrapPerhour[$array['nom']][$array['hora']] = $array['total'];
		}
		foreach($standsPerAge as $pos=>$array){
			$getGraphPerAge[$array['nom']]['<6'] = !empty($array['<6']) ? round(($array['<6']/$array['total'])*100, 2) : 0;
			$getGraphPerAge[$array['nom']]['6-12'] = !empty($array['6-12']) ? round(($array['6-12']/$array['total'])*100, 2) : 0;
			$getGraphPerAge[$array['nom']]['13-19'] = !empty($array['13-19']) ? round(($array['13-19']/$array['total'])*100, 2) : 0;
			$getGraphPerAge[$array['nom']]['>19'] = !empty($array['>19']) ? round(($array['>19']/$array['total'])*100, 2) : 0;
		}
		echo json_encode(array('totalGraph'=>$graphTotal, 'hourGraph'=>$getGrapPerhour, 'ageGraph'=>$getGraphPerAge));
	}

	public function comptatge(){
		$request = $this->html->request;	
		$output = array("SUCCESS"=>"ERROR");
		
		
		if($request->more){
				
			$compatge = new Comptatges();
			$compatge->id_stand = $request->id;
			$compatge->repite = $request->repeat;
			$compatge->pass = $request->pass;
			$compatge->soci = $request->soci;
			$compatge->edat = $request->edat;
			$compatge->save();
			
			$output = array("SUCCESS"=>"OK","MORE"=>true);	
			
		}else{
			$comtatge = Comptatges::where('id_stand','=',$request->id)->where('repite','=', $request->repeat)->where('pass','=', $request->pass)->where('soci','=', $request->soci)->where('edat','=', $request->edat)->first();
			$comtatge->delete();
			$output = array("SUCCESS"=>"OK","MORE"=>false);	 
		}
		//$comptatges_today = Comptatges::whereRaw('Date(created_at) = CURDATE()')->get();
		
		return $output;
		
	}
	
	
	public function removeBox($id){
		$request = $this->html->request;	
		$output = array("SUCCESS"=>"ERROR");
		$box = \App\Stands::find($id);
		error_log($id);
		if($box){
			// $destinationPath= base_path()."/public/images/product/".$box->id."/";
			// File::deleteDirectory(public_path($destinationPath));
			$ret = $box->delete();			
			if($ret){
				$output = array("SUCCESS"=>"OK","TOKEN"=>$request['_token']);	
			}			
		}
		
		return $output;
	}

	
	public function stand($id){
		
		setlocale(LC_TIME, 'ca_ES.utf8');
		
		$stand = Stands::find($id);
		$this->html->stand = $stand;
		
		return view('admin.stand')
        ->with('html', $this->html);
	}
	
	public function standbyday($id,$day,$month,$year,$hour){
		
		setlocale(LC_TIME, 'ca_ES.utf8');
		
		$stand = Stands::find($id);
		$this->html->stand = $stand;
		$this->html->month = $month;
		$this->html->day = $day;
		$this->html->hour = $hour;
		$this->html->year = $year;
		
		
		return view('admin.standedit')
        ->with('html', $this->html);
	}
	

	public function comptatgebyday(){
		$request = $this->html->request;	
		$output = array("SUCCESS"=>"ERROR");
		
		
		$comtatges = Comptatges::where('id_stand','=',$request->id)->
		where('repite','=', $request->repeat)->
		where('pass','=', $request->pass)->
		where('soci','=', $request->soci)->
		where('edat','=', $request->edat)->
		whereRaw('YEAR(created_at) = '.$request->year)->
		whereRaw('MONTH(created_at) = '.$request->month)->
		whereRaw('DAY(created_at) = '.$request->day)->
		whereRaw('HOUR(created_at) = '.$request->hour)->
		get();
		
		// if($request->number==0){
			// $comtatges->delete();
		// }else{
// 			
			for ($i=0; $i < $request->number; $i++) {
				 
				$compatge = new Comptatges();
				$compatge->id_stand = $request->id;
				$compatge->repite = $request->repeat;
				$compatge->pass = $request->pass;
				$compatge->soci = $request->soci;
				$compatge->edat = $request->edat;
				$compatge->save();
				
				$compatge->update(['created_at'=>date($request->year."-".$request->month."-".$request->day." ".$request->hour.":00:00")]); 
			}
// 			
		// }
				
		
		$output = array("SUCCESS"=>"OK","RESOL"=>$comtatges);	
			
		
	
		
		return $output;
		
	}
	
	
	public function insert(){
	
	
		
		$request = $this->html->request;	
		
		
		//Comprueba que estes en admin
		if (!$request->is('admin/*')) {
			die('Path incorrecto');
		}
		if (!$request->isMethod('post')) {
    			die('Método incorrecto');
		}

		$product = new Stands();
		if($request->id!=0){
			$product = Stands::find($request->id);
		}
	
        
	
		
		$product->nom = $request->name;

		
		$product->save();
		
		
		session()->flash('message', 'Stand inserit');
		return redirect()->to('/admin/stands');
		
	}
	
	public function getProduct($id){
		
		return Stands::find($id);
	}

	public function datatables(){	
	

	    $select = array('id','nom');
		$query = Stands::select($select);
		
	    return Datatables::of($query)
		->editColumn('id','#{{$id}}')
		->editColumn('nom','{{$nom}}')
		
		->addColumn('options',
		'<a  href="stand/{{$id}}" ><i class="fas fa-edit"></i></a> &nbsp;&nbsp;		
		<a href="javascript:;" onClick="removeBox({{$id}},\'{{$nom}}\')" ><i class="fas fa-trash"></i></a>')
	  	->removeColumn('year')
	    ->escapeColumns(['options'])
	    ->make(false);
	}  
	
	public function datatableStatics(){	
	

	    
		$query = Stands::getDaysAll();
		
	    return Datatables::of($query)
		->editColumn('stand','{{App\Stands::find($stand)->nom}}')
	    ->make(false);
	} 

}
