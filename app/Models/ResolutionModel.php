<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResolutionModel extends Model
{
    protected $table = 'resolution';
 	
    protected $fillable = [
                            'size'
    					  ];
}
