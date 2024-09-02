<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use App\Models\ProductRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class CommentController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|min:5|max:7000',
            'rate' => 'required|digits_between:0,5'
        ]);

        if ($validator->fails()) {
            return redirect()->to(url()->previous() . '#comments')->withErrors($validator);
        }

        if (auth()->check()) {

            try {
                DB::beginTransaction();

                Comment::create([
                    'user_id' => auth()->id(),
                    'product_id' => $product->id,
                    'text' => $request->text
                ]);


                if ($product->rates()->where('user_id', auth()->id())->exists()) {
                    $productRate = $product->rates()->where('user_id', auth()->id())->first();

                    $productRate->update([
                        'rate' => $request->rate
                    ]);
                } else {
                    ProductRate::create([
                        'user_id' => auth()->id(),
                        'product_id' => $product->id,
                        'rate' => $request->rate
                    ]);
                }

                DB::commit();
            } catch (\Exception $ex) {
                DB::rollBack();
                Alert::error($ex->getMessage(), 'مشکل در ایجاد محصول')->persistent('حله');
                return redirect()->back();
            }

            Alert::success('نظر ارزشمند شما برای این محصول با موفقیت ثبت شد', 'با تشکر');
            return redirect()->back();
        } else {
            Alert::warning("برای ثبت نظر نیاز هست ابتدا در ثبت نام کنید", 'دقت کنید')->persistent('حله');
            return redirect()->back();
        }
    }

    public function usersProfileIndex()
    {

        $comments = Comment::where('user_id', auth()->id())->where('approved', 1)->get();
        return view('home.users_profile.comments', compact('comments'));
    }
}
