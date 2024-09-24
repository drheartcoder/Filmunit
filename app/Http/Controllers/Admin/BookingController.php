<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\TransactionModel;
use App\Models\OrderDetailsModel;
use App\Models\OrderServicesModel;
use App\Models\MediaListingDetailModel;
use App\Models\MediaListingMasterModel;
use App\Models\UserModel;
use App\Models\UserRoleModel;   
use App\Models\RoleModel;
use App\Models\SiteSettingModel;


use session;
use Validator;
use file;
use DB;
use sentinel;
use Datatables;
use Flash;
use PDF;

class BookingController extends Controller
{
	public function __construct(
                                TransactionModel $TransactionModel,
                                OrderDetailsModel $orderDetailsModel,
                                MediaListingDetailModel $media_listing_details,
                                MediaListingMasterModel $media_listing_master,
                                SiteSettingModel $site_setting_model,
                                UserModel $userModel
)
	{


    		$this->TransactionModel     		= $TransactionModel;
    		$this->BaseModel 				        = $this->TransactionModel;
    		$this->OrderDetailsModel     	  = $orderDetailsModel;
        $this->MediaListingDetailModel  = $media_listing_details;
        $this->MediaListingMasterModel  = $media_listing_master;
    	  $this->UserModel                = $userModel;
        $this->SiteSettingModel         = $site_setting_model;
        $this->arr_view_data            = [];
        
        $this->module_url_path          = url(config('app.project.admin_panel_slug')."/booking");
        $this->photos_public_img_path   = url('/').'/'.config('app.project.img_path.photos');
        $this->footage_public_img_path  = url('/').'/'.config('app.project.img_path.footage');
        $this->invoice_public_img_path  = url('/').'/'.config('app.project.invoice');

        $this->module_title             = "Bookings";
        $this->module_url_slug          = "booking";
        $this->module_view_folder       = "admin.booking";
        $this->theme_color              = theme_color();
	}
    public function index(Request $request)
    { 
        $enc_user_id = '';
        $back_url = $this->module_url_path;
        $page_title = "View ".str_plural($this->module_title);
        $module_title = str_plural($this->module_title);
        $module_icon = "fa fa-dollar";
        $page_title_sub  = $this->module_title;
        $toreview='';


        $this->arr_view_data['module_icon']     = $module_icon;
        $this->arr_view_data['back_url']        = $back_url;
        $this->arr_view_data['page_title']           = "Manage ".str_plural($this->module_title);
        $this->arr_view_data['module_title']         = str_plural($this->module_title);
        $this->arr_view_data['module_url_path']      = $this->module_url_path;
        $this->arr_view_data['theme_color']          = $this->theme_color;
        //dd($this->arr_view_data);
        return view($this->module_view_folder.'.index',$this->arr_view_data);
    }

    public function seller_list(Request $request)
    {
        $enc_user_id = '';
        $back_url = $this->module_url_path;
        $page_title = "View ".str_plural($this->module_title);
        $module_title = str_plural($this->module_title);
        $module_icon = "fa fa-dollar";
        $page_title_sub  = $this->module_title;
        $toreview='';


        $this->arr_view_data['module_icon']     = $module_icon;
        $this->arr_view_data['back_url']        = $back_url;
        $this->arr_view_data['page_title']           = "Manage ".str_plural($this->module_title);
        $this->arr_view_data['module_title']         = str_plural($this->module_title);
        $this->arr_view_data['module_url_path']      = $this->module_url_path;
        $this->arr_view_data['theme_color']          = $this->theme_color;
        //dd($this->arr_view_data);
        return view($this->module_view_folder.'.seller-index',$this->arr_view_data); 
    }
    public function users()
    {
        $user_data =[];
        $user_details             = $this->UserModel->getTable();
        $prefixed_user_details    = DB::getTablePrefix().$this->UserModel->getTable();

        $user_role_table          = $this->UserRoleModel->getTable();
        $prefixed_user_role_table = DB::getTablePrefix().$this->UserRoleModel->getTable();

        $role_table               = $this->RoleModel->getTable();
        $prefixed_role_table      = DB::getTablePrefix().$this->RoleModel->getTable();


        $user_data = DB::table($user_details)
                    ->select(DB::raw($prefixed_user_details.".id as id,".
                                      "CONCAT(".$prefixed_user_details.".first_name,' ',"
                                              .$prefixed_user_details.".last_name) as user_name"
                                     ))
                    ->join($user_role_table,$user_details.'.id','=',$user_role_table.'.user_id')
                    ->join($role_table, function ($join) use($role_table,$user_role_table) {
                        $join->on($role_table.'.id', ' = ',$user_role_table.'.role_id')
                             ->where('slug','=','user');
                    })
                    ->whereNull($user_details.'.deleted_at')
                    ->orderBy($user_details.'.created_at','DESC')
                    ->get();

        $data_user_arranged=[];
        if(isset($user_data))
        {
            foreach ($user_data as $key => $value) 
            {
                $data_user_arranged[]=$value->user_name;
            }
          
        }
        if(isset($data_user_arranged))
        {
             $data_user_arranged= json_encode($data_user_arranged);
        }

        echo $data_user_arranged;
    }
    
    public function export(Request $request)
    {
       // dd($request->all());

        $start_date     = $request->input('report_start');
        $end_date       = $request->input('report_end');
        $order_number   = $request->input('order_number');
        $purchaser_name = $request->input('purchaser_name');
        $transaction_id = $request->input('transaction_id');
        $order_amount   = $request->input('order_amount');
        $status         = $request->input('status');

        $transaction_details            = $this->BaseModel->getTable();
        $prefixed_transaction_details   = DB::getTablePrefix().$this->BaseModel->getTable();

        $user_details                  = $this->UserModel->getTable();
        $prefixed_user_details_table    = DB::getTablePrefix().$this->UserModel->getTable();


        $obj_arr_count = DB::table($transaction_details)
                                ->select(DB::raw($prefixed_transaction_details.".id,".
                                                 $prefixed_transaction_details.".order_number,".
                                                 $prefixed_transaction_details.".transaction_date as booking_date, ".
                                                 $prefixed_transaction_details.".txn_id as transaction_id, ".
                                                 $prefixed_transaction_details.".status, ".
                                                 $prefixed_transaction_details.".total as order_amount, ".
                                                 $prefixed_transaction_details.".status as status, ".
                                                 "CONCAT(".$prefixed_user_details_table.".first_name,' ',"
                                                          .$prefixed_user_details_table.".last_name) as user_name"
                                                 ))
                                ->where($transaction_details.'.status','PAID')
                                ->join($user_details,$transaction_details.'.buyer_id','=',$user_details.'.id')
                                ->groupBy($transaction_details.'.txn_id');
                              

        if($start_date!='' && $end_date!='' )
        {         
           $new_start_date = date("Y-m-d",strtotime($start_date));                     
           $new_end_date   = date("Y-m-d",strtotime($end_date));  
           $obj_arr_count=   $obj_arr_count->whereRaw("((DATE(transaction_date) >= '".$new_start_date."' and DATE(transaction_date) <= '".$new_end_date."')) ");

                              
        }                  
        if($order_number!='')
        {
            $obj_arr_count = $obj_arr_count->where($transaction_details.'.order_number','=', $order_number);
        }
         if($purchaser_name!='')
        {
            $obj_arr_count = $obj_arr_count->having('user_name','=',$purchaser_name);
        }
         if($transaction_id!='')
        {
            $obj_arr_count = $obj_arr_count->where($transaction_details.'.txn_id','=', $transaction_id);
        }
         if($order_amount!='')
        {
            $obj_arr_count = $obj_arr_count->where($transaction_details.'.total','=', $order_amount);
        }
         if($status!='')
        {
            $obj_arr_count = $obj_arr_count->where($transaction_details.'.status','=', $status);
        }


        $user_arr_count= $obj_arr_count->orderBy($user_details.'.created_at','DESC')->get();

        $format="csv";

        if($format=="csv")
        {
            $arr_tmp = array();
            if($user_arr_count)
            {           
                 \Excel::create('Booking_REPORT-'.date('Ymd').uniqid(), function($excel) use($user_arr_count) 
                  {
                      $excel->sheet('Client', function($sheet) use($user_arr_count) 
                      {
                          $sheet->cell('A1', function($cell) 
                          {
                              $cell->setValue('Generated on :'.date("d-m-Y H:i:s"));
                          });
                          $sheet->row(2, array(
                                                   'Order Number' ,
                                                   'Booking Date ',
                                                   'Transaction ID',
                                                   'Transaction Status',
                                                   'Amount',
                                                   'User NAme'
                                                   
                                            
                                              ));
                          $i=0;
                          foreach($user_arr_count as $key => $row)
                          {
                            $arr_tmp[$key][] = $row->order_number;
                            $arr_tmp[$key][] = date('d-m-Y H:i A', strtotime($row->booking_date));
                            $arr_tmp[$key][] = $row->transaction_id;
                            $arr_tmp[$key][] = $row->status?$row->status:'NA';
                            $arr_tmp[$key][] = $row->order_amount?$row->order_amount:'NA';
                            $arr_tmp[$key][] = $row->user_name?$row->user_name:'NA';
                          }
                          $sheet->rows($arr_tmp);                                      
                      });

                  })->export('csv');
            }
            else
            {
              Flash::error('No result found ');
              return redirect()->back();
            }
        }        
    }

    function get_booking_details(Request $request )
    {     
        $buyerid='';
        if($request->input('buyerid')!='')
        {          
          $buyerid = base64_decode($request->input('buyerid'));
        }
        
        $column = '';

        if ($request->input('order')[0]['column'] == 0) 
        {
          $column = "order_number";
        } 
        if ($request->input('order')[0]['column'] == 1) 
        {
          $column = "user_name";
        }
        if ($request->input('order')[0]['column'] == 2) 
        {
          $column = "booking_date";
        }
        if ($request->input('order')[0]['column'] == 3) 
        {
          $column = "transaction_id";
        }
        if ($request->input('order')[0]['column'] == 4) 
        {
          $column = "order_amount";
        }
        if ($request->input('order')[0]['column'] == 5) 
        {
          $column = "transaction_type";
        }
        if ($request->input('order')[0]['column'] == 6) 
        {
          $column = "status";
        }

        $order = strtoupper($request->input('order')[0]['dir']);

        $transaction_details      		= $this->BaseModel->getTable();
        $prefixed_transaction_details   = DB::getTablePrefix().$this->BaseModel->getTable();

        $order_details          		= $this->OrderDetailsModel->getTable();
        $prefixed_order_details_table 	= DB::getTablePrefix().$this->OrderDetailsModel->getTable();

        $user_details                = $this->UserModel->getTable();
        $prefixed_user_details_table    = DB::getTablePrefix().$this->UserModel->getTable();
        
        $obj_booking = DB::table($order_details)
                                ->select(DB::raw($prefixed_order_details_table.".id,".
                                                 $prefixed_order_details_table.".transaction_id as order_id,".
                                				         $prefixed_order_details_table.".buyer_id,".
                                                 $prefixed_order_details_table.".created_at as booking_date, ".
                                                 $prefixed_transaction_details.".invoice, ".
                                                 $prefixed_transaction_details.".total as order_amount, ".
                                                 $prefixed_transaction_details.".order_number, ".
                                                 $prefixed_transaction_details.".txn_id as transaction_id,".
                                                 $prefixed_transaction_details.".transaction_type,".                                                 
                                                 "UCASE(".$transaction_details.".status) as status, ".
                                                 "CONCAT(".$prefixed_user_details_table.".first_name,' ',"
                                                          .$prefixed_user_details_table.".last_name) as user_name"
                                                  ))
                                ->where($transaction_details.'.status','PAID')
                                ->join($transaction_details,$order_details.'.transaction_id','=',$transaction_details.'.id')
                                ->join($user_details,$transaction_details.'.buyer_id','=',$user_details.'.id');
                                if($buyerid!='')
                                {
                                  $obj_booking = $obj_booking->where($prefixed_order_details_table.'.buyer_id',$buyerid);
                                }
                                $obj_booking = $obj_booking/*->orderBy($order_details.'.created_at','DESC')*/
                                ->groupBy($transaction_details.'.txn_id');
                   
        /* ---------------- Filtering Logic ----------------------------------*/                    

        $arr_search_column = $request->input('column_filter');
        
        if(isset($arr_search_column['q_order_number']) && $arr_search_column['q_order_number']!="")
        {
            $search_term      = $arr_search_column['q_order_number'];
            $obj_booking      = $obj_booking->where($transaction_details.'.order_number','LIKE', '%'.$search_term.'%');
        }   
          if(isset($arr_search_column['q_purchaser_name']) && $arr_search_column['q_purchaser_name']!="")
        {
            $search_term      = $arr_search_column['q_purchaser_name'];
            $obj_booking      = $obj_booking->having('user_name','LIKE', '%'.$search_term.'%');
        }   

        if(isset($arr_search_column['q_booking_date']) && $arr_search_column['q_booking_date']!="")
        {
            $search_term      = $arr_search_column['q_booking_date'];  
            if($search_term != "")
            {
                $search_date  = date('Y-m-d',strtotime($search_term));                
                $obj_booking  = $obj_booking->whereRaw('DATE(order_details.created_at) = DATE(\''.$search_date.'\')');
            }
            // $obj_booking = $obj_booking->where($order_details.'.txn_date','=', '.$search_term.');
        }
        if(isset($arr_search_column['q_transaction_id']) && $arr_search_column['q_transaction_id']!="")
        {
            $search_term      = $arr_search_column['q_transaction_id'];
            $obj_booking      = $obj_booking->where($transaction_details.'.txn_id','LIKE', '%'.$search_term.'%');
        }
        if(isset($arr_search_column['q_order_amount']) && $arr_search_column['q_order_amount']!="")
        {
            $search_term      = $arr_search_column['q_order_amount'];
            $obj_booking      = $obj_booking->where($transaction_details.'.total','LIKE', '%'.$search_term.'%');
        }
        if(isset($arr_search_column['q_transaction_type']) && $arr_search_column['q_transaction_type']!="")
        {
            $search_term      = $arr_search_column['q_transaction_type'];
            $obj_booking      = $obj_booking->where($transaction_details.'.transaction_type','LIKE', '%'.$search_term.'%');
        }
        
       if(isset($arr_search_column['q_status']) && $arr_search_column['q_status']!="")
        {
            $search_term      = $arr_search_column['q_status'];
            $obj_booking      = $obj_booking->where($transaction_details.'.status','LIKE', '%'.$search_term.'%');
        }

        if(($order =='ASC' || $order =='') && $column=='')
        {
          $obj_booking  = $obj_booking->orderBy($order_details.'.created_at','DESC');
        }

        if( $order !='' && $column!='' && ($order !='ASC' && $column!='order_number') )
        {
          $obj_booking = $obj_booking->orderBy($column,$order);
        }

        if( $order =='ASC' && $column=='order_number' )
        {
          $obj_booking  = $obj_booking->orderBy($order_details.'.created_at','DESC');
        }

        return $obj_booking;
    }

    public function get_buyer_records(Request $request)
    {   
        $buyerid='';
        if($request->input('buyerid')!='')
        {          
          $buyerid = base64_decode($request->input('buyerid'));
        }

        $obj_booking     = $this->get_booking_details($request);
        $current_context = $this;

        $json_result     = Datatables::of($obj_booking);
        $json_result     = $json_result->blacklist(['id']);
        
        $json_result     = $json_result->editColumn('order_count',function($data) use ($current_context)
                            { 
                               return isset($data->id) && $data->id != "" ? count($data->id) : "-";                                   

                           })
                           ->editColumn('booking_date',function($data) use ($current_context)
                            {   
                               return isset($data->booking_date) && $data->booking_date != "" ? date('d-m-Y H:i A', strtotime($data->booking_date)) : "-";    
                           })
                           ->editColumn('build_action_btn',function($data) use ($current_context)
                            {       
                                $view_href  =  $this->module_url_path.'/buyer/view/'.base64_encode($data->order_id);
                                $build_view_action = '';
                                $build_view_action .= '<a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip call_loader" href="'.$view_href.'" title="View"><i class="fa fa-eye" ></i></a>';
                                
                                $invoice_href  =  $this->invoice_public_img_path.$data->invoice;
                                $build_invoice_action = '';
                                $build_invoice_action .= '<a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip call_loader" href="'.$invoice_href.'" title="Invoice" target="_blank"><i class="fa fa-file-pdf-o" ></i></a>';

                                return $build_view_action.' '.$build_invoice_action;
                            })
                            ->make(true);

        $build_result = $json_result->getData();
        
        
        return response()->json($build_result);
    }

    public function export_seller(Request $request)
    {
       // dd($request->all());
        
        $start_date     = $request->input('report_start');
        $end_date       = $request->input('report_end');
        $order_number   = $request->input('order_number');
        $purchaser_name = $request->input('purchaser_name');
        $transaction_id = $request->input('transaction_id');
        $order_amount   = $request->input('order_amount');
        $status         = $request->input('status');

        $transaction_details          = $this->BaseModel->getTable();
        $prefixed_transaction_details   = DB::getTablePrefix().$this->BaseModel->getTable();

        $order_details              = $this->OrderDetailsModel->getTable();
        $prefixed_order_details_table   = DB::getTablePrefix().$this->OrderDetailsModel->getTable();

        $user_details                = $this->UserModel->getTable();
        $prefixed_user_details_table    = DB::getTablePrefix().$this->UserModel->getTable();

        $media_listing_details                = $this->MediaListingDetailModel->getTable();
        $prefixed_media_listing_details    = DB::getTablePrefix().$this->MediaListingDetailModel->getTable();

        $media_listing_master                = $this->MediaListingMasterModel->getTable();
        $prefixed_media_listing_master    = DB::getTablePrefix().$this->MediaListingMasterModel->getTable();
        
        $obj_arr_count = DB::table($order_details)
                                ->select(DB::raw($prefixed_order_details_table.".id,".
                                                 $prefixed_order_details_table.".transaction_id as order_id,".
                                                 $prefixed_order_details_table.".item_id,".  
                                                 $prefixed_media_listing_details.".list_id,".                                            
                                                 $prefixed_order_details_table.".seller_id,".
                                                 $prefixed_order_details_table.".created_at as booking_date, ".
                                                 $prefixed_transaction_details.".order_number, ".
                                                 $prefixed_media_listing_master.".commission,".
                                                 $prefixed_order_details_table.".price, ".
                                                 "SUM(".$prefixed_order_details_table.".price) as price_total, ".
                                                 $prefixed_transaction_details.".txn_id as transaction_id,".
                                                 $prefixed_transaction_details.".transaction_type,".
                                                 "UCASE(".$transaction_details.".status) as status, ".
                                                 "CONCAT(".$prefixed_user_details_table.".first_name,' ',"
                                                          .$prefixed_user_details_table.".last_name) as user_name"
                                                  ))
                                ->where($transaction_details.'.status','PAID')
                                ->join($transaction_details,$order_details.'.transaction_id','=',$transaction_details.'.id')
                                ->join($media_listing_details,$prefixed_media_listing_details.'.id','=',$prefixed_order_details_table.'.item_id')
                                ->join($media_listing_master,$prefixed_media_listing_master.'.id','=',$media_listing_details.'.list_id')
                                ->join($user_details,$order_details.'.seller_id','=',$user_details.'.id');                              
                                $obj_arr_count = $obj_arr_count->orderBy($order_details.'.created_at','DESC')
                                ->groupBy($transaction_details.'.txn_id',$prefixed_order_details_table.'.seller_id');

        if($start_date!='' && $end_date!='' )
        {         
           $new_start_date = date("Y-m-d",strtotime($start_date));                     
           $new_end_date   = date("Y-m-d",strtotime($end_date));  
           $obj_arr_count=   $obj_arr_count->whereRaw("((DATE(created_at) >= '".$new_start_date."' and DATE(created_at) <= '".$new_end_date."')) ");
                              
        }                  
        if($order_number!='')
        {
            $obj_arr_count = $obj_arr_count->where($transaction_details.'.order_number','=', $order_number);
        }
         if($purchaser_name!='')
        {
            $obj_arr_count = $obj_arr_count->having('user_name','=',$purchaser_name);
        }
         if($transaction_id!='')
        {
            $obj_arr_count = $obj_arr_count->where($transaction_details.'.txn_id','=', $transaction_id);
        }
         if($order_amount!='')
        {
            $obj_arr_count = $obj_arr_count->where($order_details.'.price_total','=', $order_amount);
        }
         if($status!='')
        {
            $obj_arr_count = $obj_arr_count->where($transaction_details.'.status','=', $status);
        }


        $user_arr_count= $obj_arr_count->orderBy($user_details.'.created_at','DESC')->get();

        $format="csv";        
        if($format=="csv")
        {
            $arr_tmp = array();
            if($user_arr_count)
            {           
                 \Excel::create('Booking_REPORT-'.date('Ymd').uniqid(), function($excel) use($user_arr_count) 
                  {
                      $excel->sheet('Client', function($sheet) use($user_arr_count) 
                      {
                          $sheet->cell('A1', function($cell) 
                          {
                              $cell->setValue('Generated on :'.date("d-m-Y H:i:s"));
                          });
                          $sheet->row(2, array(
                                                   'Order Number' ,
                                                   'Seller name ',
                                                   'Booking Date',
                                                   'Transaction ID',
                                                   'Transaction Type',
                                                   'Order Amount',
                                                   'Invoice Total'
                                              ));
                          $i=0;                          
                          foreach($user_arr_count as $key => $row)
                          {
                            $arr_tmp[$key][] = $row->order_number;
                            $arr_tmp[$key][] = ucfirst($row->user_name);                            
                            $arr_tmp[$key][] = date('d-m-Y H:i A', strtotime($row->booking_date));
                            $arr_tmp[$key][] = $row->transaction_id;
                            $arr_tmp[$key][] = $row->transaction_type;
                            $arr_tmp[$key][] = $row->price_total;
                            
                            $invoice_total= $this->getExportInvoiceAmmount($row->seller_id,$row->order_id);                                
                            
                            $arr_tmp[$key][] = $invoice_total;
                          }
                          $sheet->rows($arr_tmp);                                      
                      });

                  })->export('csv');
            }
            else
            {
              Flash::error('No result found ');
              return redirect()->back();
            }
        }        
    }

    function getExportInvoiceAmmount($seller_id,$order_id)
    {
      $obj_transaction = false; $arr_transaction = []; $total_ammount   = 0;
      $obj_transaction = $this->OrderDetailsModel->where('seller_id',$seller_id)
                                                                       ->where('transaction_id',$order_id)
                                                                       ->with('transaction_details','listing_details.master_details')
                                                                       ->get();
      if($obj_transaction!=false)
      {
        $arr_transaction = $obj_transaction->toArray();                              
        foreach ($arr_transaction as $key => $value)
        {
          $total_ammount   += $value['price'] - ($value['price'] * $value['listing_details']['master_details']['commission'] / 100);
        }
        $total_ammount   = sprintf("%.2f", $total_ammount);
        return $total_ammount;
      }  
    }


    function get_booking_seller_details(Request $request )
    {     
      $sellerid='';
      if($request->input('sellerid')!='')
      {          
        $sellerid = base64_decode($request->input('sellerid'));      
      }
      
      $column = '';

      if ($request->input('order')[0]['column'] == 0) 
      {
        $column = "order_number";
      } 
      if ($request->input('order')[0]['column'] == 1) 
      {
        $column = "user_name";
      }
      if ($request->input('order')[0]['column'] == 2) 
      {
        $column = "booking_date";
      }
      if ($request->input('order')[0]['column'] == 3) 
      {
        $column = "transaction_id";
      }
      if ($request->input('order')[0]['column'] == 4) 
      {
        $column = "transaction_type";
      }
      if ($request->input('order')[0]['column'] == 5) 
      {
        $column = "price_total";
      }

      $order = strtoupper($request->input('order')[0]['dir']);

      $transaction_details            = $this->BaseModel->getTable();
      $prefixed_transaction_details   = DB::getTablePrefix().$this->BaseModel->getTable();

      $order_details                  = $this->OrderDetailsModel->getTable();
      $prefixed_order_details_table   = DB::getTablePrefix().$this->OrderDetailsModel->getTable();

      $user_details                   = $this->UserModel->getTable();
      $prefixed_user_details_table    = DB::getTablePrefix().$this->UserModel->getTable();

      $media_listing_details             = $this->MediaListingDetailModel->getTable();
      $prefixed_media_listing_details    = DB::getTablePrefix().$this->MediaListingDetailModel->getTable();

      $media_listing_master             = $this->MediaListingMasterModel->getTable();
      $prefixed_media_listing_master    = DB::getTablePrefix().$this->MediaListingMasterModel->getTable();
      
      $obj_booking = DB::table($order_details)
                              ->select(DB::raw($prefixed_order_details_table.".id,".
                                               $prefixed_order_details_table.".transaction_id as order_id,".
                                               $prefixed_order_details_table.".item_id,".  
                                               $prefixed_media_listing_details.".list_id,".                                            
                                               $prefixed_order_details_table.".seller_id,".
                                               $prefixed_order_details_table.".created_at as booking_date, ".
                                               $prefixed_transaction_details.".order_number, ".
                                               $prefixed_media_listing_master.".commission,".
                                               $prefixed_order_details_table.".price, ".
                                               "SUM(".$prefixed_order_details_table.".price) as price_total, ".
                                               $prefixed_transaction_details.".txn_id as transaction_id,".
                                               $prefixed_transaction_details.".transaction_type,".
                                               "UCASE(".$transaction_details.".status) as status, ".
                                               "CONCAT(".$prefixed_user_details_table.".first_name,' ',"
                                                        .$prefixed_user_details_table.".last_name) as user_name"
                                                ))
                              ->where($transaction_details.'.status','PAID')
                              ->join($transaction_details,$order_details.'.transaction_id','=',$transaction_details.'.id')
                              ->join($media_listing_details,$prefixed_media_listing_details.'.id','=',$prefixed_order_details_table.'.item_id')
                              ->join($media_listing_master,$prefixed_media_listing_master.'.id','=',$media_listing_details.'.list_id')
                              ->join($user_details,$order_details.'.seller_id','=',$user_details.'.id');
                              if($sellerid!='')
                              {
                                $obj_booking = $obj_booking->where($prefixed_order_details_table.'.seller_id',$sellerid);
                              }
                              $obj_booking = $obj_booking/*->orderBy($order_details.'.created_at','DESC')*/
                              ->groupBy($transaction_details.'.txn_id',$prefixed_order_details_table.'.seller_id');
                      
      /* ---------------- Filtering Logic ----------------------------------*/                    

      $arr_search_column = $request->input('column_filter');
      
      if(isset($arr_search_column['q_order_number']) && $arr_search_column['q_order_number']!="")
      {
          $search_term      = $arr_search_column['q_order_number'];
          $obj_booking      = $obj_booking->where($transaction_details.'.order_number','LIKE', '%'.$search_term.'%');
      }   
      if(isset($arr_search_column['q_purchaser_name']) && $arr_search_column['q_purchaser_name']!="")
      {
          $search_term      = $arr_search_column['q_purchaser_name'];
          $obj_booking      = $obj_booking->having('user_name','LIKE', '%'.$search_term.'%');
      }   

      if(isset($arr_search_column['q_booking_date']) && $arr_search_column['q_booking_date']!="")
      {
          $search_term      = $arr_search_column['q_booking_date'];  
          if($search_term != "")
          {
              $search_date  = date('Y-m-d',strtotime($search_term));                
              $obj_booking  = $obj_booking->whereRaw('DATE(order_details.created_at) = DATE(\''.$search_date.'\')');
          }
      }

      if(isset($arr_search_column['q_transaction_id']) && $arr_search_column['q_transaction_id']!="")
      {
          $search_term      = $arr_search_column['q_transaction_id'];
          $obj_booking      = $obj_booking->where($transaction_details.'.txn_id','LIKE', '%'.$search_term.'%');
      }

      if(isset($arr_search_column['q_price_total']) && $arr_search_column['q_price_total']!="")
      {
          $search_term      = $arr_search_column['q_price_total'];
          $obj_booking      = $obj_booking->having('price_total','LIKE', '%'.$search_term.'%');
      }
      
      if(isset($arr_search_column['q_transaction_type']) && $arr_search_column['q_transaction_type']!="")
      {
          $search_term      = $arr_search_column['q_transaction_type'];
          $obj_booking      = $obj_booking->where($transaction_details.'.transaction_type','LIKE', '%'.$search_term.'%');
      }

      if(($order =='ASC' || $order =='') && $column=='')
      {
        $obj_booking  = $obj_booking->orderBy($order_details.'.created_at','DESC');
      }
      
      if( $order !='' && $column!='' && ($order !='ASC' && $column!='order_number') )
      {
        $obj_booking = $obj_booking->orderBy($column,$order);
      }

      if( $order =='ASC' && $column=='order_number' )
      {
        $obj_booking  = $obj_booking->orderBy($order_details.'.created_at','DESC');
      }
      
      return $obj_booking;
    }

    public function get_seller_records(Request $request)
    {   
        $sellerid='';
        if($request->input('sellerid')!='')
        {          
          $sellerid = base64_decode($request->input('sellerid'));
        }

        $obj_booking     = $this->get_booking_seller_details($request);
        $current_context = $this;

        $json_result     = Datatables::of($obj_booking);
        $json_result     = $json_result->blacklist(['id']);
        
        $json_result     = $json_result
                            ->editColumn('build_status_btn',function($data) use ($current_context)
                            {
                                $obj_transaction = false;
                                $arr_transaction = [];
                                $total_ammount   = 0;
                                $build_trans_status = '';

                                $obj_transaction = $this->OrderDetailsModel->where('seller_id',$data->seller_id)
                                                                           ->where('transaction_id',$data->order_id)
                                                                           ->with('transaction_details','listing_details.master_details')
                                                                           ->get();
                                if($obj_transaction!=false)
                                {
                                  $arr_transaction = $obj_transaction->toArray();
                                  if(isset($arr_transaction[0]) && $arr_transaction[0]['seller_status']=='paid')
                                  {
                                    $view_href           =  $this->module_url_path.'/seller/unpaid/'.base64_encode($data->order_id).'/'.base64_encode($data->seller_id);
                                    $build_trans_status .= '<a href="'.$view_href.'" class="btn btn-sm btn-success show-tooltip" onclick="return confirm_action(this,event,\'Do you really want to change status to Unpaid ?\')" title="" data-original-title="Paid">Paid</a>';
                                  }
                                  else
                                  {
                                    $view_href           =  $this->module_url_path.'/seller/paid/'.base64_encode($data->order_id).'/'.base64_encode($data->seller_id);
                                    $build_trans_status .= '<a href="'.$view_href.'" class="btn btn-sm btn-danger show-tooltip" onclick="return confirm_action(this,event,\'Do you really want to change status to Paid ?\')" title="" data-original-title="Unpaid">Unpaid</a>';
                                  }
                                }

                                return $build_status = $build_trans_status;
                            })
                          ->editColumn('invoice_ammount',function($data) use ($current_context)
                            {
                                $obj_transaction = false;
                                $arr_transaction = [];
                                $total_ammount   = 0;

                                $obj_transaction = $this->OrderDetailsModel->where('seller_id',$data->seller_id)
                                                                           ->where('transaction_id',$data->order_id)
                                                                           ->with('transaction_details','listing_details.master_details')
                                                                           ->get();
                                if($obj_transaction!=false)
                                {
                                  $arr_transaction = $obj_transaction->toArray();
                                  
                                  foreach ($arr_transaction as $key => $value)
                                  {
                                    $total_ammount   += $value['price'] - ($value['price'] * $value['listing_details']['master_details']['commission'] / 100);
                                  }
                                  $total_ammount   = sprintf("%.2f", $total_ammount);
                                  return $total_ammount;
                                }
                                
                            })
                          ->editColumn('booking_date',function($data) use ($current_context)
                            {   
                               return isset($data->booking_date) && $data->booking_date != "" ? date('d-m-Y H:i A', strtotime($data->booking_date)) : "-";
                           })
                          ->editColumn('build_action_btn',function($data) use ($current_context)
                            {       
                                $view_href          =  $this->module_url_path.'/seller/view/'.base64_encode($data->order_id).'/'.base64_encode($data->seller_id);
                                $build_view_action  = '';
                                $build_view_action .= '<a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip call_loader" href="'.$view_href.'" title="View"><i class="fa fa-eye" ></i></a>';

                                $invoice_href          =  $this->module_url_path.'/seller/invoice/'.base64_encode($data->order_id).'/'.base64_encode($data->seller_id);
                                $build_invoice_action  = '';
                                $build_invoice_action .= '<a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip call_loader" href="'.$invoice_href.'" title="Invoice" target="_blank"><i class="fa fa-file-pdf-o" ></i></a>';

                                return $build_view_action.' '.$build_invoice_action;
                            })
                            ->make(true);

        $build_result = $json_result->getData();
        
        
        return response()->json($build_result);
    }
    
    public function view(Request $request,$enc_id=false)
    {
      $back_url = url('/').'/admin/booking/buyer';
      $temp_arr=[];  $seller_details=[];
    	$transaction_id = base64_decode($enc_id);
      $booking = $this->TransactionModel->with('buyer_details')
                                    ->with(['order_details.listing_details'=>function($query){
                                      $query->with('master_details','format_Details','orientation_Details','resolution_Details');
                                    }])
                                    ->where('id',$transaction_id)
                                    ->first();    	
    	
        if($booking != FALSE)
        {
            $booking = $booking->toArray();                        
            //dump($booking);
            if(isset($booking) && count($booking)>0)
            {
              if(isset($booking['order_details']) && count($booking['order_details'])>0)

              foreach ($booking['order_details'] as $key => $value) 
              {
                  $temp_arr[$key]['order_details_id'] = $value['id'];
                  $temp_arr[$key]['order_number']     = $value['order_number'];
                  $temp_arr[$key]['type']             = ucfirst($value['type']);                  
                  $temp_arr[$key]['download_attempt'] = $value['download_attempt'];                  
                  
                  if($value['type']=='photo' && isset($value['listing_details'])&&count($value['listing_details'])>0)
                  {
                    $temp_arr[$key]['enc_item_name']    = $this->photos_public_img_path.$value['listing_details']['enc_item_name'];  
                  }

                  if($value['type']=='footage' && isset($value['listing_details'])&&count($value['listing_details'])>0)
                  {
                    $temp_arr[$key]['enc_item_name']    = $this->footage_public_img_path.$value['listing_details']['enc_item_name'];  
                  }                  
                  
                  $temp_arr[$key]['price']            = isset($value['listing_details'])&&count($value['listing_details'])>0?$value['listing_details']['price']:"";
                  $temp_arr[$key]['title']            = isset($value['listing_details']['master_details'])&&count($value['listing_details']['master_details'])>0?ucfirst($value['listing_details']['master_details']['title']):"";
                  $temp_arr[$key]['description']      = isset($value['listing_details']['master_details'])&&count($value['listing_details']['master_details'])>0?ucfirst($value['listing_details']['master_details']['description']):"";
                  $temp_arr[$key]['format_name']      = isset($value['listing_details']['format__details'])&&count($value['listing_details']['format__details'])>0?ucfirst($value['listing_details']['format__details']['name']):"";
                  $temp_arr[$key]['format_type']      = isset($value['listing_details']['format__details'])&&count($value['listing_details']['format__details'])>0?$value['listing_details']['format__details']['type']:"";
                  $temp_arr[$key]['value']            = isset($value['listing_details']['orientation__details'])&&count($value['listing_details']['orientation__details'])>0?ucfirst($value['listing_details']['orientation__details']['value']):"";
                  $temp_arr[$key]['size']             = isset($value['listing_details']['resolution__details'])&&count($value['listing_details']['resolution__details'])>0?$value['listing_details']['resolution__details']['size']:"";

              }
            }

            if($request->input('btnUpdateAttempt')=="Update")
            {
              $arr_rules=[];
              $arr_rules['download_attempt'] = 'required';

              $validator = Validator::make($request->all(),$arr_rules);
              if($validator->fails())
              {
                return redirect()->back()->withErrors($validator)->withInput($request->all());
              }

              $download_attempt = $request->input('download_attempt');
              $id               = $request->input('id');

              if($this->OrderDetailsModel->where('id',$id)->update(['download_attempt'=>$download_attempt]))
              {
                Flash::success('Download Attempt Update Successfully');
              }
              else
              {
                  Flash::error('Problem Occurred, While Updating Download Attempt');
              }

              return redirect()->back();
            }

        }


        //dd($temp_arr);
        $this->arr_view_data['booking']           	          = $booking;
        $this->arr_view_data['seller_details']                = $seller_details;
        $this->arr_view_data['back_url']                      = $back_url;
        $this->arr_view_data['booking_details']               = $temp_arr;
        $this->arr_view_data['page_title']                    = "View ".str_plural($this->module_title);
        $this->arr_view_data['module_title']                  = str_plural($this->module_title);
        $this->arr_view_data['module_url_path']               = $this->module_url_path;
        $this->arr_view_data['theme_color']                   = $this->theme_color;
       
        return view($this->module_view_folder.'.view',$this->arr_view_data);
    }

    public function seller_view(Request $request,$enc_id=false,$seller_id=false)
    {
      $back_url = url('/').'/admin/booking/seller';

      $temp_arr=[]; $seller_details=[];
      $transaction_id = base64_decode($enc_id);
      $sellerid = base64_decode($seller_id);

      $seller_details_obj = $this->UserModel->where('id',$sellerid)->select('id','email','first_name','last_name')->first();
      $booking = $this->TransactionModel->with('buyer_details')                                        
                                        ->with(['order_details'=>function($q) use ($sellerid){                                          
                                            $q->where('seller_id',$sellerid);                                                                                    
                                            $q->with(['listing_details'=>function($query){                                          
                                              $query->with('master_details','format_Details','orientation_Details','resolution_Details');
                                            }]);                             
                                          }])
                                      ->where('id',$transaction_id)
                                      ->first();      
      if(isset($seller_details_obj) && count($seller_details_obj)>0)
      {
        $seller_details['seller_name']  = ucfirst($seller_details_obj['first_name'].' '.$seller_details_obj['last_name']);
        $seller_details['seller_email'] = $seller_details_obj['email'];
        $seller_details['seller_address'] = $seller_details_obj['address'];
        $seller_details['seller_contact_number'] = $seller_details_obj['contact_number'];
        
      }
      
      if($booking != FALSE)
      {
          $booking = $booking->toArray();                                 
          if(isset($booking) && count($booking)>0)
          {
            if(isset($booking['order_details']) && count($booking['order_details'])>0)

            foreach ($booking['order_details'] as $key => $value) 
            {
                $temp_arr[$key]['order_details_id'] = $value['id'];
                $temp_arr[$key]['order_number']     = $value['order_number'];
                $temp_arr[$key]['type']             = ucfirst($value['type']);                  
                $temp_arr[$key]['download_attempt'] = $value['download_attempt'];                  
                
                if($value['type']=='photo' && isset($value['listing_details'])&&count($value['listing_details'])>0)
                {
                  $temp_arr[$key]['enc_item_name']    = $this->photos_public_img_path.$value['listing_details']['enc_item_name'];  
                }

                if($value['type']=='footage' && isset($value['listing_details'])&&count($value['listing_details'])>0)
                {
                  $temp_arr[$key]['enc_item_name']    = $this->footage_public_img_path.$value['listing_details']['enc_item_name'];  
                }                             
                
                $temp_arr[$key]['price']            = isset($value['listing_details'])&&count($value['listing_details'])>0?$value['listing_details']['price']:"";
                $temp_arr[$key]['title']            = isset($value['listing_details']['master_details'])&&count($value['listing_details']['master_details'])>0?ucfirst($value['listing_details']['master_details']['title']):"";
                $temp_arr[$key]['description']      = isset($value['listing_details']['master_details'])&&count($value['listing_details']['master_details'])>0?ucfirst($value['listing_details']['master_details']['description']):"";
                $temp_arr[$key]['format_name']      = isset($value['listing_details']['format__details'])&&count($value['listing_details']['format__details'])>0?ucfirst($value['listing_details']['format__details']['name']):"";
                $temp_arr[$key]['format_type']      = isset($value['listing_details']['format__details'])&&count($value['listing_details']['format__details'])>0?$value['listing_details']['format__details']['type']:"";
                $temp_arr[$key]['value']      = isset($value['listing_details']['orientation__details'])&&count($value['listing_details']['orientation__details'])>0?ucfirst($value['listing_details']['orientation__details']['value']):"";
                $temp_arr[$key]['size']      = isset($value['listing_details']['resolution__details'])&&count($value['listing_details']['resolution__details'])>0?$value['listing_details']['resolution__details']['size']:"";

            }
          }

      }

        $this->arr_view_data['booking']                       = $booking;
        $this->arr_view_data['back_url']                      = $back_url;
        $this->arr_view_data['booking_details']               = $temp_arr;
        $this->arr_view_data['seller_details']                = $seller_details;        
        $this->arr_view_data['page_title']                    = "View ".str_plural($this->module_title);
        $this->arr_view_data['module_title']                  = str_plural($this->module_title);
        $this->arr_view_data['module_url_path']               = $this->module_url_path;
        $this->arr_view_data['theme_color']                   = $this->theme_color;
       
        return view($this->module_view_folder.'.view',$this->arr_view_data);
    }

    public function paid($enc_id,$seller_id)
    {
      if($enc_id!='' && $seller_id!='')
      {
         $transaction_id = base64_decode($enc_id);
         $seller_id      = base64_decode($seller_id);

         $status = $this->OrderDetailsModel->where('seller_id',$seller_id)
                                           ->where('transaction_id',$transaction_id)
                                           ->update(['seller_status'=>'paid']);
         if($status)
         {
            Flash::success('Status has been changed to Paid Successfully.');
         }
         return redirect()->back();                                           
      }
      else
      {
        return redirect()->back();
      }
    }

    public function unpaid($enc_id,$seller_id)
    {
      if($enc_id!='' && $seller_id!='')
      {
         $transaction_id = base64_decode($enc_id);
         $seller_id      = base64_decode($seller_id);

         $status = $this->OrderDetailsModel->where('seller_id',$seller_id)
                                           ->where('transaction_id',$transaction_id)
                                           ->update(['seller_status'=>'unpaid']);
         if($status)
         {
            Flash::success('Status has been changed to Unpaid Successfully.');
         }
         return redirect()->back();                                           
      }
      else
      {
        return redirect()->back();
      }
    } 

    public function invoice($enc_transaction_id,$enc_seller_id)
    {
      $arr_invoice                = array();
      $obj_invoice_details        = array();
      $user                       = false;
      $obj_site_settings_details  = false;
      $total_amount               = 0;

      $user = Sentinel::check();

      if($enc_transaction_id!="" && $enc_seller_id!="")
      {
        $transaction_id = base64_decode($enc_transaction_id);
        $seller_id      = base64_decode($enc_seller_id);

        $obj_invoice_details = $this->TransactionModel->where('id',$transaction_id)
                                                      ->with(['order_details' => function($query) use ($transaction_id,$seller_id) {
                                                        $query->where('transaction_id',$transaction_id)
                                                              ->where('seller_id',$seller_id)
                                                              ->with('listing_details.master_details')
                                                              ->with('seller_details');
                                                            }])
                                                      ->OrderBy('created_at','DESC')
                                                      ->first();
        if(count($obj_invoice_details)<=0)
        {
          return redirect()->back();
        }

        $obj_site_settings_details = $this->SiteSettingModel->where('site_setting_id',1)
                                          ->select('site_name','info_email_address','billing_email_address','site_contact_number','site_address')
                                          ->first();

        if($obj_invoice_details)
        {
          $obj_invoice_details          = $obj_invoice_details->toArray();
          $arr_invoice                  = $obj_invoice_details;
          $arr_invoice['invoice_id']    = $arr_invoice['order_number'];
          $arr_invoice['client_name']   = isset($arr_invoice['order_details'][0]['seller_details'])?$arr_invoice['order_details'][0]['seller_details']['first_name'].' '.$arr_invoice['order_details'][0]['seller_details']['last_name'] : "";  
          $arr_invoice['invoice_date']  = isset($arr_invoice['transaction_date']) && $arr_invoice['transaction_date']!= null ? date('d M Y',strtotime($arr_invoice['transaction_date'])) : "";
          
          if($obj_site_settings_details!=false)
          {
            $arr_invoice['site_name']       = $obj_site_settings_details['site_name'];
            $arr_invoice['email']           = $obj_site_settings_details['info_email_address'];
            $arr_invoice['billing_email']   = $obj_site_settings_details['billing_email_address'];
            $arr_invoice['contact_number']  = $obj_site_settings_details['site_contact_number'];
            $arr_invoice['address']         = $obj_site_settings_details['site_address'];
          }
        }

        $html  = '';
        $html .= '<table width="100%" border="0" bgcolor="#2d2d2d" cellspacing="20" cellpadding="0" height="100%" style="border:1px solid #777777;font-family:Arial, Helvetica, sans-serif;margin:0 auto; background:#2d2d2d; height:100%;">
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="120px" style="background-color: #dbb339; verticle-align: middle; text-align: center; color:#2d2d2d;font-size:14.5pt;font-weight:bold;text-decoration: none;width: 120px; text-align: center; margin-bottom: 0; " valign="middle">  Invoice</td>
          <td >
          &nbsp;
          </td>   
          </tr>
      </table>  
    </td>
  </tr>
    
    <tr>
        <td>
            <table width="100%" border="0" cellspacing="10" cellpadding="0" style="background-color:#353535;color:#a4a4a4;font-size:11pt;">
            <tr>
                <td width="60%">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>Invoice ID : </td>
                            <td>'.$arr_invoice['invoice_id'].'</td>
                        </tr>
                        <tr>
                            <td>Purchased Date : </td>
                            <td>'.$arr_invoice['invoice_date'].'</td>
                        </tr>
                    </table>
                </td>
                <td width="40%">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>To:  '.$arr_invoice['client_name'].'  </td>
                        </tr>
                        <tr>
                            <td>From: '.$arr_invoice['site_name'].' </td>
                        </tr>
                         <tr>
                            <td>'.$arr_invoice['address'].' </td>
                        </tr>
                         <tr>
                            <td>Tel: '.$arr_invoice['contact_number'].' </td>
                        </tr>
                         <tr>
                            <td>Email: '.$arr_invoice['email'].'</td>
                        </tr>
                        <tr>
                            <td><span style="color:#dbb339;text-decoration: none;">www.filmunit.com</span></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        </td>
    </tr>
  <tr>
      <td style="font-size:18pt;color:#a4a4a4; font-weight:bold; margin-left: 15px;">Product Details</td>
  </tr>
  <tr>
      <td>
          <table width="100%" border="0" cellspacing="10" cellpadding="0" style="background-color:#353535;color:#a4a4a4;font-size:11pt;">
              
             <tr>
               <th width="10%" style="text-align:left;">Sr. No.</th> 
               <th width="40%" style="text-align:left;">Items</th>
               <th width="15%" style="text-align:left;">Unit Price</th>
               <th width="15%" style="text-align:left;">Commission</th>
               <th width="20%" style="text-align:left;">Total</th> 
             </tr>';
              
              if(isset($arr_invoice['order_details']) && count($arr_invoice['order_details'])>0)
              {
                foreach($arr_invoice['order_details'] as $key => $order)
                {
                   $html .='<tr>
                      <td>'.++$key.'</td>
                      <td>'.$order['listing_details']['master_details']['title'].'('.$order['type'].') </td>
                      <td>$ '.sprintf("%.2f", $order['price']).'</td>
                      <td>'.$order['listing_details']['master_details']['commission'].'%</td>
                      <td>$ '.sprintf("%.2f",$order['price'] - ($order['price'] * $order['listing_details']['master_details']['commission']/100)).'</td>
                      </tr>';

                      $total_amount += $order['price'] - ($order['price'] * $order['listing_details']['master_details']['commission']/100);
                }
              }

          $html .= '</table>
                    </td>
                </tr>
                <tr>
                    <td>
                    <table width="100%" border="0" cellspacing="10" cellpadding="0">
                         <tr>
                            <td width="60%">&nbsp;</td>
                            <td width="40%">
                                <table width="100%" border="0" cellspacing="10" cellpadding="0" style="font-size:11pt;">
                                    <tr style="text-align:right; color:#a4a4a4;">
                                        <td >Sub Total :</td>
                                        <td style="font-weight: bold;">$ '.sprintf("%.2f", $total_amount).'</td>
                                    </tr>
                                </table>
                            </td>
                            
                        </tr>
                        <tr>
                           <td width="60%"  style="background-color: #353535;">&nbsp;</td>
                            <td width="40%"  style="background-color: #353535;">
                                <table width="100%" border="0" cellspacing="10" cellpadding="0">
                                    <tr style="color:#fafafa; text-align:right;">
                                        <td>
                                            TOTAL: 
                                        </td>
                                        <td style="font-weight: bold;color:#fafafa;font-size:11pt; text-align: right;">$ '.sprintf("%.2f", $total_amount).'</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                   </td>
                  </tr>
                <br><br>
              </table>';
                  
        $file_name = $arr_invoice['invoice_id'];
        PDF::SetTitle('FilmUnit Invoice');
        PDF::AddPage();
        PDF::writeHTML($html, true, false, true, false, '');

        PDF::Output($file_name.'.pdf');
      }
      else
      {
        return redirect()->back();
      }
    }

}
