<?php

namespace App\Common\Services;

use App\Models\UserModel;

class RoleService
{
	public function __construct(
								UserModel $user
								)
	{
		$this->UserModel	      = $user;
	}

	  /*------------------------------------------------------------------------------
	  | Get all roles for user
	  --------------------------------------------------------------------------------*/

	  public function get_user_roles($user_id)
	  {
	    $arr_roles = [];
	    $obj_user = $this->UserModel->where('id',$user_id);
	    $obj_user = $obj_user->whereHas('roles', function () {})
	               ->with('roles')
	               ->first();
	    
	    if($obj_user)
	    {
	      $arr_user = $obj_user->toArray();
	      if( isset($arr_user['roles']) && count($arr_user['roles']) > 0 )
	      {
	        $arr_roles = array_column($arr_user['roles'],'slug');
	      } 
	    }
	    return $arr_roles;
	  }

	  public function has_role($user_id, $role)
	  {
	    $arr_roles = $this->get_user_roles($user_id);
	    if(count($arr_roles) > 0)
	    { 
	      if(in_array($role, $arr_roles))
	      {
	          return true;
	      }
	    }
	    return false;
	  }

	  /*------------------------------------------------------------------------------
	  | Ends
	  --------------------------------------------------------------------------------*/



	
}

?>