    @extends('admin.layout.master')                 


    @section('main_content')
    <!-- BEGIN Page Title -->
    <div class="page-title">
        <div>

        </div>
    </div>
    <!-- END Page Title -->

    <!-- BEGIN Breadcrumb -->
    <div id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{ url($admin_panel_slug.'/dashboard') }}">Dashboard</a>
            </li>
            <span class="divider">
                <i class="fa fa-angle-right"></i>
                <i class="fa fa-user-secret"></i>
                <a href="{{ $module_url_path }}">{{ $module_title or ''}}</a>
            </span> 
            <span class="divider">
                <i class="fa fa-angle-right"></i>
                  <i class="fa fa-edit"></i>
            </span>
            <li class="active">{{ $page_title or ''}}</li>
        </ul>
      </div>
    <!-- END Breadcrumb -->

    <!-- BEGIN Main Content -->
    <div class="row">
      <div class="col-md-12">

          <div class="box {{ $theme_color }}">
            <div class="box-title">
              <h3>
                <i class="fa fa-edit"></i>
                {{ isset($page_title)?$page_title:"" }}
            </h3>
            <div class="box-tool">
                <a data-action="collapse" href="#"></a>
                <a data-action="close" href="#"></a>
            </div>
        </div>
        <div class="box-content">
          
          @include('admin.layout._operation_status')  
          {!! Form::open([ 'url' => $module_url_path.'/update/'.$enc_id,
                                 'method'=>'POST',
                                 'enctype' =>'multipart/form-data',   
                                 'class'=>'form-horizontal', 
                                 'id'=>'validation-form' 
                                ]) !!} 



           {{ csrf_field() }}

            <div class="form-group">
                  <label class="col-sm-3 col-lg-2 control-label">First Name<i class="red">*</i></label>
                  <div class="col-sm-9 col-lg-4 controls" >
                      <input type="text" class="form-control" name="first_name" value="{{ $obj_user->first_name or '' }}" data-rule-required="true" data-rule-maxlength="255"  data-rule-lettersonly ='true'   placeholder="First Name" />
                      <span class="help-block">{{ $errors->first('first_name') }}</span>
                  </div>
            </div>

            <div class="form-group">
                  <label class="col-sm-3 col-lg-2 control-label">Last Name<i class="red">*</i></label>
                  <div class="col-sm-9 col-lg-4 controls" >
                      <input type="text" class="form-control" name="last_name" value="{{ $obj_user->last_name or ''  }}" data-rule-required="true" data-rule-maxlength="255"  data-rule-lettersonly ='true'  placeholder="Last Name" />
                      <span class="help-block">{{ $errors->first('last_name') }}</span>
                  </div>
            </div>

            <div class="form-group">
                  <label class="col-sm-3 col-lg-2 control-label">Email<i class="red">*</i></label>
                  <div class="col-sm-9 col-lg-4 controls" >
                      <input type="text" class="form-control" name="email" value="{{ $obj_user->email or ''  }}" data-rule-required="true" data-rule-maxlength="255" data-rule-email="true" placeholder="Email" />
                      <span class="help-block">{{ $errors->first('email') }}</span>
                  </div>
            </div>

            <div class="form-group">
                  <label class="col-sm-3 col-lg-2 control-label">Password</label>
                  <div class="col-sm-9 col-lg-4 controls" >
                      <input type="password" class="form-control" name="password" value="" data-rule-minlength="6" data-rule-maxlength="255" id="password" placeholder="Password" />
                      <span class="help-block">{{ $errors->first('password') }}</span>
                  </div>
            </div>

            <div class="form-group">
                  <label class="col-sm-3 col-lg-2 control-label">Confirm Password</label>
                  <div class="col-sm-9 col-lg-4 controls" >
                      <input type="password" class="form-control" name="password_confirmation" value="" data-rule-minlength="6" data-rule-maxlength="255"  data-rule-equalto="#password" placeholder="Confirm Password" />
                      <span class="help-block">{{ $errors->first('password_confirmation') }}</span>
                  </div>
            </div>

            <div class="form-group">
                  <label class="col-sm-3 col-lg-2 control-label">Role<i class="red">*</i></label>
                  <div class="col-sm-9 col-lg-4 controls" >
                    <?php 
                      $readonly= "";
                    ?>

                    @if(Sentinel::check()->inRole('admin')==true && Sentinel::check()->id==$obj_user->id)
                    <?php 
                      $readonly = "disabled";
                    ?>
                    @endif
                    
                    <select class="form-control" name="roles[]" data-rule-required="true" {{ $readonly }}>
                      <option value="">Select Role</option>
                      @if(isset($arr_roles) && sizeof($arr_roles)>0)
                        @foreach($arr_roles as $role)
                              <option value="{{ $role['id'] }}" @if(isset($arr_assigned_roles[0]) && $arr_assigned_roles[0]==$role['id']){{'selected'}}@endif>{{ $role['name'] }}</option>
                         @endforeach
                        @endif
                    </select>
                      <span class="help-block">{{ $errors->first('roles') }}</span>
                  </div>


            </div>
             <div class="form-group">
             <?php 
                    $user_permission ='';
                    $user_permission = $obj_user->permissions;
                   /* echo '<pre>';
                    print_r($user_permission);
                    echo'</pre>';
                   */
             ?>
              <label class="col-sm-3 col-lg-2 control-label">Permissions <i class="red">*</i></label>
              <div class="col-sm-9 col-lg-8 controls" >
                    <div class="table-responsive" style="border:0">
                        <table class="table " id="table_module">
                            <thead>
                              <tr>
                                    <th style="width:5%;">Module Name</th> 
                                    <th style="text-align:center;width:5%;">List / View</th>
                                    <th style="text-align:center;width:5%;">Create</th>
                                    <th style="text-align:center;width:5%;">Update / Multiple Action</th>
                                    <th style="text-align:center;width:5%;">Delete(Single delete)</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr class="info">
                                      <td><b>All</b></td>
                                      <td> <input class="form-control" type="checkbox" data-module-action="List" onclick="selectAll(this)"> </td>
                                      <td> <input class="form-control" type="checkbox" data-module-action="Create" onclick="selectAll(this)"> </td>
                                      <td> <input class="form-control" type="checkbox" data-module-action="Update" onclick="selectAll(this)"> </td>
                                      <td> <input class="form-control" type="checkbox" data-module-action="Delete" onclick="selectAll(this)"> </td>
                              </tr>     

                  
                            @if(isset($arr_modules) && sizeof($arr_modules)>0)
                             @foreach($arr_modules as $key => $row )
                              <tr class="info">
                                  <td><b>{{$row['title']}}</b></td>                                                                   
                                        <?php 
                                              $slug = $row['slug']; 
                                        ?>
                                        <td> <input class="form-control" @if(array_key_exists($slug.'.list',$user_permission)) checked @endif type="checkbox" data-module-ref="{{$slug}}" data-module-action-ref="List" name="arr_permisssion[subadmin][{{$slug}}.list]"   value="true" > 
                                        </td> 
                                                         
                                        
                                        <td> <input class="form-control" @if(array_key_exists($slug.'.create',$user_permission)) checked @endif type="checkbox" data-module-ref="{{$slug}}" data-module-action-ref="Create" name="arr_permisssion[subadmin][{{$slug}}.create]"  value="true" > 
                                        </td>
                                                                                                      
                                        
                                        <td> <input class="form-control" @if(array_key_exists($slug.'.update',$user_permission)) checked @endif type="checkbox" data-module-ref="{{$slug}}" data-module-action-ref="Update" name="arr_permisssion[subadmin][{{$slug}}.update]" value="true" "> 
                                        </td>                                               
                                                       
                                        
                                        <td> <input class="form-control" @if(array_key_exists($slug.'.delete',$user_permission)) checked @endif type="checkbox" data-module-ref="{{$slug}}" data-module-action-ref="Delete" name="arr_permisssion[subadmin][{{$slug}}.delete]" value="true" > 
                                        </td>
                                 
                                   
                              </tr>
                              @endforeach
                            @endif
                          </tbody>
                        </table>
              
                    </div>
                </div>
            </div>

            <div class="form-group">
              <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                <input type="submit"  class="btn btn-primary" value="Update">
            </div>
        </div>
    </form>
</div>
</div>
</div>

<!-- END Main Content -->
 <script type="text/javascript">
  
  var glob_fields_modified = false;

  function selectAll(ref)
  {
    var action = $(ref).attr('data-module-action');

    var is_checked = $(ref).is(":checked");

    var arr_input = $('input[data-module-action-ref="'+action+'"]');  

    if(is_checked)
    {
      $.each(arr_input,function(index,elem)
      {
        $(elem).prop('checked', true);
      });  
    }
    else
    {
      
      $.each(arr_input,function(index,elem)
      {
        $(elem).prop('checked', false);
      });   
    }
    
  }

  
</script> 
@stop                    