<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavouritePackageModel extends Model
{
   
    protected $table   =  'favourite_packages';

	protected $fillable = [                    		
                            'package_id', 
                            'user_id'
                        ];

    public function package_details()
    {
		return $this->belongsTo('App\Models\PackageModel','package_id','id');
    }
}
