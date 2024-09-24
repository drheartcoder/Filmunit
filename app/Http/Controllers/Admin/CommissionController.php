<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\CommissionModel;

use Flash;
use Validator;

class CommissionController extends Controller
{

	public function __construct(CommissionModel $commission_model)
	{
		$this->CommissionModel 	  = $commission_model;
		$this->BaseModel 		  = $this->CommissionModel;

		$this->arr_view_data 	  = [];
		$this->admin_url_path     = url(config('app.project.admin_panel_slug'));
        $this->module_url_path    = $this->admin_url_path."/commission";

        $this->module_icon        = "fa-money";
        $this->theme_color        = theme_color();

        $this->module_title       = "Commission";
        $this->module_view_folder = "admin.commission";
	}

	public function index()
	{
		$arr_data = [];
		$status   = false;

		$obj_commission = $this->BaseModel->first();
		if(isset($obj_commission) && sizeof($obj_commission)>0)
		{
			$arr_data = $obj_commission->toArray();
		}

        $this->arr_view_data['arr_data']        = $arr_data;
        $this->arr_view_data['page_title']      = $this->module_title;
        $this->arr_view_data['module_title']    = $this->module_title;
        $this->arr_view_data['module_icon']     = $this->module_icon;
        $this->arr_view_data['theme_color']     = $this->theme_color;
        $this->arr_view_data['module_url_path'] = $this->module_url_path;

        return view($this->module_view_folder.'.index',$this->arr_view_data);
	}
    
    public function update(Request $request)
    {
    	$arr_rules = [];
    	$arr_rules['commission'] = "required|numeric|max:99|min:0";

    	$validator = Validator::make($request->all(),$arr_rules);
    	if($validator->fails())
    	{
    		return redirect()->back()->withErrors($validator)->withInputs($request->all());
    	}
    	$arr_update['commission'] = $request->input('commission');
    	$status = $this->BaseModel->where('id','1')->update($arr_update);

    	if($status)
    	{
    		Flash::success(str_singular($this->module_title).' Updated Successfully'); 
    	}
    	else
    	{
    		Flash::error('Problem Occurred, While Updating '.str_singular($this->module_title));  
    	}
    	return redirect()->back();
    }
}
