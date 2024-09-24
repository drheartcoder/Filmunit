<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class OrderServicesModel extends Eloquent
{
    protected $table = 'order_services';

    protected $fillable = ['order_details_id','service_id','service_price'];

    public function service_details()
    {
    	return $this->belongsTo('App\Models\ServiceModel' , 'service_id' ,'id');
    }
}