<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="language" content="en">

<meta name="keywords" content="PT. DWI SELO GIRI MAS">
<meta name="description" content="PT. DWI SELO GIRI MAS">

<meta name="_token" content="{{ csrf_token() }}">
	
<link rel="Shortcut Icon" href="{{asset('app-assets/asset/images/favicon.png')}}">
<link rel="icon" type="image/ico" href="{{asset('app-assets/asset/images/favicon.png')}}">
<link rel="icon" type="image/x-icon" href="{{asset('app-assets/asset/images/favicon.png')}}">

<link rel="stylesheet" type="text/css" href="{{asset('app-assets/asset/css/screen.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/asset/css/comon.css')}}">

@yield('title')

<!-- Bootstrap -->
<link rel="stylesheet" href="{{asset('app-assets/asset/js/bootstrap-4.0.0/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('app-assets/asset/css/styles.css')}}"> 

<style type="text/css">
    section.home-sec-4 .box-section form button{
        cursor: pointer;
        background: 0 0;
        border: 0;
        padding: 9px 25px 9px 150px;
        color: #fff;
        text-transform: uppercase;
        font-size: 18px;
        font-family: Montserrat,sans-serif;
        letter-spacing: 1px;
        text-decoration: none;
    }
    .alert,
    .alert.alert-danger{
        font-size: 13px;
    }
    .alert ul,
    .alert.alert-danger ul{
        margin-bottom: 0;
    }
    
    .modal-dialog {
        min-height: calc(100vh - 60px);
        display: flex;
        flex-direction: column;
        justify-content: center;
        overflow: auto;
    }
    @media(max-width: 768px) {
      .modal-dialog {
        min-height: calc(100vh - 20px);
      }
    }

    @media only screen and (max-width: 768px) {
      /* For mobile phones: */
      .dropdown {
        width: 100%;
      }
      .dropdown .dropdown-content a {
        border-bottom: 1px solid #888;
      }
    }
</style>

            
<!-- Css -->
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/asset/css/styles.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/asset/css/style.deory.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/asset/css/pager.css')}}">
<!-- Css Responsive -->
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/asset/css/animate.css')}}">
    
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

@yield('css_login')