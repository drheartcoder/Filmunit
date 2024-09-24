<?php

namespace Cmgmyr\Messenger\Models;

use App\User;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

use Sentinel;

class Participant extends Eloquent
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'participants';

    /**
     * The attributes that can be set with Mass Assignment.
     *
     * @var array
     */
    protected $fillable = ['thread_id', 'user_id', 'last_read'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'last_read'];

    /**
     * {@inheritDoc}
     */
    public function __construct(array $attributes = [])
    {
        $this->table = Models::table('participants');

        parent::__construct($attributes);
    }

    /**
     * Thread relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(Models::classname(Thread::class), 'thread_id', 'id');
    }

    /**
     * User relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(Models::classname(User::class), 'user_id');
    }


    /*-------------------------------------
    Auther : Nayan S.
    --------------------------------------*/                            
    
    public $appends = ['role_info'];  

    public function getRoleInfoAttribute()
    {   
        $user_role = $this->get_user_role($this->user_id);

        if( $user_role == "client" )
        {
            $model = app(\App\Models\ClientsModel::class);
        } 
        else if( $user_role == "expert" )
        {
            $model = app(\App\Models\ExpertsModel::class);
        } 
        else if( $user_role == "project_manager" )
        {
            $model = app(\App\Models\ProjectManagerModel::class);
        }

        $obj_user = $model->where('user_id','=',$this->user_id)->get();
       
        $arr_user = [];

        if($obj_user)
        {
            $arr_user = $obj_user->toArray();
        }
        return $arr_user;
    }
    

    public function get_user_role($user_id) 
    {
      $obj_role  = Sentinel::findById($user_id)->roles()->first();
      
      $role_slug = "";

      if($obj_role)
      {
        $role_slug = $obj_role->slug; 
      }

      return $role_slug;
    }

    /*----------------ends-------------------*/  

}
