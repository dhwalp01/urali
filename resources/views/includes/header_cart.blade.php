@php
    $grandSubtotal = 0;
    $qty = 0;
    $option_price = 0;
@endphp
@if (Session::has('cart'))
@foreach (Session::get('cart') as $key => $cart)
@php
    $itemPrice = isset($cart['attribute_price']) && $cart['attribute_price'] > 0 
        ? $cart['attribute_price'] 
        : $cart['main_price'];
    $grandSubtotal += $itemPrice * $cart['qty'];
@endphp
<div class="entry">
  <div class="entry-thumb"><a href="{{route('front.product',$cart['slug'])}}"><img src="{{url('/storage/images/'.$cart['photo'])}}" alt="Product"></a></div>
  <div class="entry-content">
    <h4 class="entry-title"><a href="{{route('front.product',$cart['slug'])}}">
        {{ Str::limit($cart['name'], 45) }}
    </a></h4>
    @php
      $hasAttributes = isset($cart['attribute']) && !empty($cart['attribute']['option_price']);
      $unitPrice = $hasAttributes
          ? array_sum($cart['attribute']['option_price'])
          : $cart['main_price'];
    @endphp
    <span class="entry-meta">{{ $cart['qty'] }} x {{ PriceHelper::setCurrencyPrice($unitPrice) }}</span>
    @foreach ($cart['attribute']['option_name'] as $optionkey => $option_name)
    <span class="att"><em>{{$cart['attribute']['names'][$optionkey]}}:</em> {{$option_name}} ({{PriceHelper::setCurrencyPrice($cart['attribute']['option_price'][$optionkey])}})</span>
    @endforeach

 </div>
  <div class="entry-delete"><a href="{{route('front.cart.destroy',$key)}}"><i class="icon-x"></i></a></div>
</div>
@endforeach
<div class="text-right">
<p class="text-gray-dark py-2 mb-0"><span class="text-muted">{{__('Subtotal')}}:</span> {{PriceHelper::setCurrencyPrice($grandSubtotal)}}</p>
</div>
<div class="d-flex justify-content-between">
<div class="w-50 d-block"><a class="btn btn-primary btn-sm  mb-0" href="{{route('front.cart')}}"><span>{{__('Cart')}}</span></a></div>
<div class="w-50 d-block text-end"><a class="btn btn-primary btn-sm  mb-0" href="{{route('front.checkout.billing')}}"><span>{{__('Checkout')}}</span></a></div>
@else
{{__('Cart empty')}}
  @endif
</div>
