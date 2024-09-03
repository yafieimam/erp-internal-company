<!DOCTYPE html>
<html>
	<head>
		@include('layouts.includes_id.head')

		@laravelPWA
	</head>
	
	<body>
		@include('layouts.includes_id.nav')

		@yield('content')

		@include('layouts.includes_id.footer')

		@include('layouts.includes_id.footer-scripts')
	</body>