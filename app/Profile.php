<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pfofile extends Model
{
    protected $guarded = array('id');
    
    public static $rules = array(
        'name' => 'required',
        'gender' => 'required',
        'hobby' => 'required',
        'introduction' =>'required',
    );
}
