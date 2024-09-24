<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageLocationModel extends Model
{
    protected $table    = 'package_locations';

    protected $fillable = [
							'id',
							'package_id',
							'location'
    					];
}
