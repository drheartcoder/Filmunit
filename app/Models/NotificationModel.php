<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationModel extends Model
{
	protected $table    = 'notifications';

    protected $fillable = [                          
                            'from_user_id',
                            'to_user_id',
                            'message',
                            'is_read'
                         ];

    public function to_user_id_details()
    {
        return $this->belongsTo('App\Models\UserModel','to_user_id','id');        
    }  

     public function from_user_id_details()
    {
        return $this->belongsTo('App\Models\UserModel','from_user_id','id');        
    }                     
	  
}
