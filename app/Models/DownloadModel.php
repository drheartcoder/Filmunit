<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownloadModel extends Model
{
    protected $table = "download_attempts";

    protected $fillable = ['attempts'];
}