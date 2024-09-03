<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CompareController extends Controller
{
    public function add(Product $product)
    {

        // dd($product);

        if (session()->has('compareProduct')) {
            if (in_array($product->id, session()->get('compareProduct'))) {
                Alert::warning("محصول مورد نظر شما به لیست مقایسه اضافه شده است", 'دقت کنید');
                return redirect()->back();
            }
            session()->push('compareProduct', $product->id);
        } else {

            session()->put('compareProduct', [$product->id]);
        }

        Alert::success("محصول مورد نظر به لیست مقایسه اضافه شد", 'با تشکر');
        return redirect()->back();
    }

    public function index()
    {
        if (session()->has('compareProduct')) {
            $products = Product::findOrFail(session()->get('compareProduct'));

            return view('home.compare.index', compact('products'));
        }

        Alert::warning("در ابتدا محصولی به لیست مقایسه اضافه کنید", 'دقت کنید');
        return redirect()->back();
    }

    public function remove($productId)
    {
        if (session()->has('compareProduct')) {
            foreach (session()->get('compareProduct') as $key => $item) {
                if ($item == $productId) {
                    session()->pull('compareProduct.' . $key);
                }
            }
            if (session()->get('compareProduct') == []) {
                session()->forget('compareProduct');
                return redirect()->route('home.index');
            }
            return redirect()->route('home.compare.index');
        }
        Alert::warning("در ابتدا محصولی به لیست مقایسه اضافه کنید", 'دقت کنید');
        return redirect()->back();
    }
}
