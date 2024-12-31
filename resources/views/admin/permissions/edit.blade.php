@extends('admin.layouts.admin')

@section('title', 'edit permissions')

@section('content')
    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
            <div class="mb-4">
                <h5 class="font-weight-bold">ویرایش مجوز {{ $permission->name }}</h5>
            </div>
            <hr>
            @include('admin.sections.errors')
            <form action="{{ route('admin.permissions.update', ['permission' => $permission->id]) }}" method="POST">
                @csrf
                @method('put')
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="name">نام نمایشی</label>
                        <input type="text" name="display_name" class="form-control"
                            value="{{ $permission->display_name }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="name">نام</label>
                        <input type="text" name="name" class="form-control" value="{{ $permission->name }}">
                    </div>

                </div>

                <button type="submit" class="btn btn-outline-primary mt-5">ویرایش</button>
                <a href="{{ route('admin.permissions.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>
    </div>
@endsection
