<!--favicon-->
@php
    $favIcon = \App\Models\Settings::shop_fav();
@endphp
@if(!is_null($favIcon))
    <link rel="icon" href="{{ asset($favIcon->fav_icon) }}" type="image/png" />
@endif

<!-- jquery.vectormap css -->
<link href="{{ asset('backend/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />
<!-- Bootstrap Css -->
<link href="{{ asset('backend/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ asset('backend/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{ asset('backend/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<link href="{{ asset('backend/css/custom.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<!-- Sweet Alert-->
<link href="{{asset('backend/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
@yield('page-css')