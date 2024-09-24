<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageModel extends Model
{
    protected $table = 'packs';
    protected $fillable = [
                            'is_active',
                            'title',
                            'slug',
                            'order_number',
                            'enc_image_name'
                            ];

    public function package_details()
    {
        return $this->hasMany('App\Models\PackageDetailModel','pack_id','id');        
    }
}
