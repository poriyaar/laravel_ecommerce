@extends('admin.layouts.admin')

@section('title', 'index users')

@section('content')
    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="d-flex flex-column text-center flex-md-row justify-content-md-between mb-4">
                <h5 class="font-weight-bold mb-3 mb-0">لیست کاربران ({{ $users->total() }})</h5>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-striped text-center">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>نام</th>
                            <th>ایمیل</th>
                            <th>شماره تلفن</th>
                            {{-- <th>عضویت از طریق</th> --}}
                            <th>عملیات</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($users as $key => $user)
                            <tr>
                                <th>
                                    {{ $users->firstItem() + $key }}
                                </th>
                                <th>
                                    {{ $user->name ?? 'کاربر' }}
                                </th>

                                <th>
                                    {{ $user->email }}
                                </th>

                                <th>
                                    {{ $user->cellphone ?? 'خالی' }}
                                </th>

                                <th>

                                    <a class="btn btn-sm btn-outline-info mr-3" href="{{ route('admin.users.edit', $user) }}">
                                        ویرایش</a>
                                </th>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $users->render() }}
            </div>

        </div>
    </div>
@endsection
