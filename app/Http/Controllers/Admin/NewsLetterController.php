<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\SubscribersModel;
use App\Models\UserModel;

use App\Common\Services\EmailService;
use App\Common\Traits\MultiActionTrait;

use App\Models\NewsLetterModel;
//use App\Models\NewsLetterSubscriberModel;
use App\Models\SiteSettingModel;

use Session;
use Validator;
use Flash;
use DB;

use Mail;

class NewsLetterController extends Controller
{
    use MultiActionTrait;

    public function __construct(
                                    SubscribersModel $subscribers,
                                    UserModel $UserModel,
                                    EmailService $EmailService,
                                    NewsLetterModel $newsletter, 
                                    SiteSettingModel $adminemail
                                )
    {
        $this->arr_view_data 		= [];
		$this->SubscribersModel 	= $subscribers;
		$this->NewsLetterModel      = $newsletter;

		$this->SiteSettingModel   =  $adminemail; 
        $this->BaseModel            = $this->SubscribersModel;
        $this->UserModel            = $UserModel;
        $this->EmailService         = $EmailService;

        $this->module_url_path = url(config('app.project.admin_panel_slug')."/newsletter");
		//$this->module_url_path 		= url(config('app.project.admin_panel_slug')."/subscribers");
        //$this->module_view_folder   = "admin.subscribers";
        $this->module_view_folder   = "admin.newsletter";
        $this->module_title         = "Subscribers ";
    }




  /*
    | Index  : Display listing of newsletter
    | auther : Sayali Patil
    | Date   : 08/09/2016    
    */ 
    public function index()
    {    	
        $obj_news_letter = $this->NewsLetterModel->orderBy('id','desc')->get();
        if($obj_news_letter != FALSE)
        {
            $arr_news_letter = $obj_news_letter->toArray();
        }
        $this->arr_view_data['arr_news_letter'] = $arr_news_letter;
        $this->arr_view_data['page_title'] = "Manage Newsletter";
        $this->arr_view_data['module_title'] = "Newsletter";
        $this->arr_view_data['module_url_path'] = $this->module_url_path;

        return view($this->module_view_folder.'.index',$this->arr_view_data);
    }

    /*
    | Create : create new newsletter
    | auther : Sayali Patil
    | Date   : 08/09/2016    
    */ 
    public function create()
    {
        $this->arr_view_data['page_title'] = "Add Newsletter";
        $this->arr_view_data['module_title'] = "Newsletter";
        $this->arr_view_data['module_url_path'] = $this->module_url_path;

        return view('admin.newsletter.create',$this->arr_view_data);
    }

     /*
    | view   : view newsletter details
    | auther : Sayali Patil
    | Date   : 29/09/2016      
    */
    public function view($enc_id)
    {
        $id = base64_decode($enc_id);
        $obj_news_letter = $this->NewsLetterModel->where('id', $id)->first();
        $arr_news_letter = [];
        if($obj_news_letter)
        {
           $arr_news_letter = $obj_news_letter->toArray(); 
        }
        $this->arr_view_data['enc_id'] = $enc_id;        
        $this->arr_view_data['arr_news_letter'] = $arr_news_letter;  
        $this->arr_view_data['page_title'] = "View Newsletter";
        $this->arr_view_data['module_title'] = "Newsletter";
        $this->arr_view_data['module_url_path'] = $this->module_url_path;

        return view('admin.newsletter.view',$this->arr_view_data);
    }

    /*
    | store() : store newsletter
    | auther  : Sayali Patil
    | Date    : 08/09/2016
    | @param  \Illuminate\Http\Request  $request    
    */
    public function store(Request $request)
    {   
        //dd($request->all());
        $form_data = array();
        $form_data = $request->all();
        $arr_rules['news_title']        = "required";
        $arr_rules['news_subject']      = "required";
        $arr_rules['news_description']  = "required";                
        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails())
        {
             return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        $is_exists   =NewsLetterModel::where('title',$form_data['news_title'])->count();
        if($is_exists!=0)
        {
            Flash::error('Newsletter allready exists!');
            return redirect()->Back();
        }    
        $form_data = $request->all();
        $arr_data = array();
        $arr_data['title']        = $form_data['news_title'];
        $arr_data['subject']      = $form_data['news_subject'];
        $arr_data['news_message'] = $form_data['news_description'];                      
        $news_letter    = $this->NewsLetterModel->create($arr_data);
        if($news_letter)
        {
            Flash::success("Newsletter Added Successfullly.");
        }
        else
        {
            Flash::error("Error while adding newsletter.");  
        }
        return redirect()->back();
    }

     /*
    | edit() : edit newsletter details
    | auther : Sayali Patil
    | Date   : 08/09/2016  
    */
    public function edit($enc_id)
    {
        $id = base64_decode($enc_id);
        $obj_news_letter = $this->NewsLetterModel->where('id', $id)->first();
        $arr_news_letter = [];
        if($obj_news_letter)
        {
           $arr_news_letter = $obj_news_letter->toArray(); 
        }
        $this->arr_view_data['enc_id'] = $enc_id;        
        $this->arr_view_data['arr_news_letter'] = $arr_news_letter;  
        $this->arr_view_data['page_title'] = "Edit Newsletter";
        $this->arr_view_data['module_title'] = "Newsletter";
        $this->arr_view_data['module_url_path'] = $this->module_url_path;

        return view('admin.newsletter.edit',$this->arr_view_data);
    }

    /*
    | update() : update newsletter details
    | auther   : Sayali Patil
    | Date     : 08/09/2016
    | @param  \Illuminate\Http\Request  $request
    */
    public function update(Request $request, $enc_id)
    {
        $news_id = base64_decode($enc_id);
        $arr_rules = array();
        $status = FALSE;
        $arr_rules['news_title']         = "required";
        $arr_rules['news_subject']       = "required";
        $arr_rules['news_description']   = "required";     
        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        $form_data = array();
        $form_data = $request->all(); 
        $arr_data = array();
        $arr_data = [ 'title'            =>  $form_data['news_title'],
                      'subject'          =>  $form_data['news_subject'],
                      'news_message'     =>  $form_data['news_description'],
                    ];
        $status = $this->NewsLetterModel->where('id',$news_id)->update($arr_data);
        if ($status) 
        {
            Flash::success('Newsletter updated successfully.');   
            //dd(Flash::success('Newsletter updated successfully.'));
        }
        else
        {
            Flash::error('Error while updating newsletter.');       
        }
        return redirect()->back();
    }

    /*
    | Following Fuctions for active ,deactive and delete
    | auther : Sayali Patil
    | Date   : 08/09/2016
    */ 
    public function activate($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            Flash::error('Problem occured while newsletter activation.');
            return redirect()->back();
        }
        if($this->perform_activate(base64_decode($enc_id)))
        {
            Flash::success('Newsletter activated successfully.');
        }
        else
        {
            Flash::error('Problem occured while newsletter activation.');
        }
        return redirect()->back();
    }

    public function deactivate($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            Flash::error('Problem occured while newsletter deactivation.');
            return redirect()->back();
        }
        if($this->perform_deactivate(base64_decode($enc_id)))
        {
            Flash::success('Newsletter deactivated successfully.');
        }
        else
        {
            Flash::error('Problem occured while newsletter deactivation.');
        }
        return redirect()->back();
    }

    public function delete($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            Flash::error('Problem occured while newsletter deletion.');
            return redirect()->back();
        }
        if($this->perform_delete(base64_decode($enc_id)))
        {
            Flash::success('Newsletter deleted successfully.');
        }
        else
        {
            Flash::error('Problem occured while newsletter deletion.');
        }
        return redirect()->back();
    }

    public function perform_activate($id)
    {
        //dd('test');
        if ($id) 
        {
            $news_letter = $this->NewsLetterModel->where('id',$id)->first();
            if($news_letter)
            {
                return $news_letter->update(['is_active'=>1]);
            }
        }
        return FALSE;
    }

    public function perform_deactivate($id)
    {     
        if ($id) 
        {
            $news_letter = $this->NewsLetterModel->where('id',$id)->first();
            if($news_letter)
            {
                return $news_letter->update(['is_active'=>0]);
            }
        }
        return FALSE;
    }

    public function perform_delete($id)
    {
        if ($id) 
        {
            $news_letter= $this->NewsLetterModel->where('id',$id)->first();
            if($news_letter)
            {
                return $news_letter->delete();
            }
        }
        return FALSE;
    }   

    /*
    | multi_action: Following Fuctions for active ,deactive and delete for multiple records
    | auther : Sayali Patil
    | Date   : 08/09/2016
    */ 
    public function multi_action(Request $request)
    {
        $arr_rules = array();
        $arr_rules['multi_action'] = "required";
        $arr_rules['checked_record'] = "required";
        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails())
        {
            Flash::error('Please Select Atleast 1 Record');
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
               Flash::success('Selected newsletter(s) deleted successfully.');
            } 
            elseif($multi_action=="activate")
            {
               $this->perform_activate(base64_decode($record_id)); 
               Flash::success('Selected newsletter(s) activated successfully');               
            }
            elseif($multi_action=="deactivate")
            {
               $this->perform_deactivate(base64_decode($record_id));    
               Flash::success('Selected newsletter(s) blocked successfully.');
            }
        }
        return redirect()->back();
    }

    public function arrange_locale_wise(array $arr_data)
    {
        if(sizeof($arr_data)>0)
        {
            foreach ($arr_data as $key => $data) 
            {
                $arr_tmp = $data;
                unset($arr_data[$key]);

                $arr_data[$data['locale']] = $data;                    
            }

            return $arr_data;
        }
        else
        {
            return [];
        }
    }

    public function send()
    {
        $obj_news = $this->NewsLetterModel->where('is_active',1)->orderBy('title','ASC')->get();
        if($obj_news != FALSE)
        {
            $arr_newsletters = $obj_news->toArray();
        }
        $this->arr_view_data['arr_newsletters'] = $arr_newsletters;
        $obj_subscriber = $this->SubscribersModel->where('is_active',1)->get();
        if($obj_subscriber != FALSE)
        {
            $arr_subscriber = $obj_subscriber->toArray();
        }
        $this->arr_view_data['arr_subscriber'] = $arr_subscriber;
        $this->arr_view_data['page_title'] = "Send Newsletter";
        $this->arr_view_data['module_title'] = "Newsletters";
        $this->arr_view_data['module_url_path'] = $this->module_url_path;
         
        return view('admin.newsletter.newsletter_send',$this->arr_view_data);        
    }   

    public function send_email(Request $request)
    {
        $arr_rules = array();
        $arr_rules['news_letter'] = "required";
        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $news_letter_id = base64_decode($request->input('news_letter'));
        $checked_record = $request->input('checked_record');
        $admin_email = $this->SiteSettingModel->first();
        //dd($admin_email);      
        if(isset($checked_record) && sizeof($checked_record)>0)
        {
            $newsletter_details = $this->NewsLetterModel->where('id',$news_letter_id)->first();
            //dd($newsletter_details);
            if (isset($newsletter_details) && $newsletter_details!=FALSE)
            {
                $newsletter_details = $newsletter_details->toArray();  
                
                foreach ($checked_record as $key => $email_id) 
                {  
                  $project_name = config('app.project.name');
                  $mail_subject = isset($newsletter_details['subject'])?$newsletter_details['subject']:'';
                  $mail_form    = isset($admin_email)?$admin_email->info_email_address:'admin@photoshoot.com';
                  //dd($mail_form);
                  Mail::send('email.newsletter', $newsletter_details, function ($message) use ($email_id,$mail_form,$project_name,$mail_subject) 
                   {
                          $message->from($mail_form, $project_name);
                          $message->subject($project_name.':'.$mail_subject);
                          $message->to($email_id);
                          //->attach(\Swift_Attachment::fromPath($newsletter_details['news_message']));

                   }); 
                  Flash::success('Newsletter sent successfully.');
                }
            }
        }
        else
        {
            Flash::error('Please select subscriber to send newsletter.');
            return redirect()->back();
        }
        return redirect()->back();        
    }  


}
