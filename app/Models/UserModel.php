<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Cartalyst\Sentinel\Users\EloquentUser as CartalystUser;

use Illuminate\Database\Eloquent\SoftDeletes;

class UserModel extends CartalystUser
{
    use SoftDeletes;

    protected $fillable = [
                    		'email',
                            'password',
                            'last_name',
                            'first_name',
                            'profile_image',
                            'is_active',
                            'is_block',
                            'role',
                            'terms',
                            'last_login',
                            'country',
                            'city',
                            'state',
                            'phone',
                            'address',
                            'zipcode',
                            'fax',
                            'contact_number',
                            'card_number',
                            'card_exp_month',
                            'card_exp_year',
                            'card_cvc',
                            'card_holder_name',
                            'card_save',
                            'is_agree',
                            'card_type'
                        ];

}