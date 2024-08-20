@extends('admin.layouts.admin')

@section('title')
    edit products category
@endsection

@section('script')
    <script>
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
                <h5 class="font-weight-bold">ویرایش دسته بندی محصول : {{ $product->name }}</h5>
            </div>
            <hr>
            @include('admin.sections.errors')
            <form action="{{ route('admin.products.category.update', ['product' => $product->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-row">
                    {{-- Category & Attributes Section --}}

                    <div class="col-md-12">
                        <div class="row justify-content-center">
                            <div class="form-group col-md-3">
                                <label for="category_id">دسته بندی</label>
                                <select id="categorySelect" name="category_id" class="form-control" data-live-search="true">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $category->id == $product->category->id ? 'selected' : '' }}>
                                            {{ $category->name }} -
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
                </div>
                <button class="btn btn-outline-primary mt-5" type="submit">ویرایش</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>
    </div>
@endsection
