<!doctype html>
<html lang="en">

<head>
	
    @include('backend.includes.header')
    @include('backend.includes.css')

</head>

<body data-topbar="dark" data-sidebar="dark">
	<!--wrapper-->
	<div id="layout-wrapper">
        <!--start header -->
		@include('backend.includes.topbar')
		<!--end header -->
        
        <!-- Sidebar -->
        @include('backend.includes.sidebar')

		
        <div class="main-content">
            <!-- Body Content -->
           @yield('body-content')
           {{-- footer --}}
           @include('backend.includes.footer')
	    </div>
	</div>
     <!-- END layout-wrapper --> 
	<!--end wrapper-->
    
	<!-- Script File -->
    <!-- @include('backend.includes.rightsidebar') -->
    @include('backend.includes.script')
    
</body>
</html>