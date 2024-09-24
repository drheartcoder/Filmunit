<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Dimsav\Translatable\Translatable;
use Watson\Rememberable\Rememberable;

class TestimonialModel extends Model
{
    use Rememberable;
	use Translatable;

	protected $table = 'testimonial';

    public $translationModel = 'App\Models\TestimonialTranslationModel';
 	public $translationForeignKey = 'testimonial_id';
    public $translatedAttributes = ['user_name','description','user_post'];

    protected $fillable = ['id', 'is_active','user_image'];

    public function delete()
    {
        $this->translations()->delete();
        return parent::delete();
    }
  
}
