@extends('front.layout.master')    
@section('main_content')
 

        <form class="form-horizontal" action="{{ url('/terms_condition') }}" name="terms_condition" method="post" id="validation-form">
                    {{ csrf_field() }}

              
               <div class="terms-maon-block">
        <div class="container">
           <div class="subtits"> Term and Conditions </div>
            <div class="welcome-to-travel">
                <h3> Lorem ipsum dolor sit amet, consectetur. </h3>
                <p class="terms-margin-botto">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam aliquam rhoncus turpis eget lacinia. Nam porta risus non porttitor suscipit. Praesent nisi nulla, tempor aceros in, ullamcorper tempor urna. Aliquam sit amet lacinia tortor. Curabitur tellus velit, eleifend a tellus vel, ornare efficitur elit. Morbi risus erat, tincidunt vitae placerat vel, viverra sed dui.
                </p>
            </div>
            <div class="welcome-to-travel">
                <h3> Duis eu posuere dui. Cras eget mauris. </h3>
                <div class="boolet-block">
                    <span class="yellow-circle"><i class="fa fa-circle"></i></span>
                    <span class="yellow-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam aliquam rhoncus turpis eget lacinia.</span>
                </div>
                <div class="boolet-block">
                    <span class="yellow-circle"><i class="fa fa-circle"></i></span>
                    <span class="yellow-text">Nam porta risus non porttitor suscipit. Praesent nisi nulla, tempor aceros in, ullamcorper tempor urna. </span>
                </div>
            </div>
            <div class="welcome-to-travel">
                <h3> Lorem ipsum dolor sit amet, consectetur. </h3>
                <p class="terms-margin-botto">
                    <span>Lorem ipsum dolor sit amet, consectetur:</span> adipiscing elit. Nullam aliquam rhoncus turpis eget lacinia. Nam porta risus non porttitor suscipit. Praesent nisi nulla, tempor aceros in, ullamcorper tempor urna. Aliquam sit amet lacinia tortor. Curabitur tellus
                </p>
                <p class="terms-margin-botto">
                    <span> velit, eleifend a tellus vel:</span> ornare efficitur elit. Morbi risus erat, tincidunt vitae placerat vel, viverra sed dui. Pellentesque in justo lorem. Aenean vitae molestie nibh. Duis ut facilisis sem, sed hendrerit justo. Pellentesque sedelit et lorem aliquam rhoncus in in arcu. Maecenas ligula dolor, mollis eget elit et,tincidunt facilisis augue. Nunc ac lorem tincidunt leo convallis cursus at et tellus.
                </p>
            </div>
            <div class="welcome-to-travel">
                <h3> <span>1.</span> Lorem ipsum dolor sit amet, consectetur. </h3>
                <p class="terms-margin-botto marg-botto">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam aliquam rhoncus turpis eget lacinia. Nam porta risus non porttitor suscipit. Praesent nisi nulla, tempor aceros in, ullamcorper tempor urna. Aliquam sit amet lacinia tortor. Curabitur tellus velit, eleifend a tellus vel, ornare efficitur elit. Morbi risus erat, tincidunt vitae placerat vel, viverra sed dui. 
                </p>
            </div>
            <div class="welcome-to-travel">
                <h3> <span>2.</span> Interdum et malesuada fames ac ante ipsum. </h3>
                <p class="terms-margin-botto">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam aliquam rhoncus turpis eget lacinia. Nam porta risus non porttitor suscipit. Praesent nisi nulla, tempor aceros in, ullamcorper tempor urna. Aliquam sit amet lacinia tortor. Curabitur tellus velit, eleifend a tellus vel.
                </p>
            </div>
            <div class="welcome-to-travel">
                <h3> <span>3.</span> Interdum et malesuada fames ac ante ipsum. </h3>
                <p class="terms-margin-botto">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam aliquam rhoncus turpis eget lacinia. Nam porta risus non porttitor suscipit. Praesent nisi nulla, tempor aceros in, ullamcorper tempor urna. Aliquam sit amet lacinia tortor. Curabitur tellus velit, eleifend a tellus vel. Nullam aliquam rhoncus turpis eget lacinia. Nam porta risus non porttitor suscipit. Praesent nisi nulla, tempor aceros in, ullamcorper tempor urna. Aliquam sit amet lacinia tortor. Curabitur tellus velit, eleifend a tellus vel. 
                </p>
            </div>
            <div class="welcome-to-travel">
                <h3> Duis eu posuere dui. Cras eget mauris. </h3>
                <div class="boolet-block">
                    <span class="yellow-circle"><i class="fa fa-circle"></i></span>
                    <span class="yellow-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam aliquam rhoncus turpis eget lacinia.</span>
                </div>
                <div class="boolet-block">
                    <span class="yellow-circle"><i class="fa fa-circle"></i></span>
                    <span class="yellow-text">Nam porta risus non porttitor suscipit. Praesent nisi nulla, tempor aceros in, ullamcorper tempor urna. </span>
                </div>
                <div class="boolet-block">
                    <span class="yellow-circle"><i class="fa fa-circle"></i></span>
                    <span class="yellow-text">Aliquam sit amet lacinia tortor. Curabitur tellusvelit, eleifend a tellus vel.</span>
                </div>
                <div class="boolet-block">
                    <span class="yellow-circle"><i class="fa fa-circle"></i></span>
                    <span class="yellow-text">Ornare efficitur elit. Morbi risus erat, tincidunt vitae placerat vel, viverra sed dui. Pellentesque in justo lorem.</span>
                </div>
                <div class="boolet-block">
                    <span class="yellow-circle"><i class="fa fa-circle"></i></span>
                    <span class="yellow-text">Aenean vitae molestie nibh. Duis ut facilisis sem, sed hendrerit justo. </span>
                </div>
                <div class="boolet-block">
                    <span class="yellow-circle"><i class="fa fa-circle"></i></span>
                    <span class="yellow-text">Pellentesque sedelit et lorem aliquam rhoncus in in arcu. Maecenas ligula dolor, mollis eget elit et,tincidunt facilisis augue.</span>
                </div>
            </div>
            <div class="welcome-to-travel">
                <h3> Interdum et malesuada fames ac ante ipsum. </h3>
                <p class="terms-margin-botto">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam aliquam rhoncus turpis eget lacinia. Nam porta risus non porttitor suscipit. Praesent nisi nulla, tempor aceros in, ullamcorper tempor urna. Aliquam sit amet lacinia tortor. Curabitur tellus velit, eleifend a tellus vel. Nullam aliquam rhoncus turpis eget lacinia. Nam porta risus non porttitor suscipit. Praesent nisi nulla, tempor aceros in.
                </p>
            </div>
<input type="hidden" name="_token" value="{{ csrf_token() }}">
             <div class="button-section right-side tp-btns">
             <input type="submit" value="accept" name="status" class="">
             <input type="submit" value="reject" name="status" class="">
            </div>
        </div>
    </div>
               </form>
        
      
@if($check_conditions=1)
<script type="text/javascript">$('#modal_terms').trigger('click');</script>
@endif

@endsection