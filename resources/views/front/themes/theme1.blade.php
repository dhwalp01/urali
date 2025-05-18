@extends('master.front')
@section('meta')
    <meta name="keywords" content="{{ $setting->meta_keywords }}">
    <meta name="description" content="{{ $setting->meta_description }}">
@endsection

@section('content')
    <div class="slider-area-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="owl-carousel category-slider">
                        @foreach($categorySlider as $cat)
                        <div class="cat-slider p-2 item text-center">
                            <a href="{{ route('front.category', $cat->slug) }}">
                                <div class="img-container" style="height: 200px; overflow: hidden;">
                                    <img 
                                        src="{{ asset('storage/images/'.$cat->photo) }}"
                                        alt="{{ $cat->name }}" 
                                        class="w-100 rounded h-100"
                                        style="object-fit: cover; object-position: top center;"
                                    >
                                </div>
                                <div class="category-title text-dark fw-bold mt-2">{{ $cat->name }}</div>
                            </a>
                        </div>
                        @endforeach
                    </div>                          
                </div>
            </div>
        </div>
    </div>

    @if ($setting->is_slider == 1)
        <div class="slider-area-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Main Slider-->
                        <div class="hero-slider">
                            <div class="hero-slider-main owl-carousel dots-inside">
                                @foreach ($sliders as $slider)
                                    <div class="hero-slider-item item
                                    @if (DB::table('languages')->where('is_default', 1)->first()->rtl == 1) d-flex justify-content-end @endif
                                    "
                                        style="background: url('{{ url('/storage/images/' . $slider->photo) }}')">
                                        <div class="item-inner">
                                            <div class="from-bottom">
                                                @if ($slider->logo)
                                                    <img class="d-inline-block brand-logo"
                                                        src="{{ url('/storage/images/' . $slider->logo) }}" alt="logo">
                                                @endif
                                                <div class="title text-body">{{ $slider->title }}</div>
                                                <div class="subtitle text-body">{{ $slider->details }}</div>
                                            </div>
                                            @if ($slider->link != '#')
                                                <a class="btn btn-primary scale-up delay-1" href="{{ $slider->link }}">
                                                    <span>{{ __('Buy Now') }}</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- @if (isset($hero_banner))
                        <div class="col-lg-4 d-none d-lg-block">
                            <a href="{{ $hero_banner['url1'] }}" class="sright-image">
                                <img src="{{ url('/storage/images/' . $hero_banner['img1']) }}" alt="">
                                <div class="inner-content">

                                    @if (isset($hero_banner['subtitle1']))
                                        <p>{{ $hero_banner['subtitle1'] }}</p>
                                    @endif

                                    @if (isset($hero_banner['title1']))
                                        <h4>{{ $hero_banner['title1'] }}</h4>
                                    @endif
                                </div>
                            </a>
                            <a href="{{ $hero_banner['url2'] }}" class="sright-image mb-0">
                                <img src="{{ url('/storage/images/' . $hero_banner['img2']) }}" alt="">
                                <div class="inner-content">
                                    @if (isset($hero_banner['subtitle2']))
                                        <p>{{ $hero_banner['subtitle2'] }}</p>
                                    @endif
                                    @if (isset($hero_banner['title2']))
                                        <h4>{{ $hero_banner['title2'] }}</h4>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endif --}}
                </div>
            </div>
        </div>
    @endif

    @if ($setting->campaign_status == 0)
        <div class="deal-of-day-section mt-20">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title">
                            <h2 class="h3">{{ $setting->campaign_title }}</h2>
                            <div class="right-area">
                                <div class="countdown countdown-alt" data-date-time="{{ $setting->campaign_end_date }}">
                                </div>
                                <a class="right_link" href="{{ route('front.campaign') }}">{{ __('View All') }} <i
                                        class="icon-chevron-right"></i></a>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-3">

                    <div class="col-lg-12">
                        <div class="popular-category-slider owl-carousel">
                            @foreach ($campaign_items as $compaign_item)
                                <div class="slider-item">
                                    <div class="product-card">
                                        <div class="product-thumb">
                                            @if (!$compaign_item->item->is_stock())
                                                <div
                                                    class="product-badge bg-secondary border-default text-body
                                        ">
                                                    {{ __('out of stock') }}</div>
                                            @endif

                                            @if ($compaign_item->item->previous_price && $compaign_item->item->previous_price != 0)
                                                <div class="product-badge product-badge2 bg-info">
                                                    -{{ PriceHelper::DiscountPercentage($compaign_item->item) }}</div>
                                            @endif
                                            <img class="lazy"
                                                data-src="{{ url('/storage/images/' . $compaign_item->item->thumbnail) }}"
                                                alt="Product">
                                            <div class="product-button-group"><a class="product-button wishlist_store"
                                                    href="{{ route('user.wishlist.store', $compaign_item->item->id) }}"
                                                    title="{{ __('Wishlist') }}"><i class="icon-heart"></i></a>
                                                @if ($compaign_item->item->is_stock())
                                                    <a class="product-button add_to_single_cart"
                                                        data-target="{{ $compaign_item->item->id }}" href="javascript:;"
                                                        title="{{ __('To Cart') }}"><i class="icon-shopping-cart"></i>
                                                    </a>
                                                @else
                                                    <a class="product-button"
                                                        href="{{ route('front.product', $compaign_item->item->slug) }}"
                                                        title="{{ __('Details') }}"><i class="icon-arrow-right"></i></a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="product-card-body">

                                            {{-- <div class="product-category"><a
                                                    href="{{ route('front.catalog') . '?category=' . $compaign_item->item->category->slug }}">{{ $compaign_item->item->category->name }}</a>
                                            </div> --}}
                                            <h3 class="product-title"><a
                                                    href="{{ route('front.product', $compaign_item->item->slug) }}">
                                                    {{ Str::limit($compaign_item->item->name, 35) }}
                                                </a></h3>
                                            {{-- <div class="rating-stars">
                                                {!! Helper::renderStarRating($compaign_item->item->reviews->avg('rating')) !!}
                                            </div> --}}
                                            <h4 class="product-price text-start">
                                                @if ($compaign_item->item->previous_price != 0)
                                                    <del>{{ PriceHelper::setPreviousPrice($compaign_item->item->previous_price) }}</del>
                                                @endif

                                                {{ PriceHelper::grandCurrencyPrice($compaign_item->item) }}
                                            </h4>

                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>

                </div>
            </div>
        </div>
    @endif


    @if ($setting->is_three_c_b_first == 0)
        <div class="bannner-section mt-60">
            <div class="container ">
                <div class="row gx-3">
                    <div class="col-md-4">
                        <a href="{{ $banner_first['firsturl1'] }}" class="genius-banner">
                            <img src="{{ url('/storage/images/' . $banner_first['img1']) }}" alt="">
                            <div class="inner-content">
                                @if (isset($banner_first['subtitle1']))
                                    <p>{{ $banner_first['subtitle1'] }}</p>
                                @endif
                                @if (isset($banner_first['title1']))
                                    <h4>{{ $banner_first['title1'] }}</h4>
                                @endif
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ $banner_first['firsturl2'] }}" class="genius-banner">
                            <img src="{{ url('/storage/images/' . $banner_first['img2']) }}" alt="">
                            <div class="inner-content">
                                @if (isset($banner_first['subtitle2']))
                                    <p>{{ $banner_first['subtitle2'] }}</p>
                                @endif
                                @if (isset($banner_first['title2']))
                                    <h4>{{ $banner_first['title2'] }}</h4>
                                @endif
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ $banner_first['firsturl3'] }}" class="genius-banner">
                            <img src="{{ url('/storage/images/' . $banner_first['img3']) }}" alt="">
                            <div class="inner-content">
                                @if (isset($banner_first['subtitle3']))
                                    <p>{{ $banner_first['subtitle3'] }} </p>
                                @endif
                                @if (isset($banner_first['title3']))
                                    <h4>{{ $banner_first['title3'] }}</h4>
                                @endif
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if ($setting->is_popular_category == 0)
        <section class="newproduct-section popular-category-sec mt-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title">
                            <h2 class="h3">{{ $popular_category_title }}</h2>
                            <div class="links">
                                @foreach ($popular_categories as $key => $popular_categorie)
                                    <a class="category_get {{ $loop->first ? 'active' : '' }}"
                                        data-target="popular_category_view"
                                        data-href="{{ route('front.popular.category', [$popular_categorie->slug, 'popular_category', 'slider']) }}"
                                        href="javascript:;"
                                        class="{{ $loop->first ? 'active' : '' }}">{{ $popular_categorie->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="popular_category_view d-none">
                    <img src="{{ url('/storage/images/ajax_loader.gif') }}" alt="">
                </div>

                <div class="row" id="popular_category_view">
                    <div class="col-lg-12">
                        <div class="popular-category-slider  owl-carousel">
                            @foreach ($popular_category_items as $popular_category_item)
                                <div class="slider-item">
                                    <div class="product-card">
                                        <div class="product-thumb">

                                            @if (!$popular_category_item->is_stock())
                                                <div
                                                    class="product-badge bg-secondary border-default text-body
                                            ">
                                                    {{ __('out of stock') }}</div>
                                            @endif
                                            @if ($popular_category_item->previous_price && $popular_category_item->previous_price != 0)
                                                <div class="product-badge product-badge2 bg-info">
                                                    -{{ PriceHelper::DiscountPercentage($popular_category_item) }}</div>
                                            @endif
                                            <img class="lazy"
                                                data-src="{{ url('/storage/images/' . $popular_category_item->thumbnail) }}"
                                                alt="Product">
                                            <div class="product-button-group">
                                                <a class="product-button wishlist_store"
                                                    href="{{ route('user.wishlist.store', $popular_category_item->id) }}"
                                                    title="{{ __('Wishlist') }}"><i class="icon-heart"></i></a>
                                                @include('includes.item_footer', [
                                                    'sitem' => $popular_category_item,
                                                ])
                                            </div>
                                        </div>
                                        <div class="product-card-body">
                                            {{-- <div class="product-category"><a
                                                    href="{{ route('front.catalog') . '?category=' . $popular_category_item->category->slug }}">{{ $popular_category_item->category->name }}</a>
                                            </div> --}}
                                            <h3 class="product-title"><a
                                                    href="{{ route('front.product', $popular_category_item->slug) }}">
                                                    {{ Str::limit($popular_category_item->name, 35) }}
                                                </a></h3>
                                            {{-- <div class="rating-stars">
                                                {!! Helper::renderStarRating($popular_category_item->reviews->avg('rating')) !!}
                                            </div> --}}
                                            <h4 class="product-price text-start">
                                                @if ($popular_category_item->previous_price != 0)
                                                    <del>{{ PriceHelper::setPreviousPrice($popular_category_item->previous_price) }}</del>
                                                @endif
                                                {{ PriceHelper::grandCurrencyPrice($popular_category_item) }}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </section>
    @endif

    @if ($setting->is_three_c_b_second == 0)
        <div class="bannner-section mt-60">
            <div class="container ">
                <div class="row gx-3">
                    <div class="col-md-4">
                        <a href="{{ $banner_secend['url1'] }}" class="genius-banner">
                            <img class="lazy" data-src="{{ url('/storage/images/' . $banner_secend['img1']) }}"
                                alt="">
                            <div class="inner-content">
                                @if (isset($banner_secend['subtitle1']))
                                    <p>{{ $banner_secend['subtitle1'] }}</p>
                                @endif

                                @if (isset($banner_secend['title1']))
                                    <h4>{{ $banner_secend['title1'] }}</h4>
                                @endif
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ $banner_secend['url2'] }}" class="genius-banner">
                            <img class="lazy" data-src="{{ url('/storage/images/' . $banner_secend['img2']) }}"
                                alt="">
                            <div class="inner-content">
                                @if (isset($banner_secend['subtitle2']))
                                    <p>{{ $banner_secend['subtitle2'] }}</p>
                                @endif

                                @if (isset($banner_secend['title2']))
                                    <h4> {{ $banner_secend['title2'] }}</h4>
                                @endif
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ $banner_secend['url3'] }}" class="genius-banner">
                            <img class="lazy" data-src="{{ url('/storage/images/' . $banner_secend['img3']) }}"
                                alt="">
                            <div class="inner-content">
                                @if (isset($banner_secend['subtitle3']))
                                    <p>{{ $banner_secend['subtitle3'] }} </p>
                                @endif

                                @if (isset($banner_secend['title3']))
                                    <h4>{{ $banner_secend['title3'] }}</h4>
                                @endif
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($setting->is_highlighted == 0)
        <section class="selected-product-section speacial-product-sec mt-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title">
                            <div class="links">
                                <a data-href="{{ route('front.get.product', 'feature') }}" data-target="type_product_view"
                                    href="javascript:;" class="product_get active">{{ __('Featured') }}</a>
                                <a data-href="{{ route('front.get.product', 'best') }}" data-target="type_product_view"
                                    class="product_get" href="javascript:;">{{ __('Best Seller') }}</a>
                                <a data-href="{{ route('front.get.product', 'top') }}" data-target="type_product_view"
                                    class="product_get" href="javascript:;">{{ __('Top Rated') }}</a>
                                <a data-href="{{ route('front.get.product', 'new') }}" data-target="type_product_view"
                                    class="product_get" href="javascript:;">{{ __('New Product') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="type_product_view d-none">
                        <img src="{{ url('/storage/images/ajax_loader.gif') }}" alt="">
                    </div>
                    <div class="col-lg-12" id="type_product_view">

                        <div class="features-slider  owl-carousel">
                            @foreach ($products->orderBy('id', 'DESC')->get() as $item)
                                @if ($item->is_type == 'feature')
                                    <div class="slider-item">
                                        <div class="product-card ">
                                            <div class="product-thumb">
                                                @if (!$item->is_stock())
                                                    <div
                                                        class="product-badge bg-secondary border-default text-body
                                                    ">
                                                        {{ __('out of stock') }}</div>
                                                @endif
                                                @if ($item->previous_price && $item->previous_price != 0)
                                                    <div class="product-badge product-badge2 bg-info">
                                                        -{{ PriceHelper::DiscountPercentage($item) }}</div>
                                                @endif
                                                <div class="product-thumb-image-wrapper">
                                                    <a href="{{ route('front.product', $item->slug) }}">
                                                        <img class="lazy"
                                                            data-src="{{ url('/storage/images/' . $item->thumbnail) }}"
                                                            alt="Product">
                                                    </a>
                                                </div>
                                                <div class="product-button-group"><a class="product-button wishlist_store"
                                                        href="{{ route('user.wishlist.store', $item->id) }}"
                                                        title="{{ __('Wishlist') }}"><i class="icon-heart"></i></a>
                                                    {{-- <a data-target="{{ route('fornt.compare.product', $item->id) }}"
                                                        class="product-button product_compare" href="javascript:;"
                                                        title="{{ __('Compare') }}"><i class="icon-repeat"></i></a> --}}
                                                    @include('includes.item_footer', ['sitem' => $item])
                                                </div>
                                            </div>
                                            <div class="product-card-inner">
                                                <div class="product-card-body">
                                                    {{-- <div class="product-category"><a
                                                            href="{{ route('front.category', $cat->slug) }}">{{ $item->category->name }}</a>
                                                    </div> --}}
                                                    <h3 class="product-title"><a
                                                            href="{{ route('front.product', $item->slug) }}">
                                                            {{ Str::limit($item->name, 35) }}
                                                        </a></h3>
                                                    {{-- <div class="rating-stars">
                                                        {!! Helper::renderStarRating($item->reviews->avg('rating')) !!}
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
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </section>
    @endif
    @if ($extra_settings->is_t1_falsh == 1)
        <div class="flash-sell-new-section mt-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title d-flex justify-content-between align-items-center">
                            <h2 class="h3">{{ __('Explore') }}</h2>
                            <button class="show-btn"><a href="{{ url('/catalog') }}">Show all</a></button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-content">
                            <div class="flash-deal-slider owl-carousel">
                                @foreach ($products->orderBy('id', 'DESC')->get() as $item)
                                    @if ($item->is_type == 'flash_deal' && $item->date != null)
                                        <div class="slider-item">
                                            <div class="product-card ">
                                                <div class="product-thumb">
                                                    @php
                                                        $hasAttributeStock = $item->attributes->flatMap->options->pluck('stock')->filter(fn($stock) => $stock > 0)->count() > 0;
                                                        $isInStock = count($item->attributes) > 0 ? $hasAttributeStock : $item->is_stock();
                                                    @endphp

                                                    @php
                                                        // Calculate discount percentage based on whether product has attributes
                                                        $discountPercentage = 0;
                                                        
                                                        if (count($item->attributes) > 0) {
                                                            // For products with attributes, find the highest discount percentage among options
                                                            foreach ($item->attributes as $attribute) {
                                                                foreach ($attribute->options as $option) {
                                                                    if ($option->sale_price && $option->sale_price < $option->price) {
                                                                        $optionDiscount = round((($option->price - $option->sale_price) / $option->price) * 100);
                                                                        if ($optionDiscount > $discountPercentage) {
                                                                            $discountPercentage = $optionDiscount;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        } elseif ($item->previous_price && $item->previous_price != 0) {
                                                            // For regular products - use the existing DiscountPercentage method
                                                            $discountPercentage = str_replace('%', '', PriceHelper::DiscountPercentage($item));
                                                        }
                                                    @endphp

                                                    {{-- @if ($discountPercentage > 0)
                                                        <div class="product-badge product-badge2 bg-info 12">-{{ $discountPercentage }}%</div>
                                                    @endif --}}
                                                    <div class="product-thumb-image-wrapper">
                                                        <a href="{{ route('front.product', $item->slug) }}">
                                                            <img class="lazy" data-src="{{ url('/storage/images/' . $item->thumbnail) }}" alt="Product">
                                                        </a>
                                                    </div>
                                                    <div class="product-button-group"><a
                                                            class="product-button wishlist_store"
                                                            href="{{ route('user.wishlist.store', $item->id) }}"
                                                            title="{{ __('Wishlist') }}"><i class="icon-heart"></i></a>
                                                        @include('includes.item_footer', [
                                                            'sitem' => $item,
                                                        ])
                                                    </div>
                                                </div>
                                                <div class="product-card-inner">
                                                    <div class="product-card-body">

                                                        {{-- <div class="product-category"><a
                                                                href="{{ route('front.category', $cat->slug) }}">{{ $item->category->name }}</a>
                                                        </div> --}}
                                                        <h3 class="product-title"><a
                                                                href="{{ route('front.product', $item->slug) }}">
                                                                {{ Str::limit($item->name, 50) }}
                                                            </a></h3>
                                                        {{-- <div class="rating-stars">
                                                            {!! Helper::renderStarRating($item->reviews->avg('rating')) !!}
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
                                                        {{-- @if (date('d-m-y') != \Carbon\Carbon::parse($item->date)->format('d-m-y'))
                                                            <div class="countdown countdown-alt mb-3"
                                                                data-date-time="{{ $item->date }}">
                                                            </div>
                                                        @endif --}}
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($extra_settings->is_t1_falsh == 1)
        <div class="flash-sell-new-section mt-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 ">
                        <div class="section-title d-flex justify-content-between align-items-center">
                            <h2 class="h3">{{ __('Best Seller') }}</h2>
                            <button class="show-btn"><a href="{{ url('/catalog') }}">Show all</a></button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-content">
                            <div class="flash-deal-slider owl-carousel">
                                @foreach ($products->orderBy('id', 'DESC')->get() as $item)
                                    @if ($item->is_type == 'flash_deal' && $item->date != null)
                                        <div class="slider-item">
                                            <div class="product-card ">
                                                <div class="product-thumb">
                                                    @php
                                                        $hasAttributeStock = $item->attributes->flatMap->options->pluck('stock')->filter(fn($stock) => $stock > 0)->count() > 0;
                                                        $isInStock = count($item->attributes) > 0 ? $hasAttributeStock : $item->is_stock();
                                                    @endphp

                                                    @if (!$isInStock)
                                                        <div class="product-badge bg-secondary border-default text-body">
                                                            {{ __('out of stock') }}
                                                        </div>
                                                    @endif
                                                    @php
                                                        // Calculate discount percentage based on whether product has attributes
                                                        $discountPercentage = 0;
                                                        
                                                        if (count($item->attributes) > 0) {
                                                            // For products with attributes, find the highest discount percentage among options
                                                            foreach ($item->attributes as $attribute) {
                                                                foreach ($attribute->options as $option) {
                                                                    if ($option->sale_price && $option->sale_price < $option->price) {
                                                                        $optionDiscount = round((($option->price - $option->sale_price) / $option->price) * 100);
                                                                        if ($optionDiscount > $discountPercentage) {
                                                                            $discountPercentage = $optionDiscount;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        } elseif ($item->previous_price && $item->previous_price != 0) {
                                                            // For regular products - use the existing DiscountPercentage method
                                                            $discountPercentage = str_replace('%', '', PriceHelper::DiscountPercentage($item));
                                                        }
                                                    @endphp

                                                    {{-- @if ($discountPercentage > 0)
                                                        <div class="product-badge product-badge2 bg-info 12">-{{ $discountPercentage }}%</div>
                                                    @endif --}}
                                                    <div class="product-thumb-image-wrapper">
                                                        <a href="{{ route('front.product', $item->slug) }}">
                                                            <img class="lazy" data-src="{{ url('/storage/images/' . $item->thumbnail) }}" alt="Product">
                                                        </a>
                                                    </div>
                                                    <div class="product-button-group"><a
                                                            class="product-button wishlist_store"
                                                            href="{{ route('user.wishlist.store', $item->id) }}"
                                                            title="{{ __('Wishlist') }}"><i class="icon-heart"></i></a>
                                                        @include('includes.item_footer', [
                                                            'sitem' => $item,
                                                        ])
                                                    </div>
                                                </div>
                                                <div class="product-card-inner">
                                                    <div class="product-card-body">

                                                        {{-- <div class="product-category"><a
                                                                href="{{ route('front.category', $cat->slug) }}">{{ $item->category->name }}</a>
                                                        </div> --}}
                                                        <h3 class="product-title"><a
                                                                href="{{ route('front.product', $item->slug) }}">
                                                                {{ Str::limit($item->name, 50) }}
                                                            </a></h3>
                                                        {{-- <div class="rating-stars">
                                                            {!! Helper::renderStarRating($item->reviews->avg('rating')) !!}
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
                                                        {{-- @if (date('d-m-y') != \Carbon\Carbon::parse($item->date)->format('d-m-y'))
                                                            <div class="countdown countdown-alt mb-3"
                                                                data-date-time="{{ $item->date }}">
                                                            </div>
                                                        @endif --}}
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($setting->is_two_column_category == 0)
        <div class="flash-sell-area mt-50">
            <div class="container">
                <div class="row gx-3 justify-content-center">
                    @foreach ($two_column_categoriess as $two_column_key => $two_column_category)
                        <div class="col-xl-4 col-lg-6">
                            <div class="section-title">
                                <h2 class="h3">{{ $two_column_category['name']->name }}</h2>
                            </div>
                            <div class="main-content">
                                <div class="newproduct-slider owl-carousel">
                                    @foreach ($two_column_categoriess[$two_column_key]['items']->chunk(4) as $two_column_category_itemt)
                                        <div class="slider-item">
                                            @foreach ($two_column_category_itemt as $two_column_category_item)
                                                <div class="product-card p-col">
                                                    <a class="product-thumb"
                                                        href="{{ route('front.product', $two_column_category_item->slug) }}">
                                                        @if (!$two_column_category_item->is_stock())
                                                            <div
                                                                class="product-badge bg-secondary border-default text-body
                                                    ">
                                                                {{ __('out of stock') }}</div>
                                                        @endif

                                                        <img class="lazy"
                                                            data-src="{{ url('/storage/images/' . $two_column_category_item->thumbnail) }}"
                                                            alt="Product">
                                                    </a>
                                                    <div class="product-card-body">
                                                        <h3 class="product-title"><a
                                                                href="{{ route('front.product', $two_column_category_item->slug) }}">
                                                                {{ Str::limit($two_column_category_item->name, 40) }}
                                                            </a></h3>
                                                        {{-- <div class="rating-stars">
                                                            {!! Helper::renderStarRating($two_column_category_item->reviews->avg('rating')) !!}
                                                        </div> --}}
                                                        <h4 class="product-price text-start">
                                                            @if ($two_column_category_item->previous_price != 0)
                                                                <del>{{ PriceHelper::setPreviousPrice($two_column_category_item->previous_price) }}</del>
                                                            @endif
                                                            {{ PriceHelper::grandCurrencyPrice($two_column_category_item) }}
                                                        </h4>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    @endif

    @if ($setting->is_two_c_b == 0)
        <div class="bannner-section mt-50">
            <div class="container ">
                <div class="row gx-3">
                    <div class="col-md-6">
                        <a href="{{ $banner_third['url1'] }}" class="genius-banner">
                            <img class="lazy" data-src="{{ url('/storage/images/' . $banner_third['img1']) }}"
                                alt="">
                            <div class="inner-content">
                                @if (isset($banner_third['subtitle1']))
                                    <p>{{ $banner_third['subtitle1'] }}</p>
                                @endif
                                @if (isset($banner_third['title1']))
                                    <h4>{{ $banner_third['title1'] }}</h4>
                                @endif
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ $banner_third['url2'] }}" class="genius-banner">
                            <img class="lazy" data-src="{{ url('/storage/images/' . $banner_third['img2']) }}"
                                alt="">
                            <div class="inner-content">
                                @if (isset($banner_third['subtitle2']))
                                    <p>{{ $banner_third['subtitle2'] }} </p>
                                @endif
                                @if (isset($banner_third['title2']))
                                    <h4>{{ $banner_third['title2'] }}</h4>
                                @endif
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($setting->is_featured_category == 0)
        <section class="selected-product-section featured_cat_sec sps-two mt-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title">
                            <h2 class="h3">{{ $feature_category_title }}</h2>
                            <div class="links">
                                @foreach ($feature_categories as $key => $feature_category)
                                    <a class="category_get {{ $loop->first ? 'active' : '' }}"
                                        data-target="feature_category_view"
                                        data-href="{{ route('front.popular.category', [$feature_category->slug, 'feature_category', 'normal']) }}"
                                        href="javascript:;"
                                        class="{{ $loop->first ? 'active' : '' }}">{{ $feature_category->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="feature_category_view d-none">
                    <img src="{{ url('/storage/images/ajax_loader.gif') }}" alt="">
                </div>
                <div class="row g-3" id="feature_category_view">
                    @foreach ($feature_category_items as $feature_category_item)
                        <div class="col-gd">
                            <div class="product-card">
                                <div class="product-thumb">

                                    @if (!$feature_category_item->is_stock())
                                        <div
                                            class="product-badge bg-secondary border-default text-body
                                    ">
                                            {{ __('out of stock') }}</div>
                                    @endif
                                    @if ($feature_category_item->previous_price && $feature_category_item->previous_price != 0)
                                        <div class="product-badge product-badge2 bg-info">
                                            -{{ PriceHelper::DiscountPercentage($feature_category_item) }}</div>
                                    @endif
                                    <img class="lazy"
                                        data-src="{{ url('/storage/images/' . $feature_category_item->thumbnail) }}"
                                        alt="Product">
                                    <div class="product-button-group"><a class="product-button wishlist_store"
                                            href="{{ route('user.wishlist.store', $feature_category_item->id) }}"
                                            title="{{ __('Wishlist') }}"><i class="icon-heart"></i></a>
                                        @include('includes.item_footer', [
                                            'sitem' => $feature_category_item,
                                        ])

                                    </div>
                                </div>
                                <div class="product-card-body">
                                    {{-- <div class="product-category"><a
                                            href="{{ route('front.catalog') . '?category=' . $feature_category_item->category->slug }}">{{ $feature_category_item->category->name }}</a>
                                    </div> --}}
                                    <h3 class="product-title"><a
                                            href="{{ route('front.product', $feature_category_item->slug) }}">
                                            {{ Str::limit($feature_category_item->name, 35) }}
                                        </a></h3>
                                    {{-- <div class="rating-stars">
                                        {!! Helper::renderStarRating($feature_category_item->reviews->avg('rating')) !!}
                                    </div> --}}
                                    <h4 class="product-price text-start">
                                        @if ($feature_category_item->previous_price != 0)
                                            <del>{{ PriceHelper::setPreviousPrice($feature_category_item->previous_price) }}</del>
                                        @endif
                                        {{ PriceHelper::grandCurrencyPrice($feature_category_item) }}
                                    </h4>
                                </div>

                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </section>
    @endif

    @if ($setting->is_blogs == 0)
        <div class="blog-section-h page_section mt-50 mb-30">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title">
                            <h2 class="h3">{{ __('Our Blog') }}</h2>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="home-blog-slider owl-carousel">
                            @foreach ($posts as $post)
                                <div class="slider-item">
                                    <a href="{{ route('front.blog.details', $post->slug) }}" class="blog-post">
                                        <div class="post-thumb">
                                            <img class="lazy"
                                                data-src="{{ url('/storage/images/' . json_decode($post->photo, true)[array_key_first(json_decode($post->photo, true))]) }}"
                                                alt="Blog Post">
                                        </div>
                                        <div class="post-body">

                                            <h3 class="post-title"> {{ Str::limit($post->title, 55) }}
                                            </h3>
                                            <ul class="post-meta">

                                                <li><i class="icon-user"></i>{{ __('Admin') }}</li>
                                                <li><i
                                                        class="icon-clock"></i>{{ date('jS F, Y', strtotime($post->created_at)) }}
                                                </li>
                                            </ul>
                                            <p>{{ Str::limit(strip_tags($post->details), 120) }}
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($setting->is_popular_brand == 0)
        <section class="brand-section mt-30 mb-60">
            <div class="container ">
                <div class="row">
                    <div class="col-lg-12 ">
                        <div class="section-title">
                            <h2 class="h3">{{ __('Popular Brands') }}</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="brand-slider owl-carousel">
                            @foreach ($brands as $brand)
                                <div class="slider-item">
                                    <a class="text-center"
                                        href="{{ route('front.catalog') . '?brand=' . $brand->slug }}">
                                        <img class="d-block hi-50 lazy"
                                            data-src="{{ url('/storage/images/' . $brand->photo) }}"
                                            alt="{{ $brand->name }}" title="{{ $brand->name }}">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if ($setting->is_service == 1)
        <section class="service-section">
            <div class="container">
                <div class="row">
                    @foreach ($services as $service)
                        <div class="col-6 col-md-3 text-center mb-30">
                            <div class="single-service single-service2">
                                <img src="{{ url('/storage/images/' . $service->photo) }}" alt="Shipping">
                                <div class="content">
                                    <h6 class="mb-2">{{ $service->title }}</h6>
                                    {{-- <p class="text-sm text-muted mb-0">{{ $service->details }}</p> --}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
