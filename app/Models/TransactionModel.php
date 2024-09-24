<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionModel extends Model
{
	protected $table = 'transactions';

    protected $fillable = [
                            'order_number',
                            'buyer_id',
                            'txn_id',
                            'status',
                            'transaction_type',
                            'transaction_date',
    						'response_data',                            
                            'total',
                            'currency',
    						'currency_code'
    						];

    public function order_details()
    {
        return  $this->hasMany('App\Models\OrderDetailsModel' ,'transaction_id' ,'id');  
    }

    public function buyer_details()
    {
        return  $this->belongsTo('App\Models\UserModel' ,'buyer_id' ,'id');  
    } 

}
