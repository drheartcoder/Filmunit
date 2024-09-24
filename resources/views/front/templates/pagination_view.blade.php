 <div class="pagination-block margin-change">
   @if ($arr_pagination->lastPage() > 1 && isset($arr_pagination))
   <?php $arr_pagination->appends(\Request::all()); ?>
   <ul>
      <li>
         
         @if($arr_pagination->currentPage() == 1)
            <a class="{{ ($arr_pagination->currentPage() == 1) ? '' : '' }}" href="javascript: void(0);" >
               <i class="fa fa-angle-left"></i>
            </a>
         @else
            <a class="{{ ($arr_pagination->currentPage() == 1) ? '' : '' }}" href="{{ $arr_pagination->url($arr_pagination->currentPage()-1) }}" >
            <i class="fa fa-angle-left"></i>
            </a>
         @endif
      </li>
      
      @for ($i = 1; $i <= $arr_pagination->lastPage(); $i++)
      <li>
         <a  class="{{ ($arr_pagination->currentPage() == $i) ? ' active' : '' }}" href="{{ $arr_pagination->url($i) }}">{{ $i }}</a>
      </li>
      @endfor

      <li>
            @if ($arr_pagination->currentPage() == $arr_pagination->lastPage())
               <a class="{{ ($arr_pagination->currentPage() == $arr_pagination->lastPage()) ? '' : '' }}" href="javascript: void(0);" >
                  <i class="fa fa-angle-right"></i>
                  </a>
            @else
                  <a class="{{ ($arr_pagination->currentPage() == $arr_pagination->lastPage()) ? '' : '' }}" href="{{ $arr_pagination->url($arr_pagination->currentPage()+1) }}" >
                  <i class="fa fa-angle-right"></i>
                  </a>
            @endif
      </li>
   </ul>
   @endif
</div>