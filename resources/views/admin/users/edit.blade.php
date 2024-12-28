@extends('admin.layouts.admin')

@section('title', 'edit users')

@section('content')
    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
            <div class="mb-4">
                <h5 class="font-weight-bold">ویرایش ویژگی {{ $user->name }}</h5>
            </div>
            <hr>
            @include('admin.sections.errors')
            <form action="{{ route('admin.users.update', ['user' => $user->id]) }}" method="POST">
                @csrf
                @method('put')
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="name">نام</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="name">شماره تلفن همراه</label>
                        <input type="text" id="name" name="cellphone" class="form-control"
                            value="{{ $user->cellphone }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="role">نقش کاربر</label>
                        <select class="form-control" name="role" id="role">
                            <option></option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}"
                                    {{ in_array($role->id, $user->roles->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $role->display_name }}</option>
                            @endforeach

                        </select>
                    </div>

                </div>

                <button type="submit" class="btn btn-outline-primary mt-5">ویرایش</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>
    </div>
@endsection
