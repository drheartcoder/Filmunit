<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class SubscribersModel extends Model
{
   
    protected $table = "subscribers";

    protected $fillable = ['subscriber_email','is_active'];

    
}
