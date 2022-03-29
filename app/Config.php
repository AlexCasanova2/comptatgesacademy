<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
	
	
	use SoftDeletes;
	
    protected $table = 'config';
	protected $dates = ['deleted_at'];
	
	public static function getVal($slug=null){
    if(is_null($slug)) return false;
    $cfg = \App\Config::where('slug','LIKE',strtolower($slug))->first();
    if(is_null($cfg)) return false;
    return $cfg->value;
  }

  public static function findBySlug($slug=null){
    if(is_null($slug)) return null;
    $cfg = \App\Config::where('slug','LIKE',strtolower($slug))->first();
    if(is_null($cfg)){
      $cfg = new \App\Config();
      $cfg->slug = trim(strtolower($slug));
    }
    return $cfg;
  }

  public static function setVal($slug=null, $value=null){
    if(is_null($slug)) return false;
    $cfg = \App\Config::where('slug','LIKE',strtolower($slug))->first();
    if(is_null($cfg)) {
      $cfg = new \App\Config();
    }
    $cfg->slug = trim($slug);
    $cfg->value = $value;
    $ret = $cfg->save();
    if($ret) return $cfg;
    else return false;
  }

	
}
