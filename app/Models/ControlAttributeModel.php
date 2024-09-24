<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ControlAttributeModel extends Model
{
    protected $table = "control_attributes";

    protected $fillable = [
    						'subcategory_id',
    						'label',
    						'label_slug',
    						'control_type',
    						'order_index',
    						'is_required',
    						'minlength',
    						'maxlength',
    						'is_active'
    					];

    public function control_attribute_options()
    {
    	return $this->hasMany('App\Models\ControlAttributeOptionModel','control_attribute_id','id');
    }

    public function subcategory_details()
    {
    	return $this->belongsTo('App\Models\CategoryModel','subcategory_id','id');
    }
}
