<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class OrderDetailsModel extends Eloquent
{
    protected $table = 'order_details';
    protected $fillable = [
                            'transaction_id',
                            'buyer_id',
                            'seller_id',
                            'item_id',
                            'order_number',
                            'type',
                            'price',
                            'buyer_status',
                            'seller_status',
                            'download_attempt'
                            ];

    public function listing_details()
    {
    	return $this->belongsTo('App\Models\MediaListingDetailModel','item_id','id');
    }

    public function transaction_details()
    {
        return $this->belongsTo('App\Models\TransactionModel','transaction_id','id');
    }    

    public function seller_details()
    {
        return  $this->belongsTo('App\Models\UserModel' ,'seller_id' ,'id');  
    } 

}   
