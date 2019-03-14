<?php

namespace uisits\ipay\App;


use Illuminate\Database\Eloquent\Model;

class Ipay extends Model
{
    //
    public $timestamps = false;
    protected $fillable = array('transactionid','certification','token','created_at','updated_at');
}
