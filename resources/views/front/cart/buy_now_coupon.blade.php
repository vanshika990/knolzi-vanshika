@if($is_discount == true)
@if(is_numeric($total_dis_price))
<span><del>{{ getCurrencySymbol() }}{{ $total_price }}</del></span><br/><span>{{ getCurrencySymbol() }}{{ $total_dis_price }}</span>
@else 
<span>{{ getCurrencySymbol() }}{{ $total_price }}</span>
@endif
@else
<span> {{ getCurrencySymbol() }}{{ $total_price }}</span>
@endif

