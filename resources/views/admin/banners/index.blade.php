@extends('admin.layouts.admin')

@section('title', 'index banners')

@section('content')
    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="d-flex flex-column text-center flex-md-row justify-content-between mb-4">
                <h5 class="font-weight-bold">لیست بنر ها ({{ $banners->total() }})</h5>

                <a class="btn btn-outline-primary" href="{{ route('admin.banners.create') }}">

                    <i class="fa fa-plus">ایجاد بنر</i>

                </a>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-striped text-center">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>تصویر</th>
                            <th>عنوان</th>
                            <th>متن</th>
                            <th>اولویت</th>
                            <th>وضعیت</th>
                            <th>نوع</th>
                            <th>دکمه متن</th>
                            <th>لینک دکمه</th>
                            <th>ایکون دکمه</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($banners as $key => $banner)
                            <tr>
                                <th>
                                    {{ $banners->firstItem() + $key }}
                                </th>
                                <th>
                                    <a href="{{ url(env('BANNER_IMAGES_UPLOAD_PATH') . $banner->image) }}"
                                        target="_blank">{{ $banner->image }}</a>
                                    {{ $banner->name }}
                                </th>

                                <th>
                                    {{ $banner->title }}
                                </th>
                                <th>
                                    {{ $banner->text }}
                                </th>

                                <th>
                                    {{ $banner->priority }}
                                </th>

                                <th>
                                    <span
                                        class="{{ $banner->getOriginal('is_active') ? 'text-success' : 'text-danger' }}">
                                        {{ $banner->Active }}
                                    </span>

                                    {{ $banner->full_name }}
                                </th>

                                <th>
                                    {{ $banner->type }}
                                </th>
                                <th>
                                    {{ $banner->button_text }}
                                </th>
                                <th>
                                    {{ $banner->button_link }}
                                </th>
                                <th>
                                    {{ $banner->button_icon }}
                                </th>

                                <th>
                                    <form action="{{ route('admin.banners.destroy', ['banner' => $banner->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-outline-danger mr-3" >حذف</button>
                                    </form>
                                    <a class="btn btn-sm btn-outline-info mr-3 mt-2"
                                        href="{{ route('admin.banners.edit', ['banner' => $banner->id]) }}"> ویرایش</a>
                                </th>
                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $banners->links() }}
            </div>

        </div>

    </div>
@endsection
