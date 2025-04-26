<aside class="sidebar">
    <div class="padding-top-2x hidden-lg-up"></div>
    <!-- Items in Cart Widget-->


    <section class="card widget widget-featured-posts widget-order-summary p-4">
        <h3 class="widget-title">{{ __('Order Summary') }}</h3>
        @php
            $free_shipping = DB::table('shipping_services')->whereStatus(1)->whereIsCondition(1)->first();
        @endphp

        @if ($free_shipping)
            @if ($free_shipping->minimum_price >= $cart_total)
                <p class="free-shippin-aa"><em>{{ __('Free Shipping After Order') }}
                        {{ PriceHelper::setCurrencyPrice($free_shipping->minimum_price) }}</em></p>
            @endif
        @endif

        <table class="table">
            @php
                $cart_total = 0;
                foreach ($cart as $item) {
                    // Determine the unit price based on available price options
                    if (isset($item['attribute']) && !empty($item['attribute']['option_sale_price']) && array_sum($item['attribute']['option_sale_price']) > 0) {
                        // Use sale price if available for attributed products
                        $unitPrice = array_sum($item['attribute']['option_sale_price']);
                    } elseif (isset($item['attribute']) && !empty($item['attribute']['option_price'])) {
                        // Use regular attribute price as fallback
                        $unitPrice = array_sum($item['attribute']['option_price']);
                    } elseif (isset($item['discount_price']) && $item['discount_price'] > 0) {
                        // For regular products with discount
                        $unitPrice = $item['discount_price'];
                    } else {
                        // Default to main price
                        $unitPrice = $item['main_price'];
                    }
                    
                    $cart_total += $unitPrice * $item['qty'];
                }
                
                // Calculate grand total
                $grand_total = $cart_total;
                
                // Add tax if applicable
                if (isset($tax) && $tax != 0) {
                    $grand_total += $tax;
                }
                
                // Add state tax if applicable
                if (DB::table('states')->count() > 0 && Auth::check() && Auth::user()->state_id) {
                    $state_tax = ($cart_total * Auth::user()->state->price) / 100;
                    $grand_total += $state_tax;
                }
                
                // Subtract discount if applicable
                if (isset($discount) && $discount) {
                    $grand_total -= $discount['discount'];
                }
                
                // Add shipping if applicable
                if (isset($shipping) && $shipping) {
                    $grand_total += $shipping->price;
                }
            @endphp
            <tr>
                <td>{{ __('Cart subtotal') }}:</td>
                <td class="text-gray-dark">{{ PriceHelper::setCurrencyPrice($cart_total) }}</td>
            </tr>

            @if ($tax != 0)
                <tr>
                    <td>{{ __('Estimated tax') }}:</td>
                    <td class="text-gray-dark">{{ PriceHelper::setCurrencyPrice($tax) }}</td>
                </tr>
            @endif

            @if (DB::table('states')->count() > 0)
                <tr class="{{ Auth::check() && Auth::user()->state_id ? '' : 'd-none' }} set__state_price_tr">
                    <td>{{ __('State tax') }}:</td>
                    <td class="text-gray-dark set__state_price">
                        {{ PriceHelper::setCurrencyPrice(Auth::check() && Auth::user()->state_id ? ($cart_total * Auth::user()->state->price) / 100 : 0) }}
                    </td>
                </tr>
            @endif

            @if ($discount)
                <tr>
                    <td>{{ __('Coupon discount') }}:</td>
                    <td class="text-danger">-
                        {{ PriceHelper::setCurrencyPrice($discount ? $discount['discount'] : 0) }}</td>
                </tr>
            @endif

            @if ($shipping)
                <tr class="d-none set__shipping_price_tr">
                    <td>{{ __('Shipping') }}:</td>
                    <td class="text-gray-dark set__shipping_price">
                        {{ PriceHelper::setCurrencyPrice($shipping ? $shipping->price : 0) }}</td>
                </tr>
            @endif
            <tr>
                <td class="text-lg text-primary">{{ __('Order total') }}</td>
                <td class="text-lg text-primary grand_total_set">{{ PriceHelper::setCurrencyPrice($grand_total) }}</td>
            </tr>
        </table>
    </section>


    <section class="card widget widget-featured-posts widget-featured-products p-4">
        <h3 class="widget-title">{{ __('Items In Your Cart') }}</h3>
        @foreach ($cart as $key => $item)
            <div class="entry">
                <div class="entry-thumb"><a href="{{ route('front.product', $item['slug']) }}"><img
                            src="{{ url('/storage/images/' . $item['photo']) }}" alt="Product"></a>
                </div>
                <div class="entry-content">
                    <h4 class="entry-title"><a href="{{ route('front.product', $item['slug']) }}">
                            {{ Str::limit($item['name'], 45) }}

                        </a></h4>
                        @php
                            // Determine the unit price based on available price options
                            if (isset($item['attribute']) && !empty($item['attribute']['option_sale_price']) && array_sum($item['attribute']['option_sale_price']) > 0) {
                                // Use sale price if available
                                $unitPrice = array_sum($item['attribute']['option_sale_price']);
                            } elseif (isset($item['attribute']) && !empty($item['attribute']['option_price'])) {
                                // Use regular attribute price as fallback
                                $unitPrice = array_sum($item['attribute']['option_price']);
                            } elseif (isset($item['discount_price']) && $item['discount_price'] > 0) {
                                // For regular products with discount
                                $unitPrice = $item['discount_price'];
                            } else {
                                // Default to main price
                                $unitPrice = $item['main_price'];
                            }
                        @endphp
                        <span class="entry-meta">{{ $item['qty'] }} x {{ PriceHelper::setCurrencyPrice($unitPrice) }}</span>

                        @if(isset($item['attribute']['option_name']))
                            @foreach ($item['attribute']['option_name'] as $optionkey => $option_name)
                                <span class="entry-meta"><b>{{ $option_name }}</b> :
                                    @php
                                        $optionPrice = isset($item['attribute']['option_sale_price'][$optionkey]) && 
                                                    $item['attribute']['option_sale_price'][$optionkey] > 0 
                                                    ? $item['attribute']['option_sale_price'][$optionkey] 
                                                    : $item['attribute']['option_price'][$optionkey];
                                    @endphp
                                    {{ PriceHelper::setCurrencySign() }}{{ $optionPrice }}
                                </span>
                            @endforeach
                        @endif
                </div>
            </div>
        @endforeach
    </section>

</aside>
