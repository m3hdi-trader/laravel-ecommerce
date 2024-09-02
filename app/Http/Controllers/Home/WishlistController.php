<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class WishlistController extends Controller
{
    public function add(Product $product)
    {
        if (auth()->check()) {
            if ($product->checkUserWishlist(auth()->id())) {
                Alert::warning("'دقت کنید", '"محصول مورد نظر به لیست مورد علاقه مندی ها اضافه شده است')->persistent('حله');
                return redirect()->back();
            } else {
                Wishlist::create([
                    'user_id' => auth()->id(),
                    'product_id' => $product->id
                ]);
            }

            Alert::success("محصول مورد نظر به  لیست  علاقه مندی ها اضافه شد", 'با تشکر');
            return redirect()->back();
        } else {
            Alert::warning("برای افزودن به لیست علاقه مندی ها  نیاز هست ابتدا در ثبت نام کنید", 'دقت کنید')->persistent('حله');
            return redirect()->back();
        }
    }

    public function remove(Product $product)
    {
        if (auth()->check()) {
            $wishlist = Wishlist::where('product_id', $product->id)->where('user_id', auth()->id())->firstOrFail();
            if ($wishlist) {
                Wishlist::where('product_id', $product->id)->where('user_id', auth()->id())->delete();
            }


            Alert::success("محصول مورد نظر از لیست علاقه مندی ها شما حذف شد", 'با تشکر');
            return redirect()->back();
        } else {
            Alert::warning("برای حذف از لیست علاقه مندی ها  نیاز هست ابتدا در ثبت نام کنید", 'دقت کنید')->persistent('حله');
            return redirect()->back();
        }
    }

    public function usersProfileIndex()
    {
        $wishlist = Wishlist::where('user_id', auth()->id())->get();
        return view('home.users_profile.wishlist', compact('wishlist'));
    }
}
