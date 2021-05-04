<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $guarded = array('id');
    
    public static $rules = array(
        'name' => 'required',
        'lat' => 'required',
        'lng' => 'required'
        );
}
