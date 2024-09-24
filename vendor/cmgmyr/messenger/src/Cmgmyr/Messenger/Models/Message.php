<?php

namespace Cmgmyr\Messenger\Models;

use App\User;
use Illuminate\Database\Eloquent\Model as Eloquent;

use Sentinel;

class Message extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'messages';

    /**
     * The relationships that should be touched on save.
     *
     * @var array
     */
    protected $touches = ['thread'];

    /**
     * The attributes that can be set with Mass Assignment.
     *
     * @var array
     */
    protected $fillable = ['thread_id', 'user_id', 'body'];

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $rules = [
        'body' => 'required',
    ];

    

    /**
     * {@inheritDoc}
     */
    public function __construct(array $attributes = [])
    {
        $this->table = Models::table('messages');

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

    /**
     * Participants relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participants()
    {
        return $this->hasMany(Models::classname(Participant::class), 'thread_id', 'thread_id');
    }

    /**
     * Recipients of this message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recipients()
    {
        return $this->participants()->where('user_id', '!=', $this->user_id);
    }


    
    /*-------------------------------------
    Auther : Nayan S.
    --------------------------------------*/                            
    
    public $appends = ['role_info','time_ago'];  

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

    public function getTimeAgoAttribute()
    {
        $tmp_date     = new \DateTime($this->created_at);
        $created_date = $tmp_date->format('Y-m-d H:i:s');
        $time_ago     = $this->humanTiming(strtotime($created_date)).' ago';
        return $time_ago;
    }

    public function humanTiming($time)
    {
        $time = time() - $time; // to get the time since that moment
        $time = ($time<1) ? 1 : $time;
        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );
        
        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
        }
    }



    /*----------------ends-------------------*/  

}
