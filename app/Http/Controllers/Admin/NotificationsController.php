<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\NotificationModel;  

use Session;
use Validator;
use Flash;

class NotificationsController extends Controller
{
    public function __construct(NotificationModel $notifications)
    {      
        $this->NotificationModel    = $notifications;     
        $this->module_url_path      = url(config('app.project.admin_panel_slug')."/notifications");
        $this->module_view_folder   = "admin.notification";
        $this->module_title         = "Notifications ";
        $this->module_icon          = "fa-bell";
    }

    public function index()
    {
        $obj_notifications = $this->NotificationModel->orderBy('created_at', 'DESC')->where('to_user_id',1)->get();
        if($obj_notifications != FALSE)
        {
            $update_status     = $this->NotificationModel->where('to_user_id',1)->where('is_read','0')->update(array('is_read'=>'1'));
            $arr_notifications = $obj_notifications->toArray();
        }
        
        $this->arr_view_data['arr_notifications']   = $arr_notifications;
        $this->arr_view_data['page_title']          = "Manage Notifications";
        $this->arr_view_data['module_title']        = $this->module_title;
        $this->arr_view_data['module_url_path']     = $this->module_url_path;
        $this->arr_view_data['module_icon']         = $this->module_icon;

        return view($this->module_view_folder.'.index',$this->arr_view_data);
    }

    public function get_notifications(request $request)
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

     public function delete($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            Flash::error('Problem occured while notification deletion.');
            return redirect()->back();
        }

        if($this->perform_delete(base64_decode($enc_id)))
        {
            Flash::success('Notification deleted successfully.');
        }
        else
        {
            Flash::error('Problem occured while notification deletion.');
        }
        return redirect()->back();
    }

    public function perform_delete($id)
    {
        if ($id) 
        {
            $notification= $this->NotificationModel->where('id',$id)->first();
            if($notification)
            {
                return $notification->delete();
            }
        }
        return FALSE;
    }   

    public function multi_action(Request $request)
    {
        $arr_rules = array();
        $arr_rules['multi_action'] = "required";
        $arr_rules['checked_record'] = "required";
        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails())
        {
            Flash::error('Please Select Atleast 1 Record.');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $multi_action = $request->input('multi_action');
        $checked_record = $request->input('checked_record');
        /* Check if array is supplied*/
        if(is_array($checked_record) && sizeof($checked_record)<=0)
        {
            Flash::error('Problem occured, while doing multi action.');
            return redirect()->back();
        }
        foreach ($checked_record as $key => $record_id) 
        {  
            if($multi_action=="delete")
            {
               $this->perform_delete(base64_decode($record_id));    
               Flash::success('Selected Notification(s) deleted successfully.');
            } 
        }
        return redirect()->back();
    }  
}
