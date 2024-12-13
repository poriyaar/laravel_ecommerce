@extends('admin.layouts.admin')

@section('title', 'comment show')

@section('content')
    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
            <div class="mb-4">
                <h5 class="font-weight-bold"> نظر :{{ $comment->user->name ?? $comment->user->cellphone }}</h5>
            </div>
            <hr>

            <div class="row">
                <div class="form-group col-md-3">
                    <label>نام کاربر</label>
                    <input type="text" class="form-control" value="{{ $comment->user->name ?? $comment->user->cellphone }}"
                        disabled>
                </div>

                <div class="form-group col-md-3">
                    <label>نام محصول</label>
                    <input type="text" class="form-control" value="{{ $comment->product->name }}" disabled>
                </div>

                <div class="form-group col-md-3">
                    <label>وضعیت</label>
                    <input type="text"
                        class="form-control {{ $comment->getRawOriginal('approved') ? 'text-success' : 'text-danger' }}"
                        value="{{ $comment->approved }}" disabled>
                </div>

                <div class="form-group col-md-3">
                    <label>تاریخ ایجاد</label>
                    <input type="text" class="form-control" value="{{ verta($comment->created_at) }}" disabled>
                </div>

                <div class="form-group col-md-12">
                    <label>متن نظر</label>
                    <textarea class="form-control" disabled>{{ $comment->text }}</textarea>
                </div>


            </div>

            <a href="{{ route('admin.comments.index') }}" class="btn btn-dark mt-5">بازگشت</a>
            @if ($comment->getRawOriginal('approved'))
                <a href="{{ route('admin.comment.change.status', $comment) }}" class="btn btn-danger mt-5">عدم تایید</a>
            @else
                <a href="{{ route('admin.comment.change.status', $comment) }}" class="btn btn-success mt-5">تایید</a>
            @endif

        </div>
    </div>
@endsection
