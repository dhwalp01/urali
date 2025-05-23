@extends('master.front')
@section('title')
{{ $item->name }}
@endsection
@section('meta')
<meta name="tile" content="{{ $item->title }}">
<meta name="keywords" content="{{ $item->meta_keywords }}">
<meta name="description" content="{{ $item->meta_description }}">
<meta name="twitter:title" content="{{ $item->title }}">
<meta name="twitter:image" content="{{ url('/storage/images/' . $item->photo) }}">
<meta name="twitter:description" content="{{ $item->meta_description }}">
<meta name="og:title" content="{{ $item->title }}">
<meta name="og:image" content="{{ url('/storage/images/' . $item->photo) }}">
<meta name="og:description" content="{{ $item->meta_description }}">
@endsection
@section('content')
<div class="page-title">
</div>
<!-- Page Content-->
<div class="container padding-bottom-1x mb-1">
   <div class="row">
      <!-- Poduct Gallery-->
      <div class="col-xxl-5 col-lg-6 col-md-6">
         <div class="product-gallery">
            <!-- Main image display with navigation arrows -->
            <div class="product-main-image-container position-relative">
               <img src="{{ url('/storage/images/' . $item->photo) }}"
                     alt="zoom" class="product-main-image animate__animated" id="main-product-image" />
               
               <!-- Navigation arrows for main image -->
               <div class="main-image-nav">
                     <button class="nav-arrow prev-image" id="prev-image">
                        <i class="icon-chevron-left"></i>
                     </button>
                     <button class="nav-arrow next-image" id="next-image">
                        <i class="icon-chevron-right"></i>
                     </button>
               </div>
            </div>
            
            <!-- Thumbnail slider -->
            <div class="product-thumbnails-slider owl-carousel mt-3">
               <div class="item thumbnail-item" data-image="{{ url('/storage/images/' . $item->photo) }}" data-index="0">
                     <img src="{{ url('/storage/images/' . $item->photo) }}"
                        alt="thumb" class="img-fluid" />
               </div>
               @foreach ($galleries as $key => $gallery)
               <div class="item thumbnail-item" data-image="{{ url('/storage/images/' . $gallery->photo) }}" data-index="{{ $key + 1 }}">
                     <img src="{{ url('/storage/images/' . $gallery->photo) }}"
                        alt="thumb" class="img-fluid" />
               </div>
               @endforeach
            </div>
         </div>
      </div>
      <!-- Product Info-->
      <div class="col-xxl-7 col-lg-6 col-md-6">
         <div class="details-page-top-right-content d-flex">
            <div class="div w-100">
               <input type="hidden" id="item_id" value="{{ $item->id }}">
               @php
               $initialPrice = 0;
               if (count($item->attributes)) {
               foreach ($item->attributes as $attribute) {
               foreach ($attribute->options as $option) {
               if ($option->stock != 0 && $option->price > 0) {
               $initialPrice = $option->price;
               break 2; // exit both loops when first in-stock price is found
               }
               }
               }
               } else {
               $initialPrice = $item->discount_price;
               }
               @endphp
               <input type="hidden" id="demo_price" value="{{ count($item->attributes) > 0 ? PriceHelper::setConvertPrice($item->attributes->first()->options->first()->price ?? 0) : PriceHelper::setConvertPrice($item->discount_price) }}">
               <input type="hidden" value="{{ PriceHelper::setCurrencySign() }}" id="set_currency">
               <input type="hidden" value="{{ PriceHelper::setCurrencyValue() }}" id="set_currency_val">
               <input type="hidden" value="{{ $setting->currency_direction }}" id="currency_direction">
               <ul class="breadcrumbs">
                  <li><a href="{{ route('front.index') }}">{{ __('Home') }}</a>
                  </li>
                  <li class="separator"></li>
                  <li><a href="{{ route('front.catalog') }}">{{ __('Shop') }}</a>
                  </li>
                  <li class="separator"></li>
                  <li>{{ $item->name }}</li>
               </ul>
               <h4 class="mb-2 p-title-main">{{ $item->name }}</h4>
               <div class="mb-3">
                  <div class="rating-stars d-inline-block gmr-3">
                     {!! Helper::renderStarRating($item->reviews->avg('rating')) !!}
                  </div>
                  <span id="dynamic_stock">
                  @if ($item->is_stock())
                  <span class="text-success d-inline-block">
                  {{ __('In Stock') }} <b>({{ $item->stock }} @lang('items'))</b>
                  </span>
                  @else
                  <span class="text-danger d-inline-block">{{ __('Out of stock') }}</span>
                  @endif
                  </span>
               </div>
               @if ($item->is_type == 'flash_deal')
               {{-- @if (date('d-m-y') != \Carbon\Carbon::parse($item->date)->format('d-m-y'))
                <div class="countdown countdown-alt mb-3" data-date-time="{{ $item->date }}">
                </div>
               @endif --}}
               @endif
               <span class="h3 d-block price-area">
                  @php
                      // Calculate initial prices
                      $initialPrice = $item->price;
                      $initialDiscountPrice = $item->discount_price ?: $item->price;
                      $hasDiscount = $item->previous_price && $item->previous_price > $initialDiscountPrice;
                  @endphp
                  
                  @if ($hasDiscount)
                      <small class="d-inline-block"><del>{{ PriceHelper::setPreviousPrice($item->previous_price) }}</del></small>
                  @endif
                  
                  <span id="main_price" class="main-price" 
                        data-base-price="{{ $initialPrice }}"
                        data-discount-price="{{ $initialDiscountPrice }}"
                        data-has-attributes="{{ count($item->attributes) > 0 ? 'true' : 'false' }}">
                     {{ PriceHelper::grandCurrencyPrice($item) }}
                  </span>
              </span>              
               <p class="text-muted">
                  {!! $item->sort_details !!}
                  <a href="#details"
                     class="scroll-to">{{ __('Read more..') }}</a>
               </p>
               <div class="row margin-top-1x">
                  @foreach ($attributes as $attribute)
                      @if ($attribute->options->count())
                          <div class="col-sm-6">
                              <div class="form-group">
                                  <label>{{ $attribute->name }}</label>
                                  <div class="attribute-options d-flex flex-wrap">
                                      @foreach ($attribute->options as $index => $option)
                                          <label class="option-box mr-2 mb-2 {{ $index === 0 ? 'selected' : '' }}">
                                              <input 
                                                  type="radio" 
                                                  name="attribute_{{ $attribute->id }}" 
                                                  value="{{ $option->name }}"
                                                  data-type="{{ $attribute->id }}"
                                                  data-href="{{ $option->id }}"
                                                  data-target="{{ PriceHelper::setConvertPrice($option->price) }}"
                                                  @if($option->sale_price)
                                                      data-sale-price="{{ PriceHelper::setConvertPrice($option->sale_price) }}"
                                                  @endif
                                                  data-stock="{{ $option->stock }}"
                                                  data-sku="{{ $option->sku ?: $item->sku }}"
                                                  @if ($index === 0) checked @endif
                                              >
                                              <span class="box-label">{{ $option->name }}</span>
                                          </label>
                                      @endforeach
                                  </div>
                              </div>
                          </div>
                      @endif
                  @endforeach
              </div>
               <div class="row align-items-end pb-4">
                  <div class="col-sm-12">
                     @if ($item->item_type == 'normal')
                     <div class="form-group product-quantity mb-3 align-items-center gap-1">
                        <label for="quantity" class="form-label d-block">{{ __('Quantity') }}</label>
                        <select class="form-select w-auto d-inline-block" id="quantity" name="quantity">
                           @for ($i = 1; $i <= $item->stock; $i++)
                           <option value="{{ $i }}">{{ $i }}</option>
                           @endfor
                        </select>
                        <input type="hidden" value="{{ $item->stock }}" id="current_stock">
                     </div>
                     @endif
                     <div class="p-action-button">
                        @if ($item->item_type != 'affiliate')
                        @php
                        // Determine initial stock: either from first attribute option or fallback to product stock
                        $hasAttributes = count($item->attributes) > 0;
                        $firstOptionStock = 0;
                        if ($hasAttributes) {
                        foreach ($item->attributes as $attribute) {
                        if ($attribute->options->count()) {
                        $firstOptionStock = (int) $attribute->options->first()->stock;
                        break; // Check only first attribute with options
                        }
                        }
                        }
                        @endphp
                        @if (!$hasAttributes && $item->is_stock() || $hasAttributes && $firstOptionStock > 0)
                        <button class="btn btn-primary m-0 a-t-c-mr" id="add_to_cart"><i class="icon-bag"></i>
                        <span>{{ __('Add to Cart') }}</span></button>
                        <button class="btn btn-primary m-0" id="but_to_cart"><i class="icon-bag"></i>
                        <span>{{ __('Buy Now') }}</span></button>
                        @else
                        <button class="btn btn-primary m-0" disabled><i class="icon-bag"></i>
                        <span>{{ __('Out of stock') }}</span></button>
                        @endif
                        @else
                        <a href="{{ $item->affiliate_link }}" target="_blank" class="btn btn-primary m-0">
                        <span><i class="icon-bag"></i>{{ __('Buy Now') }}</span></a>
                        @endif
                     </div>
                  </div>
               </div>
               <div class="div">
                  {{-- <div class="t-c-b-area">
                     @if ($item->brand_id)
                     <div class="pt-1 mb-1"><span class="text-medium">{{ __('Brand') }}:</span>
                        <a
                           href="{{ route('front.catalog') . '?brand=' . $item->brand->slug }}">{{ $item->brand->name }}</a>
                     </div>
                     @endif
                     
                     <div class="pt-1 mb-1"><span class="text-medium">{{ __('Categories') }}:</span>
                        <a
                           href="{{ route('front.category', $item->category->slug) }}">{{ $item->category->name }}</a>
                        @if ($item->subcategory->name)
                        /
                        @endif
                        <a
                           href="{{ route('front.catalog') . '?subcategory=' . $item->subcategory->slug }}">{{ $item->subcategory->name }}</a>
                        @if ($item->childcategory->name)
                        /
                        @endif
                        <a
                           href="{{ route('front.catalog') . '?childcategory=' . $item->childcategory->slug }}">{{ $item->childcategory->name }}</a>
                     </div>
                     <div class="pt-1 mb-1"><span class="text-medium">{{ __('Tags') }}:</span>
                        @if ($item->tags)
                        @foreach (explode(',', $item->tags) as $tag)
                        @if ($loop->last)
                        <a
                           href="{{ route('front.catalog') . '?tag=' . $tag }}">{{ $tag }}</a>
                        @else
                        <a
                           href="{{ route('front.catalog') . '?tag=' . $tag }}">{{ $tag }}</a>,
                        @endif
                        @endforeach
                        @endif
                     </div>
                    
                     @if ($item->item_type == 'normal')
                        <div class="pt-1 mb-4"><span class="text-medium">{{ __('SKU') }}:</span>
                           <span class="sku-display">#{{ $item->sku }}</span>
                        </div>
                     @endif
                  </div> --}}
                  <div class="mt-4 p-d-f-area">
                     <div class="left">
                        <a class="btn btn-primary btn-sm wishlist_store wishlist_text"
                           href="{{ route('user.wishlist.store', $item->id) }}"><span><i
                           class="icon-heart"></i></span>
                        @if (Auth::check() &&
                        App\Models\Wishlist::where('user_id', Auth::user()->id)->where('item_id', $item->id)->exists())
                        <span>{{ __('Added To Wishlist') }}</span>
                        @else
                        <span class="wishlist1">{{ __('Wishlist') }}</span>
                        <span class="wishlist2 d-none">{{ __('Added To Wishlist') }}</span>
                        @endif
                        </a>
                        {{-- <button class="btn btn-primary btn-sm  product_compare"
                           data-target="{{ route('fornt.compare.product', $item->id) }}"><span><i
                           class="icon-repeat"></i>{{ __('Compare') }}</span></button> --}}
                     </div>
                     <div class="d-flex align-items-center">
                        <span class="text-muted mr-1">{{ __('Share') }}: </span>
                        <div class="d-inline-block a2a_kit">
                           <a class="facebook  a2a_button_facebook" href="">
                           <span><i class="fab fa-facebook-f"></i></span>
                           </a>
                           <a class="twitter  a2a_button_twitter" href="">
                           <span><i class="fab fa-twitter"></i></span>
                           </a>
                           <a class="linkedin  a2a_button_linkedin" href="">
                           <span><i class="fab fa-linkedin-in"></i></span>
                           </a>
                           <a class="pinterest   a2a_button_pinterest" href="">
                           <span><i class="fab fa-pinterest"></i></span>
                           </a>
                        </div>
                        <script async src="https://static.addtoany.com/menu/page.js"></script>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class=" padding-top-3x mb-3" id="details">
         <div class="col-lg-12">
            <ul class="nav nav-tabs" role="tablist">
               {{-- <li class="nav-item" role="presentation">
                  <a class="nav-link rounded active" id="description-tab" data-bs-toggle="tab"
                     data-bs-target="#description" type="button" role="tab" aria-controls="description"
                     aria-selected="true">{{ __('Descriptions') }}</a>
               </li> --}}
               {{-- 
               <li class="nav-item" role="presentation">
                  <a class="nav-link rounded" id="specification-tab" data-bs-toggle="tab"
                     data-bs-target="#specification" type="button" role="tab"
                     aria-controls="specification" aria-selected="false">{{ __('Specifications') }}</a>
               </li>
               --}}
            </ul>
            <div class="tab-content card">
               <div class="tab-pane fade show active" id="description" role="tabpanel"
                  aria-labelledby="description-tab"">
                  {!! $item->details !!}
               </div>
               <div class="tab-pane fade show" id="specification" role="tabpanel"
                  aria-labelledby="specification-tab">
                  <div class="comparison-table">
                     <table class="table table-bordered">
                        <thead class="bg-secondary">
                        </thead>
                        <tbody>
                           <tr class="bg-secondary">
                              <th class="text-uppercase">{{ __('Specifications') }}</th>
                              <td><span class="text-medium">{{ __('Descriptions') }}</span></td>
                           </tr>
                           @if ($sec_name)
                           @foreach (array_combine($sec_name, $sec_details) as $sname => $sdetail)
                           <tr>
                              <th>{{ $sname }}</th>
                              <td>{{ $sdetail }}</td>
                           </tr>
                           @endforeach
                           @else
                           <tr class="text-center">
                              <td colspan="2">{{ __('No Specifications') }}</td>
                           </tr>
                           @endif
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- Reviews-->
{{-- 
<div class="container  review-area">
   <div class="row">
      <div class="col-lg-12">
         <div class="section-title">
            <h2 class="h3">{{ __('Latest Reviews') }}</h2>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-8">
         @forelse ($reviews as $review)
         <div class="single-review">
            <div class="comment">
               <div class="comment-author-ava"><img class="lazy"
                  data-src="{{ url('/storage/images/' . $review->user->photo) }}"
                  alt="Comment author">
               </div>
               <div class="comment-body">
                  <div class="comment-header d-flex flex-wrap justify-content-between">
                     <div>
                        <h4 class="comment-title mb-1">{{ $review->subject }}</h4>
                        <span>{{ $review->user->first_name }}</span>
                        <span class="ml-3">{{ $review->created_at->format('M d, Y') }}</span>
                     </div>
                     <div class="mb-2">
                        <div class="rating-stars">
                           @php
                           for ($i = 0; $i < $review->rating; $i++) {
                           echo "<i class = 'far fa-star filled'></i>";
                           }
                           @endphp
                        </div>
                     </div>
                  </div>
                  <p class="comment-text  mt-2">{{ $review->review }}</p>
               </div>
            </div>
         </div>
         @empty
         <div class="card p-5">
            {{ __('No Review') }}
         </div>
         @endforelse
         <div class="row mt-15">
            <div class="col-lg-12 text-center">
               {{ $reviews->links() }}
            </div>
         </div>
      </div>
      <div class="col-md-4 mb-4">
         <div class="card">
            <div class="card-body">
               <div class="text-center">
                  <div class="d-inline align-baseline display-3 mr-1">
                     {{ round($item->reviews->avg('rating'), 2) }}
                  </div>
                  <div class="d-inline align-baseline text-sm text-warning mr-1">
                     <div class="rating-stars">
                        {!! Helper::renderStarRating($item->reviews->avg('rating')) !!}
                     </div>
                  </div>
               </div>
               <div class="pt-3">
                  <label class="text-medium text-sm">5 {{ __('stars') }} <span class="text-muted">-
                  {{ $item->reviews->where('status', 1)->where('rating', 5)->count() }}</span></label>
                  <div class="progress margin-bottom-1x">
                     <div class="progress-bar bg-warning" role="progressbar"
                        style="width: {{ $item->reviews->where('status', 1)->where('rating', 5)->sum('rating') * 20 }}%; height: 2px;"
                        aria-valuenow="100"
                        aria-valuemin="{{ $item->reviews->where('rating', 5)->sum('rating') * 20 }}"
                        aria-valuemax="100"></div>
                  </div>
                  <label class="text-medium text-sm">4 {{ __('stars') }} <span class="text-muted">-
                  {{ $item->reviews->where('status', 1)->where('rating', 4)->count() }}</span></label>
                  <div class="progress margin-bottom-1x">
                     <div class="progress-bar bg-warning" role="progressbar"
                        style="width: {{ $item->reviews->where('status', 1)->where('rating', 4)->sum('rating') * 20 }}%; height: 2px;"
                        aria-valuenow="{{ $item->reviews->where('rating', 4)->sum('rating') * 20 }}"
                        aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <label class="text-medium text-sm">3 {{ __('stars') }} <span class="text-muted">-
                  {{ $item->reviews->where('status', 1)->where('rating', 3)->count() }}</span></label>
                  <div class="progress margin-bottom-1x">
                     <div class="progress-bar bg-warning" role="progressbar"
                        style="width: {{ $item->reviews->where('rating', 3)->sum('rating') * 20 }}%; height: 2px;"
                        aria-valuenow="{{ $item->reviews->where('rating', 3)->sum('rating') * 20 }}"
                        aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <label class="text-medium text-sm">2 {{ __('stars') }} <span class="text-muted">-
                  {{ $item->reviews->where('status', 1)->where('rating', 2)->count() }}</span></label>
                  <div class="progress margin-bottom-1x">
                     <div class="progress-bar bg-warning" role="progressbar"
                        style="width: {{ $item->reviews->where('status', 1)->where('rating', 2)->sum('rating') * 20 }}%; height: 2px;"
                        aria-valuenow="{{ $item->reviews->where('rating', 2)->sum('rating') * 20 }}"
                        aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <label class="text-medium text-sm">1 {{ __('star') }} <span class="text-muted">-
                  {{ $item->reviews->where('status', 1)->where('rating', 1)->count() }}</span></label>
                  <div class="progress mb-2">
                     <div class="progress-bar bg-warning" role="progressbar"
                        style="width: {{ $item->reviews->where('status', 1)->where('rating', 1)->sum('rating') * 20 }}; height: 2px;"
                        aria-valuenow="0"
                        aria-valuemin="{{ $item->reviews->where('rating', 1)->sum('rating') * 20 }}"
                        aria-valuemax="100"></div>
                  </div>
               </div>
               @if (Auth::user())
               <div class="pb-2"><a class="btn btn-primary btn-block" href="#"
                  data-bs-toggle="modal"
                  data-bs-target="#leaveReview"><span>{{ __('Leave a Review') }}</span></a></div>
               @else
               <div class="pb-2"><a class="btn btn-primary btn-block"
                  href="{{ route('user.login') }}"><span>{{ __('Login') }}</span></a></div>
               @endif
            </div>
         </div>
      </div>
   </div>
</div>
--}}
@if (count($related_items) > 0)
<div class="relatedproduct-section container padding-bottom-3x mb-1 s-pt-30">
   <!-- Related Products Carousel-->
   <div class="row">
      <div class="col-lg-12">
         <div class="section-title">
            <h2 class="h3">{{ __('You May Also Like') }}</h2>
         </div>
      </div>
   </div>
   <!-- Carousel-->
   <div class="row">
      <div class="col-lg-12">
         <div class="relatedproductslider owl-carousel">
            @foreach ($related_items as $related)
            <div class="slider-item">
               <div class="product-card">
                @php
                    $hasAttributeStock = $related->attributes->flatMap->options->pluck('stock')->filter(fn($stock) => $stock > 0)->count() > 0;
                    $isInStock = count($related->attributes) > 0 ? $hasAttributeStock : $related->is_stock();
                    @endphp
                    @if ($isInStock)
                    @if ($related->is_type != 'new')
                        <div
                            class="product-badge
                            @if ($related->is_type == 'feature') bg-warning
                            @elseif($related->is_type == 'top') bg-info
                            @elseif($related->is_type == 'best') bg-dark
                            @elseif($related->is_type == 'flash_deal') bg-success
                            @endif
                            ">
                            {{ $related->is_type != 'undefine' ? ucfirst(str_replace('_', ' ', $related->is_type)) : '' }}
                        </div>
                    @endif
                @else
                    <div class="product-badge bg-secondary border-default text-body">
                        {{ __('out of stock') }}
                    </div>
                @endif
                  @php
                     // Calculate discount percentage based on whether product has attributes
                     $relatedDiscountPercentage = 0;
                     
                     if (count($related->attributes) > 0) {
                        // For products with attributes, find the highest discount percentage among options
                        foreach ($related->attributes as $attribute) {
                              foreach ($attribute->options as $option) {
                                 if ($option->sale_price && $option->sale_price < $option->price) {
                                    $optionDiscount = round((($option->price - $option->sale_price) / $option->price) * 100);
                                    if ($optionDiscount > $relatedDiscountPercentage) {
                                          $relatedDiscountPercentage = $optionDiscount;
                                    }
                                 }
                              }
                        }
                     } elseif ($related->previous_price && $related->previous_price != 0) {
                        // For regular products - use the existing DiscountPercentage method
                        $relatedDiscountPercentage = str_replace('%', '', PriceHelper::DiscountPercentage($related));
                     }
                  @endphp

                  @if ($relatedDiscountPercentage > 0)
                     <div class="product-badge product-badge2 bg-info">-{{ $relatedDiscountPercentage }}%</div>
                  @endif
                  <div class="product-thumb">
                     <div class="product-thumb-image-wrapper">
                        <a
                           href="{{ route('front.product', $related->slug) }}">
                        <img
                           class="lazy product-thumb-image"
                           data-src="{{ url('/storage/images/' . $related->thumbnail) }}"
                           alt="Product"
                           >
                        </a>
                     </div>
                     <div class="product-button-group">
                        <a class="product-button wishlist_store"
                           href="{{ route('user.wishlist.store', $related->id) }}"
                           title="{{ __('Wishlist') }}"><i class="icon-heart"></i></a>
                        {{-- <a class="product-button product_compare" href="javascript:;"
                           data-target="{{ route('fornt.compare.product', $related->id) }}"
                           title="{{ __('Compare') }}"><i class="icon-repeat"></i></a> --}}
                        @include('includes.item_footer', ['sitem' => $related])
                     </div>
                  </div>
                  <div class="product-card-body">
                     <div class="product-category">
                        <a href="{{route('front.category', $item->category->slug)}}">{{$item->category->name}}</a>
                     </div>
                     <h3 class="product-title"><a
                        href="{{ route('front.product', $related->slug) }}">
                        {{ Str::limit($related->name, 35) }}
                        </a>
                     </h3>
                     <h4 class="product-price text-start">
                        {{ PriceHelper::grandCurrencyPrice($related) }}
                        @if ($related->previous_price != 0)
                           <del>{{ PriceHelper::setPreviousPrice($related->previous_price) }}</del>
                        @endif
                     </h4>
                  </div>
               </div>
            </div>
            @endforeach
         </div>
      </div>
   </div>
</div>
@endif
@auth
<form class="modal fade ratingForm" action="{{ route('front.review.submit') }}" method="post" id="leaveReview"
   tabindex="-1">
   @csrf
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">{{ __('Leave a Review') }}</h4>
            <button class="close modal_close" type="button" data-bs-dismiss="modal" aria-label="Close"><span
               aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
            @php
            $user = Auth::user();
            @endphp
            <div class="row">
               <div class="col-sm-6">
                  <div class="form-group">
                     <label for="review-name">{{ __('Your Name') }}</label>
                     <input class="form-control" type="text" id="review-name"
                        value="{{ $user->first_name }}" required>
                  </div>
               </div>
               <input type="hidden" name="item_id" value="{{ $item->id }}">
               <div class="col-sm-6">
                  <div class="form-group">
                     <label for="review-email">{{ __('Your Email') }}</label>
                     <input class="form-control" type="email" id="review-email"
                        value="{{ $user->email }}" required>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-sm-6">
                  <div class="form-group">
                     <label for="review-subject">{{ __('Subject') }}</label>
                     <input class="form-control" type="text" name="subject" id="review-subject" required>
                  </div>
               </div>
               <div class="col-sm-6">
                  <div class="form-group">
                     <label for="review-rating">{{ __('Rating') }}</label>
                     <select name="rating" class="form-control" id="review-rating">
                        <option value="5">5 {{ __('Stars') }}</option>
                        <option value="4">4 {{ __('Stars') }}</option>
                        <option value="3">3 {{ __('Stars') }}</option>
                        <option value="2">2 {{ __('Stars') }}</option>
                        <option value="1">1 {{ __('Star') }}</option>
                     </select>
                  </div>
               </div>
            </div>
            <div class="form-group">
               <label for="review-message">{{ __('Review') }}</label>
               <textarea class="form-control" name="review" id="review-message" rows="8" required></textarea>
            </div>
         </div>
         <div class="modal-footer">
            <button class="btn btn-primary" type="submit"><span>{{ __('Submit Review') }}</span></button>
         </div>
      </div>
   </div>
</form>
@endauth
@endsection
@section('script')
<script>
   $(function () {
    // Only initialize price updates for products WITH attributes
    if ($('input[type=radio][name^="attribute_"]').length > 0) {
        function updatePriceAndStock() {
            let total = 0;
            let saleTotal = 0;
            let stock = null;
            let hasSale = false;
            let discountPercentage = 0;

            $('input[type=radio][name^="attribute_"]:checked').each(function () {
                const $input = $(this);
                const price = parseFloat($input.data('target')) || 0;
                const salePrice = $input.data('sale-price') ? parseFloat($input.data('sale-price')) : price;
                
                total += price;
                saleTotal += salePrice;
                
                if (salePrice < price) {
                    hasSale = true;
                    const optionDiscount = Math.round(((price - salePrice) / price) * 100);
                    if (optionDiscount > discountPercentage) {
                        discountPercentage = optionDiscount;
                    }
                }
                
                const currentStock = parseInt($input.data('stock'));
                if (stock === null || currentStock < stock) {
                    stock = currentStock;
                }
            });

            // Update display
            const currency = $('#set_currency').val();
            const direction = $('#currency_direction').val();
            const currencyValue = parseFloat($('#set_currency_val').val()) || 1;

            // Calculate final prices
            const finalTotal = total * currencyValue;
            const finalSaleTotal = saleTotal * currencyValue;

            let priceHtml = '';
            if (hasSale) {
                if (direction === '1') {
                    priceHtml = `<span class="main-price">${currency}${finalSaleTotal.toFixed(2)}</span>  <small class="d-inline-block"><del>${currency}${finalTotal.toFixed(2)}</del></small>
                                `;
                } else {
                    priceHtml = `<small class="d-inline-block"><del>${finalTotal.toFixed(2)}${currency}</del></small>
                                <span class="main-price">${finalSaleTotal.toFixed(2)}${currency}</span>`;
                }
            } else {
                if (direction === '1') {
                    priceHtml = `<span class="main-price">${currency}${finalTotal.toFixed(2)}</span>`;
                } else {
                    priceHtml = `<span class="main-price">${finalTotal.toFixed(2)}${currency}</span>`;
                }
            }

            $('.price-area').html(priceHtml);

            // Update stock display
            $('#dynamic_stock').html(
                stock > 0
                    ? `<span class="text-success d-inline-block">In Stock <b>(${stock} items)</b></span>`
                    : `<span class="text-danger d-inline-block">Out of Stock</span>`
            );

            // Update quantity dropdown
            const $quantitySelect = $('#quantity');
            $quantitySelect.empty();
            const maxQty = stock;
            for (let i = 1; i <= maxQty; i++) {
                $quantitySelect.append(`<option value="${i}">${i}</option>`);
            }

            // Update hidden stock field
            $('#current_stock').val(stock);
        }

        // Set up event handlers only for products with attributes
        $('.option-box input[type=radio]').on('change', function () {
            const $box = $(this).closest('.option-box');
            const name = $(this).attr('name');

            // Remove selected from all in this group
            $(`input[name="${name}"]`).closest('.option-box').removeClass('selected');
            $box.addClass('selected');

            updatePriceAndStock();
        });

        // Initial trigger
        updatePriceAndStock();
    }
});

const img = document.querySelector('#main-product-image');
  const panzoom = Panzoom(img, {
    maxScale: 1,
    contain: 'outside'
  });
  // enable mouse wheel zoom
  img.parentElement.addEventListener('wheel', panzoom.zoomWithWheel);
  // toggle on tap
  img.addEventListener('click', () => panzoom.toggleZoom());
</script>
<script>
function magnify(imgID, zoom) {
  var img, glass, w, h, bw;
  img = document.getElementById(imgID);

  // Remove any existing glass
  var oldGlass = img.parentElement.querySelector('.img-magnifier-glass');
  if (oldGlass) oldGlass.remove();

  /* Create magnifier glass: */
  glass = document.createElement("DIV");
  glass.setAttribute("class", "img-magnifier-glass");

  /* Insert magnifier glass: */
  img.parentElement.appendChild(glass);

  // Set background to latest src!
  glass.style.backgroundImage = "url('" + img.src + "')";
  glass.style.backgroundRepeat = "no-repeat";
  glass.style.backgroundSize = (img.width * zoom) + "px " + (img.height * zoom) + "px";
  bw = 3;
  w = glass.offsetWidth / 2;
  h = glass.offsetHeight / 2;

  glass.addEventListener("mousemove", moveMagnifier);
  img.addEventListener("mousemove", moveMagnifier);
  glass.addEventListener("touchmove", moveMagnifier);
  img.addEventListener("touchmove", moveMagnifier);

  function moveMagnifier(e) {
    var pos, x, y;
    e.preventDefault();
    pos = getCursorPos(e);
    x = pos.x;
    y = pos.y;
    if (x > img.width - (w / zoom)) { x = img.width - (w / zoom);}
    if (x < w / zoom) { x = w / zoom;}
    if (y > img.height - (h / zoom)) { y = h / zoom;}
    if (y < h / zoom) { y = h / zoom;}
    glass.style.left = (x - w) + "px";
    glass.style.top = (y - h) + "px";
    glass.style.backgroundPosition = "-" + ((x * zoom) - w + bw) + "px -" + ((y * zoom) - h + bw) + "px";
  }

  function getCursorPos(e) {
    var a, x = 0, y = 0;
    e = e || window.event;
    a = img.getBoundingClientRect();
    x = e.pageX - a.left - window.pageXOffset;
    y = e.pageY - a.top - window.pageYOffset;
    return {x: x, y: y};
  }

  img.addEventListener("mouseleave", function() {
    glass.style.display = "none";
  });
  img.addEventListener("mouseenter", function() {
    glass.style.display = "block";
  });
}

function setMainImageAndMagnify(newSrc) {
    var img = document.getElementById('main-product-image');
    img.onload = function() {
        magnify('main-product-image', 2);
        img.onload = null; // remove handler after it fires
    };
    img.src = newSrc;
}

// For thumbnails
$('.thumbnail-item').on('click', function() {
    var newSrc = $(this).data('image'); // Adjust if your attribute is different
    setMainImageAndMagnify(newSrc);
});

// For arrows
$('.nav-arrow').on('click', function() {
    // Your logic for finding the new image src
    // For example, if you have a way to get the next image src:
    var currentIndex = parseInt($('.thumbnail-item.active').data('index'));
    var total = $('.thumbnail-item').length;
    var newIndex;
    if ($(this).hasClass('next-image')) {
        newIndex = (currentIndex + 1) % total;
    } else {
        newIndex = (currentIndex - 1 + total) % total;
    }
    var newSrc = $('.thumbnail-item[data-index="' + newIndex + '"]').data('image');
    setMainImageAndMagnify(newSrc);
});

// Call this whenever you change the image:
function updateMagnifierForNewImage(imgID, zoom) {
  magnify(imgID, zoom);
}

updateMagnifierForNewImage('main-product-image', 2);
</script>
@endsection