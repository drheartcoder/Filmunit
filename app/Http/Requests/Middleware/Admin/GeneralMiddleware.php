<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Session;
use Sentinel;

class GeneralMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        Session::put('locale','en');
        view()->share('admin_panel_slug',config('app.project.admin_panel_slug'));
        view()->share('arr_current_user_access',$this->current_user_access($request));

        return $next($request);
    }
    
    public function current_user_access($request)
    {
        $data =[];
        
        $user = Sentinel::check();
        
        if($user)
        {
           $data = $request->user()->permissions;
        }
    
        return  $data;
        


    }
}
