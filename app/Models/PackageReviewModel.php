<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageReviewModel extends Model
{
    protected $table = "reviews";

    protected $fillable = [
    							'package_id', 
    							'user_id', 
    							'review',
    							'rating',
    							'is_active'
    					];
}