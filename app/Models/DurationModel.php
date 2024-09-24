<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DurationModel extends Model
{
    protected $table = 'duration';
    protected $primaryKey = 'id';
    protected $fillable = ['value'];
}
