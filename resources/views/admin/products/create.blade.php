@extends('admin.layouts.admin')

@section('title')
    create products
@endsection

@section('script')
    <script>
        $('#brandSelect').selectpicker({
            'title': 'انتخاب برند'
        });

        $('#tagSelect').selectpicker({
            'title': 'انتخاب تگ'
        });

        // Show File Name
        $('#primary_image').change(function() {
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        });

        $('#images').change(function() {
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        });

        $('#categorySelect').selectpicker({
            'title': 'انتخاب دسته بندی'
        });

        $('#attributeContainer').hide();

        $('#categorySelect').on('changed.bs.select', function() {
            let categoryId = $(this).val();
            $.get(`{{ url('/admin-panel/management/category-attributes/${categoryId}') }}}`, function(response,
                status) {
                if (status == 'success') {
                    $('#attributeContainer').fadeIn();
                    // Empty Attribut Container
                    $('#attributes').find('div').remove();
                    // Create And Append Attribut Input
                    response.attribute.forEach(attribute => {
                        let attributeFormGroup = $('<div/>', {
                            class: 'form-group col-md-3'
                        });

                        attributeFormGroup.append($('<label/>', {
                            for: attribute.name,
                            text: attribute.name
                        }));

                        attributeFormGroup.append($('<input/>', {
                            type: 'text',
                            class: "form-control",
                            id: attribute.name,
                            name: `attribute_ids[${attribute.id}]`

                        }));
                        $('#attributes').append(attributeFormGroup);

                    });
                    // console.log(response.variation.name);
                    $('#variationName').text(response.variation.name);

                } else {
                    alert('مشکل در دریافت لیست ویژگی ها');

                }

            }).fail(function() {
                alert('مشکل در دریافت لیست ویژگی ها');
            })

        });
        $("#czContainer").czMore();
    </script>
@endsection


@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="mb-4 text-center text-md-right">
                <h5 class="font-weight-bold">ایجاد محصول</h5>
            </div>
            <hr>
            @include('admin.sections.errors')
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="name">نام</label>
                        <input class="form-control" type="text" id="name" name="name"
                            value="{{ old('name') }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="brand_id">برند</label>
                        <select id="brandSelect" name="brand_id" class="form-control" data-live-search="true">
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="is_active">وضعیت</label>
                        <select class="form-control" id="is_active" name="is_active">
                            <option value="1" selected>فعال</option>
                            <option value="0">غیر فعال</option>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="tag_ids">تگ</label>
                        <select id="tagSelect" name="tag_ids[]" class="form-control" multiple data-live-search="true">
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="description">توضیحات</label>
                        <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
                    </div>

                    {{-- Product Image Section --}}

                    <div class="col-md-12">
                        <hr>
                        <p>تصاویر محصول : </p>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="primary_image"> انتخاب تصویر اصلی </label>
                        <div class="custom-file">
                            <input type="file" name="primary_image" class="custom-file-input" id="primary_image">
                            <label class="custom-file-label" for="primary_image"> انتخاب فایل </label>
                        </div>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="images"> انتخاب تصاویر </label>
                        <div class="custom-file">
                            <input type="file" name="images[]" multiple class="custom-file-input" id="images">
                            <label class="custom-file-label" for="images"> انتخاب فایل ها </label>
                        </div>
                    </div>
                    {{-- Category & Attributes Section --}}

                    <div class="col-md-12">
                        <hr>
                        <p>دسته بندی و ویژگی ها: </p>
                    </div>

                    <div class="col-md-12">
                        <div class="row justify-content-center">
                            <div class="form-group col-md-3">
                                <label for="category_id">دسته بندی</label>
                                <select id="categorySelect" name="category_id" class="form-control" data-live-search="true">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }} -
                                            {{ $category->parent->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="attributeContainer" class="col-md-12">
                        <div id="attributes" class="row">
                        </div>
                        <div class="col-md-12">
                            <hr>
                            <p> افزودن قیمت و موجودی برای متغیر <span class="font-weight-bold" id="variationName"></span> :
                            </p>
                        </div>
                        <div id="czContainer">
                            <div id="first">
                                <div class="recordset">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label>نام</label>
                                            <input class="form-control" type="text" name="variation_values[value][]">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>قیمت</label>
                                            <input class="form-control" type="text" name="variation_values[price][]">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>تعداد</label>
                                            <input class="form-control" type="text" name="variation_values[quantity][]">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>شناسه انبار</label>
                                            <input class="form-control" type="text" name="variation_values[sku][]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    {{-- Delivery Section --}}
                    <div class="col-md-12">
                        <hr>
                        <p>هزینه ارسال : </p>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="delivery_amount">هزینه ارسال</label>
                        <input class="form-control" type="text" id="delivery_amount" name="delivery_amount"
                            value="{{ old('delivery_amount') }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="delivery_amount_per_product">هزینه ارسال به ازای هر محصول اضافی</label>
                        <input class="form-control" type="text" id="delivery_amount_per_product"
                            name="delivery_amount_per_product" value="{{ old('delivery_amount_per_product') }}">
                    </div>
                </div>



                <button class="btn btn-outline-primary mt-5" type="submit">ثبت</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>
    </div>
@endsection
