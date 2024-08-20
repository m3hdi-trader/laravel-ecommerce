<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\UploadImages;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ProductImageController extends Controller
{
    public function upload($primaryImage, $images)
    {
        $fileNamePrimaryImage = UploadImages::generateFileName($primaryImage->getClientOriginalName());

        $primaryImage->move(public_path(env('PRODUCT_IMAGES_UPLOAD_PAHT')), $fileNamePrimaryImage);

        $fileNameImages = [];
        foreach ($images as $image) {
            $fileNameImage = UploadImages::generateFileName($image->getClientOriginalName());

            $image->move(public_path(env('PRODUCT_IMAGES_UPLOAD_PAHT')), $fileNameImage);

            array_push($fileNameImages, $fileNameImage);
        }

        return ['fileNamePrimaryImage' => $fileNamePrimaryImage, 'fileNameImages' => $fileNameImages];
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit_images', compact('product'));
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'image_id' => 'required|exists:product_images,id'
        ]);

        ProductImage::destroy($request->image_id);

        Alert::success('تصویر محصول مورد نظر شما حذف شد', 'با تشکر');
        return redirect()->back();
    }

    public function setPrimary(Request $request, Product $product)
    {
        $request->validate([
            'image_id' => 'required|exists:product_images,id'
        ]);

        $productImage = ProductImage::findOrFail($request->image_id);
        $product->update([
            'primary_image' => $productImage->image
        ]);

        Alert::success('ویرایش تصویر اصلی  محصول با موفقیت انجام شد', 'با تشکر');
        return redirect()->back();
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'primary_image' => 'nullable|mimes:jpg,jprg,png,svg',
            'images.*' => 'nullable|mimes:jpg,jprg,png,svg',
        ]);

        if ($request->primary_image == null && $request->images == null) {
            return redirect()->back()->withErrors(['msg' => 'تصویر یا تصاویر محصول الزامیست']);
        }
        try {
            DB::beginTransaction();
            if ($request->has('primary_image')) {

                $fileNamePrimaryImage = UploadImages::generateFileName($request->primary_image->getClientOriginalName());

                $request->primary_image->move(public_path(env('PRODUCT_IMAGES_UPLOAD_PAHT')), $fileNamePrimaryImage);

                $product->update([
                    'primary_image' => $fileNamePrimaryImage
                ]);
            }

            if ($request->has('images')) {
                $fileNameImages = [];
                foreach ($request->images as $image) {
                    $fileNameImage = UploadImages::generateFileName($image->getClientOriginalName());

                    $image->move(public_path(env('PRODUCT_IMAGES_UPLOAD_PAHT')), $fileNameImage);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $fileNameImage,
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();

            Alert::error($ex->getMessage(), 'مشکل در ویرایش تصویر اصلی یا تصاویر محصول')->persistent('حله');
            return redirect()->back();
        }
        Alert::success('ویرایش تصویر اصلی  محصول با موفقیت انجام شد', 'با تشکر');
        return redirect()->back();
    }
}
