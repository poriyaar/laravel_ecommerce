@extends('admin.layouts.admin')

@section('title', 'edit product category')

@section('content')
    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="mb-4 text-center text-md-right">
                <h5 class="font-weight-bold">ویرایش دسته بندی محصول : {{ $product->name }}</h5>
            </div>
            <hr>
            @include('admin.sections.errors')
            <form action="{{ route('admin.products.category.update' , $product) }}" method="POST"">
                @csrf

                <div class="form-row">

                    {{-- category&Attribute section --}}

                    <div class="col-md-12">
                        <div class="row justify-content-center">
                            <div class="form-group col-md-3">
                                <label for="categorySelect">دسته بندی</label>
                                <select id="categorySelect" name="category_id" class="form-control" data-live-search="true">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->parent->name }} -
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

                </div>



                <button type="submit" class="btn btn-outline-primary mt-5">ویرایش دسته بندی</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize selectpickers
            $("#categorySelect").selectpicker({
                'title': 'انتخاب دسته بندی'
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
