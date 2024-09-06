<?php

namespace App\Http\Controllers\Home;

use Cart;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariation;
use Darryldecode\Cart\Cart as CartCart;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'qtybutton' => 'required'
        ]);

        $product = Product::findOrFail($request->product_id);
        $productVariation = ProductVariation::findOrFail(json_decode($request->variation)->id);

        if ($request->qtybutton > $productVariation->quantity) {
            Alert::error("تعداد وارد شده صحیح نمی باشد", 'دقت کنید');
            return redirect()->back();
        }
        $rowId = $product->id . '-' . $productVariation->id;
        if (Cart::get($rowId) == null) {

            Cart::add(array(
                'id' => $rowId, // inique row ID
                'name' => $product->name,
                'price' => $productVariation->is_sale ? $productVariation->sale_price : $productVariation->price,
                'quantity' => $request->qtybutton,
                'attributes' => $productVariation->toArray(),
                'associatedModel' => $product
            ));
        } else {
            Alert::warning("محصول مورد نظر به سبد خرید اضافه شده است", 'دقت کنید');
            return redirect()->back();
        }


        Alert::success("محصول مورد نظر به سبد خرید اضافه شد", 'با تشکر');
        return redirect()->back();
    }

    public function index()
    {
        return view('home.cart.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'qtybutton' => 'required'
        ]);
        foreach ($request->qtybutton as $rowId => $quantity) {

            $item = Cart::get($rowId);

            if ($quantity > $item->attributes->quantity) {
                alert()->error('تعداد وارد شده از محصول درست نمی باشد', 'دقت کنید');
                return redirect()->back();
            }

            Cart::update($rowId, array(
                'quantity' => array(
                    'relative' => false,
                    'value' => $quantity
                ),
            ));
        }

        alert()->success('سبد خرید شما ویرایش شد', 'باتشکر');
        return redirect()->back();
    }

    public function remove($rowId)
    {
        Cart::remove($rowId);
        alert()->success("محصول مورد نظر  از سبد خرید شما حذف شد", 'باتشکر');
        return redirect()->back();
    }
}
