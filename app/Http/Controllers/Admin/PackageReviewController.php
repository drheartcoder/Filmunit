<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\PackageModel;
use App\Models\PackageReviewModel;
use App\Models\UserModel;

use App\Common\Traits\MultiActionTrait;

use DB;
use Datatables;

class PackageReviewController extends Controller
{
	use MultiActionTrait;

    public function __construct(
    								PackageModel $package_model,
    								PackageReviewModel $package_review_model,
    								UserModel $user_model
    							)
    {
    	$this->PackageModel       = $package_model;
    	$this->UserModel          = $user_model;
    	$this->PackageReviewModel = $package_review_model;	
    	$this->BaseModel 		  = $this->PackageReviewModel;

    	$this->arr_view_data                = [];
        $this->module_url_path              = url(config('app.project.admin_panel_slug')."/packages/reviews");
        
        $this->module_title                 = "Package Review";
        $this->module_view_folder           = "admin.package_reviews";
        $this->theme_color                  = theme_color();
    }

    public function index(Request $request)
    {   	
		$this->arr_view_data['page_title']      = "View ".str_plural($this->module_title);
        $this->arr_view_data['module_title']    = str_plural($this->module_title);
        $this->arr_view_data['module_url_path'] = $this->module_url_path;
        $this->arr_view_data['theme_color']     = $this->theme_color;

        return view($this->module_view_folder.'.index', $this->arr_view_data);
    }

    function get_records_details(Request $request)
    { 
        $package_reviews             = $this->BaseModel->getTable();
        $prefixed_package_reviews    = DB::getTablePrefix().$this->BaseModel->getTable();

        $packages         		     = $this->PackageModel->getTable();
        $prefixed_packages           = DB::getTablePrefix().$this->PackageModel->getTable();

        $users               		 = $this->UserModel->getTable();
        $prefixed_users              = DB::getTablePrefix().$this->UserModel->getTable();

        $obj_user = DB::table($package_reviews)
                                ->select(DB::raw($prefixed_package_reviews.".id as id,".
                                                 $prefixed_package_reviews.".review as review, ".
                                                 $prefixed_package_reviews.".rating as rating, ".
                                                 $prefixed_package_reviews.".is_active as is_active, ".

                                                 "CONCAT(user.first_name,' ',user.last_name) as user_name, ".
                                                 "CONCAT(photographer.first_name,' ',photographer.last_name) as photographer_name, ".
                                                 "packages.title as package_title"
                                                 )
                                		)
                                ->join($packages.' as packages',$package_reviews.'.package_id','=','packages.id')
                                ->join($users.' as user',$package_reviews.'.user_id','=','user.id')
                                ->join($users.' as photographer','packages.user_id','=','photographer.id')
                                ->orderBy($package_reviews.'.created_at','DESC');

        /* ---------------- Filtering Logic ----------------------------------*/                    

        $arr_search_column = $request->input('column_filter');
        
        if(isset($arr_search_column['q_package_title']) && $arr_search_column['q_package_title']!="")
        {
            $search_term      = $arr_search_column['q_package_title'];
            $obj_user = $obj_user->having('package_title','LIKE', '%'.$search_term.'%');
        }
        
        if(isset($arr_search_column['q_review']) && $arr_search_column['q_review']!="")
        {
            $search_term      = $arr_search_column['q_review'];
            $obj_user = $obj_user->having('review','LIKE', '%'.$search_term.'%');
        }

        if(isset($arr_search_column['q_rating']) && $arr_search_column['q_rating']!="")
        {
            $search_term      = $arr_search_column['q_rating'];
            $obj_user = $obj_user->having('rating','LIKE', '%'.$search_term.'%');
        }

        if(isset($arr_search_column['q_user_name']) && $arr_search_column['q_user_name']!="")
        {
            $search_term      = $arr_search_column['q_user_name'];
            $obj_user = $obj_user->having('user_name','LIKE', '%'.$search_term.'%');
        }

        if(isset($arr_search_column['q_photographer']) && $arr_search_column['q_photographer']!="")
        {
            $search_term      = $arr_search_column['q_photographer'];
            $obj_user = $obj_user->having('photographer_name','LIKE', '%'.$search_term.'%');
        }

        return $obj_user;
    }

    public function get_records(Request $request)
    {
        $obj_user     = $this->get_records_details($request);

        $current_context = $this;

        $json_result     = Datatables::of($obj_user);

        $json_result     = $json_result->blacklist(['id']);
        
        $json_result     = $json_result->editColumn('enc_id',function($data) use ($current_context)
                            {
                                return base64_encode($data->id);
                            })
        					 ->editColumn('build_status_btn',function($data) use ($current_context)
                            {   
                                if($data->is_active != null && $data->is_active == "0")
                                {   
                                    $build_status_btn = '<a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Lock" href="'.$this->module_url_path.'/activate/'.base64_encode($data->id).'" 
                                    onclick="return confirm_action(this,event,\'Do you really want to activate this record ?\')" ><i class="fa fa-lock"></i></a>';
                                }
                                elseif($data->is_active != null && $data->is_active == "1")
                                {
                                    $build_status_btn = '<a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" title="Unlock" href="'.$this->module_url_path.'/deactivate/'.base64_encode($data->id).'" onclick="return confirm_action(this,event,\'Do you really want to deactivate this record ?\')" ><i class="fa fa-unlock"></i></a>';
                                }
                                return $build_status_btn;
                            })    
                            ->editColumn('build_action_btn',function($data) use ($current_context)
                            {
                                $delete_href =  $this->module_url_path.'/delete/'.base64_encode($data->id);
                                $build_delete_action = '<a class="btn btn-circle btn-to-success btn-bordered btn-fill show-tooltip" href="'.$delete_href.'" title="Delete" onclick="return confirm_action(this,event,\'Do you really want to delete this record ?\')" ><i class="fa fa-trash" ></i></a>';
                                return  $build_delete_action;
                            })
                            ->make(true);

        $build_result = $json_result->getData();
        
        return response()->json($build_result);
    }

}