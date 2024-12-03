@extends('admin.layouts.admin')

@section('title', 'edit banner')

@section('content')
    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="mb-4 text-center text-md-right">
                <h5 class="font-weight-bold">ویرایش بنر {{ $banner->title }}</h5>
            </div>
            <hr>
            @include('admin.sections.errors')
            <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row justify-content-center mb-4">
                    <div class="col-md-4">
                        <div class="card">
                            <img class="card-img-top" src="{{ generateBannerImageLink($banner->image) }}" alt="">
                        </div>
                    </div>
                </div>
                <div class="form-row">

                    <div class="form-group col-md-3">
                        <label for="bannerImage">انتخاب تصویر </label>
                        <div class="custom-file">
                            <input type="file" name="image" class="custom-file-input" id="bannerImage">
                            <label class="custom-file-label" for="bannerImage">انتخاب فایل</label>
                        </div>
                    </div>


                    <div class="form-group col-md-3">
                        <label for="title">عنوان</label>
                        <input type="text" id="title" name="title" class="form-control"
                            value="{{ $banner->title }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="text">متن</label>
                        <input type="text" id="text" name="text" class="form-control"
                            value="{{ $banner->text }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="priority">اولویت</label>
                        <input type="number" id="priority" name="priority" class="form-control"
                            value="{{ $banner->priority }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="is_active">وضعیت</label>
                        <select id="is_active" name="is_active" class="form-control">
                            <option value="1" {{ $banner->getRawOriginal('is_active') ? 'selected' : '' }} >فعال</option>
                            <option value="0" {{ $banner->getRawOriginal('is_active') ? '' : 'selected' }}>غیرفعال</option>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="type">نوع بنر</label>
                        <input type="text" id="type" name="type" class="form-control"
                            value="{{ $banner->type }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="button_text">متن دکمه</label>
                        <input type="text" id="button_text" name="button_text" class="form-control"
                            value="{{ $banner->button_text }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="button_link">لینک دکمه</label>
                        <input type="text" id="button_link" name="button_link" class="form-control"
                            value="{{ $banner->button_link }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="button_icon">ایکون دکمه</label>
                        <input type="text" id="button_icon" name="button_icon" class="form-control"
                            value="{{$banner->button_icon }}">
                    </div>

                </div>

                <button type="submit" class="btn btn-outline-primary mt-5">ثبت</button>
                <a href="{{ route('admin.banners.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Show file name for primary image
        $('#bannerImage').change(function() {
            var fileName = $(this).val();
            $(this).next('.custom-file-label').html(fileName);
        });
    </script>
@endsection
