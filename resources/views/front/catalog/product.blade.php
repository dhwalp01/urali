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
<link rel="stylesheet" href="{{ asset('assets/front/css/product-zoom.css') }}">

<div class="page-title">
</div>

<!-- Page Content-->
<div class="container padding-bottom-1x mb-1">
  <div class="row">
    <!-- Product Gallery-->
    <div class="col-xxl-5 col-lg-6 col-md-6">
      <div class="product-gallery">
        <div class="product-main-image-container">
          <img
            src="{{ url('/storage/images/' . $item->photo) }}"
            alt="zoom"
            class="product-main-image"
            id="main-product-image"
          />
          <div class="product-main-image-next">
            <img src="{{ url('/storage/images/' . $item->photo) }}" alt="next" />
          </div>

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

        <!-- LIGHTBOX MODAL -->
        <div
          class="modal fade"
          id="lightboxModal"
          tabindex="-1"
          aria-labelledby="lightboxModalLabel"
          aria-hidden="true"
        >
          <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
              <div class="modal-body p-0">
                <img
                  src=""
                  alt="Product Zoom"
                  id="lightbox-image"
                  class="img-fluid rounded"
                />
              </div>
              <button
                type="button"
                class="btn-close position-absolute top-0 end-0 m-2"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
          </div>
        </div>
        <!-- End Lightbox -->

        <!-- Thumbnail slider -->
        <div class="product-thumbnails-slider owl-carousel mt-3">
          <div
            class="item thumbnail-item"
            data-image="{{ url('/storage/images/' . $item->photo) }}"
            data-index="0"
          >
            <img
              src="{{ url('/storage/images/' . $item->photo) }}"
              alt="thumb"
              class="img-fluid"
            />
          </div>
          @foreach ($galleries as $key => $gallery)
            <div
              class="item thumbnail-item"
              data-image="{{ url('/storage/images/' . $gallery->photo) }}"
              data-index="{{ $key + 1 }}"
            >
              <img
                src="{{ url('/storage/images/' . $gallery->photo) }}"
                alt="thumb"
                class="img-fluid"
              />
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <!-- Product Info-->
    <div class="col-xxl-7 col-lg-6 col-md-6" style="z-index: 0;">
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

          <input
            type="hidden"
            id="demo_price"
            value="{{
              count($item->attributes) > 0
                ? PriceHelper::setConvertPrice($item->attributes->first()->options->first()->price ?? 0)
                : PriceHelper::setConvertPrice($item->discount_price)
            }}"
          >
          <input
            type="hidden"
            value="{{ PriceHelper::setCurrencySign() }}"
            id="set_currency"
          >
          <input
            type="hidden"
            value="{{ PriceHelper::setCurrencyValue() }}"
            id="set_currency_val"
          >
          <input
            type="hidden"
            value="{{ $setting->currency_direction }}"
            id="currency_direction"
          >

          <ul class="breadcrumbs">
            <li><a href="{{ route('front.index') }}">{{ __('Home') }}</a></li>
            <li class="separator"></li>
            <li><a href="{{ route('front.catalog') }}">{{ __('Shop') }}</a></li>
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
            {{-- Flash deal countdown (commented out) --}}
          @endif

          <span class="h3 d-block price-area">
            @php
              // Calculate initial prices
              $initialPrice = $item->price;
              $initialDiscountPrice = $item->discount_price ?: $item->price;
              $hasDiscount =
                $item->previous_price && $item->previous_price > $initialDiscountPrice;
            @endphp

            @if ($hasDiscount)
              <small class="d-inline-block">
                <del>{{ PriceHelper::setPreviousPrice($item->previous_price) }}</del>
              </small>
            @endif

            <span
              id="main_price"
              class="main-price"
              data-base-price="{{ $initialPrice }}"
              data-discount-price="{{ $initialDiscountPrice }}"
              data-has-attributes="{{ count($item->attributes) > 0 ? 'true' : 'false' }}"
            >
              {{ PriceHelper::grandCurrencyPrice($item) }}
            </span>
          </span>

          <p class="text-muted">
            {!! $item->sort_details !!}
            <a href="#details" class="scroll-to">{{ __('Read more..') }}</a>
          </p>

          <div class="row margin-top-1x">
            @foreach ($attributes as $attribute)
              @if ($attribute->options->count())
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>{{ $attribute->name }}</label>
                    <div class="attribute-options d-flex flex-wrap">
                      @foreach ($attribute->options as $index => $option)
                        <label
                          class="option-box mr-2 mb-2 {{ $index === 0 ? 'selected' : '' }}"
                        >
                          <input
                            type="radio"
                            name="attribute_{{ $attribute->id }}"
                            value="{{ $option->name }}"
                            data-type="{{ $attribute->id }}"
                            data-href="{{ $option->id }}"
                            data-target="{{ PriceHelper::setConvertPrice($option->price) }}"
                            @if ($option->sale_price)
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
                <div
                  class="form-group product-quantity mb-3 align-items-center gap-1"
                >
                  <label for="quantity" class="form-label d-block">
                    {{ __('Quantity') }}
                  </label>
                  <select
                    class="form-select w-auto d-inline-block"
                    id="quantity"
                    name="quantity"
                  >
                    @for ($i = 1; $i <= $item->stock; $i++)
                      <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                  </select>
                  <input
                    type="hidden"
                    value="{{ $item->stock }}"
                    id="current_stock"
                  >
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
                          $firstOptionStock =
                            (int) $attribute->options->first()->stock;
                          break; // Check only first attribute with options
                        }
                      }
                    }
                  @endphp

                  @if (
                    (!$hasAttributes && $item->is_stock()) ||
                    ($hasAttributes && $firstOptionStock > 0)
                  )
                    <button
                      class="btn btn-primary m-0 a-t-c-mr"
                      id="add_to_cart"
                    >
                      <i class="icon-bag"></i>
                      <span>{{ __('Add to Cart') }}</span>
                    </button>
                    <button class="btn btn-primary m-0" id="but_to_cart">
                      <i class="icon-bag"></i>
                      <span>{{ __('Buy Now') }}</span>
                    </button>
                  @else
                    <button class="btn btn-primary m-0" disabled>
                      <i class="icon-bag"></i>
                      <span>{{ __('Out of stock') }}</span>
                    </button>
                  @endif
                @else
                  <a
                    href="{{ $item->affiliate_link }}"
                    target="_blank"
                    class="btn btn-primary m-0"
                  >
                    <span><i class="icon-bag"></i>{{ __('Buy Now') }}</span>
                  </a>
                @endif
              </div>
            </div>
          </div>

          <div class="div">
            <div class="mt-4 p-d-f-area">
              <div class="left">
                <a
                  class="btn btn-primary btn-sm wishlist_store wishlist_text"
                  href="{{ route('user.wishlist.store', $item->id) }}"
                >
                  <span><i class="icon-heart"></i></span>
                  @if (
                    Auth::check() &&
                    App\Models\Wishlist::where('user_id', Auth::user()->id)
                      ->where('item_id', $item->id)
                      ->exists()
                  )
                    <span>{{ __('Added To Wishlist') }}</span>
                  @else
                    <span class="wishlist1">{{ __('Wishlist') }}</span>
                    <span class="wishlist2 d-none">
                      {{ __('Added To Wishlist') }}
                    </span>
                  @endif
                </a>
              </div>
              <div class="d-flex align-items-center">
                <span class="text-muted mr-1">{{ __('Share') }}: </span>
                <div class="d-inline-block a2a_kit">
                  <a class="facebook a2a_button_facebook" href="">
                    <span><i class="fab fa-facebook-f"></i></span>
                  </a>
                  <a class="twitter a2a_button_twitter" href="">
                    <span><i class="fab fa-twitter"></i></span>
                  </a>
                  <a class="linkedin a2a_button_linkedin" href="">
                    <span><i class="fab fa-linkedin-in"></i></span>
                  </a>
                  <a class="pinterest a2a_button_pinterest" href="">
                    <span><i class="fab fa-pinterest"></i></span>
                  </a>
                </div>
                <script
                  async
                  src="https://static.addtoany.com/menu/page.js"
                ></script>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="padding-top-3x mb-3" id="details">
      <div class="col-lg-12">
        <ul class="nav nav-tabs" role="tablist">
          {{-- (If you want tabs, you can enable them here) --}}
        </ul>
        <div class="tab-content card">
          <div
            class="tab-pane fade show active"
            id="description"
            role="tabpanel"
            aria-labelledby="description-tab"
          >
            {!! $item->details !!}
          </div>
          <div
            class="tab-pane fade show"
            id="specification"
            role="tabpanel"
            aria-labelledby="specification-tab"
          >
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
                  $hasAttributeStock =
                    $related
                      ->attributes
                      ->flatMap
                      ->options
                      ->pluck('stock')
                      ->filter(fn($stock) => $stock > 0)
                      ->count() > 0;
                  $isInStock =
                    count($related->attributes) > 0
                      ? $hasAttributeStock
                      : $related->is_stock();
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
                      "
                    >
                      {{ $related->is_type != 'undefine'
                        ? ucfirst(str_replace('_', ' ', $related->is_type))
                        : '' }}
                    </div>
                  @endif
                @else
                  <div class="product-badge bg-secondary border-default text-body">
                    {{ __('out of stock') }}
                  </div>
                @endif

                @php
                  $relatedDiscountPercentage = 0;
                  if (count($related->attributes) > 0) {
                    foreach ($related->attributes as $attribute) {
                      foreach ($attribute->options as $option) {
                        if ($option->sale_price && $option->sale_price < $option->price) {
                          $optionDiscount = round(
                            (($option->price - $option->sale_price) / $option->price) * 100
                          );
                          if ($optionDiscount > $relatedDiscountPercentage) {
                            $relatedDiscountPercentage = $optionDiscount;
                          }
                        }
                      }
                    }
                  } elseif ($related->previous_price && $related->previous_price != 0) {
                    $relatedDiscountPercentage = str_replace(
                      '%',
                      '',
                      PriceHelper::DiscountPercentage($related)
                    );
                  }
                @endphp

                @if ($relatedDiscountPercentage > 0)
                  <div class="product-badge product-badge2 bg-info">
                    -{{ $relatedDiscountPercentage }}%
                  </div>
                @endif
                <div class="product-thumb">
                  <div class="product-thumb-image-wrapper">
                    <a href="{{ route('front.product', $related->slug) }}">
                      <img
                        class="lazy product-thumb-image"
                        data-src="{{ url('/storage/images/' . $related->thumbnail) }}"
                        alt="Product"
                      >
                    </a>
                  </div>
                  <div class="product-button-group">
                    <a
                      class="product-button wishlist_store"
                      href="{{ route('user.wishlist.store', $related->id) }}"
                      title="{{ __('Wishlist') }}"
                    >
                      <i class="icon-heart"></i>
                    </a>
                    @include('includes.item_footer', ['sitem' => $related])
                  </div>
                </div>
                <div class="product-card-body">
                  <div class="product-category">
                    <a href="{{ route('front.category', $item->category->slug) }}">{{ $item->category->name }}</a>
                  </div>
                  <h3 class="product-title">
                    <a href="{{ route('front.product', $related->slug) }}">
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
  <form
    class="modal fade ratingForm"
    action="{{ route('front.review.submit') }}"
    method="post"
    id="leaveReview"
    tabindex="-1"
  >
    @csrf
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('Leave a Review') }}</h4>
          <button
            class="close modal_close"
            type="button"
            data-bs-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          @php $user = Auth::user(); @endphp
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="review-name">{{ __('Your Name') }}</label>
                <input
                  class="form-control"
                  type="text"
                  id="review-name"
                  value="{{ $user->first_name }}"
                  required
                >
              </div>
            </div>
            <input type="hidden" name="item_id" value="{{ $item->id }}">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="review-email">{{ __('Your Email') }}</label>
                <input
                  class="form-control"
                  type="email"
                  id="review-email"
                  value="{{ $user->email }}"
                  required
                >
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="review-subject">{{ __('Subject') }}</label>
                <input
                  class="form-control"
                  type="text"
                  name="subject"
                  id="review-subject"
                  required
                >
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
            <textarea
              class="form-control"
              name="review"
              id="review-message"
              rows="8"
              required
            ></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="submit">
            <span>{{ __('Submit Review') }}</span>
          </button>
        </div>
      </div>
    </div>
  </form>
@endauth
@endsection

@section('script')
  <script>
    $(document).ready(function(){
      // ********** (A) Desktop: Initialize EXT-Zoom **********
      let zoomInstance = null;
      function initializeZoom() {
        $('.extm').remove();
        zoomInstance = $('#main-product-image').extm({
          squareOverlay: true,
          zoomLevel: 2,
          overlaySize: 150,
          position: 'right',
          margin: 20,
          overlayClass: 'extm-overlay'
        });
      }
      initializeZoom();

      // ********** (B) Mobile: Lightbox on tap **********
      function bindMobileLightbox() {
        if (window.innerWidth <= 767) {
          // Destroy extm (so the overlay disappears)
          if (zoomInstance) {
            $('#main-product-image').extm('destroy');
            zoomInstance = null;
          }
          // Change cursor to indicate it’s tappable
          $('#main-product-image')
            .css('cursor','pointer')
            .off('click.mobileLightbox')
            .on('click.mobileLightbox', function() {
              const currentSrc = $(this).attr('src');
              $('#lightbox-image').attr('src', currentSrc);
              $('#lightboxModal').modal('show');
            });
        } else {
          // On desktop, remove tap handler and re-init zoom
          $('#main-product-image')
            .css('cursor','zoom-in')
            .off('click.mobileLightbox');
          if (!zoomInstance) {
            initializeZoom();
          }
        }
      }
      bindMobileLightbox();
      $(window).on('resize', function() {
        bindMobileLightbox();
      });

      // ********** (C) Thumbnails: update main image + re-init zoom if desktop **********
      $('.thumbnail-item').on('click', function() {
        const newSrc = $(this).data('image');
        const newIndex = $(this).data('index');

        $('#main-product-image').attr('src', newSrc);
        $('.product-main-image-next img').attr('src', newSrc);
        $('.thumbnail-item').removeClass('active');
        $(this).addClass('active');

        if (zoomInstance) {
          $('#main-product-image').extm('destroy');
          zoomInstance = null;
        }
        setTimeout(function() {
          if (window.innerWidth > 767) {
            initializeZoom();
          }
        }, 100);
      });

      // ********** (D) Prev/Next arrows: same idea **********
      $('#prev-image, #next-image').on('click', function() {
        const currentIndex = $('.thumbnail-item.active').data('index');
        let newIndex;
        if ($(this).attr('id') === 'prev-image') {
          newIndex = currentIndex > 0
            ? currentIndex - 1
            : $('.thumbnail-item').length - 1;
        } else {
          newIndex = currentIndex < $('.thumbnail-item').length - 1
            ? currentIndex + 1
            : 0;
        }
        const newThumbnail = $(`.thumbnail-item[data-index="${newIndex}"]`);
        const newSrc = newThumbnail.data('image');

        if (zoomInstance) {
          $('#main-product-image').extm('destroy');
          zoomInstance = null;
        }
        $('#main-product-image').attr('src', newSrc);
        $('.product-main-image-next img').attr('src', newSrc);

        const owl = $('.product-thumbnails-slider').data('owl.carousel');
        if (owl) {
          owl.to(newIndex, 300);
        }
        setTimeout(function() {
          $('.owl-item').removeClass('active');
          $('.thumbnail-item').removeClass('active');
          newThumbnail.addClass('active');
          newThumbnail.closest('.owl-item').addClass('active');
          if (window.innerWidth > 767) {
            initializeZoom();
          }
        }, 300);
      });
    });
  </script>

  <script>
    // ===== Your existing “price & stock update” script remains unchanged =====
    $(function () {
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
            const salePrice = $input.data('sale-price')
              ? parseFloat($input.data('sale-price'))
              : price;

            total += price;
            saleTotal += salePrice;

            if (salePrice < price) {
              hasSale = true;
              const optionDiscount = Math.round(
                ((price - salePrice) / price) * 100
              );
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
              priceHtml = `<span class="main-price">${currency}${finalSaleTotal.toFixed(2)}</span>  <small class="d-inline-block"><del>${currency}${finalTotal.toFixed(2)}</del></small>`;
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
  </script>
@endsection
