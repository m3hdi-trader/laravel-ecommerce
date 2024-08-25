<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {

        $sliders = Banner::where('type', 'slider')->where('is_active', 1)->orderBy('priority')->get();
        $indexTopBanners = Banner::where('type', 'index_top')->where('is_active', 1)->orderBy('priority')->get();
        $indexBottomBanners = Banner::where('type', 'index_bottom')->where('is_active', 1)->orderBy('priority')->get();


        $products = Product::where('is_active', 1)->get();
        // $t = $products;  
        // dd($t);
        // $product->where('is_active', 1)->get();
        $category = Category::all();
        // dd($product->category()->name);
        // dd($category);
        // $t = $products->category()->where('parent_id', 1)->get();
        // $productsmens = $products->category()->name;
        // dd($t);

        return view('home.index', compact('sliders', 'indexTopBanners', 'indexBottomBanners', 'products', 'category'));
    }
}
