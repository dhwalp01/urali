@extends('master.front')
@section('meta')
<meta name="keywords" content="{{$setting->meta_keywords}}">
<meta name="description" content="{{$setting->meta_description}}">
@endsection
@section('title')
    {{__('Products')}}
@endsection

@section('content')
    <!-- Page Title-->
<div class="page-title">
    <div class="container">
      <div class="row">
          <div class="col-lg-12">
            <ul class="breadcrumbs">
                <li><a href="{{route('front.index')}}">{{__('Home')}}</a> </li>
                <li class="separator"></li>
                <li>{{__('Shop')}}</li>
              </ul>
          </div>
      </div>
    </div>
  </div>
  <!-- Page Content-->
  <div class="container padding-bottom-3x mb-1">
        <div class="row">
            <div class="col-lg-12">
                <div class="shop-top-filter-wrapper">
                    <div class="row">
                      <div class="col-12 mb-2 d-flex justify-content-between align-items-center gd-text-sm-center">
                          <div class="col-sm-6 col-md-6">
                            <a class="category-name" href="{{ url()->previous() }}">
                              <i class="fas fa-chevron-left"></i>&nbsp;&nbsp;{{ $category->name ?? '' }}
                            </a>
                          </div>
                          <div class="col-sm-6 col-md-6 shop-view"><a class="list-view {{Session::has('view_catalog') && Session::get('view_catalog') == 'grid' ? 'active' : ''}} " data-step="grid" href="javascript:;" data-href="{{route('front.catalog').'?view_check=grid'}}"><i class="fas fa-th-large"></i></a>
                                <a class="list-view {{Session::has('view_catalog') && Session::get('view_catalog') == 'list' ? 'active' : ''}}" href="javascript:;" data-step="list" data-href="{{route('front.catalog').'?view_check=list'}}"><i class="fas fa-list"></i></a>
                          </div>
                      </div>
                      <div class="col-12 mb-4 rounded p-2 catalog hidden-on-desktop" style="background:#e6f7fd;">
                        <div class="row">
                          <div class="col-6">
                            <div class="single-service single-service2">
                                  <img src="/storage/images/162196463701.png" alt="Shipping">
                                  <div class="content">
                                      <h6 class="mb-2">Premium 100% Cotton</h6>
                                      
                                  </div>
                              </div>
                          </div>
                          <div class="col-6">
                            <div class="single-service single-service2">
                                  <img src="/storage/images/162196463701.png" alt="Shipping">
                                  <div class="content">
                                      <h6 class="mb-2">Long-lasting colours</h6>
                                      
                                  </div>
                              </div>
                          </div>
                          <div class="col-6">
                            <div class="single-service single-service2">
                                  <img src="/storage/images/162196463701.png" alt="Shipping">
                                  <div class="content">
                                      <h6 class="mb-2">Long-length Gown</h6>
                                      
                                  </div>
                              </div>
                          </div>
                          <div class="col-6">
                            <div class="single-service single-service2">
                                  <img src="/storage/images/162196463701.png" alt="Shipping">
                                  <div class="content">
                                      <h6 class="mb-2">Durable, Resilient</h6>
                                      
                                  </div>
                              </div>
                          </div>
                        </div>
                      </div>
                      <div class="d-flex justify-content-between align-items-center col-md-10 gd-text-sm-center">
                        <div class="col-7">
                          <div class="d-flex align-items-center">
                            <label class="" for="sorting">{{('Sort by')}}:</label>&nbsp;&nbsp;
                            <select class="sorting form-select w-auto d-inline-block" id="sorting">
                              <option value="">{{('All Products')}}</option>
                              <option value="high_to_low" {{request()->input('high_to_low') ? 'selected' : ''}}>{{('New Arrival')}}</option>
                              <option value="low_to_high" {{request()->input('low_to_high') ? 'selected' : ''}}>{{('Low - High Price')}}</option>
                              <option value="high_to_low" {{request()->input('high_to_low') ? 'selected' : ''}}>{{('High - Low Price')}}</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="sidebar-open">
                            <!-- Mobile Filter Button (Visible only on mobile) -->
                            <button class="sidebar-open mobile-filter-toggle d-lg-none btn btn-primary btn-sm">
                                <i class="fas fa-filter"></i> {{__('Filters')}}
                            </button>
                        </div>
                        <style>
                            
                            /* Mobile-specific styles */
                            @media (max-width: 991px) {
                                .mobile-filter-toggle {
                                    width: 100%;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                }
                                
                                .mobile-filter-toggle i {
                                    margin-right: 8px;
                                }
                                
                                .filter-container {
                                    display: none;
                                    position: fixed;
                                    top: 0;
                                    left: 0;
                                    right: 0;
                                    bottom: 0;
                                    background: rgba(0,0,0,0.5);
                                    z-index: 1000;
                                    padding: 20px;
                                    overflow-y: auto;
                                }
                                
                                .filter-container.active {
                                    display: block;
                                }
                                
                                .mobile-filter-section .quickFilter, 
                                .mobile-filter-section .shop-sorting {
                                    background: white;
                                    margin: 10px 0;
                                    position: relative;
                                }
                                
                                .mobile-close-filter {
                                    cursor: pointer;
                                    color: #666;
                                }
                                
                                .mobile-filter-section .shop-sorting .sorting-options {
                                    background: #f8f9fa;
                                    padding: 15px;
                                    border-radius: 8px;
                                }
                                
                                .desktop-filter-section {
                                    display: none !important;
                                }
                            }
                        </style>
                        </div>

                      </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-3">

          <div class="col-lg-9 order-lg-2" id="list_view_ajax">
            @include('front.catalog.catalog')
          </div>

          <!-- Sidebar          -->
          <div class="col-lg-3 order-lg-1">
            {{-- <div class="sidebar-toggle position-left"><i class="icon-filter"></i></div> --}}
            <aside class="sidebar sidebar-offcanvas position-left"><span class="sidebar-close"><i class="icon-x"></i></span>
              <!-- Widget Categories-->
              {{-- <section class="widget widget-categories card rounded p-4">
                <h3 class="widget-title">{{__('Shop Categories')}}</h3>
                <ul id="category_list" class="category-scroll">
                    @foreach ($categories as $getcategory)
                    <li class="has-children  {{isset($category) && $category->id == $getcategory->id ? 'expanded active' : ''}} ">
                      <a class="category_search" href="javascript:;"  data-href="{{$getcategory->slug}}">
                        {{$getcategory->name}}
                        <span class="count">({{ $getcategory->items_count }})</span>
                      </a>

                        <ul id="subcategory_list">
                            @foreach ($getcategory->subcategory as $getsubcategory)
                            <li class="{{isset($subcategory) && $subcategory->id == $getsubcategory->id ? 'active' : ''}}">
                              <a class="subcategory" href="javascript:;" data-href="{{$getsubcategory->slug}}">
                                {{$getsubcategory->name}}
                                <span class="count">({{ $getsubcategory->items_count }})</span>
                              </a>
                              

                              <ul id="childcategory_list">
                                @foreach ($getsubcategory->childcategory as $getchildcategory)
                                <li class="{{isset($childcategory) && $getchildcategory->id == $getchildcategory->id ? 'active' : ''}}">
                                  <a class="childcategory" href="javascript:;" data-href="{{$getchildcategory->slug}}">
                                    {{$getchildcategory->name}}
                                    <span class="count">({{ $getchildcategory->items_count }})</span>
                                  </a>

                                </li>
                                @endforeach
                            </ul>
                            </li>
                            @endforeach
                        </ul>
                      </li>
                    @endforeach
                </ul>
              </section> --}}

              @if ($setting->is_range_search == 1)
                   <!-- Widget Price Range-->
              <section class="widget widget-categories card rounded p-4">
                <h3 class="widget-title">{{ __('Filter by Price') }}</h3>
                <form class="price-range-slider" method="post" data-start-min="{{request()->input('minPrice') ? request()->input('minPrice') : '0'}}" data-start-max="{{request()->input('maxPrice') ? request()->input('maxPrice') : $setting->max_price}}" data-min="0" data-max="{{$setting->max_price}}" data-step="5">
                  <div class="ui-range-slider"></div>
                  <footer class="ui-range-slider-footer">
                    <div class="column">
                      <button class="btn btn-primary btn-sm" id="price_filter" type="button"><span>{{__('Filter')}}</span></button>
                    </div>
                    <div class="column">
                      <div class="ui-range-values">
                        <div class="ui-range-value-min">{{PriceHelper::setCurrencySign()}}<span class="min_price"></span>
                          <input type="hidden">
                        </div>-
                        <div class="ui-range-value-max">{{PriceHelper::setCurrencySign()}}<span class="max_price"></span>
                          <input type="hidden">
                        </div>
                      </div>
                    </div>
                  </footer>
                </form>
              </section>
              @endif

              @if ($setting->is_attribute_search == 1)
                  @foreach ($attrubutes as $attrubute)
                  <section class="widget widget-categories card rounded p-4">
                      <h3 class="widget-title">{{ __('Filter by') }} {{$attrubute->name}}</h3>
                      @foreach ($options as $option)
                          @if ($attrubute->keyword == $option->attribute->keyword)
                              <div class="custom-control custom-checkbox">
                                  <input class="custom-control-input attribute-option" 
                                        name="options[]" 
                                        type="checkbox" 
                                        data-attribute="{{$attrubute->keyword}}"
                                        value="{{$option->name}}" 
                                        id="{{$attrubute->id}}{{$option->name}}"
                                        {{request()->input('option') && in_array($option->name, explode(',', request()->input('option'))) ? 'checked' : ''}}>
                                  <label class="custom-control-label" for="{{$attrubute->id}}{{$option->name}}">
                                      {{$option->name}}
                                      <span class="text-muted">({{$option->items_count ?? 0}})</span>
                                  </label>
                              </div>  
                          @endif
                      @endforeach
                  </section>
                  @endforeach
              @endif

              <!-- Widget Brand Filter-->
              {{-- <section class="widget widget-categories card rounded p-4">
                <h3 class="widget-title">{{__('Filter by Brand')}}</h3>
                <div class="custom-control custom-checkbox">
                  <input class="custom-control-input brand-select" type="checkbox" value="" id="all-brand">
                  <label class="custom-control-label" for="all-brand">{{__('All Brands')}}</label>
                </div>
                @foreach ($brands as $getbrand)
                <div class="custom-control custom-checkbox">
                    <input class="custom-control-input brand-select" {{isset($brand) && $brand->id == $getbrand->id ? 'checked' : ''}} type="checkbox" value="{{$getbrand->slug}}" id="{{$getbrand->slug}}">
                    <label class="custom-control-label" for="{{$getbrand->slug}}">{{$getbrand->name}}</label>
                  </div>
                @endforeach
              </section> --}}


            </aside>
          </div>
        </div>
      </div>



      <form id="search_form" class="d-none" action="{{route('front.catalog')}}" method="GET">

        <input type="text" name="maxPrice" id="maxPrice" value="{{request()->input('maxPrice') ? request()->input('maxPrice') : ''}}">
        <input type="text" name="minPrice" id="minPrice" value="{{request()->input('minPrice') ? request()->input('minPrice') : ''}}">
        <input type="text" name="brand" id="brand" value="{{isset($brand) ? $brand->slug : ''}}">
        <input type="text" name="brand" id="brand" value="{{isset($brand) ? $brand->slug : ''}}">
        <input type="text" name="category" id="category" value="{{isset($category) ? $category->slug : ''}}">
        <input type="text" name="quick_filter" id="quick_filter" value="">
        <input type="text" name="childcategory" id="childcategory" value="{{isset($childcategory) ? $childcategory->slug : ''}}">
        <input type="text" name="page" id="page" value="{{isset($page) ? $page : ''}}">
        <input type="text" name="attribute" id="attribute" value="{{isset($attribute) ? $attribute : ''}}">
        <input type="text" name="option" id="option" value="{{isset($option) ? $option : ''}}">
        <input type="text" name="subcategory" id="subcategory" value="{{isset($subcategory) ? $subcategory->slug : ''}}">
        <input type="text" name="sorting" id="sorting" value="{{isset($sorting) ? $sorting : ''}}">
        <input type="text" name="view_check" id="view_check" value="{{isset($view_check) ? $view_check : ''}}">


        <button type="submit" id="search_button" class="d-none"></button>
    </form>
@endsection
@section('script')
<script>
  document.addEventListener('DOMContentLoaded', function() {
      // Only apply mobile filter logic on mobile screens
      if (window.innerWidth <= 991) {
          const filterToggle = document.querySelector('.mobile-filter-toggle');
          const filterContainer = document.querySelector('.filter-container');
          const closeButtons = document.querySelectorAll('.mobile-close-filter');
          
          if (filterToggle && filterContainer) {
              filterToggle.addEventListener('click', function() {
                  filterContainer.classList.add('active');
                  document.body.style.overflow = 'hidden';
              });
              
              closeButtons.forEach(button => {
                  button.addEventListener('click', function() {
                      filterContainer.classList.remove('active');
                      document.body.style.overflow = '';
                  });
              });
              
              // Close when clicking outside on mobile
              filterContainer.addEventListener('click', function(e) {
                  if (e.target === filterContainer) {
                      filterContainer.classList.remove('active');
                      document.body.style.overflow = '';
                  }
              });
          }
      }
  });
</script>
@endsection