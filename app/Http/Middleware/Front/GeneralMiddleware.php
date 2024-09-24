<?php

namespace App\Http\Middleware\Front;
use Closure;
use Sentinel;
use Session;

use App\Models\SiteSettingModel;
use App\Models\StaticPageModel;

use App;
use DB;
use Flash;
class GeneralMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next )
    {        
        $visitor_id = '';
        $visitor_id = Session::get('visitor_id'); /* minutes */
        $cache_time = 30; /* minutes */
        $user       = false;

        $user = Sentinel::check();

        if($user!=false && $user['role']!='admin')
        {
            if($user['is_block']==1)
            {
                Sentinel::logout();
                Flash::error('Your account is blocked by admin, please contact to admin.');
                return redirect(url('login'));
            }
        }

        if(!Session::has('locale'))
        {   
           Session::put('locale', \Config::get('app.locale'));
        }

        App::setLocale(Session::get('locale'));

        view()->share('selected_lang',Session::get('locale'));
       
        $current_url_route = app()->router->getCurrentRoute()->uri();
        
        /* Site Setting*/    
        
        $arr_site_settings = [];
        $site_setting = SiteSettingModel::first();

        if($site_setting) 
        {
            $arr_site_settings = $site_setting->toArray();

            if($arr_site_settings['site_status']==0 && $request->path() != 'site_offline')
            {
                return response()->view('site_offline');
            }
            /*elseif($arr_site_settings['site_status']==1 && $request->path() == 'site_offline')
            {
                return view('comming_soon');
            }*/
        }

        view()->share('arr_site_settings',$arr_site_settings); 

        $pages = [];
        $pages = DB::select('select post_name,post_title from wp_posts where post_type = "page" AND post_status="publish"');
        view()->share('pages',$pages); 

        /* Static Pages */ 
            
        $arr_static_pages = [];
        //static pages links share in footer
        $obj_static_pages = StaticPageModel::remember($cache_time)
                                            ->where('is_active',1)
                                            ->with(['translations'=>function($query) use ($cache_time)
                                            {
                                                return $query->remember($cache_time);
                                            }])
                                            ->get();
        
        if ($obj_static_pages)
        {
            $arr_tmp_static_pages = $obj_static_pages->toArray();
            
            if(isset($arr_tmp_static_pages) && sizeof($arr_tmp_static_pages))
            {
                $arr_static_pages = $arr_tmp_static_pages;
            }   
        }

        if($visitor_id=='')
        {   
           Session::put('visitor_id', uniqid());
        }
        
        $social_links = [];
        $arr_social_links=[];
        $social_links_obj = SiteSettingModel::first();
        if($social_links_obj)
        {
            $social_links =  $social_links_obj->toArray();
        }
        $arr_social_links = $social_links;
        view()->share('arr_social_links', $arr_social_links);

        view()->share('arr_static_pages',$arr_static_pages); 

        return $next($request);
    }

    


}
