<div class="row g-3" id="main_div">
    @if($items->count() > 0)
        @if ($checkType != 'list')
            @foreach ($items as $item)
            <div class="col-xxl-3 col-md-4 col-6">
                <div class="product-card">
                    @if (
                        count($item->attributes) > 0
                            ? $item->attributes->flatMap->options->pluck('stock')->filter(fn($stock) => $stock > 0)->count() > 0
                            : $item->is_stock()
                    )
                        <div class="product-badge
                            @if($item->is_type == 'feature')
                            bg-warning
                            @elseif($item->is_type == 'new')
                            bg-danger
                            @elseif($item->is_type == 'top')
                            bg-info
                            @elseif($item->is_type == 'best')
                            bg-dark
                            @elseif($item->is_type == 'flash_deal')
                            bg-success
                            @endif
                            "> {{  $item->is_type != 'undefine' ?  (str_replace('_',' ',__("$item->is_type"))) : ''   }}
                        </div>
                    @else
                    <div class="product-badge bg-secondary border-default text-body
                    ">{{__('out of stock')}}</div>
                    @endif

                    @php
                    $discountPercentage = null;
                    
                    // Check if this is a product with attributes
                    $hasAttributesWithStock = count($item->attributes) > 0 && 
                        $item->attributes->flatMap->options->where('stock', '>', 0)->count() > 0;
                        
                    if ($hasAttributesWithStock) {
                        // For products with attributes, find options with sale prices
                        $options = $item->attributes->flatMap->options->where('stock', '>', 0);
                        
                        // Get options that have both regular price and sale price
                        $optionsWithSale = $options->filter(function($option) {
                            return $option->sale_price && $option->sale_price > 0 && $option->sale_price < $option->price;
                        });
                        
                        if ($optionsWithSale->count() > 0) {
                            // Calculate highest discount percentage among options
                            $highestDiscount = 0;
                            foreach ($optionsWithSale as $option) {
                                $discount = (($option->price - $option->sale_price) / $option->price) * 100;
                                $highestDiscount = max($highestDiscount, $discount);
                            }
                            $discountPercentage = round($highestDiscount);
                        }
                    } else {
                        // For regular products, use existing logic
                        if($item->previous_price && $item->previous_price != 0) {
                            $discountPercentage = PriceHelper::DiscountPercentage($item);
                        }
                    }
                @endphp
            
                {{-- @if($discountPercentage)
                    <div class="product-badge product-badge2 bg-info">-{{$discountPercentage}}%</div>
                @endif --}}
                <div class="product-thumb">
                    <a href="{{route('front.product',$item->slug)}}">
                        <div class="product-thumb-image-wrapper">
                            <img
                                class="lazy product-thumb-image"
                                data-src="{{ url('/storage/images/'.$item->thumbnail) }}"
                                alt="Product"
                            >
                        </div>
                        <div class="product-button-group">
                            <a class="product-button wishlist_store" href="{{route('user.wishlist.store',$item->id)}}" title="{{__('Wishlist')}}"><i class="icon-heart"></i></a>
                            {{-- <a class="product-button product_compare" href="javascript:;" data-target="{{route('fornt.compare.product',$item->id)}}" title="{{__('Compare')}}"><i class="icon-repeat"></i></a> --}}
                            @include('includes.item_footer',['sitem' => $item])
                        </div>
                    </a>
                </div>
                <div class="product-card-body">
                    {{-- <div class="product-category">
                        <a href="{{route('front.category', $item->category->slug)}}">{{$item->category->name}}</a>
                    </div>                     --}}
                    <h3 class="product-title"><a href="{{route('front.product',$item->slug)}}">
                        {{ Str::limit($item->name, 38) }}
                    </a></h3>
                    {{-- <div class="rating-stars">
                        {!! Helper::renderStarRating($item->reviews->avg('rating'))!!}
                    </div> --}}
                    <h4 class="product-price text-start">
                        @php
                            $hasAttributesWithStock = count($item->attributes) > 0 && 
                                $item->attributes->flatMap->options->where('stock', '>', 0)->count() > 0;
                                
                            // Get lowest option price for products with attributes
                            $minPrice = null;
                            $minSalePrice = null;
                            
                            if ($hasAttributesWithStock) {
                                $options = $item->attributes->flatMap->options->where('stock', '>', 0);
                                if ($options->count() > 0) {
                                    // Find lowest regular price
                                    $minPrice = $options->pluck('price')->min();
                                    
                                    // Find lowest sale price (if available)
                                    $salesPrices = $options->pluck('sale_price')->filter()->values();
                                    $minSalePrice = $salesPrices->count() > 0 ? $salesPrices->min() : null;
                                }
                            }
                        @endphp
                        
                        @if ($hasAttributesWithStock)
                            @if ($minSalePrice)
                                <del>{{PriceHelper::setCurrencyPrice($minPrice)}}</del>
                                {{PriceHelper::setCurrencyPrice($minSalePrice)}}
                            @else
                                {{PriceHelper::setCurrencyPrice($minPrice)}}
                            @endif
                        @else
                            @if ($item->previous_price != 0)
                                <del>{{PriceHelper::setPreviousPrice($item->previous_price)}}</del>
                            @endif
                            {{PriceHelper::grandCurrencyPrice($item)}}
                        @endif
                    </h4>
                </div>

                </div>
            </div>
            @endforeach
        @else
            @foreach ($items as $item)
                <div class="col-lg-12">
                    <div class="product-card product-list">
                        <div class="product-thumb" >
                            @if (
                                count($item->attributes) > 0
                                    ? $item->attributes->flatMap->options->pluck('stock')->filter(fn($stock) => $stock > 0)->count() > 0
                                    : $item->is_stock()
                            )

                            <div class="product-badge
                                @if($item->is_type == 'feature')
                                bg-warning
                                @elseif($item->is_type == 'new')
                                bg-danger
                                @elseif($item->is_type == 'top')
                                bg-info
                                @elseif($item->is_type == 'best')
                                bg-dark
                                @elseif($item->is_type == 'flash_deal')
                                bg-success
                                @endif
                                ">{{  $item->is_type != 'undefine' ?  ucfirst(str_replace('_',' ',$item->is_type)) : ''   }}
                            </div>
                            @else
                            <div class="product-badge bg-secondary border-default text-body
                            ">{{__('out of stock')}}</div>
                            @endif
                            @if($item->previous_price && $item->previous_price !=0)
                            <div class="product-badge product-badge2 bg-info"> -{{PriceHelper::DiscountPercentage($item)}}</div>
                            @endif

                            <img class="lazy" data-src="{{url('/storage/images/'.$item->thumbnail)}}" alt="Product">
                            <div class="product-button-group">
                                <a class="product-button wishlist_store" href="{{route('user.wishlist.store',$item->id)}}" title="{{__('Wishlist')}}"><i class="icon-heart"></i></a>
                                <a data-target="{{route('fornt.compare.product',$item->id)}}" class="product-button product_compare" href="javascript:;" title="{{__('Compare')}}"><i class="icon-repeat"></i></a>
                                @include('includes.item_footer',['sitem' => $item])
                            </div>
                        </div>
                            <div class="product-card-inner">
                                <div class="product-card-body">
                                    <div class="product-category">
                                        <a href="{{route('front.category', $item->category->slug)}}">{{$item->category->name}}</a>
                                    </div>
                                    <h3 class="product-title"><a href="{{route('front.product',$item->slug)}}">
                                        {{ Str::limit($item->name, 52) }}
                                    </a></h3>
                                    {{-- <div class="rating-stars">
                                        {!! Helper::renderStarRating($item->reviews->avg('rating')) !!}
                                    </div> --}}
                                    <h4 class="product-price text-start">
                                        @if ($item->previous_price !=0)
                                        <del>{{PriceHelper::setPreviousPrice($item->previous_price)}}</del>
                                        @endif
                                        {{PriceHelper::grandCurrencyPrice($item)}}
                                    </h4>
                                    <p class="text-sm sort_details_show  text-muted hidden-xs-down my-1">
                                    {{ Str::limit(strip_tags($item->sort_details), 100) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                </div>
            @endforeach
        @endif
    @else
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body text-center">
                    <h4 class="h4 mb-0">{{ __('No Product Found') }}</h4>
                </div>
            </div>
        </div>
    @endif
</div>


<!-- Pagination-->
<div class="row mt-15" id="item_pagination">
    <div class="col-lg-12 text-center">
        {{$items->links()}}
    </div>
</div>

<script type="text/javascript" src="{{asset('assets/front/js/catalog.js')}}"></script>
