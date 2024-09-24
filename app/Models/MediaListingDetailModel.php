<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaListingDetailModel extends Model
{
    	protected $table = 'media_listing_details';

        protected $fillable = [
        						'list_id',
        						'item_name',
        						'enc_item_name',
        						'price',
        						'format',
        						'orientation',
        						'resolution',
        						'fps'
        						];

    public function format_details()
    {
        return $this->belongsTo('App\Models\FormatModel','format','id');        
    }

    public function orientation_details()
    {
        return $this->belongsTo('App\Models\OrientationModel','orientation','id');        
    }

    public function resolution_details()
    {
        return $this->belongsTo('App\Models\ResolutionModel','resolution','id');        
    }                                

    public function fps_details()
    {
        return $this->belongsTo('App\Models\FpsModel','fps','id');        
    }

    public function master_details()
    {
        return $this->belongsTo('App\Models\MediaListingMasterModel','list_id','id');        
    }

    public function order_details()
    {
        return $this->belongsTo('App\Models\OrderDetailsModel','id','item_id');        
    }                                               
}
