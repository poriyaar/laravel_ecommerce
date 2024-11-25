@extends('admin.layouts.admin')

@section('title', 'edit product image')

@section('content')
    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-12 col-md-12 mb-4 p-5 bg-white">
            <div class="mb-4 mb-4 text-center text-md-right">
                <h5 class="font-weight-bold">ویرایش تصاویر محصول : {{ $product->name }}</h5>
            </div>
            <hr>

            @include('admin.sections.errors')


            {{-- show primary image --}}
            <div class="row">
                <div class="col-12 col-md-12 mb-5">
                    <h5>تصویر اصلی : </h5>
                </div>

                <div class="col-12 col-md-3 mb5">
                    <div class="card">
                        <img class="card-img-top" src="{{ generateProductImageLink($product->primary_image) }}"
                            alt="{{ $product->name }}">
                    </div>
                </div>
            </div>

            <hr>
            {{-- show product images --}}

            <div class="row">
                <div class="col-12 col-md-12 mb-5">
                    <h5>تصاویر محصول : </h5>
                </div>

                @foreach ($product->images as $image)
                    <div class="col-12 col-md-3 mb5">
                        <div class="card">
                            <img class="card-img-top" src="{{ generateProductImageLink($image->image) }}"
                                alt="{{ $product->name }}">

                            <div class="card-body text-center">
                                <form action="{{ route('admin.products.image.destroy' , $product) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="image_id" value="{{ $image->id }}">
                                    <button class="btn btn-sm btn-danger mb-4" type="submit">حذف</button>
                                </form>

                                <form action="{{ route('admin.products.image.set_primary', $product) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="image_id" value="{{ $image->id }}">
                                    <button class="btn btn-sm btn-primary mb-4" type="submit">انتخاب بعنوان تصویر اصلی
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                @endforeach

            </div>

            <hr>


            {{-- upload(add) image to product --}}

            <form action="{{ route('admin.products.images.add', $product) }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="primary_image">انتخاب تصویر اصلی</label>
                        <div class="custom-file">
                            <input type="file" name="primary_image" class="custom-file-input" id="primary_image">
                            <label class="custom-file-label" for="primary_image">انتخاب فایل</label>
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="primary_image">انتخاب تصویر</label>
                        <div class="custom-file">
                            <input type="file" name="images[]" multiple class="custom-file-input" id="images">
                            <label class="custom-file-label" for="images">انتخاب فایل ها</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-outline-primary mt-5">ویرایش</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>

            </form>


        </div>
    </div>
@endsection

@section('scripts')

    <script>
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
    </script>


@endsection
