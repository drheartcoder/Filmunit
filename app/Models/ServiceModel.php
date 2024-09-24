<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceModel extends Model
{
    protected $table = "services";

    protected $fillable = ['category_id','subcategory_id','title','image','price','description','is_active'];

    public function subcategory_details()
    {
		return $this->belongsTo('App\Models\CategoryModel','subcategory_id','id');
    }

    public function category_details()
    {
		return $this->belongsTo('App\Models\CategoryModel','category_id','id');
    }
}
