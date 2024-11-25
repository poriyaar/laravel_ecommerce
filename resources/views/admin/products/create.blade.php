@extends('admin.layouts.admin')

@section('title', 'create product')

@section('content')
    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
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
                        <input type="text" id="name" name="name" class="form-control"
                            value="{{ old('name') }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="brandSelect">برند</label>
                        <select id="brandSelect" name="brand_id" class="form-control" data-live-search="true">
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="is_active">وضعیت</label>
                        <select id="is_active" name="is_active" class="form-control">
                            <option value="1" selected>فعال</option>
                            <option value="0">غیرفعال</option>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="tagSelect">تگ</label>
                        <select id="tagSelect" name="tag_ids[]" class="form-control" data-live-search="true" multiple>
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="description">توضیحات</label>
                        <textarea id="description" name="description" class="form-control"> {{ old('description') }} </textarea>
                    </div>

                    {{-- Product Image section --}}

                    <div class="col-md-12">
                        <hr>

                        <p>تصاویر محصول :</p>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="primary_image">انتخاب تصویر اصلی</label>
                        <div class="custom-file">
                            <input type="file" name="primary_image" class="custom-file-input" id="primary_image">
                            <label class="custom-file-label" for="primary_image">انتخاب فایل</label>
                        </div>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="primary_image">انتخاب تصویر</label>
                        <div class="custom-file">
                            <input type="file" name="images[]" multiple class="custom-file-input" id="images">
                            <label class="custom-file-label" for="images">انتخاب فایل ها</label>
                        </div>
                    </div>


                    {{-- category&Attribute section --}}
                    <div class="col-md-12">
                        <hr>

                        <p>دسته بندی و ویژگی ها :</p>
                    </div>

                    <div class="col-md-12">
                        <div class="row justify-content-center">
                            <div class="form-group col-md-3">
                                <label for="categorySelect">دسته بندی</label>
                                <select id="categorySelect" name="category_id" class="form-control" data-live-search="true">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->parent->name }} -
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>


                    <div id="attributesContainer" class="col-md-12">
                        <div id="attributes" class="row"></div>
                        <div class="col-md-12">
                            <hr>
                            <p>افزودن قیمت و موجودی برای متغییر <span class="font-weight-bold" id="variationName"></span> :
                            </p>
                        </div>

                        <div id="czContainer">
                            <div id="first">
                                <div class="recordset">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label>نام</label>
                                            <input type="text" name="variation_values[value][]" class="form-control">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>قیمت</label>
                                            <input type="text" name="variation_values[price][]" class="form-control">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>تعداد</label>
                                            <input type="text" name="variation_values[quantity][]" class="form-control">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>شناسه انبار</label>
                                            <input type="text" name="variation_values[sku][]" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                    {{-- delivery section --}}

                    <div class="col-md-12">
                        <hr>
                        <p>هزینه ارسال :</p>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="delivery_amount">هزینه ارسال</label>
                        <input type="text" id="delivery_amount" name="delivery_amount" class="form-control"
                            value="{{ old('delivery_amount') }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="delivery_amount_per_product">هزینه ارسال به اجزای محصول اضافی</label>
                        <input type="text" id="delivery_amount_per_product" name="delivery_amount_per_product" class="form-control"
                            value="{{ old('delivery_amount_per_product') }}">
                    </div>

                </div>



                <button type="submit" class="btn btn-outline-primary mt-5">ثبت</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize selectpickers
            $('#brandSelect').selectpicker({
                'title': 'انتخاب برند'
            });

            $("#tagSelect").selectpicker({
                'title': 'انتخاب تگ'
            });

            $("#categorySelect").selectpicker({
                'title': 'انتخاب دسته بندی'
            });

            // Show file name for primary image
            $('#primary_image').change(function() {
                var fileName = $(this).val();
                $(this).next('.custom-file-label').html(fileName);
            });

            // Show file name for additional images
            $('#images').change(function() {
                var fileName = $(this).val();
                $(this).next('.custom-file-label').html(fileName);
            });

            $('#attributesContainer').hide()

            // Update attributes when category changes
            $('#categorySelect').on('changed.bs.select', function() {
                let categoryId = $(this).val();

                $.get(`{{ url('/admin-panel/management/category-attributes/${categoryId}') }}`)
                    .done(function(response, status) {
                        if (status == 'success') {

                            $('#attributesContainer').fadeIn()


                            $('#attributes').empty(); // Clear existing attributes



                            response.attributes.forEach(function(attribute) {
                                let attributeFormGroup = $('<div/>', {
                                    class: 'form-group col-md-3'
                                });

                                attributeFormGroup.append($('<label/>', {
                                    for: attribute.name,
                                    class: attribute.name,
                                    text: attribute.name // Set label text directly
                                }));

                                attributeFormGroup.append($('<input/>', {
                                    type: 'text',
                                    class: "form-control",
                                    id: attribute.name,
                                    name: `attribute_ids[${attribute.id}]`
                                }));

                                $('#attributes').append(attributeFormGroup);
                            });

                            $('#variationName').text(response.variation.name)

                        } else {
                            alert('مشکل در دریافت ویژگی ها');
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        console.error("Error details:", textStatus, errorThrown);
                        console.error("Response:", jqXHR.responseText);
                        alert('مشکل در دریافت لیست ویژگی ها');
                    });
            });
        });

        $("#czContainer").czMore();
    </script>
@endsection
