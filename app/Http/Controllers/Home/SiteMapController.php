<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

class SiteMapController extends Controller
{
    public function index()
    {
        $path = public_path('sitemap.xml');
        SitemapGenerator::create('http://localhost:8000')
            ->getSitemap()
            // here we add one extra link, but you can add as many as you'd like
            ->add(Url::create('/')->setPriority(1.0))
            ->writeToFile($path);
    }
}
