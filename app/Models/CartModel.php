<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartModel extends Model
{
    protected $table	=	"cart";
    protected $fillable	=	[
                                'id',
                                'visitor_id',
                                'buyer_id',
                                'master_id',
                                'list_id',
                                'type',
                                'price'
                            ];

    public function listing_details()
    {
        return  $this->belongsTo('App\Models\MediaListingDetailModel' ,'list_id','id');  
    }

    public function master_details()
    {
        return  $this->belongsTo('App\Models\MediaListingMasterModel' ,'master_id','id');  
    }

    public function buyer_details()
    {
        return  $this->belongsTo('App\Models\UserModel' ,'buyer_id' ,'id');  
    }
}
