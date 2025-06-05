
@php
    $cart = Session::has('cart') ? Session::get('cart') : [];
    $cartTotal = 0;
    
    foreach ($cart as $item) {
        // Use the same price calculation logic everywhere
        if (!empty($item['attribute']['option_sale_price']) && array_sum($item['attribute']['option_sale_price']) > 0) {
            $itemPrice = array_sum($item['attribute']['option_sale_price']);
        } elseif (!empty($item['attribute']['option_price']) && array_sum($item['attribute']['option_price']) > 0) {
            $itemPrice = array_sum($item['attribute']['option_price']);
        } elseif (isset($item['discount_price']) && $item['discount_price'] > 0) {
            $itemPrice = $item['discount_price'];
        } else {
            $itemPrice = $item['main_price'];
        }

        $subtotal = $itemPrice * $item['qty'];
        $cartTotal += $subtotal;
    }
@endphp

<div class="card border-0">
    <div class="card-body">
        <div class="table-responsive shopping-cart">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>{{ __('Product Name') }}</th>
                        <th>{{ __('Product Price') }}</th>
                        <th class="text-center">{{ __('Quantity') }}</th>
                        <th class="text-center">{{ __('Subtotal') }}</th>
                        <th class="text-center"><a class="btn btn-sm btn-primary"
                                href="{{ route('front.cart.clear') }}"><span>{{ __('Clear Cart') }}</span></a></th>
                    </tr>
                </thead>

                <tbody id="cart_view_load" data-target="{{ route('cart.get.load') }}">
                    @foreach ($cart as $key => $item)
                        @php
                            // Calculate base price (either from attributes or main price)
                            $basePrice = $item['main_price'];
                            
                            // If product has attributes, sum all option prices
                            if (isset($item['attribute']['option_price']) && !empty($item['attribute']['option_price'])) {
                                $attributePrice = array_sum($item['attribute']['option_price']);
                                $itemPrice = $attributePrice;
                            } else {
                                $itemPrice = $basePrice;
                            }
                            
                            $subtotal = $itemPrice * $item['qty'];
                        @endphp
                        <tr>
                            <td>
                                <div class="product-item">
                                    <a class="product-thumb" href="{{ route('front.product', $item['slug']) }}">
                                        <img src="{{ url('/storage/images/' . $item['photo']) }}" alt="Product">
                                    </a>
                                    <div class="product-info">
                                        <h4 class="product-title">
                                            <a href="{{ route('front.product', $item['slug']) }}">
                                                {{ Str::limit($item['name'], 45) }}
                                            </a>
                                        </h4>
                                        @if(isset($item['attribute']['option_name']))
                                            @foreach ($item['attribute']['option_name'] as $optionkey => $option_name)
                                                <span>
                                                    <em>{{ $item['attribute']['names'][$optionkey] }}:</em>
                                                    {{ $option_name }}
                                                    @php
                                                        // Check if sale price exists and is greater than 0
                                                        $displayPrice = isset($item['attribute']['option_sale_price'][$optionkey]) && 
                                                                        $item['attribute']['option_sale_price'][$optionkey] > 0 
                                                                        ? $item['attribute']['option_sale_price'][$optionkey] 
                                                                        : $item['attribute']['option_price'][$optionkey];
                                                    @endphp
                                                    ({{ PriceHelper::setCurrencyPrice($displayPrice) }})
                                                </span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-center text-lg">
                                @php
                                    // Calculate base price (either from attributes or main price)
                                    $basePrice = $item['main_price'];
                                    
                                    // If product has attributes, check for sale prices first
                                    if (isset($item['attribute']['option_sale_price']) && !empty($item['attribute']['option_sale_price'])) {
                                        $attributePrice = array_sum($item['attribute']['option_sale_price']);
                                        $itemPrice = $attributePrice;
                                    } 
                                    // If no sale price but has regular attribute prices
                                    elseif (isset($item['attribute']['option_price']) && !empty($item['attribute']['option_price'])) {
                                        $attributePrice = array_sum($item['attribute']['option_price']);
                                        $itemPrice = $attributePrice;
                                    } 
                                    // For products without attributes, use discount price if available
                                    elseif (isset($item['discount_price']) && $item['discount_price'] > 0) {
                                        $itemPrice = $item['discount_price'];
                                    } 
                                    // Fallback to main price
                                    else {
                                        $itemPrice = $basePrice;
                                    }
                                @endphp
                                {{ PriceHelper::setCurrencyPrice($itemPrice) }}
                            </td>                            
                            <td class="text-center">
                                @if ($item['item_type'] == 'normal')
                                    <div class="qtySelector justify-content-center product-quantity" style="float: none;">
                                        <span class="decreaseQtycart cartsubclick" data-id="{{ $key }}"
                                            data-target="{{ PriceHelper::GetItemId($key) }}"><i
                                                class="fas fa-minus"></i></span>
                                        <input type="text" disabled class="qtyValue cartcart-amount"
                                            value="{{ $item['qty'] }}">
                                        <span class="increaseQtycart cartaddclick" data-id="{{ $key }}"
                                            data-target="{{ PriceHelper::GetItemId($key) }}"
                                            data-item="{{ implode(',', $item['options_id']) }}"><i
                                                class="fas fa-plus"></i></span>
                                        <input type="hidden" value="{{ $item['qty'] }}" id="current_stock">
                                    </div>
                                @endif
                            </td>
                            <td class="text-center text-lg">
                                @php
                                    $itemPrice = PriceHelper::getCartItemPrice($item);
                                    $subtotal = $itemPrice * $item['qty'];
                                @endphp
                                {{ PriceHelper::setCurrencyPrice($subtotal) }}
                            </td>                            
                            <td class="text-center">
                                <a class="remove-from-cart"
                                    href="{{ route('front.cart.destroy', $key) }}" data-toggle="tooltip"
                                    title="Remove item"><i class="icon-x"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card border-0 mt-4">
    <div class="card-body">
        <div class="shopping-cart-footer">
            <div class="column">
                <form class="coupon-form" method="post" id="coupon_form" action="{{ route('front.promo.submit') }}">
                    @csrf
                    <input class="form-control form-control-sm" name="code" type="text"
                        placeholder="{{ __('Coupon code') }}" required>
                    <button class="btn btn-primary btn-sm"
                        type="submit"><span>{{ __('Apply Coupon') }}</span></button>
                </form>
            </div>

            <div class="text-right text-lg column {{ Session::has('coupon') ? '' : 'd-none' }}"><span
                    class="text-muted">{{ __('Discount') }}
                    ({{ Session::has('coupon') ? Session::get('coupon')['code']['title'] : '' }}) : </span><span
                    class="text-gray-dark">{{ PriceHelper::setCurrencyPrice(Session::has('coupon') ? Session::get('coupon')['discount'] : 0) }}</span>
                    <a class="remove-from-cart btn btn-danger btn-sm "
                                    href="{{ route('front.promo.destroy') }}" data-toggle="tooltip"
                                    title="Remove item"><i class="icon-x"></i></a>
            </div>

            <div class="text-right column text-lg">
                <span class="text-muted">{{ __('Subtotal') }}:</span>
                <span class="text-gray-dark">
                    {{ PriceHelper::setCurrencyPrice($cartTotal - (Session::has('coupon') ? Session::get('coupon')['discount'] : 0)) }}
                </span>
            </div>


        </div>
        <div class="shopping-cart-footer">
            <div class="column"><a class="btn btn-primary " href="{{ route('front.catalog') }}"><span><i
                            class="icon-arrow-left"></i> {{ __('Shop More') }}</span></a></div>
            <div class="column"><a class="btn btn-primary"
                    href="{{ route('front.checkout.billing') }}"><span>{{ __('Checkout') }}</span></a></div>
        </div>
    </div>
</div>
</div>
