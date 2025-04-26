<?php

namespace App\Repositories\Front;

use App\{
    Models\Cart,
    Models\Item,
    Models\PromoCode,
    Helpers\PriceHelper
};
use App\Models\AttributeOption;
use App\Models\Attribute;
use Illuminate\Support\Facades\Session;

class CartRepository
{

    /**
     * Store cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function store($request)
     {
         if (empty($request->all())) {
             $parsedUrl = parse_url($request->getRequestUri(), PHP_URL_QUERY);
             parse_str($parsedUrl, $queryArray);
             $request = (object)$queryArray;
             $qty_check = 0;
             $input = $queryArray;
         } else {
             $input = $request->all();
         }
     
         $qty_check = 0;
         $input['option_name'] = [];
         $input['option_price'] = [];
         $input['option_sale_price'] = []; // New array for sale prices
         $input['attr_name'] = [];
     
         $qty = isset($input['quantity']) ? $input['quantity'] : 1;
         $qty = is_numeric($qty) ? $qty : 1;
     
         // Stock validation for options
         if ($input['options_ids']) {
             foreach (explode(',', $input['options_ids']) as $optionId) {
                 $option = AttributeOption::findOrFail($optionId);
                 if ($qty > $option->stock) {
                     return ['message' => 'Product Out Of Stock', 'status' => 'outStock'];
                 }
             }
         }
     
         $cart = Session::get('cart');
         $item = Item::where('id', $input['item_id'])
             ->select('id', 'name', 'photo', 'discount_price', 'previous_price', 'slug', 'item_type', 'license_name', 'license_key', 'stock')
             ->first();
     
         // Stock validation
         if ($item->item_type == 'normal') {
             if (!empty($input['options_ids'])) {
                 foreach (explode(',', $input['options_ids']) as $optionId) {
                     $option = AttributeOption::findOrFail($optionId);
                     if ($option->stock != 'unlimited' && $qty > (int)$option->stock) {
                         return ['message' => 'Product Out Of Stock', 'status' => 'outStock'];
                     }
                 }
             } elseif ($item->stock < $qty) {
                 return ['message' => 'Product Out Of Stock', 'status' => 'outStock'];
             }
         }
     
         $single = isset($request->type) ? ($request->type == '1' ? 1 : 0) : 0;
     
         // Check for duplicate items
         if (Session::has('cart') && ($item->item_type == 'digital' || $item->item_type == 'license')) {
             if (array_key_exists($input['item_id'], Session::get('cart')) || 
                 array_key_exists($input['item_id'].'-', Session::get('cart'))) {
                 return ['message' => 'Product already added', 'status' => 'alreadyInCart'];
             }
         }
     
         $option_id = [];
     
         if ($single == 1) {
             $attr_name = [];
             $option_name = [];
             $option_price = [];
             $option_sale_price = [];
     
             if (count($item->attributes) > 0) {
                 foreach ($item->attributes as $attr) {
                     if (isset($attr->options[0]->name)) {
                         $attr_name[] = $attr->name;
                         $option_name[] = $attr->options[0]->name;
                         $option_price[] = $attr->options[0]->price;
                         $option_sale_price[] = $attr->options[0]->sale_price ?? $attr->options[0]->price;
                         $option_id[] = $attr->options[0]->id;
                     }
                 }
             }
     
             $input['attr_name'] = $attr_name;
             $input['option_price'] = $option_price;
             $input['option_sale_price'] = $option_sale_price;
             $input['option_name'] = $option_name;
             $input['option_id'] = $option_id;
     
             $qty = ($request->quantity != 'NaN') ? $request->quantity : 1;
             $qty_check = ($request->quantity != 'NaN') ? 1 : 0;
         } else {
             if ($input['attribute_ids']) {
                 foreach (explode(',', $input['attribute_ids']) as $attrId) {
                     $attr = Attribute::findOrFail($attrId);
                     $attr_name[] = $attr->name;
                 }
                 $input['attr_name'] = $attr_name;
             }
     
             if ($input['options_ids']) {
                 foreach (explode(',', $input['options_ids']) as $optionId) {
                     $option = AttributeOption::findOrFail($optionId);
                     $option_name[] = $option->name;
                     $option_price[] = $option->price;
                     $option_sale_price[] = $option->sale_price ?? $option->price;
                     $option_id[] = $option->id;
                 }
                 $input['option_name'] = $option_name;
                 $input['option_price'] = $option_price;
                 $input['option_sale_price'] = $option_sale_price;
             }
         }
     
         if (!$item) {
             abort(404);
         }
     
         $attribute['names'] = $input['attr_name'];
         $attribute['option_name'] = $input['option_name'];
         $attribute['option_price'] = $input['option_price'];
         $attribute['option_sale_price'] = $input['option_sale_price'];
     
         // Calculate prices
         $hasOptions = !empty($input['options_ids']);
         
         if ($hasOptions) {
             // For products with options, use sum of option sale prices if available, otherwise regular prices
             $option_prices = !empty($input['option_sale_price']) ? $input['option_sale_price'] : $input['option_price'];
             $attribute_price = array_sum($option_prices);
             $display_price = $attribute_price;
         } else {
             // For regular products, use discount price if available, otherwise base price
             $display_price = $item->discount_price ?: $item->price;
             $attribute_price = $item->price; // Store base price for reference
         }
     
         $cart_item_key = (isset($request->item_key) && $request->item_key != (int)0) 
             ? explode('-', $request->item_key)[1] 
             : str_replace(' ', '', implode(',', $attribute['option_name']));
     
         $cart = Session::get('cart');
     
         if (!$cart || !isset($cart[$item->id . '-' . $cart_item_key])) {
             $license_name = json_decode($item->license_name, true);
             $license_key = json_decode($item->license_name, true);
             
             $cart[$item->id . '-' . $cart_item_key] = [
                'options_id' => $option_id,
                'attribute' => $attribute,
                'attribute_price' => $attribute_price,
                'display_price' => $display_price, // This should be the sale price if available
                'name' => $item->name,
                'slug' => $item->slug,
                'qty' => $qty,
                'price' => PriceHelper::grandPrice($item),
                'main_price' => $item->price,
                'discount_price' => $item->discount_price,
                'photo' => $item->photo,
                'type' => $item->item_type,
                'item_type' => $item->item_type,
                'item_l_n' => $item->item_type == 'license' ? end($license_name) : null,
                'item_l_k' => $item->item_type == 'license' ? end($license_key) : null
            ];
     
             Session::put('cart', $cart);
     
             // Apply coupon if exists
             if ($coupon = Session::get('coupon')) {
                 $promo_code = (object)$coupon['code'];
                 $cartTotal = PriceHelper::cartTotal($cart, 2);
                 $discount = $this->getDiscount($promo_code->discount, $promo_code->type, $cartTotal);
                 Session::put('coupon', [
                     'discount' => $discount['sub'],
                     'code' => $promo_code
                 ]);
             }
     
             return ['message' => __('Product added successfully'), 'qty' => count(Session::get('cart'))];
         }
     
         // Update existing cart item
         if (isset($cart[$item->id . '-' . $cart_item_key])) {
            $cart = Session::get('cart');
        
            if ($qty_check == 1) {
                $cart[$item->id . '-' . $cart_item_key]['qty'] = $qty;
            } else {
                $cart[$item->id . '-' . $cart_item_key]['qty'] += $qty;
            }
        
            // Stock validation based on product type
            if ($item->item_type == 'normal') {
                // If product has options, check option stocks
                if (!empty($input['options_ids'])) {
                    $outOfStock = false;
                    foreach (explode(',', $input['options_ids']) as $optionId) {
                        $option = AttributeOption::findOrFail($optionId);
                        if ($option->stock != 'unlimited' && (int)$cart[$item->id . '-' . $cart_item_key]['qty'] > (int)$option->stock) {
                            return ['message' => 'Product Option Out Of Stock', 'status' => 'outStock'];
                        }
                    }
                } 
                // Otherwise check base product stock
                elseif ($item->stock < (int)$cart[$item->id . '-' . $cart_item_key]['qty']) {
                    return ['message' => 'Product Out Of Stock', 'status' => 'outStock'];
                }
            }
        
            Session::put('cart', $cart);
     
             // Update coupon if exists
             if ($coupon = Session::get('coupon')) {
                 $promo_code = (object)$coupon['code'];
                 $cartTotal = PriceHelper::cartTotal($cart, 2);
                 $discount = $this->getDiscount($promo_code->discount, $promo_code->type, $cartTotal);
                 Session::put('coupon', [
                     'discount' => $discount['sub'],
                     'code' => $promo_code
                 ]);
             }
     
             return [
                 'message' => __('Product quantity updated'), 
                 'qty' => count(Session::get('cart'))
             ];
         }
     
         return ['message' => __('Product added successfully'), 'qty' => count(Session::get('cart'))];
    }

    public function promoStore($request)
    {

        $input = $request->all();
        $promo_code = PromoCode::where('status', 1)->whereCodeName($input['code'])->where('no_of_times', '>', 0)->first();

        if ($promo_code) {
            $cart = Session::get('cart');
            $cartTotal = PriceHelper::cartTotal($cart, 2);
            $discount = $this->getDiscount($promo_code->discount, $promo_code->type, $cartTotal);

            $coupon = [
                'discount' => $discount['sub'],
                'code'  => $promo_code
            ];
            Session::put('coupon', $coupon);

            return [
                'status'  => true,
                'message' => __('Promo code found!')
            ];
        } else {
            return [
                'status'  => false,
                'message' => __('No coupon code found')
            ];
        }
    }



    public function getCart()
    {
        $cart = Session::has('cart') ? Session::get('cart') : null;
        return $cart;
    }

    public function getDiscount($discount, $type, $price)
    {
        if ($type == 'amount') {
            $sub = $discount;
            $total = $price - $sub;
        } else {
            $val = $price / 100;
            $sub = $val * $discount;
            $total = $price - $sub;
        }

        return [
            'sub' => $sub,
            'total' => $total
        ];
    }
}
