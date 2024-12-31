@extends('admin.layouts.admin')

@section('title', 'edit roles')

@section('content')
    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
            <div class="mb-4">
                <h5 class="font-weight-bold">ویرایش نقش {{ $role->name }}</h5>
            </div>
            <hr>
            @include('admin.sections.errors')
            <form action="{{ route('admin.roles.update', ['role' => $role->id]) }}" method="POST">
                @csrf
                @method('put')
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="name">نام نمایشی</label>
                        <input type="text" name="display_name" class="form-control" value="{{ $role->display_name }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="name">نام</label>
                        <input type="text" name="name" class="form-control" value="{{ $role->name }}">
                    </div>


                    <div class="accordion col-md-12 mt-3" id="accordionPermission">
                        <div class="card">
                            <div class="card-header p-1" id="headingOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-right" type="button" data-toggle="collapse"
                                        data-target="#collapsPermission" aria-expanded="true" aria-controls="collapseOne">
                                        مجوز های دسترسی
                                    </button>
                                </h2>
                            </div>

                            <div id="collapsPermission" class="collapse show" aria-labelledby="headingOne"
                                data-parent="#accordionPermission">
                                <div class="card-body row">

                                    @foreach ($permissions as $permission)
                                        <div class="form-group form-check col-md-3">
                                            <input type="checkbox" class="form-check-input"
                                                id="permission-{{ $permission->id }}" name="{{ $permission->name }}"
                                                value="{{ $permission->name }}"
                                                    {{ in_array($permission->id , $rolePermissions) ? 'checked' : '' }}
                                                >
                                            <label class="form-check-label mr-3" for="permission-{{ $permission->id }}">
                                                {{ $permission->display_name }}</label>
                                        </div>
                                    @endforeach


                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                <button type="submit" class="btn btn-outline-primary mt-5">ویرایش</button>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>
    </div>
@endsection
