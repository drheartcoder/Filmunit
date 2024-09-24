<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageDetailModel extends Model
{
    protected $table = 'packs_details';

    protected $fillable = [
                            'pack_id',
                            'list_id'
                            ];

    public function listing_details()
    {
        return $this->belongsTo('App\Models\MediaListingDetailModel','list_id','id');        
    }                            

}
