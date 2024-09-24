<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class BookingModel extends Eloquent
{
    protected $table = 'transactions';
    
    protected $fillable = ['order_number','txn_id','total','commission_percentage','commission_amount'];

    public function user_details()
    {
        return $this->belongsTo('App\Models\UserModel','user_id','id');
    }

    public function order_details()
    {
        return $this->hasMany('App\Models\OrderDetailsModel','transaction_id','id');
    } 

    public function buyer_details()
    {
        return $this->belongsTo('App\Models\UserModel','buyer_id','id');
    }   
}
