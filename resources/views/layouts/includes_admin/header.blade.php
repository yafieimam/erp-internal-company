<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ url('/homepage') }}" class="nav-link">Home</a>
    </li>
  </ul>

  <!-- SEARCH FORM -->
  <form class="form-inline ml-3">
    <div class="input-group input-group-sm">
      <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
      <div class="input-group-append">
        <button class="btn btn-navbar" type="submit">
          <i class="fas fa-search"></i>
        </button>
      </div>
    </div>
  </form>
  <?php
  $id_user = Session::get('id_user_admin');
  $user = App\ModelUser::select('id_user as id')->where('id_user', $id_user)->first();
  ?>
  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Notifications Dropdown Menu -->
    <li class="nav-item dropdown dropdown-notifications">
      <a class="nav-link" id="header-notif" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <?php
        if($user->unreadnotifications->count()){
        ?>
        <span class="badge badge-danger navbar-badge">{{ $user->unreadnotifications->count() }}</span>
        <?php
        }
        ?>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="max-width: none;">
        <span class="dropdown-item dropdown-header">{{ $user->unreadnotifications->count() }} New Notifications</span>
        <?php
        foreach ($user->unreadnotifications->where('type', 'App\Notifications\NotifNewCustomers') as $notification_cust) {
        ?>
        <div class="dropdown-divider"></div>
        <a href="{{ url('/sales/customers') }}" class="dropdown-item" style="text-align: center; background-color: #e9ecef;">
          {{ $notification_cust->data['message'] }}
        </a>
        <?php
        }
        foreach ($user->unreadnotifications->where('type', 'App\Notifications\NotifNewComplaint') as $notification_comp) {
        ?>
        <div class="dropdown-divider"></div>
        <a href="{{ url('/sales/complaint') }}" class="dropdown-item" style="text-align: center; background-color: #e9ecef;">
          {{ $notification_comp->data['message'] }}
        </a>
        <?php
        }
        foreach ($user->unreadnotifications->where('type', 'App\Notifications\NotifNewOrders') as $notification_ord) {
        ?>
        <div class="dropdown-divider"></div>
        <a href="{{ url('/sales/orders') }}" class="dropdown-item" style="text-align: center; background-color: #e9ecef;">
          {{ $notification_ord->data['message'] }}
        </a>
        <?php
        }
        foreach ($user->unreadnotifications->where('type', 'App\Notifications\NotifNewResume') as $notification_resume) {
        ?>
        <div class="dropdown-divider"></div>
        <a href="{{ url('/hrd/list_resume') }}" class="dropdown-item" style="text-align: center; background-color: #e9ecef;">
          {{ $notification_resume->data['message'] }}
        </a>
        <?php
        }
        foreach ($user->unreadnotifications->where('type', 'App\Notifications\NotifNewContactUs') as $notification_contact) {
        ?>
        <div class="dropdown-divider"></div>
        <a href="{{ url('/sales/list_message') }}" class="dropdown-item" style="text-align: center; background-color: #e9ecef;">
          {{ $notification_contact->data['message'] }}
        </a>
        <?php
        }
        ?>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i
            class="fas fa-th-large"></i></a>
    </li>
    <li class="nav-item">
      <a href="{{ url('/logoutEn') }}" class="nav-link">Logout</a>
    </li>
  </ul>
</nav>
<!-- /.navbar -->