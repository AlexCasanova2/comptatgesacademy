<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Comptatges extends Model
{
	
    protected $table = 'comptatges';
    protected $fillable = [
        'created_at'
    ];
	
}
