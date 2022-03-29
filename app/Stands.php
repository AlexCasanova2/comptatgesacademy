<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class Stands extends Model
{
    protected $table = 'stands';
	
	public function countTodayAll(){
		return Comptatges::whereRaw('Date(created_at) = CURDATE()')
		->where('id_stand','=',$this->id)
		->whereRaw('HOUR(created_at) >= 11')
		->whereRaw('HOUR(created_at) <= 19')
		->count();
	}
	
	public function countToday(){
		return Comptatges::whereRaw('Date(created_at) = CURDATE()')
		->where('id_stand','=',$this->id)
		->where('repite','=',0)
		->where('pass','=',0)
		->where('soci','=',0)
		->whereRaw('HOUR(created_at) >= 11')
		->whereRaw('HOUR(created_at) <= 19')
		->count();
	}
	
	public function countTodayRepeat(){
		return Comptatges::whereRaw('Date(created_at) = CURDATE()')
		->where('id_stand','=',$this->id)
		->where('repite','=',1)
		->whereRaw('HOUR(created_at) >= 11')
		->whereRaw('HOUR(created_at) <= 19')
		->count();
	}
	
	public function countTodayPass(){
		return Comptatges::whereRaw('Date(created_at) = CURDATE()')
		->where('id_stand','=',$this->id)
		->where('pass','=',1)
		->whereRaw('HOUR(created_at) >= 11')
		->whereRaw('HOUR(created_at) <= 19')
		->count();
	}

	public function countTodaySoci(){
		return Comptatges::whereRaw('Date(created_at) = CURDATE()')
		->where('id_stand','=',$this->id)
		->where('soci','=',1)
		->whereRaw('HOUR(created_at) >= 11')
		->whereRaw('HOUR(created_at) <= 19')
		->count();
	}
	
	///
	
	public function countTodayEdit($year,$month,$day,$hour){
		return Comptatges::where('id_stand','=',$this->id)
		->where('repite','=',0)
		->where('pass','=',0)
		->where('soci','=',0)->
		whereRaw('YEAR(created_at) = '.$year)->
		whereRaw('MONTH(created_at) = '.$month)->
		whereRaw('DAY(created_at) = '.$day)->
		whereRaw('HOUR(created_at) = '.$hour)->
		count();
	}
	
	public function countTodayRepeatEdit($year,$month,$day,$hour){
		return Comptatges::where('id_stand','=',$this->id)
		->where('repite','=',1)->
		whereRaw('YEAR(created_at) = '.$year)->
		whereRaw('MONTH(created_at) = '.$month)->
		whereRaw('DAY(created_at) = '.$day)->
		whereRaw('HOUR(created_at) = '.$hour)->
		count();
	}
	
	public function countTodayPassEdit($year,$month,$day,$hour){
		return Comptatges::where('id_stand','=',$this->id)
		->where('pass','=',1)->
		whereRaw('YEAR(created_at) = '.$year)->
		whereRaw('MONTH(created_at) = '.$month)->
		whereRaw('DAY(created_at) = '.$day)->
		whereRaw('HOUR(created_at) = '.$hour)->
		count();
	}

	public function countTodaySociEdit($year,$month,$day,$hour){
		return Comptatges::where('id_stand','=',$this->id)
		->where('soci','=',1)->
		whereRaw('YEAR(created_at) = '.$year)->
		whereRaw('MONTH(created_at) = '.$month)->
		whereRaw('DAY(created_at) = '.$day)->
		whereRaw('HOUR(created_at) = '.$hour)->
		count();
		
	}
	
	///
	
	public function getTotals(){
		return Comptatges::where('id_stand','=',$this->id)->count();
		
	}
	
	public function countTotalsepeat(){
		return Comptatges::where('id_stand','=',$this->id)->where('repite','=',1)->count();
	}
	
	public function countTotalsPass(){
		return Comptatges::where('id_stand','=',$this->id)->where('pass','=',1)->count();
	}
	public function countTotalsSoci(){
		return Comptatges::where('id_stand','=',$this->id)->where('soci','=',1)->count();
	}
	
	
	public function getDays(){
			
		return Comptatges::select(DB::raw('CONCAT(DAY(created_at),'/',MONTH(created_at),'/',YEAR(created_at)) as date, count(*) as total, id_stand as stand'))
		->where('id_stand','=',$this->id)
		->groupBy(DB::raw('YEAR(created_at), MONTH(created_at), DAY(created_at)'));
	}
	
	public function getHours(){
			
		return Comptatges::select(DB::raw("CONCAT(DAY(created_at),'/',MONTH(created_at),'/',YEAR(created_at)) as date, HOUR(created_at) as hour, count(*) as total, id_stand as stand"))
		->where('id_stand','=',$this->id)
		->where('date','=',$date)
		->where('hour','=',$hour)
		->groupBy(DB::raw('YEAR(created_at), MONTH(created_at), DAY(created_at), HOUR(created_at)'));
	}
	

	
	
	public function getNewsbyHour($date,$hour){
			
		$ret= Comptatges::select(DB::raw("CONCAT(DAY(created_at),'/',MONTH(created_at),'/',YEAR(created_at)) as date, HOUR(created_at) as hour, count(*) as total, id_stand as stand"))
		->where('id_stand','=',$this->id)
		->where('repite','=',0)->where('pass','=',0)->where('soci','=',0)
		->groupBy(DB::raw('YEAR(created_at), MONTH(created_at), DAY(created_at), HOUR(created_at)'))
		->having('date','=',$date)
		->having('hour','=',$hour)->first();
		
		return $ret?$ret->total:0;
	}
	

	public function getRepeatbyHour($date,$hour){
			
		$ret=  Comptatges::select(DB::raw("CONCAT(DAY(created_at),'/',MONTH(created_at),'/',YEAR(created_at)) as date, HOUR(created_at) as hour, count(*) as total, id_stand as stand"))
		->where('id_stand','=',$this->id)
		->where('repite','=',1)->where('pass','=',0)->where('soci','=',0)
		->groupBy(DB::raw('YEAR(created_at), MONTH(created_at), DAY(created_at), HOUR(created_at)'))
		->having('date','=',$date)
		->having('hour','=',$hour)->first();
		
		return $ret?$ret->total:0;
	}
	
	public function getPassbyHour($date,$hour){
			
		$ret=  Comptatges::select(DB::raw("CONCAT(DAY(created_at),'/',MONTH(created_at),'/',YEAR(created_at)) as date, HOUR(created_at) as hour, count(*) as total, id_stand as stand"))
		->where('id_stand','=',$this->id)
		->where('pass','=',1)
		->groupBy(DB::raw('YEAR(created_at), MONTH(created_at), DAY(created_at), HOUR(created_at)'))
		->having('date','=',$date)
		->having('hour','=',$hour)->first();
		
		return $ret?$ret->total:0;
	}

	public function getSocibyHour($date,$hour){
			
		$ret=  Comptatges::select(DB::raw("CONCAT(DAY(created_at),'/',MONTH(created_at),'/',YEAR(created_at)) as date, HOUR(created_at) as hour, count(*) as total, id_stand as stand"))
		->where('id_stand','=',$this->id)
		->where('soci','=',1)
		->groupBy(DB::raw('YEAR(created_at), MONTH(created_at), DAY(created_at), HOUR(created_at)'))
		->having('date','=',$date)
		->having('hour','=',$hour)->first();
		
		return $ret?$ret->total:0;
	}
	
	
	
	public static function getDaysAll(){
			
		return Comptatges::select(DB::raw(
		"CONCAT(DAY(created_at),'/',MONTH(created_at),'/',YEAR(created_at)) as data,  id_stand , count(pass) as total"
		))
		->groupBy(DB::raw('YEAR(created_at), MONTH(created_at), DAY(created_at), id_stand'))
		->orderBy('created_at','ASC')->orderBy('id','ASC');
		
	}
	
	
	
	public static function getHoursAll(){
			
		return Comptatges::select(
			DB::raw(
					"CONCAT(DAY(created_at),'/',MONTH(created_at),'/',YEAR(created_at)) as data
					, HOUR(created_at) as hour
					, id_stand as stand
					, count(*) as total"
			)
		)
		->whereRaw('HOUR(created_at) > 10')
		->whereRaw('HOUR(created_at) < 19')
		->groupBy(DB::raw('YEAR(created_at), MONTH(created_at), DAY(created_at), HOUR(created_at), stand'))
		->orderBy('created_at','ASC')->orderBy('id','ASC');
		
	}
	public static function getHoursAllNoFilter(){
			
		return Comptatges::select(
			DB::raw(
					"CONCAT(DAY(created_at),'/',MONTH(created_at),'/',YEAR(created_at)) as data
					, HOUR(created_at) as hour
					, id_stand as stand
					, count(*) as total"
			)
		)
	
		->groupBy(DB::raw('YEAR(created_at), MONTH(created_at), DAY(created_at), HOUR(created_at), stand'))
		->orderBy('created_at','ASC')->orderBy('id','ASC');
		
	}

	/**
	 * Consulta per obtenir la graella
	 */
	public static function getGraella(){
		return DB::select(
				"SELECT
					counts.dia
					, stands.nom
					, stands.id
					, counts.hora
					, SUM((CASE WHEN comptatges.id_stand IS NOT NULL AND comptatges.repite = 0 AND comptatges.pass = 0 AND comptatges.soci = 0 THEN 1 ELSE 0 END)) as news
					, SUM((CASE WHEN comptatges.id_stand IS NOT NULL AND comptatges.repite = 1 THEN 1 ELSE 0 END)) as 'repeat'
					, SUM((CASE WHEN comptatges.id_stand IS NOT NULL AND comptatges.pass = 1 THEN 1 ELSE 0 END)) as pass
					, SUM((CASE WHEN comptatges.id_stand IS NOT NULL AND comptatges.soci = 1 THEN 1 ELSE 0 END)) as soci
					
				FROM
					stands
					JOIN (
						SELECT DISTINCT	
							DATE_FORMAT(created_at, '%d/%m/%Y') as dia
							, HOUR(created_at) as hora
							, id     
							, created_at
						FROM
							comptatges
						WHERE
							HOUR(created_at) BETWEEN 11 AND 18
						ORDER BY 
							UNIX_TIMESTAMP(created_at) DESC
					) counts                                                        
					LEFT JOIN comptatges ON stands.id = comptatges.id_stand AND comptatges.id = counts.id
					GROUP BY
						counts.dia
						, stands.id
						, stands.nom
						, counts.hora"
		);
	}

	/**
	 * Consulta per obtenir el nombre total de comptatges d'un stand
	 */
	public static function getStandTotal(){
		return DB::select(
			"SELECT 
				stands.nom
				, SUM((CASE WHEN comptatges.id_stand IS NOT NULL THEN 1 ELSE 0 END)) as total
			FROM
				stands
				JOIN (
					SELECT DISTINCT	
						DAY(created_at) as dia
						, id
					FROM
						comptatges
					WHERE
						HOUR(created_at) BETWEEN 11 AND 18
				) counts                                                        
			LEFT JOIN comptatges ON stands.id = comptatges.id_stand AND comptatges.id = counts.id
			GROUP BY
				stands.id
				, stands.nom
			ORDER BY	
				stands.id"
		);
	}
	/**
	 * Consulta per obtenir l'afluencia de stands per hora
	 */
	public static function getStandPerHour(){
		return DB::select(
			"SELECT 
				stands.nom
				, SUM((CASE WHEN comptatges.id_stand IS NOT NULL THEN 1 ELSE 0 END)) as total                
				, counts.hora
			FROM
				stands
				JOIN (
					SELECT DISTINCT	
						DAY(created_at) as dia
						, HOUR(created_at) as hora
						, id
					FROM
						comptatges
					WHERE
						HOUR(created_at) BETWEEN 11 AND 18
				) counts                                                        
			LEFT JOIN comptatges ON stands.id = comptatges.id_stand AND comptatges.id = counts.id
			GROUP BY
				stands.id
				, stands.nom
				, counts.hora
			ORDER BY 				
				stands.id
				, counts.hora DESC"
		);
	}
		/**
	 * Consulta per obtenir percentatge d'edat per activitat
	 */
	public static function getStandPerAge(){
		return DB::select(
			"SELECT 
				stands.nom
				, SUM((CASE WHEN comptatges.id_stand IS NOT NULL THEN 1 ELSE 0 END)) as total
				, SUM((CASE WHEN comptatges.edat = 5 THEN 1 ELSE 0 END)) as '<6'
				, SUM((CASE WHEN comptatges.edat = 12 THEN 1 ELSE 0 END)) as '6-12'
				, SUM((CASE WHEN comptatges.edat = 19 THEN 1 ELSE 0 END)) as '13-19'
				, SUM((CASE WHEN comptatges.edat = 20 THEN 1 ELSE 0 END)) as '>19'
				
			FROM
				stands
				JOIN (
					SELECT DISTINCT	
						id
					FROM
						comptatges
					WHERE
						HOUR(created_at) BETWEEN 11 AND 18
				) counts                                                        
			LEFT JOIN comptatges ON stands.id = comptatges.id_stand AND comptatges.id = counts.id
			GROUP BY
				stands.id
				
			ORDER BY	
				stands.id"
		);
	}
}


