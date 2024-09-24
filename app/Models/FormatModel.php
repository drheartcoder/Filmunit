<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormatModel extends Model
{
    protected $table = 'formats';
 	
    protected $fillable = [
                            'name','type'
    					  ];
}
