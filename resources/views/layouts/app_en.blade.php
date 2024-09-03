<!DOCTYPE html>
<html>
	<head>
		@include('layouts.includes_en.head')

		@laravelPWA
	</head>
	
	<body>
		@include('layouts.includes_en.nav')

		@yield('content')

		@include('layouts.includes_en.footer')

		@include('layouts.includes_en.footer-scripts')
	</body>