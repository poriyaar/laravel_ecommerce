<!DOCTYPE html>
<html class="no-js" dir="trl">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>myWeb.net - @yield('title')</title>



    <!-- Custom styles for this template-->
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
    @yield('style')

    {!! SEO::generate() !!}
</head>

<body>


    <div class="wrapper text-center">


        <div id="overlayer"></div>
        <span class="loader">
            <span class="loader-inner"></span>
        </span>



        {{-- include header --}}
        @include('home.sections.header')

        {{-- include mobile Off Canvas --}}
        @include('home.sections.mobileOffCanvas')


        @yield('content')



        {{-- include footer --}}
        @include('home.sections.footer')





        <!-- JavaScript-->
        <script src="{{ asset('js/home/jquery-1.12.4.min.js') }}"></script>
        <script src="{{ asset('js/home/plugins.js') }}"></script>
        <script src="{{ asset('js/home.js') }}"></script>

        @include('sweetalert::alert')


        @yield('scripts')

        <script>
            $(window).load(function() {
                $(".loader").delay(2000).fadeOut("slow");
                $("#overlayer").delay(2000).fadeOut("slow")
            })
        </script>


        {!! GoogleReCaptchaV3::init() !!}

</body>

</html>
