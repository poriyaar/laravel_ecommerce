@extends('admin.layouts.admin')

@section('title', 'edit brand')

@section('content')
    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
            <div class="mb-4">
                <h5 class="font-weight-bold">ویرایش برند {{ $brand->name }}</h5>
            </div>
            <hr>
            @include('admin.sections.errors')
            <form action="{{ route('admin.brands.update', ['brand' => $brand->id]) }}" method="POST">
                @csrf
                @method('put')
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="name">نام</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ $brand->name }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="is_active">وضعیت</label>
                        <select id="is_active" name="is_active" class="form-control">
                            <option value="1" {{ $brand->getRawOriginal('is_active') == 1 ? 'selected' : '' }}>فعال</option>
                            <option value="0" {{ $brand->getRawOriginal('is_active') == 0 ? 'selected' : '' }}>غیرفعال
                            </option>
                        </select>
                    </div>

                </div>

                <button type="submit" class="btn btn-outline-primary mt-5">ثبت</button>
                <a href="{{ route('admin.brands.index') }}" class="btn btn-dark mt-5 mr-3">ویرایش</a>
            </form>
        </div>
    </div>
@endsection
