<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Request $request, Category $category)
    {

        $attributes = $category->attributes()->where('is_filter', 1)->with('values')->get();
        $varition = $category->attributes()->where('is_variation', 1)->with('varitionValues')->first();

        $products = $category->products()->filter()->search()->paginate(9);
        // dd($products);

        return view('home.categories.show', compact('attributes', 'varition', 'category', 'products'));
    }
}
