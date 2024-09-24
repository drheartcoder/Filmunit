<?php 
namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Usermodel;
use App\Models\NotificationModel; 

use Validator;
use Session;
use Input;
use Auth;
use Sentinel;
use Flash;

 
class CommonDataController extends Controller
{
    public function __construct(
                                UserModel $user_model,
                                NotificationModel $notify
                                )  
    {
        $this->UserModel                        = $user_model;
        $this->NotificationModel                = $notify;
        $this->arr_view_data                    = [];
        $this->profile_image_public_img_path    = url('/').config('app.project.img_path.user_profile_images');

    }

    public function get_all_notifications()
    {
        $obj_arr_data   = false;
        $arr_data       = [];
        $user           = [];
        $arr_pagination = [];
        $user_id      = '';

        //Check User
        $user = Sentinel::check();

        if(isset($user) && $user==true)
        {
            $user_id    = $user['id'];
            $obj_arr_data = $this->NotificationModel->where('to_user_id',$user_id)
                                                    ->with(['from_user_id_details'=>function($query){
                                                        $query->select('id','first_name','profile_image');
                                                    }])
                                                    ->orderBy('id','DESC')
                                                    ->paginate(10);
            if($obj_arr_data!=false)
            {
                $update_status     = $this->NotificationModel->where('to_user_id',$user_id)->where('is_read','0')->update(array('is_read'=>'1'));
                $arr_pagination   = $obj_arr_data;
                $arr_data         = $obj_arr_data->toArray();
            }
        }
        else
        {
            return redirect()->back();
        }

        $this->arr_view_data['arr_data']                        = $arr_data;
        $this->arr_view_data['arr_pagination']                  = $arr_pagination;
        $this->arr_view_data['title']                           = "Notifications - ".config('app.project.name');
        $this->arr_view_data['module_title']                    = "Notifications";
        $this->arr_view_data['module_url_path']                 = url('/')."/notifications";
        $this->arr_view_data['profile_image_public_img_path']   = $this->profile_image_public_img_path;

        return view('front.common.notification',$this->arr_view_data);
    }

    public function delete_notifications($enc_id)
    {
        $status   = false;
        $arr_data       = [];
        $user           = [];
        $user_id        = $id = '';

        if($enc_id!='')
        {
            $id = base64_decode($enc_id);
            
            //Check User
            $user = Sentinel::check();
            if(isset($user) && $user==true)
            {
                $user_id    = $user['id'];
                $status = $this->NotificationModel->where('to_user_id',$user_id)->where('id',$id)->delete();
                
                if($status!=false)
                {
                    Flash::success("Notification has been deleted successfully.");
                    return redirect()->back();
                }
                else
                {
                    Flash::error("Error occured while deleting notification.");
                    return redirect()->back();
                }
            }
        }
        else
        {
            Flash::error("Error occured while deleting notification.");
            return redirect()->back();
        }
    }

    public function get_notification_count(request $request)
    {
        $notification_count = 0;
        $receiver_id        = '';
        if($request->has('to_user_id'))
        {
            $receiver_id = $request->input('to_user_id');        
            if($receiver_id!="" && $receiver_id!=FALSE)
            {  
                $notification_count = $this->NotificationModel->where('to_user_id',$receiver_id)->where('is_read','0')->count();            
            } 
            
        }
         return $notification_count;
    }      

}