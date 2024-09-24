<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Common\Services\LanguageService;  
use App\Models\SubscribersModel;
use Validator;
use Session;
Use Sentinel;
Use Excel;
 
class SubscribersController extends Controller
{
    /*
        Auther : Sayali Patil
        Date   : 08/09/2016
        Comments: controller for Newsletter Subscriber
    */ 
    public $SubscribersModel; 
    
    public function __construct(SubscribersModel $news_subscriber)
    {      
       $this->SubscribersModel = $news_subscriber;     
       $this->module_url_path = url(config('app.project.admin_panel_slug')."/newsletter/subscriber");
    }

    /*
    | Index  : Display listing of Subscribers
    | auther : Sayali Patil
    | Date   : 08/09/2016
    | 
    */ 
    public function index()
    {
        $obj_news_subscriber = $this->SubscribersModel->orderBy('id','desc')->get();
        if($obj_news_subscriber != FALSE)
        {
            $arr_news_subscriber = $obj_news_subscriber->toArray();
        }
        $this->arr_view_data['arr_news_subscriber'] = $arr_news_subscriber;
        $this->arr_view_data['page_title'] = "Manage Subscribers";
        $this->arr_view_data['module_title'] = "Subscribers";
        $this->arr_view_data['module_url_path'] = $this->module_url_path;

        return view('admin.newsletter_subscriber.index',$this->arr_view_data);
    }

    public function activate($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            Session::flash('error','Problem occured while subscriber activation.');
            return redirect()->back();
        }
        if($this->perform_activate(base64_decode($enc_id)))
        {
            Session::flash('success','Subscriber activated successfully.');
        }
        else
        {
            Session::flash('error','Problem occured while subscriber activation.');
        }
        return redirect()->back();
    }

    public function deactivate($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            Session::flash('error','Problem occured while subscriber deactivation.');
            return redirect()->back();
        }
        if($this->perform_deactivate(base64_decode($enc_id)))
        {
            Session::flash('success','Subscriber deactivated successfully.');
        }
        else
        {
            Session::flash('error','Problem occured while subscriber deactivation.');
        }
        return redirect()->back();
    }

    public function delete($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            Session::flash('error','Problem occured while subscriber deletion.');
            return redirect()->back();
        }
        if($this->perform_delete(base64_decode($enc_id)))
        {
            Session::flash('success','Subscriber deleted successfully.');
        }
        else
        {
            Session::flash('error','Problem occured while subscriber deletion.');
        }
        return redirect()->back();
    }

    public function perform_activate($id)
    {
        if ($id) 
        {   
            $news_subscriber = $this->SubscribersModel->where('id',$id)->first();
            if($news_subscriber)
            {
                return $news_subscriber->update(['is_active'=>1]);
            }
        }
        return FALSE;
    }

    public function perform_deactivate($id)
    {
        if ($id) 
        {
            $news_subscriber = $this->SubscribersModel->where('id',$id)->first();
            if($news_subscriber)
            {
                return $news_subscriber->update(['is_active'=>0]);
            }
        }
        return FALSE;
    }

    public function perform_delete($id)
    {
        if ($id) 
        {   
            $news_subscriber= $this->SubscribersModel->where('id',$id)->first();
            if($news_subscriber)
            {   
                return $news_subscriber->delete();
            }
        }
        return FALSE;
    }   

    /*
    | multi_action: Following Fuctions for active ,deactive and delete for multiple records
    | auther      : Sayali Patil
    | Date        : 08/09/2016
    | 
    */ 
    public function multi_action(Request $request)
    {
        $arr_rules = array();
        $arr_rules['multi_action'] = "required";
        $arr_rules['checked_record'] = "required";
        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails())
        {
            Session::flash('error','Please Select Atleast 1 Record');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $multi_action = $request->input('multi_action');
        $checked_record = $request->input('checked_record');
        /* Check if array is supplied*/
        if(is_array($checked_record) && sizeof($checked_record)<=0)
        {
            Session::flash('error','Problem occured, while doing multi action.');
            return redirect()->back();
        }
        foreach ($checked_record as $key => $record_id) 
        {  
            if($multi_action=="delete")
            {
               $this->perform_delete(base64_decode($record_id));    
               Session::flash('success','Selected subscriber(s) deleted successfully.');
            } 
            elseif($multi_action=="activate")
            {
               $this->perform_activate(base64_decode($record_id)); 
               Session::flash('success','Selected subscriber(s) activated successfully');               
            }
            elseif($multi_action=="deactivate")
            {
               $this->perform_deactivate(base64_decode($record_id));    
               Session::flash('success','Selected subscriber(s) deactivated successfully.');
            }
        }
        return redirect()->back();
    }

    public function export(Request $request)
    {      

        $obj_news_subscriber = $this->SubscribersModel->orderBy('id','desc')->get();
        if($obj_news_subscriber != FALSE)
        {
            $arr_news_subscriber = $obj_news_subscriber->toArray();
            //dd($arr_news_subscriber); 
        }
        $format="csv";
        //dd($arr_news_subscriber); 
        if($format=="csv")
        {
            $arr_tmp = array();
            if($arr_news_subscriber)
            {  
                 Excel::create('Subscriber_REPORT-'.date('Ymd').uniqid(), function($excel) use($arr_news_subscriber) 
                  {
                        $excel->sheet('Subscriber', function($sheet) use($arr_news_subscriber) 
                        {        
                            $sheet->cell('A1', function($cell) 
                                    { 
                                        $cell->setValue('Generated on :'.date("d-m-Y H:i:s"));
                                    });

                            $sheet->row(2, array(
                                                       'Subscriber Email' ,
                                                       'Created At'
                                                  ));
                            $i=0;
                            foreach($arr_news_subscriber as $key => $row)
                            {
                                $arr_tmp[$key][]=$row['subscriber_email'];                          
                                $arr_tmp[$key][]=$row['created_at']?$row['created_at']:'NA';
                            }   
                            $sheet->rows($arr_tmp);                                     
                        });

                  })->export('csv');
            }
            else
            {
                $userMsg = 'Error occure while making export due to no data to create csv file';
                Session::flash('error', $userMsg);
                return redirect()->back();
            }
        }               
    }
}