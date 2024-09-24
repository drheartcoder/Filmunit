<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Watson\Rememberable\Rememberable;


class CountryModel extends Model
{
	use Rememberable,SoftDeletes;

    protected $table = 'countries';
    protected $primaryKey = 'id';
    protected $fillable = ['country_code','country_name','is_active'];

    public function states()
    {
        return $this->hasMany('App\Models\StateModel','country_id','id');
    }

   	public function cities()
    {
        return $this->hasMany('App\Models\CityModel','country_id','id');
    }

    public function delete()
    {
        $this->translations()->delete();
        $this->states()->delete();
        $this->cities()->delete();
        return parent::delete();
    }
}
