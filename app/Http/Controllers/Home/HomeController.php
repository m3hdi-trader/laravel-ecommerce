<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\ContactUs;
use App\Models\Product;
use App\Models\Setting;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class HomeController extends Controller
{
    public function index()
    {

        SEOTools::setTitle('Home');
        SEOTools::setDescription('This is my page description');
        SEOTools::setCanonical('https://codecasts.com.br/lesson');

        SEOTools::opengraph()->setDescription('mohammad');
        SEOTools::opengraph()->setUrl(route('home.index'));
        SEOTools::opengraph()->addProperty('type', 'articles');
        SEOTools::twitter()->setSite('@LuizVinicius73');
        SEOTools::jsonLd()->addImage('https://codecasts.com.br/img/logo.jpg');

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

    public function aboutUs()
    {
        $bottomBanners = Banner::where('type', 'index_bottom')->where('is_active', 1)->orderBy('priority')->get();
        return view('home.about-us', compact('bottomBanners'));
    }

    public function contactUs()
    {
        $setting = Setting::findOrFail(1);
        return view('home.contact-us', compact('setting'));
    }

    public function contactUsForm(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|email',
            'subject' => 'required|string|min:3|max:100',
            'text' => 'required|string|min:3|max:3000',
        ]);
        ContactUs::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'text' => $request->text,
        ]);

        Alert::success("پیام شما با موفقیت ثبت شد", 'با تشکر');
        return redirect()->back();
    }
}
