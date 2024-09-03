@extends('layouts.app_admin')

@section('title')
<title>EDIT PASSWORD - PT. DWI SELO GIRI MAS</title>
@endsection

@section('css_login')
<style type="text/css">
  @media only screen and (max-width: 768px) {
    /* For mobile phones: */
    [class*="col-"] {
      flex: none !important; 
      max-width: 100% !important;
    }
  }
</style>
@endsection

@section('content_nav')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Edit Password</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/homepage') }}">Home</a></li>
            <li class="breadcrumb-item">Settings</li>
            <li class="breadcrumb-item">Edit Password</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  @endsection

  @section('content')
  <div class="row"> 
    <div class="col-3">
    </div>
    <div class="col-6">
      <div class="card">
        <form method="post" action="{{ url('/editPassword') }}" class="password-form" id="password-form">
          {{ csrf_field() }}
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="old_password">Old Password</label>
                  <input class="form-control" type="password" name="old_password" id="old_password" placeholder="Old Password" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="new_password">New Password</label>
                  <input class="form-control" type="password" name="new_password" id="new_password" placeholder="New Password" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="confirm_pass">Confirm New Password</label>
                  <input class="form-control" type="password" name="confirm_pass" id="confirm_pass" placeholder="Confirm New Password" />
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary float-right">Submit</button>
          </div>
        </form>
      </div>
    </div>
    <div class="col-3">
    </div>
  </div>
  @endsection

  @section('right_nav')
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->
  @endsection

  @section('script_login')
  <script src="https://code.jquery.com/jquery.js"></script>
  <script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <script src="{{asset('lte/plugins/jquery-validation/jquery.validate.min.js')}}"></script>

  <script>
    var msg = '{{ Session::get('alert') }}';
    var exist = '{{ Session::has('alert') }}';
    if(exist){
      alert(msg);
    }
  </script>

  <script type="text/javascript">
    $(document).ready(function () {
      $('#password-form').validate({
        rules: {
          old_password: {
            required: true,
          },
          new_password: {
            required: true,
          },
          confirm_pass: {
            required: true,
            equalTo: "#new_password",
          },
        },
        messages: {
          old_password: {
            required: "Old Password is Required",
          },
          new_password: {
            required: "New Password is Required",
          },
          confirm_pass: {
            required: "Confim New Password is Required",
            equalTo: "Confirm Password Harus Sama Dengan New Password",
          },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        }
      });
    });
  </script>
@endsection
