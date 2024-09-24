<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavouriteModel extends Model
{
	protected $table   =  'favourites';

	protected $fillable = [                    		
                            'list_id', 
                            'buyer_id'
                        ];

    public function listing_details()
    {
    	return $this->belongsTo('App\Models\MediaListingDetailModel','list_id','id');
    }
}
