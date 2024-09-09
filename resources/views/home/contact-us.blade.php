@extends('home.layouts.home')

@section('title')
    صفحه ای تماس با ما
@endsection

@section('style')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endsection

@section('script')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        var map = L.map('map').setView([{{ $setting->latitude }}, {{ $setting->longitude }}], 17);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map)

        var marker = L.marker([{{ $setting->latitude }}, {{ $setting->longitude }}]).addTo(map);
        marker.bindPopup("<b>مهدی پیدا شد!</b><br>اینجا").openPopup();
    </script>
@endsection

@section('content')
    <div class="breadcrumb-area pt-35 pb-35 bg-gray" style="direction: rtl;">
        <div class="container">
            <div class="breadcrumb-content text-center">
                <ul>
                    <li>
                        <a href="index.html">صفحه ای اصلی</a>
                    </li>
                    <li class="active">فروشگاه </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="contact-area pt-100 pb-100">
        <div class="container">
            <div class="row text-right" style="direction: rtl;">
                <div class="col-lg-5 col-md-6">
                    <div class="contact-info-area">
                        <h2> لورم ایپسوم متن </h2>
                        <p>
                            لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک
                            است.
                        </p>
                        <div class="contact-info-wrap">
                            <div class="single-contact-info">
                                <div class="contact-info-icon">
                                    <i class="sli sli-location-pin"></i>
                                </div>
                                <div class="contact-info-content">
                                    <p> {{ $setting->address }} </p>
                                </div>
                            </div>
                            <div class="single-contact-info">
                                <div class="contact-info-icon">
                                    <i class="sli sli-envelope"></i>
                                </div>
                                <div class="contact-info-content">
                                    <p><a href="#">info@example.com</a> / <a href="#">info@example.com</a></p>
                                </div>
                            </div>
                            <div class="single-contact-info">
                                <div class="contact-info-icon">
                                    <i class="sli sli-screen-smartphone"></i>
                                </div>
                                <div class="contact-info-content">
                                    <p style="direction: ltr;"> {{ $setting->telephone }} / {{ $setting->telephone2 }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 col-md-6">
                    <div class="contact-from contact-shadow">
                        <form id="contact-form" action="{{ route('home.contact-us.form') }}" method="post">
                            @csrf
                            <input name="name" type="text" placeholder="نام شما" value="{{ old('name') }}">
                            @error('name')
                                <p class="input-error-validation">
                                    <strong>{{ $message }}</strong>
                                </p>
                            @enderror
                            <input name="email" type="email" placeholder="ایمیل شما" value="{{ old('email') }}">
                            @error('email')
                                <p class="input-error-validation">
                                    <strong>{{ $message }}</strong>
                                </p>
                            @enderror
                            <input name="subject" type="text" placeholder="موضوع پیام" value="{{ old('subject') }}">
                            @error('subject')
                                <p class="input-error-validation">
                                    <strong>{{ $message }}</strong>
                                </p>
                            @enderror
                            <textarea name="text" placeholder="متن پیام">{{ old('text') }}</textarea>
                            @error('text')
                                <p class="input-error-validation">
                                    <strong>{{ $message }}</strong>
                                </p>
                            @enderror
                            <div id="contact_us_id"></div>
                            <button class="submit" type="submit"> ارسال پیام </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="contact-map pt-100">
                <div id="map"></div>
            </div>
        </div>
    </div>
@endsection
