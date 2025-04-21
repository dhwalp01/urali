<?php

namespace App\Http\Controllers\Back;

use App\{
    Models\Item,
    Models\Attribute,
    Models\AttributeOption,
    Models\Gallery,
    Http\Requests\ItemRequest,
    Http\Controllers\Controller,
    Http\Requests\GalleryRequest,
    Repositories\Back\ItemRepository
};
use App\Helpers\ImageHelper;
use App\Models\Category;
use App\Models\ChieldCategory;
use App\Models\Currency;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Automattic\WooCommerce\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ItemController extends Controller
{

    /**
     * Constructor Method.
     *
     * Setting Authentication
     *
     * @param  \App\Repositories\Back\ItemRepository $repository
     *
     */
    public function __construct(ItemRepository $repository)
    {
        $this->middleware('auth:admin');
        $this->middleware('adminlocalize');
        $this->repository = $repository;
    }


    public function summernoteUpload(Request $request)
    {
        $name = ImageHelper::uploadSummernoteImage($request->file('image'), 'images/summernote');

        return response()->json([
            'success' => true,
            'image' => url('/storage/images/summernote/' . $name)
        ]);
    }


    public function add()
    {
        return view('back.item.add');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $item_type = $request->has('item_type') ? ($request->item_type ? $request->item_type : '') : '';
        $is_type = $request->has('is_type') ? ($request->is_type ? $request->is_type : '') : '';
        $category_id = $request->has('category_id') ? ($request->category_id ? $request->category_id : '') : '';
        $orderby = $request->has('orderby') ? ($request->orderby ? $request->orderby : 'desc') : 'desc';

        $datas = Item::when($item_type, function ($query, $item_type) {
                return $query->where('item_type', $item_type);
            })
            ->when($is_type, function ($query, $is_type) {
                if ($is_type != 'outofstock') {
                    return $query->where('is_type', $is_type);
                } else {
                    return $query->whereStock(0)->whereItemType('normal');
                }
            })
            ->when($category_id, function ($query, $category_id) {
                return $query->where('category_id', $category_id);
            })
            ->when($orderby, function ($query, $orderby) {
                return $query->orderby('id', $orderby);
            })
            ->get();

        return view('back.item.index', [
            'datas' => $datas
        ]);
    }

    /**
     * Show the form for get subcategory a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getsubCategory(Request $request)
    {

        if ($request->category_id) {
            $data = Category::findOrFail($request->category_id);
            $data = $data->subcategory;
        } else {
            $data = [];
        }

        return response()->json(['data' => $data]);
    }

    /**
     * Show the form for get subcategory a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getChildCategory(Request $request)
    {

        if ($request->subcategory_id) {
            $data = Subcategory::findOrFail($request->subcategory_id);
            $data = $data->childcategory;
        } else {
            $data = [];
        }

        return response()->json(['data' => $data]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('back.item.create', [
            'curr' => Currency::where('is_default', 1)->first()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemRequest $request)
    {
        $item_id = $this->repository->store($request);

        if ($request->is_button == 0) {
            return redirect()->route('back.item.index')->withSuccess(__('Product Added Successfully.'));
        } else {
            return redirect(route('back.item.edit', $item_id))->withSuccess(__('Product Added Successfully.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        return view('back.item.edit', [
            'item' => $item,
            'curr' => Currency::where('is_default', 1)->first(),
            'social_icons' => json_decode($item->social_icons, true),
            'social_links' => json_decode($item->social_links, true),
            'specification_name' => json_decode($item->specification_name, true),
            'specification_description' => json_decode($item->specification_description, true),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\ItemRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(ItemRequest $request, Item $item)
    {
        $this->repository->update($item, $request);

        if ($request->is_button == 0) {
            return redirect()->route('back.item.index')->withSuccess(__('Product Updated Successfully.'));
        } else {
            return redirect()->back()->withSuccess(__('Product Updated Successfully.'));
        }
    }

    /**
     * Change the status for editing the specified resource.
     *
     * @param  int  $id
     * @param  int  $status
     * @return \Illuminate\Http\Response
     */
    public function status(Item $item, $status)
    {
        $item->update(['status' => $status]);
        return redirect()->back()->withSuccess(__('Status Updated Successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $this->repository->delete($item);
        return redirect()->back()->withSuccess(__('Product Deleted Successfully.'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function galleries(Item $item)
    {
        return view('back.item.galleries', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\GalleryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function galleriesUpdate(GalleryRequest $request)
    {
        $this->repository->galleriesUpdate($request);
        return redirect()->back()->withSuccess(__('Gallery Information Updated Successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function galleryDelete(Gallery $gallery)
    {
        $this->repository->galleryDelete($gallery);
        return redirect()->back()->withSuccess(__('Successfully Deleted From Gallery.'));
    }


    public function highlight(Item $item)
    {
        return view('back.item.highlight', [
            'item' => $item
        ]);
    }
    public function highlight_update(Item $item, Request $request)
    {
        $this->repository->highlight($item, $request);
        return redirect()->route('back.item.index')->withSuccess(__('Product Updated Successfully.'));
    }




    // ---------------- DIGITAL PRODUCT START ---------------//

    public function deigitalItemCreate()
    {
        return view('back.item.digital.create', [
            'curr' => Currency::where('is_default', 1)->first()
        ]);
    }

    public function deigitalItemStore(ItemRequest $request)
    {
        $this->repository->store($request);
        return redirect()->route('back.item.index')->withSuccess(__('New Product Added Successfully.'));
    }

    public function deigitalItemEdit($id)
    {
        $item = Item::findOrFail($id);

        return view('back.item.digital.edit', [
            'item' => $item,
            'curr' => Currency::where('is_default', 1)->first(),
            'social_icons' => json_decode($item->social_icons, true),
            'social_links' => json_decode($item->social_links, true),
            'specification_name' => json_decode($item->specification_name, true),
            'specification_description' => json_decode($item->specification_description, true),
        ]);
    }


    // ---------------- LICENSE PRODUCT START ---------------//

    public function licenseItemCreate()
    {
        return view('back.item.license.create', [
            'curr' => Currency::where('is_default', 1)->first()
        ]);
    }

    public function licenseItemStore(ItemRequest $request)
    {
        $this->repository->store($request);
        return redirect()->route('back.item.index')->withSuccess(__('New Product Added Successfully.'));
    }

    public function licenseItemEdit($id)
    {
        $item = Item::findOrFail($id);

        return view('back.item.license.edit', [
            'item' => $item,
            'curr' => Currency::where('is_default', 1)->first(),
            'social_icons' => json_decode($item->social_icons, true),
            'social_links' => json_decode($item->social_links, true),
            'specification_name' => json_decode($item->specification_name, true),
            'specification_description' => json_decode($item->specification_description, true),
            'license_name' => json_decode($item->license_name, true),
            'license_key' => json_decode($item->license_key, true),
        ]);
    }


    public function stockOut()
    {
        $datas = Item::where('item_type', 'normal')->where('stock', 0)->get();
        return view('back.item.stockout', compact('datas'));
    }

    public function wooPreview()
    {
        // Initialize WooCommerce API client
        $woocommerce = new \Automattic\WooCommerce\Client(
            'https://www.urali.in',
            'ck_f5de9a386d38d682e4ebdbe64a1da51f4662accc',
            'cs_73f5284097c8790386647fb2622b7f2416371892',
            [
                'version' => 'wc/v3',
                'verify_ssl' => false,
                'timeout' => 3000,
            ]
        );

        // 1. Fetch one product (assumed variable product)
        $products = $woocommerce->get('products', ['per_page' => 100]);
        if (empty($products)) {
            Log::warning("No products found in WooCommerce.");
            return redirect()->route('back.item.index')->withError('No products found.');
        }
        foreach($products as $product) {
            // Process each product
            // 2. Create the main Item record (only one product listing)
            $item = Item::create([
                'name'         => $product->name,
                'slug'         => $product->slug,
                'sku'          => $product->sku ?? null,
                'price'        => $product->price ?? 0,
                'discount_price' => !empty($product->sale_price) ? $product->sale_price : $product->price,
                'previous_price' => !empty($product->regular_price) ? $product->regular_price : $product->price,
                'item_type'    => 'normal',  // mark as variable
                'sort_details' => $product->short_description ?? '',
                'details'      => $product->description ?? '',
                'is_specification' => 1,
                'tax_id' => 2,
            ]);
            // Log::info("Item created", ['id' => $item->id]);

            // 2.5 Map Category (if available)
            if (!empty($product->categories) && is_array($product->categories)) {
                $wooCat = $product->categories[0];
                // Lookup or create category by slug
                $cat = Category::firstOrCreate(
                    ['slug' => $wooCat->slug],
                    ['name' => $wooCat->name, 'status' => 1 /*, add other fields as needed */]
                );
                $item->update(['category_id' => $cat->id]);
                // Log::info("Product category mapped", ['category' => $wooCat->name, 'cat_id' => $cat->id]);
            }

            // 3. Download and set the main image (photo & thumbnail)
            if (!empty($product->images) && is_array($product->images)) {
                $firstImage = $product->images[0];
                $imgUrl = $firstImage->src;
                try {
                    $imgContent = file_get_contents($imgUrl);
                    // Generate a unique filename
                    $fileName = time().'_'.basename($imgUrl);
                    // Save in "images" folder on the public disk
                    Storage::disk('public')->put('images/'.$fileName, $imgContent);
                    $item->update([
                        'photo'     => $fileName,
                        'thumbnail' => $fileName,
                    ]);
                    // Log::info("Main image downloaded and saved", ['file' => $fileName]);
                } catch (\Exception $e) {
                    // Log::error("Error downloading main image: " . $e->getMessage());
                }
            }

            // 4. Process attributes:
            // Separate variation attributes from non-variation (specification) attributes.
            $attributeMap = []; // For variation attributes; key = attribute name, value = Attribute record
            $specNames = [];
            $specValues = [];

            if (!empty($product->attributes)) {
                foreach ($product->attributes as $attr) {
                    // If 'variation' flag is set and equals "1", treat as variation attribute.
                    if (isset($attr->variation) && (string)$attr->variation === "1") {
                        $attribute = Attribute::create([
                            'item_id' => $item->id,
                            'name'    => $attr->name,
                            'keyword' => Str::slug($attr->name),
                        ]);
                        $attributeMap[$attr->name] = $attribute;
                        // Create options for this attribute
                        if (!empty($attr->options) && is_array($attr->options)) {
                            foreach ($attr->options as $option) {
                                $attribute->options()->create([
                                    'name'    => $option,
                                    'keyword' => Str::slug($option),
                                    'price'   => 0,  // will update later with variation data
                                    'stock'   => 0,
                                ]);
                            }
                        }
                        // Log::info("Created variation attribute", ['name' => $attr->name]);
                    } else {
                        // Otherwise, treat as specification
                        $specNames[] = $attr->name;
                        $specValues[] = is_array($attr->options) ? implode(", ", $attr->options) : $attr->options;
                        // Log::info("Added specification", ['name' => $attr->name, 'value' => is_array($attr->options) ? implode(", ", $attr->options) : $attr->options]);
                    }
                }
            }

            // Save specification data into the Item record
            if (!empty($specNames)) {
                $item->update([
                    'specification_name'        => json_encode($specNames),
                    'specification_description' => json_encode($specValues),
                ]);
                // Log::info("Specifications saved", ['spec_names' => $specNames, 'spec_values' => $specValues]);
            }

            // 5. Process variations – update the options for variation attributes with price/stock info.
            if (!empty($product->variations) && is_array($product->variations)) {
                foreach ($product->variations as $variationId) {
                    $variation = $woocommerce->get("products/{$product->id}/variations/{$variationId}");
                    // Log::info("Fetched variation", json_decode(json_encode($variation), true));

                    // Update the corresponding attribute options based on this variation
                    foreach ($variation->attributes as $vAttr) {
                        $attrName = $vAttr->name;
                        $optionValue = $vAttr->option;
                        if (isset($attributeMap[$attrName])) {
                            $attribute = $attributeMap[$attrName];
                            // Look up an existing option for this value
                            $existingOption = $attribute->options()->where('name', $optionValue)->first();
                            if ($existingOption) {
                                $existingOption->update([
                                    'price' => $variation->price ?? $existingOption->price,
                                    'stock' => $variation->stock_quantity ?? $existingOption->stock,
                                ]);
                                // Log::info("Updated attribute option", [
                                //     'attribute' => $attrName,
                                //     'option'    => $optionValue,
                                //     'price'     => $variation->price,
                                //     'stock'     => $variation->stock_quantity,
                                // ]);
                            } else {
                                // Create new option if not found
                                $attribute->options()->create([
                                    'name'    => $optionValue,
                                    'keyword' => Str::slug($optionValue),
                                    'price'   => $variation->price ?? 0,
                                    'stock'   => $variation->stock_quantity ?? 0,
                                ]);
                                // Log::info("Created new attribute option", [
                                //     'attribute' => $attrName,
                                //     'option'    => $optionValue,
                                // ]);
                            }
                        }
                    }
                }
            }

            // 6. Process gallery images: download images (skip the first, already used)
            if (!empty($product->images) && is_array($product->images)) {
                foreach ($product->images as $index => $imgObj) {
                    if ($index === 0) {
                        continue;
                    }
                    $imgUrl = $imgObj->src;
                    try {
                        $imgContent = file_get_contents($imgUrl);
                        $fileName = time().'_'.basename($imgUrl);
                        Storage::disk('public')->put('images/'.$fileName, $imgContent);
                        // Save gallery record
                        Gallery::create([
                            'item_id' => $item->id,
                            'photo'   => $fileName,
                        ]);
                        // Log::info("Gallery image saved", ['file' => $fileName]);
                    } catch (\Exception $e) {
                        // Log::error("Error downloading gallery image: " . $e->getMessage());
                    }
                }
            }

            Log::info("Product import complete", ['item_id' => $item->id]);
        }
        // $product = $products[0];
        // Log::info("Fetched product", json_decode(json_encode($product), true));
        return redirect()->route('back.item.index')->withSuccess('Product imported successfully!');
    }


}
