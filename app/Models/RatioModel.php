<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatioModel extends Model
{
   protected $table = 'ratio';
    protected $primaryKey = 'id';
    protected $fillable = ['value'];
}
