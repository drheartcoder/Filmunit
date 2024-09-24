<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ControlAttributeOptionModel extends Model
{
    protected $table = "control_attribute_options";

    protected $fillable = [
    						'control_attribute_id',
    						'option_title',
    						'option_slug'
    					];
}