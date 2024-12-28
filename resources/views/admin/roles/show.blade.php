@extends('admin.layouts.admin')

@section('title', 'role show')

@section('content')
    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
            <div class="mb-4">
                <h5 class="font-weight-bold"> نقش :{{ $role->display_name }}</h5>
            </div>
            <hr>

            <div class="row">
                <div class="form-group col-md-3">
                    <label>نام نمایشی</label>
                    <input type="text" class="form-control" value="{{ $role->display_name }}" disabled>
                </div>

                <div class="form-group col-md-3">
                    <label>نام</label>
                    <input type="text" class="form-control" value="{{ $role->name }}" disabled>
                </div>


                <div class="form-group col-md-3">
                    <label>تاریخ ایجاد</label>
                    <input type="text" class="form-control" value="{{ verta($role->created_at) }}" disabled>
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
                                    <div class="col-md-1">
                                        <span>{{ $permission->display_name }}</span>
                                    </div>
                                @endforeach


                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <a href="{{ route('admin.roles.index') }}" class="btn btn-dark mt-5">بازگشت</a>
        </div>
    </div>
@endsection
