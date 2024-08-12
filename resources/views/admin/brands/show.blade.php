@extends('admin.layouts.admin')

@section('title')
    show brands
@endsection


@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
            <div class="mb-4">
                <h5 class="font-weight-bold">برند : {{ $brand->name }}</h5>
            </div>
            <hr>

            <div class="row">
                <div class="form-group col-md-3">
                    <label>نام</label>
                    <input class="form-control" type="text" value="{{ $brand->name }}" disabled>
                </div>

                <div class="form-group col-md-3">
                    <label>وضعیت</label>
                    <input class="form-control" type="text" value="{{ $brand->is_active }}" disabled>
                </div>

                <div class="form-group col-md-3">
                    <label>تاریخ ایجاد</label>
                    <input class="form-control" type="text" value="{{ verta($brand->created_at)->format('Y-n-j H:i') }}"
                        disabled>
                </div>

            </div>
            <a href="{{ route('admin.brands.index') }}" class="btn btn-dark mt-5">بازگشت</a>

        </div>
    </div>
@endsection