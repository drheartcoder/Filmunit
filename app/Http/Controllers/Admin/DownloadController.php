<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\DownloadModel;

use Flash;
use Validator;

class DownloadController extends Controller
{

	public function __construct(DownloadModel $download_model)
	{
		$this->DownloadModel 	  = $download_model;
		$this->BaseModel 		  = $this->DownloadModel;

		$this->arr_view_data 	  = [];
		$this->admin_url_path     = url(config('app.project.admin_panel_slug'));
        $this->module_url_path    = $this->admin_url_path."/download";

        $this->module_icon        = "fa-download";
        $this->theme_color        = theme_color();

        $this->module_title       = "Download Attempts";
        $this->module_view_folder = "admin.download";
	}

	public function index()
	{
		$arr_data = [];
		$status   = false;

		$obj_download = $this->BaseModel->first();
		if(isset($obj_download) && sizeof($obj_download)>0)
		{
			$arr_data = $obj_download->toArray();
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
    	$arr_rules['attempts'] = "required";

    	$validator = Validator::make($request->all(),$arr_rules);
    	if($validator->fails())
    	{
    		return redirect()->back()->withErrors($validator)->withInputs($request->all());
    	}
    	$arr_update['attempts'] = $request->input('attempts');
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
