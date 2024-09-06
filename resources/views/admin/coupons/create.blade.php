@extends('admin.layouts.admin')

@section('title')
    create coupon
@endsection

@section('script')
    <script>
        $('#expireDate').MdPersianDateTimePicker({
            targetTextSelector: '#expireInput',
            textFormat: 'yyyy-MM-dd HH:mm:ss',
            enableTimePicker: true
        });
    </script>
@endsection


@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
            <div class="mb-4">
                <h5 class="font-weight-bold">ایجاد کوبن</h5>
            </div>
            <hr>
            @include('admin.sections.errors')
            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="name">نام</label>
                        <input class="form-control" type="text" id="name" name="name" value="{{ old('name') }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="code">کد</label>
                        <input class="form-control" type="text" id="code" name="code"
                            value="{{ old('code') }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="type">نوع</label>
                        <select class="form-control" type="text" id="type" name="type">
                            <option value="amount">مبلغی</option>
                            <option value="percentage">درصدی</option>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="amount">مبلغ</label>
                        <input class="form-control" type="text" id="amount" name="amount"
                            value="{{ old('amount') }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="amount">درصد</label>
                        <input class="form-control" type="text" id="percentage" name="percentage"
                            value="{{ old('percentage') }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="max_percentage_amount">حداکثر مبلغ برای نوع درصد</label>
                        <input class="form-control" type="text" id="max_percentage_amount" name="max_percentage_amount"
                            value="{{ old('max_percentage_amount') }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label> تاریخ انقضا</label>
                        <div class="input-group">
                            <div class="input-group-prepend order-2">
                                <span class="input-group-text" id="expireDate">
                                    <i class="fas fa-clock"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" id="expireInput" name="expired_at">
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="description">توضیحات</label>
                        <textarea class="form-control" id="description">{{ old('description') }}</textarea>
                    </div>

                </div>

                <button class="btn btn-outline-primary mt-5" type="submit">ثبت</button>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>
    </div>
@endsection
