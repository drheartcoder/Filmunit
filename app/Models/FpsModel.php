<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FpsModel extends Model
{
    protected $table = 'fps';
 	
    protected $fillable = [
                            'value'
    					  ];
}
