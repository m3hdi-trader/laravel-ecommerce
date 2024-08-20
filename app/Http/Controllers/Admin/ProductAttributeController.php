<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    public function store($attribute, $product)
    {
        foreach ($attribute as $key => $value) {
            ProductAttribute::create([
                'product_id' => $product->id,
                'attribute_id' => $key,
                'value' => $value,

            ]);
        }
    }

    public function update($attributIds)
    {
        foreach ($attributIds as $key => $value) {
            $productAttribute = ProductAttribute::findOrFail($key);
            $productAttribute->update([
                'value' => $value,
            ]);
        }
    }

    public function change($attribute, $product)
    {
        ProductAttribute::where('product_id', $product->id)->delete();
        foreach ($attribute as $key => $value) {
            $productAttribute = ProductAttribute::findOrFail($key);
            $productAttribute->update([
                'value' => $value,
            ]);
        }
    }
}
