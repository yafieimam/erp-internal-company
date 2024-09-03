<!DOCTYPE html>

<html lang="en">
<head>
  @include('layouts.includes_admin.head')

  @laravelPWA
</head>
<body class="hold-transition sidebar-mini layout-footer-fixed layout-navbar-fixed layout-fixed sidebar-collapse">
<div class="wrapper">

  @include('layouts.includes_admin.header')

  @include('layouts.includes_admin.sidebar')

  @yield('content_nav')

    <div class="content">
      <div class="container-fluid">
  		
  		@yield('content')
    
      </div>
    </div>
  </div>

  @yield('right_nav')

  @include('layouts.includes_admin.footer')

</div>
<!-- ./wrapper -->

  @include('layouts.includes_admin.footer-scripts')
</body>
</html>
