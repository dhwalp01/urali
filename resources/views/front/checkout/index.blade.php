@extends('master.front')

@section('title')
    {{ __('Billing') }}
@endsection

@section('content')
    <!-- Page Title-->
    <div class="page-title">
        <div class="container">
            <div class="column">
                <ul class="breadcrumbs">
                    <li><a href="{{ route('front.index') }}">{{ __('Home') }}</a> </li>
                    <li class="separator"></li>
                    <li>{{ __('Billing address') }}</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Page Content-->
    <div class="container padding-bottom-3x mb-1 checkut-page">
        <div class="row">
            <div class="col-xl-8 col-lg-8">
                <div class="row">
                    <div class="col-12">
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
                                        <span class="entry-meta">{{ $item['qty'] }} x
                                            {{ PriceHelper::setCurrencyPrice($item['main_price']) }}.</span>

                                        @foreach ($item['attribute']['option_name'] as $optionkey => $option_name)
                                            <span class="entry-meta"><b>{{ $option_name }}</b> :
                                                {{ PriceHelper::setCurrencySign() }}{{ $item['attribute']['option_price'][$optionkey] }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </section>
                        <div class="card">
                            <div class="card-body">
                                <h6>{{ __('Billing Address') }}</h6>
                                <form id="checkoutBilling" action="{{ route('front.checkout.store') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="checkout-fn">{{ __('First Name') }}</label>
                                                <input class="form-control" name="bill_first_name" type="text" required
                                                    id="checkout-fn" value="{{ isset($user) ? $user->first_name : '' }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="checkout-ln">{{ __('Last Name') }}</label>
                                                <input class="form-control" name="bill_last_name" type="text" required
                                                    id="checkout-ln" value="{{ isset($user) ? $user->last_name : '' }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="checkout_email_billing">{{ __('E-mail Address') }}</label>
                                                <input class="form-control" name="bill_email" type="email" required
                                                    id="checkout_email_billing"
                                                    value="{{ isset($user) ? $user->email : '' }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="checkout-phone">{{ __('Phone Number') }}</label>
                                                <input class="form-control" name="bill_phone" type="text"
                                                    id="checkout-phone" required
                                                    value="{{ isset($user) ? $user->phone : '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    @if (PriceHelper::CheckDigital())
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="checkout-company">{{ __('Company') }}</label>
                                                    <input class="form-control" name="bill_company" type="text"
                                                        id="checkout-company"
                                                        value="{{ isset($user) ? $user->bill_company : '' }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="checkout-address1">{{ __('Address') }}</label>
                                                    <input class="form-control" name="bill_address1" required
                                                        type="text" id="checkout-address1"
                                                        value="{{ isset($user) ? $user->bill_address1 : '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="checkout-zip">{{ __('Zip Code') }}</label>
                                                    <input class="form-control" name="bill_zip" type="text"
                                                        id="checkout-zip"
                                                        value="{{ isset($user) ? $user->bill_zip : '' }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="checkout-city">{{ __('City') }}</label>
                                                    <input class="form-control" name="bill_city" type="text" required
                                                        id="checkout-city"
                                                        value="{{ isset($user) ? $user->bill_city : '' }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="checkout-country">{{ __('Country') }}</label>
                                                    <select class="form-control" required name="bill_country"
                                                        id="billing-country">
                                                        <option selected>{{ __('Choose Country') }}</option>
                                                        @foreach (DB::table('countries')->get() as $country)
                                                            <option value="{{ $country->name }}"
                                                                {{ isset($user) && $user->bill_country == $country->name ? 'selected' : '' }}>
                                                                {{ $country->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                </form>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sidebar          -->
            <div class="col-xl-4 col-lg-4">
                @include('includes.single_checkout_sidebar', $cart)
                @include('includes.single_checkout_modal')
            </div>
        </div>
    </div>
@endsection
