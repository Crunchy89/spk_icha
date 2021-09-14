<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link href="{{asset('assets/img/favicon.ico')}}" rel="icon">
    <link href="{{asset('assets/img/apple-touch-icon.png')}}" rel="apple-touch-icon">
    <link href="{{asset('assets/vendor/coreui/dist/css/coreui.min.css')}}" rel="stylesheet" />
    {{-- <link href="{{asset('assets/vendor/coreui/dist/css/coreui.min.css')}}" rel="stylesheet" /> --}}
    <link href="{{asset('assets/plugins/toastr/toastr.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets')}}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css" rel="stylesheet" />
    <link href="{{asset('assets/vendor/icons/css/all.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets')}}/plugins/datatables-bs4/css/dataTables.bootstrap4.css" rel="stylesheet" />
    <script src="{{asset('assets')}}/plugins/jquery/jquery.min.js"></script>


</head>

<body class="c-app flex-row align-items-center">
    @yield('body')
    <script src="{{asset('assets')}}/plugins/datatables/jquery.dataTables.js"></script>
    <script src="{{asset('assets')}}/plugins/select2/js/select2.min.js"></script>
    <script src="{{asset('assets')}}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{asset('assets/vendor/coreui/dist/js/coreui.bundle.min.js')}}"></script>
    <script src="{{ asset('assets') }}/plugins/toastr/toastr.min.js"></script>
    <script src="{{ asset('assets/js/axios.js') }}"></script>
    <script>
        $(document).ready(function() {
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": true,
                "progressBar": false,
                "positionClass": "toast-top-center",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        })
    </script>
    @yield('script')
    <script src="{{ asset('assets/js/admin.js') }}"></script>
</body>

</html>
