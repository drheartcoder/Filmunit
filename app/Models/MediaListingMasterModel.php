<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaListingMasterModel extends Model
{
	protected $table = 'media_listing_master';

    protected $fillable = [
                            'seller_id',
                            'slug',
                            'is_approved',
                            'is_admin_uploaded',
    						'commission',
    						'description',
    						'type',
    						'keywords',
    						'title',
    						'duration',
    						'ratio',
    						'alpha_channel',
    						'alpha_matte',
    						'media_release',
    						'model_release',
    						'liscense_type',
                            'fx',
                            'admin_enc_item_name',
    						'admin_enc_footage_image',
                            'rejection_note'
    						];

    public function listing_details()
    {
        return  $this->hasMany('App\Models\MediaListingDetailModel' ,'list_id' ,'id');  
    }

    public function seller_details()
    {
        return  $this->belongsTo('App\Models\UserModel' ,'seller_id' ,'id');  
    }

    public function favourite_list()
    {
        return $this->belongsTo('App\Models\FavouriteModel','id','list_id');        
    }

    public function cart_list()
    {
        return $this->belongsTo('App\Models\CartModel','id','master_id');        
    }

    public function ratio_details()
    {
        return $this->belongsTo('App\Models\RatioModel','ratio','id');        
    }    
}
