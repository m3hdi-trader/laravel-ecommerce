<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\UploadImages;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest()->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brands = Brand::all();
        $tags = Tag::all();
        $categories = Category::where('parent_id', '!=', 0)->get();

        return view('admin.products.create', compact('tags', 'categories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'brand_id' => 'required',
            'is_active' => 'required',
            'tag_ids' => 'required',
            'description' => 'nullable',
            'primary_image' => 'required|mimes:jpg,jprg,png,svg',
            'images' => 'required',
            'images.*' => 'mimes:jpg,jprg,png,svg',
            'category_id' => 'required',
            'attribute_ids' => 'required',
            'attribute_ids.*' => 'required',
            'variation_values' => 'required',
            'variation_values.*.*' => 'required',
            'variation_values.price.*' => 'integer',
            'variation_values.quantity.*' => 'integer',
            'delivery_amount' => 'required|integer',
            'delivery_amount_pre_product' => 'nullable|integer',

        ]);

        try {
            DB::beginTransaction();

            $ProductImageController = new ProductImageController();
            $fileNameImages = $ProductImageController->upload($request->primary_image, $request->images);
            // dd($fileNameImages);

            $product = Product::create([
                'name' => $request->name,
                'brand_id' => $request->brand_id,
                'category_id' => $request->category_id,
                'primary_image' => $fileNameImages['fileNamePrimaryImage'],
                'description' => $request->description,
                'is_active' => $request->is_active,
                'delivery_amount' => $request->delivery_amount,
                'delivery_amount_pre_product' => $request->delivery_amount_pre_product,
            ]);

            foreach ($fileNameImages['fileNameImages'] as $fileNameImage) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $fileNameImage,
                ]);
            }

            $ProductAttributeController = new ProductAttributeController();
            $ProductAttributeController->store($request->attribute_ids, $product);

            $category = Category::find($request->category_id);

            $ProductVariationController = new ProductVariationController();
            $ProductVariationController->store($request->variation_values, $category->attributes()->wherePivot('is_variation', 1)->first()->id, $product);

            $product->tags()->attach($request->tag_ids);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();

            Alert::error($ex->getMessage(), 'مشکل در ایجاد محصول')->persistent('حله');
            return redirect()->back();
        }

        Alert::success('محصول مورد نظر شما ایجاد شد', 'با تشکر');
        return redirect()->route('admin.products.index');
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $productAttributes = $product->attributes()->with('attribute')->get();
        // dd($productAttributes);
        $productVariations = $product->variations;
        $images = $product->images;
        // dd($images);
        return view('admin.products.show', compact('product', 'productAttributes', 'productVariations', 'images'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $brands = Brand::all();
        $tags = Tag::all();
        $productAttributes = $product->attributes()->with('attribute')->get();
        $productVariations = $product->variations;
        return view('admin.products.edit', compact('product', 'brands', 'tags', 'productVariations', 'productAttributes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        // dd($request->all());

        $request->validate([
            'name' => 'required',
            'brand_id' => 'required|exists:brands,id',
            'is_active' => 'required',
            'tag_ids' => 'required',
            'tag_ids.*' => 'exists:tags,id',
            'description' => 'required',
            'attribute_values' => 'required',
            'variation_values' => 'required',
            'variation_values.*.price' => 'required|integer',
            'variation_values.*.quantity' => 'required|integer',
            'variation_values.*.sale_price' => 'nullable|integer',
            'variation_values.*.date_on_sale_from' => 'nullable',
            'variation_values.*.date_on_sale_to' => 'nullable',
            'delivery_amount' => 'required|integer',
            'delivery_amount_per_product' => 'nullable|integer',

        ]);

        try {
            DB::beginTransaction();

            $product->update([
                'name' => $request->name,
                'brand_id' => $request->brand_id,
                'description' => $request->description,
                'is_active' => $request->is_active,
                'delivery_amount' => $request->delivery_amount,
                'delivery_amount_pre_product' => $request->delivery_amount_pre_product,
            ]);



            $ProductAttributeController = new ProductAttributeController();
            $ProductAttributeController->update($request->attribute_values);

            $ProductVariationController = new ProductVariationController();
            $ProductVariationController->update($request->variation_values);

            $product->tags()->sync($request->tag_ids);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();

            Alert::error($ex->getMessage(), 'مشکل در ویرایش محصول')->persistent('حله');
            return redirect()->back();
        }

        Alert::success('محصول مورد نظر شما ویرایش شد', 'با تشکر');
        return redirect()->route('admin.products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function editCategory(Request $request, Product $product)
    {
        $categories = Category::where('parent_id', '!=', 0)->get();

        return view('admin.products.edit_category', compact('product', 'categories'));
    }

    public function updateCategory(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required',
            'attribute_ids' => 'required',
            'attribute_ids.*' => 'required',
            'variation_values' => 'required',
            'variation_values.*.*' => 'required',
            'variation_values.price.*' => 'integer',
            'variation_values.quantity.*' => 'integer',


        ]);

        try {
            DB::beginTransaction();

            // dd($fileNameImages);

            $product->update([
                'category_id' => $request->category_id,
            ]);


            $ProductAttributeController = new ProductAttributeController();
            $ProductAttributeController->change($request->attribute_ids, $product);

            $category = Category::find($request->category_id);

            $ProductVariationController = new ProductVariationController();
            $ProductVariationController->change($request->variation_values, $category->attributes()->wherePivot('is_variation', 1)->first()->id, $product);

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();

            Alert::error($ex->getMessage(), 'مشکل در ایجاد محصول')->persistent('حله');
            return redirect()->back();
        }

        Alert::success('محصول مورد نظر شما ایجاد شد', 'با تشکر');
        return redirect()->route('admin.products.index');
    }
}
