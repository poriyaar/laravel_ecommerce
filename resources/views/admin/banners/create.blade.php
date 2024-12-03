@extends('admin.layouts.admin')

@section('title', 'create banner')

@section('content')
    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="mb-4 text-center text-md-right">
                <h5 class="font-weight-bold">ایجاد بنر</h5>
            </div>
            <hr>
            @include('admin.sections.errors')
            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
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
                            value="{{ old('title') }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="text">متن</label>
                        <input type="text" id="text" name="text" class="form-control"
                            value="{{ old('text') }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="priority">اولویت</label>
                        <input type="number" id="priority" name="priority" class="form-control"
                            value="{{ old('priority') }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="is_active">وضعیت</label>
                        <select id="is_active" name="is_active" class="form-control">
                            <option value="1" selected>فعال</option>
                            <option value="0">غیرفعال</option>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="type">نوع بنر</label>
                        <input type="text" id="type" name="type" class="form-control"
                            value="{{ old('type') }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="button_text">متن دکمه</label>
                        <input type="text" id="button_text" name="button_text" class="form-control"
                            value="{{ old('button_text') }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="button_link">لینک دکمه</label>
                        <input type="text" id="button_link" name="button_link" class="form-control"
                            value="{{ old('button_link') }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="button_icon">ایکون دکمه</label>
                        <input type="text" id="button_icon" name="button_icon" class="form-control"
                            value="{{ old('button_icon') }}">
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
