@extends('home.layouts.home')
@section('title', 'صفحه ورود')

@section('content')


    <div class="breadcrumb-area pt-35 pb-35 bg-gray" style="direction: rtl;">
        <div class="container">
            <div class="breadcrumb-content text-center">
                <ul>
                    <li>
                        <a href="{{ route('home.index') }}">صفحه ای اصلی</a>
                    </li>
                    <li class="active"> ورود </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="login-register-area pt-100 pb-100" style="direction: rtl;">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-12 ml-auto mr-auto">
                    <div class="login-register-wrapper">
                        <div class="login-register-tab-list nav">
                            <a class="active" data-toggle="tab" href="#lg1">
                                <h4> ورود </h4>
                            </a>
                        </div>
                        <div class="tab-content">
                            <div id="lg1" class="tab-pane active">
                                <div class="login-form-container">
                                    <div class="login-register-form">


                                        {{-- login form sms --}}
                                        <form id="loginForm">
                                            <input id="cellphoneInput" placeholder="شماره تلفن همراه" type="text">
                                            <div id="cellphoneInputError" class="input-error-validation">
                                                <strong id="cellphoneInputErrorText"></strong>
                                            </div>
                                            <div class="button-box d-flex justify-content-between">
                                                <button type="submit">ارسال</button>
                                            </div>
                                        </form>
                                        {{-- end login form sms --}}

                                        {{-- check otp form --}}
                                        <form id="checkOtpForm">
                                            <input id="OTPInput" placeholder="رمز یکبار مصرف" type="text">
                                            <div id="OTPInputError" class="input-error-validation">
                                                <strong id="OTPInputErrorText"></strong>
                                            </div>
                                            <div class="button-box d-flex justify-content-between">
                                                <button type="submit">ورود</button>
                                                <div>
                                                    <button id="resendOTP" type="submit">ارسال مجدد</button>
                                                    <span id="resendOTPtime"></span>
                                                </div>
                                            </div>
                                        </form>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('scripts')



    <script>
        let loginToken;

        $('#checkOtpForm').hide();

        $('#loginForm').submit(function(event) {
            event.preventDefault();

            $.post("{{ url('/loginotp') }}", {
                '_token': "{{ csrf_token() }}",
                'cellphone': $('#cellphoneInput').val()

            }, function(response, status) {

                loginToken = response.login_token;

                swal.fire({
                    timer: 20000,
                    text: 'رمز یکبار مصرف برای شما ارسال شد',
                    icon: 'success',
                    confirmButtonText: 'حله!',
                });

                $('#loginForm').fadeOut();
                $('#checkOtpForm').fadeIn();

                startCountdown(); // آغاز شمارش معکوس پس از ارسال موفق OTP

            }).fail(function(response) {
                $('#cellphoneInput').addClass('mb-1');
                $('#cellphoneInputError').fadeIn();
                $('#cellphoneInputErrorText').html(response.responseJSON.errors.cellphone[0]);
            });
        });

        $('#checkOtpForm').submit(function(event) {
            event.preventDefault();
            $.post("{{ url('/checkOTP') }}", {
                '_token': "{{ csrf_token() }}",
                'otp': $('#OTPInput').val(),
                'login_token': loginToken

            }, function(response, status) {
                console.log(response, status);
                $(location).attr('href', "{{ route('home.index') }}");
            }).fail(function(response) {
                $('#OTPInput').addClass('mb-1');
                $('#OTPInputError').fadeIn();
                $('#OTPInputErrorText').html(response.responseJSON.errors.otp[0]);
            });
        });

        $('#resendOTP').click(function(event) {
            event.preventDefault();

            $.post("{{ url('/resendOTP') }}", {
                '_token': "{{ csrf_token() }}",
                'login_token': loginToken

            }, function(response, status) {

                loginToken = response.login_token;

                swal.fire({
                    timer: 20000,
                    text: 'رمز یکبار مصرف برای شما ارسال شد',
                    icon: 'success',
                    confirmButtonText: 'حله!',
                });

                startCountdown();

            }).fail(function(response) {
                swal.fire({
                    timer: 20000,
                    text: 'خطایی پیش آمده دوباره امتحان کنید',
                    icon: 'error',
                    confirmButtonText: 'حله!',
                });
            });
        });

        let countdownTime = 120;
        let countdownInterval;

        function startCountdown() {
            countdownTime = 120;
            $('#resendOTP').hide();
            $('#resendOTPtime').show();

            clearInterval(countdownInterval);
            countdownInterval = setInterval(function() {
                if (countdownTime > 0) {
                    countdownTime--;
                    let minutes = Math.floor(countdownTime / 60);
                    let seconds = countdownTime % 60;

                    $('#resendOTPtime').text(`${minutes}:${seconds < 10 ? '0' : ''}${seconds}`);
                } else {
                    clearInterval(countdownInterval);
                    $('#resendOTPtime').hide();
                    $('#resendOTP').show();
                }
            }, 1000);
        }

        $(document).ready(function() {
            startCountdown();
        });
    </script>


@endsection
