@extends('admin.layouts.admin')

@section('title', 'order show')

@section('content')
    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
            <div class="mb-4">
                <h5 class="font-weight-bold"> سفارش :{{ $order->id }}</h5>
            </div>
            <hr>

            <div class="row">
                <div class="form-group col-md-3">
                    <label>نام کاربر</label>
                    <input type="text" class="form-control" value="{{ $order->user->name ?? 'کاربر' }}" disabled>
                </div>

                <div class="form-group col-md-3">
                    <label>کد تخفیف</label>
                    <input type="text" class="form-control" value="{{ $order->coupon->name ?? 'خالی' }}" disabled>
                </div>

                <div class="form-group col-md-3">
                    <label>وضعیت</label>
                    <input type="text" class="form-control" value="{{ $order->status }}" disabled>
                </div>

                <div class="form-group col-md-3">
                    <label>مبلغ</label>
                    <input type="text" class="form-control" value="{{ $order->total_amount }}" disabled>
                </div>

                <div class="form-group col-md-3">
                    <label>هزینه ارسال</label>
                    <input type="text" class="form-control" value="{{ $order->delivery_amount }}" disabled>
                </div>

                <div class="form-group col-md-3">
                    <label>مبلغ کد تخفیف</label>
                    <input type="text" class="form-control" value="{{ $order->coupon_amount }}" disabled>
                </div>

                <div class="form-group col-md-3">
                    <label>مبلغ پرداختی</label>
                    <input type="text" class="form-control" value="{{ $order->paying_amount }}" disabled>
                </div>

                <div class="form-group col-md-3">
                    <label>نوع پرداخت</label>
                    <input type="text" class="form-control" value="{{ $order->payment_type }}" disabled>
                </div>

                <div class="form-group col-md-3">
                    <label>وضعیت پرداختی</label>
                    <input type="text" class="form-control" value="{{ $order->payment_status }}" disabled>
                </div>

                <div class="form-group col-md-3">
                    <label>تاریخ ایجاد</label>
                    <input type="text" class="form-control" value="{{ verta($order->created_at) }}" disabled>
                </div>

                <div class="form-group col-md-12">
                    <label>ادرس</label>
                    <textarea class="form-control" disabled>{{ $order->address->address }}</textarea>
                </div>

                <div class=" col-md-12">
                    <hr>
                    <h5>محصولات</h5>
                    <div class="table-responsive">

                        <table class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th> تصویر محصول </th>
                                    <th> نام محصول </th>
                                    <th> فی </th>
                                    <th> تعداد </th>
                                    <th> قیمت کل </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderItems as $item)
                                    <tr>
                                        <td class="product-thumbnail">
                                            <a href="{{ route('admin.products.show', $item->product) }}">
                                                <img width="70"
                                                    src="{{ generateProductImageLink($item->product->primary_image) }}"
                                                    alt="">
                                            </a>
                                        </td>
                                        <td class="product-name"><a
                                                href="{{ route('admin.products.show', $item->product) }}">
                                                {{ $item->product->name }} : {{ $item->productVariaction->value }}</a>
                                        </td>
                                        <td class="product-price-cart"><span class="amount">
                                                {{ number_format($item->price) }}
                                                تومان
                                            </span></td>
                                        <td class="product-quantity">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="product-subtotal">
                                            {{ number_format($item->subtotal) }}
                                            تومان
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <a href="{{ route('admin.orders.index') }}" class="btn btn-dark mt-5">بازگشت</a>
        </div>
    </div>
@endsection
