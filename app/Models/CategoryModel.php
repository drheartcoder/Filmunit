<?php

namespace App\Models; 
//use \Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Watson\Rememberable\Rememberable;

class CategoryModel extends Eloquent
{
    use Rememberable;


    protected $table = 'categories'; 

    protected $fillable           = ['title','description','slug','image','parent','is_active'];


    public function parent_category()
    {
    	return $this->belongsTo('App\Models\CategoryModel','parent','id');
    }

    public function child_category()
    {
        return $this->hasMany('App\Models\CategoryModel','parent','id');
    }

    public function category_control_attributes()
    {
        return $this->hasMany('App\Models\ControlAttributeModel','subcategory_id','id');
    }

    public function delete()
    {
        $this->translations()->delete();
        return parent::delete();
    }  
}
