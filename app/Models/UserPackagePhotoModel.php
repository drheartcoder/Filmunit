<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserPackagePhotoModel extends Eloquent
{
    protected $table = 'user_package_photos';
    protected $fillable = ['user_id','package_id','order_id','image_name','enc_image_name','type'];
}
