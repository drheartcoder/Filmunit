<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

class TestimonialTranslationModel extends Model
{
   use Rememberable;
    protected $table = 'testimonial_translation';
    protected $fillable = ['testimonial_id', 'user_name', 'description','user_post', 'locale'];
}
