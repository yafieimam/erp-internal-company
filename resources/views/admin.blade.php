@extends('layouts.app_admin')

@section('title')
<title>HOME - PT. DWI SELO GIRI MAS</title>
@endsection

@section('css_login')
<style type="text/css">
  .progress-bar {
    -webkit-transition: none !important;
    transition: none !important;
    font-size: 20px;
  }

  @media only screen and (max-width: 768px) {
    /* For mobile phones: */
    [class*="col-"] {
      flex: none !important; 
      max-width: 100% !important;
    }
    .lihat-table {
      overflow-x: auto;
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
          <h1 class="m-0 text-dark">Home Page</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">Home</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  @endsection

  @section('content')

  <div class="row">

  <?php
  if(Session::get('login_admin')){
    if(Session::get('tipe_user') != 11 && Session::get('tipe_user') != 12 && Session::get('tipe_user') != 13 && Session::get('tipe_user') != 14 && Session::get('tipe_user') != 15 && Session::get('tipe_user') != 17 && Session::get('tipe_user') != 19){
  ?>
 
  @foreach($reminder as $pengingat) 
    <div class="col-md-4">
      <div class="card card-danger" id="reminder-color">
        <div class="card-header">
          <h3 class="card-title">Reminder Notification</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
          <div style="font-size: 20px; font-weight: 900;" align="center">{{ strtoupper($pengingat->perihal) }}</div>
          <table  width="100%">
            <tr>&nbsp</tr>
            <tr>
              <td style="width: 15%;">Customers</td>
              <td align="center">:</td>
              <td>{{ $pengingat->nama }}</td>
            </tr>
            <tr>
              <td style="vertical-align: top;">Keterangan</td>
              <td style="vertical-align: top;" align="center">:</td>
              @if($pengingat->keterangan == null || $pengingat->keterangan == '')
              <td>--</td>
              @else
              <td>{{ $pengingat->keterangan }}</td>
              @endif
            </tr>
            <tr>
              <td colspan="3">&nbsp</td>
            </tr>
            <tr style="font-size: 20px; font-weight: 900; text-align: center;">
              <td colspan="3">Tgl : {{ date('j F Y', strtotime($pengingat->tanggal_schedule)) }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  @endforeach
  @foreach($reminder_project_danger as $rem_project)
    <div class="col-md-4">
      <div class="card card-danger" id="reminder-color">
        <div class="card-header">
          <h3 class="card-title">Reminder Project</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
          <div style="font-size: 20px; font-weight: 900;" align="center">{{ strtoupper($rem_project->title_name) }}</div>
          <table  width="100%">
            <tr>&nbsp</tr>
            <tr>
              <td style="text-align: center;">{{ $rem_project->deskripsi }}</td>
            </tr>
            <tr>
              <td>&nbsp</td>
            </tr>
            <tr style="font-size: 20px; font-weight: 900; text-align: center;">
              <td>Deadline : {{ date('j F Y', strtotime($rem_project->deadline_date)) }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  @endforeach
  @foreach($reminder_project_warning as $rem_project)
    <div class="col-md-4">
      <div class="card card-warning" id="reminder-color">
        <div class="card-header">
          <h3 class="card-title">Reminder Project</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
          <div style="font-size: 20px; font-weight: 900;" align="center">{{ strtoupper($rem_project->title_name) }}</div>
          <table  width="100%">
            <tr>&nbsp</tr>
            <tr>
              <td style="text-align: center;">{{ $rem_project->deskripsi }}</td>
            </tr>
            <tr>
              <td>&nbsp</td>
            </tr>
            <tr style="font-size: 20px; font-weight: 900; text-align: center;">
              <td>Deadline : {{ date('j F Y', strtotime($rem_project->deadline_date)) }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  @endforeach
  @foreach($reminder_complaint as $rem_complaint)
    <div class="col-md-6">
      <div class="card card-danger" id="reminder-color">
        <div class="card-header">
          <h3 class="card-title">Reminder Notification</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
          <div style="font-size: 20px; font-weight: 900;" align="center">COMPLAINT NOMOR {{ $rem_complaint->nomor_complaint }} <br>TIDAK DIPROSES LEBIH DARI 2 HARI</div>
          <table  width="100%">
            <tr>&nbsp</tr>
            <tr>
              <td style="width: 24%;">Customers</td>
              <td align="center">:</td>
              <td>{{ $rem_complaint->nama_customer }}</td>
            </tr>
            <tr>
              <td>Divisi Yang Terlibat</td>
              <td align="center">:</td>
              <td>{{ $rem_complaint->divisi }}</td>
            </tr>
            <tr>
              <td>Dibuat Tanggal</td>
              <td align="center">:</td>
              <td>{{ date('j F Y', strtotime($rem_complaint->tanggal_complaint)) }}</td>
            </tr>
            <tr>
              <td>Terakhir Update</td>
              <td align="center">:</td>
              <td>{{ date('j F Y - H:i', strtotime($rem_complaint->updated_at)) }} WIB</td>
            </tr>
            <tr>
              <td>Status Terakhir</td>
              <td align="center">:</td>
              <td>{{ $rem_complaint->status }}</td>
            </tr>

            @if(Session::get('tipe_user') == 3)
            <tr>
              <td colspan="3" style="text-align: right;"><a href="{{ url('produksi/complaint/' . Illuminate\Support\Facades\Crypt::encrypt($rem_complaint->nomor_complaint) . '/' . Illuminate\Support\Facades\Crypt::encrypt(4)) }}">Lihat Complaint >></a></td>
            </tr>
            @elseif(Session::get('tipe_user') == 6)
            <tr>
              <td colspan="3" style="text-align: right;"><a href="{{ url('logistik/complaint/' . Illuminate\Support\Facades\Crypt::encrypt($rem_complaint->nomor_complaint) . '/' . Illuminate\Support\Facades\Crypt::encrypt(5)) }}">Lihat Complaint >></a></td>
            </tr>
            @elseif(Session::get('tipe_user') == 2)
              @if($rem_complaint->no_div_sales == 1)
              <tr>
                <td colspan="3" style="text-align: right;"><a href="{{ url('sales/complaint/' . Illuminate\Support\Facades\Crypt::encrypt($rem_complaint->nomor_complaint) . '/' . Illuminate\Support\Facades\Crypt::encrypt(6)) }}">Lihat Complaint >></a></td>
              </tr>
              @else
              <tr>
                <td colspan="3" style="text-align: right;"><a href="{{ url('sales/complaint/' . Illuminate\Support\Facades\Crypt::encrypt($rem_complaint->nomor_complaint) . '/' . Illuminate\Support\Facades\Crypt::encrypt(10)) }}">Lihat Complaint >></a></td>
              </tr>
              @endif
            @elseif(Session::get('tipe_user') == 7)
            <tr>
              <td colspan="3" style="text-align: right;"><a href="{{ url('timbangan/complaint/' . Illuminate\Support\Facades\Crypt::encrypt($rem_complaint->nomor_complaint) . '/' . Illuminate\Support\Facades\Crypt::encrypt(7)) }}">Lihat Complaint >></a></td>
            </tr>
            @elseif(Session::get('tipe_user') == 8)
            <tr>
              <td colspan="3" style="text-align: right;"><a href="{{ url('warehouse/complaint/' . Illuminate\Support\Facades\Crypt::encrypt($rem_complaint->nomor_complaint) . '/' . Illuminate\Support\Facades\Crypt::encrypt(8)) }}">Lihat Complaint >></a></td>
            </tr>
            @elseif(Session::get('tipe_user') == 9)
            <tr>
              <td colspan="3" style="text-align: right;"><a href="{{ url('lab/complaint/' . Illuminate\Support\Facades\Crypt::encrypt($rem_complaint->nomor_complaint) . '/' . Illuminate\Support\Facades\Crypt::encrypt(9)) }}">Lihat Complaint >></a></td>
            </tr>
            @endif
          </table>
        </div>
      </div>
    </div>
  @endforeach
  <?php
    }
  }
  ?>

  <?php
  if(Session::get('login_admin')){
    if(Session::get('tipe_user') != 11 && Session::get('tipe_user') != 12 && Session::get('tipe_user') != 13 && Session::get('tipe_user') != 14 && Session::get('tipe_user') != 15 && Session::get('tipe_user') != 17 && Session::get('tipe_user') != 19){
  ?>
  @if($reminder->isEmpty() && $reminder_complaint->isEmpty() && $reminder_project_warning->isEmpty() && $reminder_project_danger->isEmpty())
  <div class="col-md-12">
    <div class="card card-success" id="reminder-color">
      <div class="card-header">
        <h3 class="card-title">Reminder Notification</h3>

        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
        </div>
      </div>
      <div class="card-body">
        <p align="center">You Have No Activities In The Near Future</p>
      </div>
    </div>
  </div>
  @endif
  <?php
    }
  }
  ?>

  </div>

  <?php
  if(Session::get('login_admin')){
    if(Session::get('tipe_user') == 19){
  ?>
  <div class="row"> 
    <div class="col-md-12">
      <div class="card card-primary">
        <div class="card-header">
          @if(Session::get('tipe_user') == 1)
          <h3 class="card-title">Olivia Project</h3>
          @else
          <h3 class="card-title">My Project</h3>
          @endif
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            @foreach($reminder_project_danger as $rem_project)
            <div class="col-md-4">
              <div class="card card-danger" id="reminder-color">
                <div class="card-header">
                  <h3 class="card-title">Reminder Project</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div style="font-size: 20px; font-weight: 900;" align="center">{{ strtoupper($rem_project->title_name) }}</div>
                  <table width="100%">
                    <tr>&nbsp</tr>
                    <tr>
                      <td style="text-align: center;">{{ $rem_project->deskripsi }}</td>
                    </tr>
                    <tr>
                      <td>&nbsp</td>
                    </tr>
                    <tr style="font-size: 20px; font-weight: 900; text-align: center;">
                      <td>Deadline : {{ date('j F Y', strtotime($rem_project->deadline_date)) }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            @endforeach
            @foreach($reminder_project_warning as $rem_project)
            <div class="col-md-4">
              <div class="card card-warning" id="reminder-color">
                <div class="card-header">
                  <h3 class="card-title">Reminder Project</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div style="font-size: 20px; font-weight: 900;" align="center">{{ strtoupper($rem_project->title_name) }}</div>
                  <table width="100%">
                    <tr>&nbsp</tr>
                    <tr>
                      <td style="text-align: center;">{{ $rem_project->deskripsi }}</td>
                    </tr>
                    <tr>
                      <td>&nbsp</td>
                    </tr>
                    <tr style="font-size: 20px; font-weight: 900; text-align: center;">
                      <td>Deadline : {{ date('j F Y', strtotime($rem_project->deadline_date)) }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row"> 
    <div class="col-md-12">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Sales Project</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            @foreach($reminder_project_danger_sales as $rem_project)
            <div class="col-md-4">
              <div class="card card-danger" id="reminder-color">
                <div class="card-header">
                  <h3 class="card-title">Reminder Project</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div style="font-size: 20px; font-weight: 900;" align="center">{{ strtoupper($rem_project->title_name) }}</div>
                  <table  width="100%">
                    <tr>&nbsp</tr>
                    <tr>
                      <td style="text-align: center;">{{ $rem_project->deskripsi }}</td>
                    </tr>
                    <tr>
                      <td>&nbsp</td>
                    </tr>
                    <tr style="font-size: 20px; font-weight: 900; text-align: center;">
                      <td>Deadline : {{ date('j F Y', strtotime($rem_project->deadline_date)) }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            @endforeach
            @foreach($reminder_project_warning_sales as $rem_project)
            <div class="col-md-4">
              <div class="card card-warning" id="reminder-color">
                <div class="card-header">
                  <h3 class="card-title">Reminder Project</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div style="font-size: 20px; font-weight: 900;" align="center">{{ strtoupper($rem_project->title_name) }}</div>
                  <table  width="100%">
                    <tr>&nbsp</tr>
                    <tr>
                      <td style="text-align: center;">{{ $rem_project->deskripsi }}</td>
                    </tr>
                    <tr>
                      <td>&nbsp</td>
                    </tr>
                    <tr style="font-size: 20px; font-weight: 900; text-align: center;">
                      <td>Deadline : {{ date('j F Y', strtotime($rem_project->deadline_date)) }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row"> 
    <div class="col-md-12">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Produksi Project</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            @foreach($reminder_project_danger_produksi as $rem_project)
            <div class="col-md-4">
              <div class="card card-danger" id="reminder-color">
                <div class="card-header">
                  <h3 class="card-title">Reminder Project</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div style="font-size: 20px; font-weight: 900;" align="center">{{ strtoupper($rem_project->title_name) }}</div>
                  <table  width="100%">
                    <tr>&nbsp</tr>
                    <tr>
                      <td style="text-align: center;">{{ $rem_project->deskripsi }}</td>
                    </tr>
                    <tr>
                      <td>&nbsp</td>
                    </tr>
                    <tr style="font-size: 20px; font-weight: 900; text-align: center;">
                      <td>Deadline : {{ date('j F Y', strtotime($rem_project->deadline_date)) }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            @endforeach
            @foreach($reminder_project_warning_produksi as $rem_project)
            <div class="col-md-4">
              <div class="card card-warning" id="reminder-color">
                <div class="card-header">
                  <h3 class="card-title">Reminder Project</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div style="font-size: 20px; font-weight: 900;" align="center">{{ strtoupper($rem_project->title_name) }}</div>
                  <table  width="100%">
                    <tr>&nbsp</tr>
                    <tr>
                      <td style="text-align: center;">{{ $rem_project->deskripsi }}</td>
                    </tr>
                    <tr>
                      <td>&nbsp</td>
                    </tr>
                    <tr style="font-size: 20px; font-weight: 900; text-align: center;">
                      <td>Deadline : {{ date('j F Y', strtotime($rem_project->deadline_date)) }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row"> 
    <div class="col-md-12">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">HRD Project</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            @foreach($reminder_project_danger_hrd as $rem_project)
            <div class="col-md-4">
              <div class="card card-danger" id="reminder-color">
                <div class="card-header">
                  <h3 class="card-title">Reminder Project</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div style="font-size: 20px; font-weight: 900;" align="center">{{ strtoupper($rem_project->title_name) }}</div>
                  <table  width="100%">
                    <tr>&nbsp</tr>
                    <tr>
                      <td style="text-align: center;">{{ $rem_project->deskripsi }}</td>
                    </tr>
                    <tr>
                      <td>&nbsp</td>
                    </tr>
                    <tr style="font-size: 20px; font-weight: 900; text-align: center;">
                      <td>Deadline : {{ date('j F Y', strtotime($rem_project->deadline_date)) }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            @endforeach
            @foreach($reminder_project_warning_hrd as $rem_project)
            <div class="col-md-4">
              <div class="card card-warning" id="reminder-color">
                <div class="card-header">
                  <h3 class="card-title">Reminder Project</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div style="font-size: 20px; font-weight: 900;" align="center">{{ strtoupper($rem_project->title_name) }}</div>
                  <table  width="100%">
                    <tr>&nbsp</tr>
                    <tr>
                      <td style="text-align: center;">{{ $rem_project->deskripsi }}</td>
                    </tr>
                    <tr>
                      <td>&nbsp</td>
                    </tr>
                    <tr style="font-size: 20px; font-weight: 900; text-align: center;">
                      <td>Deadline : {{ date('j F Y', strtotime($rem_project->deadline_date)) }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row"> 
    <div class="col-md-12">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Logistik Project</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            @foreach($reminder_project_danger_logistik as $rem_project)
            <div class="col-md-4">
              <div class="card card-danger" id="reminder-color">
                <div class="card-header">
                  <h3 class="card-title">Reminder Project</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div style="font-size: 20px; font-weight: 900;" align="center">{{ strtoupper($rem_project->title_name) }}</div>
                  <table  width="100%">
                    <tr>&nbsp</tr>
                    <tr>
                      <td style="text-align: center;">{{ $rem_project->deskripsi }}</td>
                    </tr>
                    <tr>
                      <td>&nbsp</td>
                    </tr>
                    <tr style="font-size: 20px; font-weight: 900; text-align: center;">
                      <td>Deadline : {{ date('j F Y', strtotime($rem_project->deadline_date)) }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            @endforeach
            @foreach($reminder_project_warning_logistik as $rem_project)
            <div class="col-md-4">
              <div class="card card-warning" id="reminder-color">
                <div class="card-header">
                  <h3 class="card-title">Reminder Project</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div style="font-size: 20px; font-weight: 900;" align="center">{{ strtoupper($rem_project->title_name) }}</div>
                  <table  width="100%">
                    <tr>&nbsp</tr>
                    <tr>
                      <td style="text-align: center;">{{ $rem_project->deskripsi }}</td>
                    </tr>
                    <tr>
                      <td>&nbsp</td>
                    </tr>
                    <tr style="font-size: 20px; font-weight: 900; text-align: center;">
                      <td>Deadline : {{ date('j F Y', strtotime($rem_project->deadline_date)) }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row"> 
    <div class="col-md-12">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Timbangan Project</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            @foreach($reminder_project_danger_timbangan as $rem_project)
            <div class="col-md-4">
              <div class="card card-danger" id="reminder-color">
                <div class="card-header">
                  <h3 class="card-title">Reminder Project</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div style="font-size: 20px; font-weight: 900;" align="center">{{ strtoupper($rem_project->title_name) }}</div>
                  <table  width="100%">
                    <tr>&nbsp</tr>
                    <tr>
                      <td style="text-align: center;">{{ $rem_project->deskripsi }}</td>
                    </tr>
                    <tr>
                      <td>&nbsp</td>
                    </tr>
                    <tr style="font-size: 20px; font-weight: 900; text-align: center;">
                      <td>Deadline : {{ date('j F Y', strtotime($rem_project->deadline_date)) }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            @endforeach
            @foreach($reminder_project_warning_timbangan as $rem_project)
            <div class="col-md-4">
              <div class="card card-warning" id="reminder-color">
                <div class="card-header">
                  <h3 class="card-title">Reminder Project</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div style="font-size: 20px; font-weight: 900;" align="center">{{ strtoupper($rem_project->title_name) }}</div>
                  <table  width="100%">
                    <tr>&nbsp</tr>
                    <tr>
                      <td style="text-align: center;">{{ $rem_project->deskripsi }}</td>
                    </tr>
                    <tr>
                      <td>&nbsp</td>
                    </tr>
                    <tr style="font-size: 20px; font-weight: 900; text-align: center;">
                      <td>Deadline : {{ date('j F Y', strtotime($rem_project->deadline_date)) }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row"> 
    <div class="col-md-12">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Warehouse Project</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            @foreach($reminder_project_danger_warehouse as $rem_project)
            <div class="col-md-4">
              <div class="card card-danger" id="reminder-color">
                <div class="card-header">
                  <h3 class="card-title">Reminder Project</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div style="font-size: 20px; font-weight: 900;" align="center">{{ strtoupper($rem_project->title_name) }}</div>
                  <table  width="100%">
                    <tr>&nbsp</tr>
                    <tr>
                      <td style="text-align: center;">{{ $rem_project->deskripsi }}</td>
                    </tr>
                    <tr>
                      <td>&nbsp</td>
                    </tr>
                    <tr style="font-size: 20px; font-weight: 900; text-align: center;">
                      <td>Deadline : {{ date('j F Y', strtotime($rem_project->deadline_date)) }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            @endforeach
            @foreach($reminder_project_warning_warehouse as $rem_project)
            <div class="col-md-4">
              <div class="card card-warning" id="reminder-color">
                <div class="card-header">
                  <h3 class="card-title">Reminder Project</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div style="font-size: 20px; font-weight: 900;" align="center">{{ strtoupper($rem_project->title_name) }}</div>
                  <table  width="100%">
                    <tr>&nbsp</tr>
                    <tr>
                      <td style="text-align: center;">{{ $rem_project->deskripsi }}</td>
                    </tr>
                    <tr>
                      <td>&nbsp</td>
                    </tr>
                    <tr style="font-size: 20px; font-weight: 900; text-align: center;">
                      <td>Deadline : {{ date('j F Y', strtotime($rem_project->deadline_date)) }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row"> 
    <div class="col-md-12">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Lab Project</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            @foreach($reminder_project_danger_lab as $rem_project)
            <div class="col-md-4">
              <div class="card card-danger" id="reminder-color">
                <div class="card-header">
                  <h3 class="card-title">Reminder Project</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div style="font-size: 20px; font-weight: 900;" align="center">{{ strtoupper($rem_project->title_name) }}</div>
                  <table  width="100%">
                    <tr>&nbsp</tr>
                    <tr>
                      <td style="text-align: center;">{{ $rem_project->deskripsi }}</td>
                    </tr>
                    <tr>
                      <td>&nbsp</td>
                    </tr>
                    <tr style="font-size: 20px; font-weight: 900; text-align: center;">
                      <td>Deadline : {{ date('j F Y', strtotime($rem_project->deadline_date)) }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            @endforeach
            @foreach($reminder_project_warning_lab as $rem_project)
            <div class="col-md-4">
              <div class="card card-warning" id="reminder-color">
                <div class="card-header">
                  <h3 class="card-title">Reminder Project</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div style="font-size: 20px; font-weight: 900;" align="center">{{ strtoupper($rem_project->title_name) }}</div>
                  <table  width="100%">
                    <tr>&nbsp</tr>
                    <tr>
                      <td style="text-align: center;">{{ $rem_project->deskripsi }}</td>
                    </tr>
                    <tr>
                      <td>&nbsp</td>
                    </tr>
                    <tr style="font-size: 20px; font-weight: 900; text-align: center;">
                      <td>Deadline : {{ date('j F Y', strtotime($rem_project->deadline_date)) }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row"> 
    <div class="col-md-12">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Teknik Project</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            @foreach($reminder_project_danger_teknik as $rem_project)
            <div class="col-md-4">
              <div class="card card-danger" id="reminder-color">
                <div class="card-header">
                  <h3 class="card-title">Reminder Project</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div style="font-size: 20px; font-weight: 900;" align="center">{{ strtoupper($rem_project->title_name) }}</div>
                  <table  width="100%">
                    <tr>&nbsp</tr>
                    <tr>
                      <td style="text-align: center;">{{ $rem_project->deskripsi }}</td>
                    </tr>
                    <tr>
                      <td>&nbsp</td>
                    </tr>
                    <tr style="font-size: 20px; font-weight: 900; text-align: center;">
                      <td>Deadline : {{ date('j F Y', strtotime($rem_project->deadline_date)) }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            @endforeach
            @foreach($reminder_project_warning_teknik as $rem_project)
            <div class="col-md-4">
              <div class="card card-warning" id="reminder-color">
                <div class="card-header">
                  <h3 class="card-title">Reminder Project</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div style="font-size: 20px; font-weight: 900;" align="center">{{ strtoupper($rem_project->title_name) }}</div>
                  <table  width="100%">
                    <tr>&nbsp</tr>
                    <tr>
                      <td style="text-align: center;">{{ $rem_project->deskripsi }}</td>
                    </tr>
                    <tr>
                      <td>&nbsp</td>
                    </tr>
                    <tr style="font-size: 20px; font-weight: 900; text-align: center;">
                      <td>Deadline : {{ date('j F Y', strtotime($rem_project->deadline_date)) }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row"> 
    <div class="col-md-12">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Raw Material Project</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            @foreach($reminder_project_danger_raw_material as $rem_project)
            <div class="col-md-4">
              <div class="card card-danger" id="reminder-color">
                <div class="card-header">
                  <h3 class="card-title">Reminder Project</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div style="font-size: 20px; font-weight: 900;" align="center">{{ strtoupper($rem_project->title_name) }}</div>
                  <table  width="100%">
                    <tr>&nbsp</tr>
                    <tr>
                      <td style="text-align: center;">{{ $rem_project->deskripsi }}</td>
                    </tr>
                    <tr>
                      <td>&nbsp</td>
                    </tr>
                    <tr style="font-size: 20px; font-weight: 900; text-align: center;">
                      <td>Deadline : {{ date('j F Y', strtotime($rem_project->deadline_date)) }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            @endforeach
            @foreach($reminder_project_warning_raw_material as $rem_project)
            <div class="col-md-4">
              <div class="card card-warning" id="reminder-color">
                <div class="card-header">
                  <h3 class="card-title">Reminder Project</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div style="font-size: 20px; font-weight: 900;" align="center">{{ strtoupper($rem_project->title_name) }}</div>
                  <table  width="100%">
                    <tr>&nbsp</tr>
                    <tr>
                      <td style="text-align: center;">{{ $rem_project->deskripsi }}</td>
                    </tr>
                    <tr>
                      <td>&nbsp</td>
                    </tr>
                    <tr style="font-size: 20px; font-weight: 900; text-align: center;">
                      <td>Deadline : {{ date('j F Y', strtotime($rem_project->deadline_date)) }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
    }
  }
  ?>

  <?php
  if(Session::get('login_admin')){
    if(Session::get('tipe_user') == 1 || Session::get('tipe_user') == 2){
  ?>
  <div class="row"> 
    <div class="col-md-12">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Sales</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
          <div class="row"> 
            <div class="col-md-12">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title">Target Omset Bulan {{ date('F') }}</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="progress" style="height:40px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated progress-bar-sales bg-success" role="progressbar" style="width: 0%; color: black !important;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="isi_progress_bar_sales"></div>
                  </div>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title">Omset Value (Bar Chart)</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-8"></div>
                    <div class="col-4">
                      <div class="form-group">
                        <select id="tahun_omset_value_sales" name="tahun_omset_value_sales" class="form-control">
                          <option value="">Pilih</option>
                          <option value="1" selected>1 Tahun</option>
                          <option value="2">2 Tahun</option>
                          <option value="3">3 Tahun</option>
                          <option value="4">4 Tahun</option>
                          <option value="5">5 Tahun</option>
                          <!-- <option value="6">6 Tahun</option>
                          <option value="7">7 Tahun</option>
                          <option value="8">8 Tahun</option>
                          <option value="9">9 Tahun</option>
                          <option value="10">10 Tahun</option> -->
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="chart" id="tempat-chart-omset-sales">
                    <canvas id="chartOmsetValue" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                  </div>
                  <div class="chart" id="tempat-chart-omset-dpp-sales">
                    <canvas id="chartOmsetDPP" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                  </div>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title">Omset Value (Line Chart)</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-8"></div>
                    <div class="col-4">
                      <div class="form-group">
                        <select id="tahun_omset_value_line_sales" name="tahun_omset_value_line_sales" class="form-control">
                          <option value="">Pilih</option>
                          <option value="1" selected>1 Tahun</option>
                          <option value="2">2 Tahun</option>
                          <option value="3">3 Tahun</option>
                          <option value="4">4 Tahun</option>
                          <option value="5">5 Tahun</option>
                          <!-- <option value="6">6 Tahun</option>
                          <option value="7">7 Tahun</option>
                          <option value="8">8 Tahun</option>
                          <option value="9">9 Tahun</option>
                          <option value="10">10 Tahun</option> -->
                        </select>
                      </div>
                    </div>
                  </div>
                  <div style="width: 100%; overflow-x: auto;overflow-y:hidden">
                    <div class="chart" id="tempat-chart-omset-sales-line" style="min-height: 250px; height: 250px; max-height: 250px; width: 4000px;">
                      <canvas id="chartOmsetValueLine" style="min-height: 250px; height: 250px; max-height: 250px; width: 0;"></canvas>
                    </div>
                  </div>
                  <div style="width: 100%; overflow-x: auto;overflow-y:hidden">
                    <div class="chart" id="tempat-chart-omset-dpp-sales-line" style="min-height: 250px; height: 250px; max-height: 250px; width: 4000px;">
                      <canvas id="chartOmsetDPPLine" style="min-height: 250px; height: 250px; max-height: 250px; width: 0;"></canvas>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
          </div>
          <div class="row"> 
            <div class="col-md-12">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title">Omset Tonase</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-8"></div>
                    <div class="col-4">
                      <div class="form-group">
                        <select id="tahun_omset_tonase_sales" name="tahun_omset_tonase_sales" class="form-control">
                          <option value="">Pilih</option>
                          <option value="1" selected>1 Tahun</option>
                          <option value="2">2 Tahun</option>
                          <option value="3">3 Tahun</option>
                          <option value="4">4 Tahun</option>
                          <option value="5">5 Tahun</option>
                          <!-- <option value="6">6 Tahun</option>
                          <option value="7">7 Tahun</option>
                          <option value="8">8 Tahun</option>
                          <option value="9">9 Tahun</option>
                          <option value="10">10 Tahun</option> -->
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="chart" id="tempat-chart-tonase-sales">
                    <canvas id="chartOmsetTonase" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                  </div>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
          </div>
          <div class="row"> 
            <div class="col-md-12">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title">Transaksi</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-8"></div>
                    <div class="col-4">
                      <div class="form-group">
                        <select id="tahun_transaksi_sales" name="tahun_transaksi_sales" class="form-control">
                          <option value="">Pilih</option>
                          <option value="1" selected>1 Tahun</option>
                          <option value="2">2 Tahun</option>
                          <option value="3">3 Tahun</option>
                          <option value="4">4 Tahun</option>
                          <option value="5">5 Tahun</option>
                          <!-- <option value="6">6 Tahun</option>
                          <option value="7">7 Tahun</option>
                          <option value="8">8 Tahun</option>
                          <option value="9">9 Tahun</option>
                          <option value="10">10 Tahun</option> -->
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="chart" id="tempat-chart-transaksi-sales">
                    <canvas id="chartTransaksi" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                  </div>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
          </div>
          <div class="row"> 
            <div class="col-md-12">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title">Complaint</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-8"></div>
                    <div class="col-4">
                      <div class="form-group">
                        <select id="tahun_complaint_sales" name="tahun_complaint_sales" class="form-control">
                          <option value="">Pilih</option>
                          <option value="1" selected>1 Tahun</option>
                          <option value="2">2 Tahun</option>
                          <option value="3">3 Tahun</option>
                          <option value="4">4 Tahun</option>
                          <option value="5">5 Tahun</option>
                          <!-- <option value="6">6 Tahun</option>
                          <option value="7">7 Tahun</option>
                          <option value="8">8 Tahun</option>
                          <option value="9">9 Tahun</option>
                          <option value="10">10 Tahun</option> -->
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="chart" id="tempat-chart-complaint-sales">
                    <canvas id="chartComplaint" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                  </div>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
    }
    if(Session::get('tipe_user') == 1 || Session::get('tipe_user') == 3){
  ?>
  <div class="row"> 
    <div class="col-md-12">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Produksi</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
          <div class="row"> 
            <div class="col-md-12">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title" id="title-target-produksi"></h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="progress" style="height:40px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated progress-bar-produksi bg-success" role="progressbar" style="width: 0%; color: black !important;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="isi_progress_bar_produksi"></div>
                  </div>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title">Rencana Produksi Hari Ini</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body lihat-table">
                  <table id="rencana_produksi_hari_ini" width="100%" style="font-size: 16px;">
                    <tr>
                      <td style="width: 12%;">SA</td>
                      <td>:</td>
                      <td id="td_sa_rencana"></td>
                    </tr>
                    <tr>
                      <td style="width: 10%;">SB</td>
                      <td>:</td>
                      <td id="td_sb_rencana"></td>
                    </tr>
                    <tr>
                      <td style="width: 10%;">Mixer</td>
                      <td>:</td>
                      <td id="td_mixer_rencana"></td>
                    </tr>
                    <tr>
                      <td style="width: 10%;">RA</td>
                      <td>:</td>
                      <td id="td_ra_rencana"></td>
                    </tr>
                    <tr>
                      <td style="width: 10%;">RB</td>
                      <td>:</td>
                      <td id="td_rb_rencana"></td>
                    </tr>
                    <tr>
                      <td style="width: 10%;">RC</td>
                      <td>:</td>
                      <td id="td_rc_rencana"></td>
                    </tr>
                    <tr>
                      <td style="width: 10%;">RD</td>
                      <td>:</td>
                      <td id="td_rd_rencana"></td>
                    </tr>
                    <tr>
                      <td style="width: 10%;">RE</td>
                      <td>:</td>
                      <td id="td_re_rencana"></td>
                    </tr>
                    <tr>
                      <td style="width: 10%;">RF</td>
                      <td>:</td>
                      <td id="td_rf_rencana"></td>
                    </tr>
                    <tr>
                      <td style="width: 10%;">Coating</td>
                      <td>:</td>
                      <td id="td_coating_rencana"></td>
                    </tr>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <div class="col-md-6">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title">List Produk Kurang</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <table id="list_produk_kurang_table" width="100%" style="font-size: 11px;" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style="vertical-align: top; text-align: center;">Jenis Produk</th>
                        <th style="vertical-align: top; text-align: center;">Jumlah Kurang (Sak)</th>
                      </tr>
                    </thead>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title">Rekap Produksi Mingguan</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body lihat-table">
                  <table id="rekap_produksi_mingguan_table" style="width: 100%; font-size: 11px;" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style="vertical-align: top; text-align: center;">Mesin</th>
                        <th style="vertical-align: top; text-align: center;" id="th_total">Total</th>
                        <th style="vertical-align: top; text-align: center;">Rata-rata</th>
                      </tr>
                    </thead>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title">Total Produksi</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-8"></div>
                    <div class="col-4">
                      <div class="form-group">
                        <select id="tahun_total_produksi" name="tahun_total_produksi" class="form-control">
                          <option value="">Pilih</option>
                          <option value="1" selected>1 Tahun</option>
                          <option value="2">2 Tahun</option>
                          <option value="3">3 Tahun</option>
                          <option value="4">4 Tahun</option>
                          <option value="5">5 Tahun</option>
                          <!-- <option value="6">6 Tahun</option>
                          <option value="7">7 Tahun</option>
                          <option value="8">8 Tahun</option>
                          <option value="9">9 Tahun</option>
                          <option value="10">10 Tahun</option> -->
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="chart" id="tempat-chart-total-produksi">
                    <canvas id="chartTotalProduksi" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                  </div>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title">Rata-rata Produksi per-Hari</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-8"></div>
                    <div class="col-4">
                      <div class="form-group">
                        <select id="tahun_rata_produksi" name="tahun_rata_produksi" class="form-control">
                          <option value="">Pilih</option>
                          <option value="1" selected>1 Tahun</option>
                          <option value="2">2 Tahun</option>
                          <option value="3">3 Tahun</option>
                          <option value="4">4 Tahun</option>
                          <option value="5">5 Tahun</option>
                          <!-- <option value="6">6 Tahun</option>
                          <option value="7">7 Tahun</option>
                          <option value="8">8 Tahun</option>
                          <option value="9">9 Tahun</option>
                          <option value="10">10 Tahun</option> -->
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="chart" id="tempat-chart-rata-produksi">
                    <canvas id="chartRataProduksi" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                  </div>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
    }
  }
  ?>
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
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-progressbar/0.9.0/bootstrap-progressbar.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>

  <!-- <script src="{{asset('lte/plugins/chart.js/Chart.min.js')}}"></script> -->
  <script type="text/javascript">
    $(document).ready(function() {
      var session = "{{ Session::get('tipe_user') }}";

      if(session == 1 || session == 3){
        var count_weeks_prd = "{{ (date('W') - date('W', strtotime(date('Y-m-01'))) + 1) }}";
        var tgl = "{{ date('Y-m-d') }}";

        if(count_weeks_prd == 1 || count_weeks_prd == 2){
          $('#title-target-produksi').html('Persentase Realisasi Produksi Minggu 1 dan 2, Bulan {{ date("F") }}');
          var url_prd = "{{ url('get_total_produksi_one') }}";
          $.get(url_prd, function (data) {
            if(data[0].total_produksi == null || data[0].total_produksi == 0 || data[0].total_produksi == ''){
              $(".progress-bar-produksi").animate({
                width: "0%"
              }, 1000);
              document.getElementById("isi_progress_bar_produksi").innerHTML = "0 %, Produksi : 0 TON";
            }else{
              var progress = data[0].total_produksi;
              var progress = (progress / 1750000) * 100;
              var persen = Math.round(progress) + "%";
              $(".progress-bar-produksi").animate({
                width: persen
              }, 1000);
              document.getElementById("isi_progress_bar_produksi").innerHTML = persen + ", Produksi : " + (data[0].total_produksi / 1000).toLocaleString('id-ID') + " TON";
            }
          });
        }else if(count_weeks_prd == 3 && tgl < "{{ date('Y-m-15') }}"){
          $('#title-target-produksi').html('Target Omset Minggu 1 dan 2, Bulan {{ date("F") }}');
          var url_prd = "{{ url('get_total_produksi_one') }}";
          $.get(url_prd, function (data) {
            if(data[0].total_produksi == null || data[0].total_produksi == 0 || data[0].total_produksi == ''){
              $(".progress-bar-produksi").animate({
                width: "0%"
              }, 1000);
              document.getElementById("isi_progress_bar_produksi").innerHTML = "0 %, Produksi : 0 TON";
            }else{
              var progress = data[0].total_produksi;
              var progress = (progress / 1750000) * 100;
              var persen = Math.round(progress) + "%";
              $(".progress-bar-produksi").animate({
                width: persen
              }, 1000);
              document.getElementById("isi_progress_bar_produksi").innerHTML = persen + ", Produksi : " + (data[0].total_produksi / 1000).toLocaleString('id-ID') + " TON";
            }
          });
        }else if(count_weeks_prd == 3 && tgl >= "{{ date('Y-m-15') }}"){
          $('#title-target-produksi').html('Target Omset Minggu 3 dan 4, Bulan {{ date("F") }}');
          var url_prd = "{{ url('get_total_produksi_two') }}";
          $.get(url_prd, function (data) {
            if(data[0].total_produksi == null || data[0].total_produksi == 0 || data[0].total_produksi == ''){
              $(".progress-bar-produksi").animate({
                width: "0%"
              }, 1000);
              document.getElementById("isi_progress_bar_produksi").innerHTML = "0 %, Produksi : 0 TON";
            }else{
              var progress = data[0].total_produksi;
              var progress = (progress / 1750000) * 100;
              var persen = Math.round(progress) + "%";
              $(".progress-bar-produksi").animate({
                width: persen
              }, 1000);
              document.getElementById("isi_progress_bar_produksi").innerHTML = persen + ", Produksi : " + (data[0].total_produksi / 1000).toLocaleString('id-ID') + " TON";
            }
          });
        }else if(count_weeks_prd == 4 || count_weeks_prd == 5 || count_weeks_prd == 6){
          $('#title-target-produksi').html('Target Omset Minggu 3 dan 4, Bulan {{ date("F") }}');
          var url_prd = "{{ url('get_total_produksi_two') }}";
          $.get(url_prd, function (data) {
            if(data[0].total_produksi == null || data[0].total_produksi == 0 || data[0].total_produksi == ''){
              $(".progress-bar-produksi").animate({
                width: "0%"
              }, 1000);
              document.getElementById("isi_progress_bar_produksi").innerHTML = "0 %, Produksi : 0 TON";
            }else{
              var progress = data[0].total_produksi;
              var progress = (progress / 1750000) * 100;
              var persen = Math.round(progress) + "%";
              $(".progress-bar-produksi").animate({
                width: persen
              }, 1000);
              document.getElementById("isi_progress_bar_produksi").innerHTML = persen + ", Produksi : " + (data[0].total_produksi / 1000).toLocaleString('id-ID') + " TON";
            }
          });
        }

        var url_rencana_hariini = "{{ url('get_rencana_produksi_hari_ini') }}";
        $.get(url_rencana_hariini, function (data) {
          if(data.length == 0){
            $('#rencana_produksi_hari_ini').html('<tr><td colspan="2" style="vertical-align: top; text-align: center;">Tidak Ada Data</td></tr>');
          }else{
            data.forEach(functionRencana);

            function functionRencana(item) {
              if(item.no_mesin == 1){
                $('#rencana_produksi_hari_ini tbody #td_sa_rencana').append('<td>' + item.jenis_produk + ' (' + item.jumlah_sak + ' Sak); </td>');
              }else if(item.no_mesin == 2){
                $('#rencana_produksi_hari_ini tbody #td_sb_rencana').append('<td>' + item.jenis_produk + ' (' + item.jumlah_sak + ' Sak); </td>');
              }else if(item.no_mesin == 3){
                $('#rencana_produksi_hari_ini tbody #td_mixer_rencana').append('<td>' + item.jenis_produk + ' (' + item.jumlah_sak + ' Sak); </td>');
              }else if(item.no_mesin == 4){
                $('#rencana_produksi_hari_ini tbody #td_ra_rencana').append('<td>' + item.jenis_produk + ' (' + item.jumlah_sak + ' Sak); </td>');
              }else if(item.no_mesin == 5){
                $('#rencana_produksi_hari_ini tbody #td_rb_rencana').append('<td>' + item.jenis_produk + ' (' + item.jumlah_sak + ' Sak); </td>');
              }else if(item.no_mesin == 6){
                $('#rencana_produksi_hari_ini tbody #td_rc_rencana').append('<td>' + item.jenis_produk + ' (' + item.jumlah_sak + ' Sak); </td>');
              }else if(item.no_mesin == 7){
                $('#rencana_produksi_hari_ini tbody #td_rd_rencana').append('<td>' + item.jenis_produk + ' (' + item.jumlah_sak + ' Sak); </td>');
              }else if(item.no_mesin == 8){
                $('#rencana_produksi_hari_ini tbody #td_re_rencana').append('<td>' + item.jenis_produk + ' (' + item.jumlah_sak + ' Sak); </td>');
              }else if(item.no_mesin == 9){
                $('#rencana_produksi_hari_ini tbody #td_rf_rencana').append('<td>' + item.jenis_produk + ' (' + item.jumlah_sak + ' Sak); </td>');
              }else if(item.no_mesin == 10){
                $('#rencana_produksi_hari_ini tbody #td_rg_rencana').append('<td>' + item.jenis_produk + ' (' + item.jumlah_sak + ' Sak); </td>');
              }else if(item.no_mesin == 11){
                $('#rencana_produksi_hari_ini tbody #td_coating_rencana').append('<td>' + item.jenis_produk + ' (' + item.jumlah_sak + ' Sak); </td>');
              }
            }
          }
        })

        var url_produk_kurang = "{{ url('get_list_produk_kurang') }}";
        $.get(url_produk_kurang, function (data) {
          if(data.length == 0){
            $('#list_produk_kurang_table').html('<tr><td colspan="2" style="vertical-align: top; text-align: center;">Tidak Ada Data</td></tr>');
          }else{
            data.forEach(functionProdukKurang);

            function functionProdukKurang(item) {
              $('#list_produk_kurang_table').append(
                '<tr>'+
                '<td style="vertical-align: top; text-align: center;">' + item.jenis_produk + '</td>'+
                '<td style="vertical-align: top; text-align: center;">' + Math.abs(item.saldo) + '</td>'+
                '</tr>'
              );
            }
          }
        })

        function weeks(month) {
          month = moment(month, 'YYYY-MM-DD');
          var first = month.day() == 0 ? 6 : month.day()-1;
          var day = 7-first;
          var last = month.daysInMonth();
          var count = (last-day)/7;
          var weeks = [];
          weeks.push([1, day]);
          for (var i=0; i < count; i++) {
            weeks.push([(day+1), (Math.min(day+=7, last))]);
          }
          return weeks;
        }

        var week_date = [];
        week_date = weeks(moment().format('YYYY-MM-01'));

        for(var i = 0; i < week_date.length; i++){
          var newrow = $('<th style="vertical-align: top; text-align: center;">' + week_date[i][0] + ' - ' + week_date[i][1] + ' ' + moment().format('MMMM') + '</th>');
          $('#th_total').before(newrow);
        }

        var url_rekap = "{{ url('get_rekap_produksi') }}";
        $.get(url_rekap, function (data) {
          $('#rekap_produksi_mingguan_table').append('<tbody>');
          $('#rekap_produksi_mingguan_table').append('<tr id="tr_sa">');
          var total_sa = 0;
          for (var key in data.sa[0]) {
            if(data.sa[0].hasOwnProperty(key)) {
              if(data.sa[0][key] == null){
                data.sa[0][key] = 0;
              }
              if(!isNaN(data.sa[0][key])){
                total_sa += parseInt(data.sa[0][key]);
              }
              $('#rekap_produksi_mingguan_table tbody #tr_sa').append('<td style="vertical-align: top; text-align: center;">' + data.sa[0][key] + '</td>');
            }
          }
          $('#rekap_produksi_mingguan_table tbody #tr_sa').append('<td style="vertical-align: top; text-align: center;">' + total_sa + '</td>');
          $('#rekap_produksi_mingguan_table tbody #tr_sa').append('<td style="vertical-align: top; text-align: center;">' + (total_sa / 26).toFixed(2) + '</td>');
          $('#rekap_produksi_mingguan_table').append('</tr>');
          $('#rekap_produksi_mingguan_table').append('<tr id="tr_sb">');
          var total_sb = 0;
          for (var key in data.sb[0]) {
            if(data.sb[0].hasOwnProperty(key)) {
              if(data.sb[0][key] == null){
                data.sb[0][key] = 0;
              }
              if(!isNaN(data.sb[0][key])){
                total_sb += parseInt(data.sb[0][key]);
              }
              $('#rekap_produksi_mingguan_table tbody #tr_sb').append('<td style="vertical-align: top; text-align: center;">' + data.sb[0][key] + '</td>');
            }
          }
          $('#rekap_produksi_mingguan_table tbody #tr_sb').append('<td style="vertical-align: top; text-align: center;">' + total_sb + '</td>');
          $('#rekap_produksi_mingguan_table tbody #tr_sb').append('<td style="vertical-align: top; text-align: center;">' + (total_sb / 26).toFixed(2) + '</td>');
          $('#rekap_produksi_mingguan_table').append('</tr>');
          $('#rekap_produksi_mingguan_table').append('<tr id="tr_mixer">');
          var total_mixer = 0;
          for (var key in data.mixer[0]) {
            if(data.mixer[0].hasOwnProperty(key)) {
              if(data.mixer[0][key] == null){
                data.mixer[0][key] = 0;
              }
              if(!isNaN(data.mixer[0][key])){
                total_mixer += parseInt(data.mixer[0][key]);
              }
              $('#rekap_produksi_mingguan_table tbody #tr_mixer').append('<td style="vertical-align: top; text-align: center;">' + data.mixer[0][key] + '</td>');
            }
          }
          $('#rekap_produksi_mingguan_table tbody #tr_mixer').append('<td style="vertical-align: top; text-align: center;">' + total_mixer + '</td>');
          $('#rekap_produksi_mingguan_table tbody #tr_mixer').append('<td style="vertical-align: top; text-align: center;">' + (total_mixer / 26).toFixed(2) + '</td>');
          $('#rekap_produksi_mingguan_table').append('</tr>');
          $('#rekap_produksi_mingguan_table').append('<tr id="tr_ra">');
          var total_ra = 0;
          for (var key in data.ra[0]) {
            if(data.ra[0].hasOwnProperty(key)) {
              if(data.ra[0][key] == null){
                data.ra[0][key] = 0;
              }
              if(!isNaN(data.ra[0][key])){
                total_ra += parseInt(data.ra[0][key]);
              }
              $('#rekap_produksi_mingguan_table tbody #tr_ra').append('<td style="vertical-align: top; text-align: center;">' + data.ra[0][key] + '</td>');
            }
          }
          $('#rekap_produksi_mingguan_table tbody #tr_ra').append('<td style="vertical-align: top; text-align: center;">' + total_ra + '</td>');
          $('#rekap_produksi_mingguan_table tbody #tr_ra').append('<td style="vertical-align: top; text-align: center;">' + (total_ra / 26).toFixed(2) + '</td>');
          $('#rekap_produksi_mingguan_table').append('</tr>');
          $('#rekap_produksi_mingguan_table').append('<tr id="tr_rb">');
          var total_rb = 0;
          for (var key in data.rb[0]) {
            if(data.rb[0].hasOwnProperty(key)) {
              if(data.rb[0][key] == null){
                data.rb[0][key] = 0;
              }
              if(!isNaN(data.rb[0][key])){
                total_rb += parseInt(data.rb[0][key]);
              }
              $('#rekap_produksi_mingguan_table tbody #tr_rb').append('<td style="vertical-align: top; text-align: center;">' + data.rb[0][key] + '</td>');
            }
          }
          $('#rekap_produksi_mingguan_table tbody #tr_rb').append('<td style="vertical-align: top; text-align: center;">' + total_rb + '</td>');
          $('#rekap_produksi_mingguan_table tbody #tr_rb').append('<td style="vertical-align: top; text-align: center;">' + (total_rb / 26).toFixed(2) + '</td>');
          $('#rekap_produksi_mingguan_table').append('</tr>');
          $('#rekap_produksi_mingguan_table').append('<tr id="tr_rc">');
          var total_rc = 0;
          for (var key in data.rc[0]) {
            if(data.rc[0].hasOwnProperty(key)) {
              if(data.rc[0][key] == null){
                data.rc[0][key] = 0;
              }
              if(!isNaN(data.rc[0][key])){
                total_rc += parseInt(data.rc[0][key]);
              }
              $('#rekap_produksi_mingguan_table tbody #tr_rc').append('<td style="vertical-align: top; text-align: center;">' + data.rc[0][key] + '</td>');
            }
          }
          $('#rekap_produksi_mingguan_table tbody #tr_rc').append('<td style="vertical-align: top; text-align: center;">' + total_rc + '</td>');
          $('#rekap_produksi_mingguan_table tbody #tr_rc').append('<td style="vertical-align: top; text-align: center;">' + (total_rc / 26).toFixed(2) + '</td>');
          $('#rekap_produksi_mingguan_table').append('</tr>');
          $('#rekap_produksi_mingguan_table').append('<tr id="tr_rd">');
          var total_rd = 0;
          for (var key in data.rd[0]) {
            if(data.rd[0].hasOwnProperty(key)) {
              if(data.rd[0][key] == null){
                data.rd[0][key] = 0;
              }
              if(!isNaN(data.rd[0][key])){
                total_rd += parseInt(data.rd[0][key]);
              }
              $('#rekap_produksi_mingguan_table tbody #tr_rd').append('<td style="vertical-align: top; text-align: center;">' + data.rd[0][key] + '</td>');
            }
          }
          $('#rekap_produksi_mingguan_table tbody #tr_rd').append('<td style="vertical-align: top; text-align: center;">' + total_rd + '</td>');
          $('#rekap_produksi_mingguan_table tbody #tr_rd').append('<td style="vertical-align: top; text-align: center;">' + (total_rd / 26).toFixed(2) + '</td>');
          $('#rekap_produksi_mingguan_table').append('</tr>');
          $('#rekap_produksi_mingguan_table').append('<tr id="tr_re">');
          var total_re = 0;
          for (var key in data.re[0]) {
            if(data.re[0].hasOwnProperty(key)) {
              if(data.re[0][key] == null){
                data.re[0][key] = 0;
              }
              if(!isNaN(data.re[0][key])){
                total_re += parseInt(data.re[0][key]);
              }
              $('#rekap_produksi_mingguan_table tbody #tr_re').append('<td style="vertical-align: top; text-align: center;">' + data.re[0][key] + '</td>');
            }
          }
          $('#rekap_produksi_mingguan_table tbody #tr_re').append('<td style="vertical-align: top; text-align: center;">' + total_re + '</td>');
          $('#rekap_produksi_mingguan_table tbody #tr_re').append('<td style="vertical-align: top; text-align: center;">' + (total_re / 26).toFixed(2) + '</td>');
          $('#rekap_produksi_mingguan_table').append('</tr>');
          $('#rekap_produksi_mingguan_table').append('<tr id="tr_rf">');
          var total_rf = 0;
          for (var key in data.rf[0]) {
            if(data.rf[0].hasOwnProperty(key)) {
              if(data.rf[0][key] == null){
                data.rf[0][key] = 0;
              }
              if(!isNaN(data.rf[0][key])){
                total_rf += parseInt(data.rf[0][key]);
              }
              $('#rekap_produksi_mingguan_table tbody #tr_rf').append('<td style="vertical-align: top; text-align: center;">' + data.rf[0][key] + '</td>');
            }
          }
          $('#rekap_produksi_mingguan_table tbody #tr_rf').append('<td style="vertical-align: top; text-align: center;">' + total_rf + '</td>');
          $('#rekap_produksi_mingguan_table tbody #tr_rf').append('<td style="vertical-align: top; text-align: center;">' + (total_rf / 26).toFixed(2) + '</td>');
          $('#rekap_produksi_mingguan_table').append('</tr>');
          $('#rekap_produksi_mingguan_table').append('<tr id="tr_rg">');
          var total_rg = 0;
          for (var key in data.rg[0]) {
            if(data.rg[0].hasOwnProperty(key)) {
              if(data.rg[0][key] == null){
                data.rg[0][key] = 0;
              }
              if(!isNaN(data.rg[0][key])){
                total_rg += parseInt(data.rg[0][key]);
              }
              $('#rekap_produksi_mingguan_table tbody #tr_rg').append('<td style="vertical-align: top; text-align: center;">' + data.rg[0][key] + '</td>');
            }
          }
          $('#rekap_produksi_mingguan_table tbody #tr_rg').append('<td style="vertical-align: top; text-align: center;">' + total_rg + '</td>');
          $('#rekap_produksi_mingguan_table tbody #tr_rg').append('<td style="vertical-align: top; text-align: center;">' + (total_rg / 26).toFixed(2) + '</td>');
          $('#rekap_produksi_mingguan_table').append('</tr>');
          $('#rekap_produksi_mingguan_table').append('<tr id="tr_coating">');
          var total_coating = 0;
          for (var key in data.coating[0]) {
            if(data.coating[0].hasOwnProperty(key)) {
              if(data.coating[0][key] == null){
                data.coating[0][key] = 0;
              }
              if(!isNaN(data.coating[0][key])){
                total_coating += parseInt(data.coating[0][key]);
              }
              $('#rekap_produksi_mingguan_table tbody #tr_coating').append('<td style="vertical-align: top; text-align: center;">' + data.coating[0][key] + '</td>');
            }
          }
          $('#rekap_produksi_mingguan_table tbody #tr_coating').append('<td style="vertical-align: top; text-align: center;">' + total_coating + '</td>');
          $('#rekap_produksi_mingguan_table tbody #tr_coating').append('<td style="vertical-align: top; text-align: center;">' + (total_coating / 26).toFixed(2) + '</td>');
          $('#rekap_produksi_mingguan_table').append('</tr>');
          $('#rekap_produksi_mingguan_table').append('<tr id="tr_total">');
          var total_total = 0;
          for (var key in data.total[0]) {
            if(data.total[0].hasOwnProperty(key)) {
              if(data.total[0][key] == null){
                data.total[0][key] = 0;
              }
              if(!isNaN(data.total[0][key])){
                total_total += parseInt(data.total[0][key]);
              }
              $('#rekap_produksi_mingguan_table tbody #tr_total').append('<td style="vertical-align: top; text-align: center;">' + data.total[0][key] + '</td>');
            }
          }
          $('#rekap_produksi_mingguan_table tbody #tr_total').append('<td style="vertical-align: top; text-align: center;">' + total_total + '</td>');
          $('#rekap_produksi_mingguan_table tbody #tr_total').append('<td style="vertical-align: top; text-align: center;">' + (total_total / 26).toFixed(2) + '</td>');
          $('#rekap_produksi_mingguan_table').append('</tr>');
          $('#rekap_produksi_mingguan_table').append('</tbody>');
        })

        $.ajax({
          type: "GET",
          url: "{{ url('get_total_produksi_chart') }}",
          success: function (data) {
            var total_prd1 = new Array(12).fill(0);
            var total_prd2 = new Array(12).fill(0);
            var total_prd3 = new Array(12).fill(0);
            var total_prd4 = new Array(12).fill(0);
            var total_prd5 = new Array(12).fill(0);
            for(var i in data) {
              if(data[i].tahun == '{{ date("Y") }}'){
                total_prd1.splice(data[i].bulan - 1, 0, data[i].total_produksi);
                total_prd1.join();
              }else if(data[i].tahun == "{{ date('Y', strtotime('-1 year')) }}"){
                total_prd2.splice(data[i].bulan - 1, 0, data[i].total_produksi);
                total_prd2.join();
              }else if(data[i].tahun == "{{ date('Y', strtotime('-2 year')) }}"){
                total_prd3.splice(data[i].bulan - 1, 0, data[i].total_produksi);
                total_prd3.join();
              }else if(data[i].tahun == "{{ date('Y', strtotime('-3 year')) }}"){
                total_prd4.splice(data[i].bulan - 1, 0, data[i].total_produksi);
                total_prd4.join();
              }else if(data[i].tahun == "{{ date('Y', strtotime('-4 year')) }}"){
                total_prd5.splice(data[i].bulan - 1, 0, data[i].total_produksi);
                total_prd5.join();
              }
            }

            var chartdata = {
              labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
              datasets : [
              {
                label: 'Tahun {{ date("Y") }}',
                backgroundColor: 'rgba(178,34,34,0.9)',
                borderColor: 'rgba(178,34,34,0.9)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(178,34,34,0.9)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(178,34,34,0.9)',
                data: total_prd1
              },
              ]
            };

            var barChartCanvas = $('#chartTotalProduksi').get(0).getContext('2d');
            var barChartOptions = {
              responsive              : true,
              maintainAspectRatio     : false,
              datasetFill             : false,
              tooltips: {
                mode: 'index',
                intersect: false,
                callbacks: {
                  label: function(tooltipItem, data) {
                    var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                    value = value.toString().split(".");
                    value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                  }
                }
              },
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero:true,
                    userCallback: function(value, index, values) {
                      value = value.toLocaleString('id-ID');
                      return value;
                    }
                  }
                }],
                xAxes: [{
                  ticks: {
                  }
                }]
              }
            }

            var barChart = new Chart(barChartCanvas, {
              type: 'bar', 
              data: chartdata,
              options: barChartOptions
            });

            $("#tahun_total_produksi").change(function() {
              if ($(this).val() == 1) {
                $('#chartTotalProduksi').remove();
                $('#tempat-chart-total-produksi').append('<canvas id="chartTotalProduksi" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: total_prd1
                  },
                  ]
                };

                var barChartCanvas = $('#chartTotalProduksi').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 2) {
                $('#chartTotalProduksi').remove();
                $('#tempat-chart-total-produksi').append('<canvas id="chartTotalProduksi" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: total_prd2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: total_prd1
                  },
                  ]
                };

                var barChartCanvas = $('#chartTotalProduksi').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 3) {
                $('#chartTotalProduksi').remove();
                $('#tempat-chart-total-produksi').append('<canvas id="chartTotalProduksi" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-2 year")) }}',
                    backgroundColor: 'rgba(75,237,57,0.9)',
                    borderColor: 'rgba(75,237,57,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(75,237,57,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(75,237,57,0.9)',
                    data: total_prd3                                                
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: total_prd2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: total_prd1
                  },
                  ]
                };

                var barChartCanvas = $('#chartTotalProduksi').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 4) {
                $('#chartTotalProduksi').remove();
                $('#tempat-chart-total-produksi').append('<canvas id="chartTotalProduksi" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-3 year")) }}',
                    backgroundColor: 'rgba(237,28,77,0.9)',
                    borderColor: 'rgba(237,28,77,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(237,28,77,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(237,28,77,0.9)',
                    data: total_prd4                                              
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-2 year")) }}',
                    backgroundColor: 'rgba(75,237,57,0.9)',
                    borderColor: 'rgba(75,237,57,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(75,237,57,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(75,237,57,0.9)',
                    data: total_prd3                                                
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: total_prd2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: total_prd1
                  },
                  ]
                };

                var barChartCanvas = $('#chartTotalProduksi').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 5) {
                $('#chartTotalProduksi').remove();
                $('#tempat-chart-total-produksi').append('<canvas id="chartTotalProduksi" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-4 year")) }}',
                    backgroundColor: 'rgba(246,79,255,0.9)',
                    borderColor: 'rgba(246,79,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(246,79,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(246,79,255,0.9)',
                    data: total_prd5                                            
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-3 year")) }}',
                    backgroundColor: 'rgba(237,28,77,0.9)',
                    borderColor: 'rgba(237,28,77,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(237,28,77,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(237,28,77,0.9)',
                    data: total_prd4                                              
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-2 year")) }}',
                    backgroundColor: 'rgba(75,237,57,0.9)',
                    borderColor: 'rgba(75,237,57,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(75,237,57,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(75,237,57,0.9)',
                    data: total_prd3                                                
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: total_prd2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: total_prd1
                  },
                  ]
                };

                var barChartCanvas = $('#chartTotalProduksi').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }
            });
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
        
        $.ajax({
          type: "GET",
          url: "{{ url('get_rata_produksi_chart') }}",
          success: function (data) {
            var rata_prd1 = new Array(12).fill(0);
            var rata_prd2 = new Array(12).fill(0);
            var rata_prd3 = new Array(12).fill(0);
            var rata_prd4 = new Array(12).fill(0);
            var rata_prd5 = new Array(12).fill(0);
            for(var i in data) {
              if(data[i].tahun == '{{ date("Y") }}'){
                rata_prd1.splice(data[i].bulan - 1, 0, parseFloat(data[i].rata_produksi).toFixed(2));
                rata_prd1.join();
              }else if(data[i].tahun == "{{ date('Y', strtotime('-1 year')) }}"){
                rata_prd2.splice(data[i].bulan - 1, 0, parseFloat(data[i].rata_produksi).toFixed(2));
                rata_prd2.join();
              }else if(data[i].tahun == "{{ date('Y', strtotime('-2 year')) }}"){
                rata_prd3.splice(data[i].bulan - 1, 0, parseFloat(data[i].rata_produksi).toFixed(2));
                rata_prd3.join();
              }else if(data[i].tahun == "{{ date('Y', strtotime('-3 year')) }}"){
                rata_prd4.splice(data[i].bulan - 1, 0, parseFloat(data[i].rata_produksi).toFixed(2));
                rata_prd4.join();
              }else if(data[i].tahun == "{{ date('Y', strtotime('-4 year')) }}"){
                rata_prd5.splice(data[i].bulan - 1, 0, parseFloat(data[i].rata_produksi).toFixed(2));
                rata_prd5.join();
              }
            }

            var chartdata = {
              labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
              datasets : [
              {
                label: 'Tahun {{ date("Y") }}',
                backgroundColor: 'rgba(178,34,34,0.9)',
                borderColor: 'rgba(178,34,34,0.9)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(178,34,34,0.9)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(178,34,34,0.9)',
                data: rata_prd1
              },
              ]
            };

            var barChartCanvas = $('#chartRataProduksi').get(0).getContext('2d');
            var barChartOptions = {
              responsive              : true,
              maintainAspectRatio     : false,
              datasetFill             : false,
              tooltips: {
                mode: 'index',
                intersect: false,
                callbacks: {
                  label: function(tooltipItem, data) {
                    var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                    value = value.toString().split(".");
                    value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                  }
                }
              },
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero:true,
                    userCallback: function(value, index, values) {
                      value = value.toLocaleString('id-ID');
                      return value;
                    }
                  }
                }],
                xAxes: [{
                  ticks: {
                  }
                }]
              }
            }

            var barChart = new Chart(barChartCanvas, {
              type: 'bar', 
              data: chartdata,
              options: barChartOptions
            });

            $("#tahun_rata_produksi").change(function() {
              if ($(this).val() == 1) {
                $('#chartRataProduksi').remove();
                $('#tempat-chart-rata-produksi').append('<canvas id="chartRataProduksi" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: rata_prd1
                  },
                  ]
                };

                var barChartCanvas = $('#chartRataProduksi').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 2) {
                $('#chartRataProduksi').remove();
                $('#tempat-chart-rata-produksi').append('<canvas id="chartRataProduksi" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: rata_prd2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: rata_prd1
                  },
                  ]
                };

                var barChartCanvas = $('#chartRataProduksi').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 3) {
                $('#chartRataProduksi').remove();
                $('#tempat-chart-rata-produksi').append('<canvas id="chartRataProduksi" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-2 year")) }}',
                    backgroundColor: 'rgba(75,237,57,0.9)',
                    borderColor: 'rgba(75,237,57,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(75,237,57,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(75,237,57,0.9)',
                    data: rata_prd3                                                
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: rata_prd2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: rata_prd1
                  },
                  ]
                };

                var barChartCanvas = $('#chartRataProduksi').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 4) {
                $('#chartRataProduksi').remove();
                $('#tempat-chart-rata-produksi').append('<canvas id="chartRataProduksi" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-3 year")) }}',
                    backgroundColor: 'rgba(237,28,77,0.9)',
                    borderColor: 'rgba(237,28,77,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(237,28,77,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(237,28,77,0.9)',
                    data: rata_prd4                                              
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-2 year")) }}',
                    backgroundColor: 'rgba(75,237,57,0.9)',
                    borderColor: 'rgba(75,237,57,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(75,237,57,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(75,237,57,0.9)',
                    data: rata_prd3                                                
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: rata_prd2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: rata_prd1
                  },
                  ]
                };

                var barChartCanvas = $('#chartRataProduksi').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 5) {
                $('#chartRataProduksi').remove();
                $('#tempat-chart-rata-produksi').append('<canvas id="chartRataProduksi" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-4 year")) }}',
                    backgroundColor: 'rgba(246,79,255,0.9)',
                    borderColor: 'rgba(246,79,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(246,79,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(246,79,255,0.9)',
                    data: rata_prd5                                            
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-3 year")) }}',
                    backgroundColor: 'rgba(237,28,77,0.9)',
                    borderColor: 'rgba(237,28,77,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(237,28,77,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(237,28,77,0.9)',
                    data: rata_prd4                                              
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-2 year")) }}',
                    backgroundColor: 'rgba(75,237,57,0.9)',
                    borderColor: 'rgba(75,237,57,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(75,237,57,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(75,237,57,0.9)',
                    data: rata_prd3                                                
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: rata_prd2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: rata_prd1
                  },
                  ]
                };

                var barChartCanvas = $('#chartRataProduksi').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }
            });
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }

      if(session == 1 || session == 2 || session == 10){
        var url = "{{ route('get_total_sales') }}";

        $.get(url, function (data) {
          if(data[0].omset == null){
            $(".progress-bar-sales").animate({
              width: "0%"
            }, 1000);
            document.getElementById("isi_progress_bar_sales").innerHTML = "0 %, Omset : Rp 0";
          }else{
            var progress = data[0].omset;
            var progress = (progress / 3000000000) * 100;
            var persen = Math.round(progress) + "%";
            $(".progress-bar-sales").animate({
              width: persen
            }, 1000);
            document.getElementById("isi_progress_bar_sales").innerHTML = persen + ", Omset : Rp " + data[0].omset.toLocaleString('id-ID');
          }
        });

        $.ajax({
          type: "GET",
          url: "{{ url('get_omsetvalueline_sales') }}",
          success: function (data) {
            var tanggal_label_line1 = [];
            var tanggal_label_line2 = [];
            var tanggal_label_line3 = [];
            var tanggal_label_line4 = [];
            var tanggal_label_line5 = [];
            var omset_line1 = [];
            var omset_line2 = [];
            var omset_line3 = [];
            var omset_line4 = [];
            var omset_line5 = [];
            tanggal_label_line1.splice(0, 0, '{{ date("Y-01-01") }}');
            tanggal_label_line1.join();
            omset_line1.splice(0, 0, 0);
            omset_line1.join();
            tanggal_label_line2.splice(0, 0, '{{ date("Y-01-01", strtotime("-1 year")) }}');
            tanggal_label_line2.join();
            omset_line2.splice(0, 0, 0);
            omset_line2.join();
            tanggal_label_line3.splice(0, 0, '{{ date("Y-01-01", strtotime("-2 year")) }}');
            tanggal_label_line3.join();
            omset_line3.splice(0, 0, 0);
            omset_line3.join();
            tanggal_label_line4.splice(0, 0, '{{ date("Y-01-01", strtotime("-3 year")) }}');
            tanggal_label_line4.join();
            omset_line4.splice(0, 0, 0);
            omset_line4.join();
            tanggal_label_line5.splice(0, 0, '{{ date("Y-01-01", strtotime("-4 year")) }}');
            tanggal_label_line5.join();
            omset_line5.splice(0, 0, 0);
            omset_line5.join();

            for(var i in data) {
              var d = new Date(data[i].tanggal);
              if(d.getFullYear() == '{{ date("Y") }}'){
                omset_line1.push(data[i].omset);
                tanggal_label_line1.push(data[i].tanggal);
              }else if(d.getFullYear() == '{{ date("Y", strtotime("-1 year")) }}'){
                omset_line2.push(data[i].omset);
                tanggal_label_line2.push(data[i].tanggal);
              }else if(d.getFullYear() == '{{ date("Y", strtotime("-2 year")) }}'){
                omset_line3.push(data[i].omset);
                tanggal_label_line3.push(data[i].tanggal);
              }else if(d.getFullYear() == '{{ date("Y", strtotime("-3 year")) }}'){
                omset_line4.push(data[i].omset);
                tanggal_label_line4.push(data[i].tanggal);
              }else if(d.getFullYear() == '{{ date("Y", strtotime("-4 year")) }}'){
                omset_line5.push(data[i].omset);
                tanggal_label_line5.push(data[i].tanggal);
              }
            }

            omset_line2 = omset_line2.concat(omset_line1);
            tanggal_label_line2 = tanggal_label_line2.concat(tanggal_label_line1);
            omset_line3 = omset_line3.concat(omset_line2);
            tanggal_label_line3 = tanggal_label_line3.concat(tanggal_label_line2);
            omset_line4 = omset_line4.concat(omset_line3);
            tanggal_label_line4 = tanggal_label_line4.concat(tanggal_label_line3);
            omset_line5 = omset_line5.concat(omset_line4);
            tanggal_label_line5 = tanggal_label_line5.concat(tanggal_label_line4);

            var chartdata = {
              labels: tanggal_label_line1,
              datasets : [
              {
                label : 'Omset',
                backgroundColor: 'rgba(178,34,34,0.9)',
                borderColor: 'rgba(178,34,34,0.9)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(178,34,34,0.9)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(178,34,34,0.9)',
                lineTension: 0,
                data: omset_line1
              },
              ]
            };

            document.getElementById('tempat-chart-omset-sales-line').style.width='940px';
            var barChartCanvas = $('#chartOmsetValueLine').get(0).getContext('2d');
            var barChartOptions = {
              responsive              : true,
              maintainAspectRatio     : false,
              datasetFill             : false,
              tooltips: {
                mode: 'index',
                intersect: false,
                callbacks: {
                  label: function(tooltipItem, data) {
                    var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                    value = value.toString().split(".");
                    value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                  }
                }
              },
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero:true,
                    userCallback: function(value, index, values) {
                      value = value.toLocaleString('id-ID');
                      return value;
                    }
                  }
                }],
                xAxes: [{
                  type: 'time',
                  time: {
                    unit: 'month'
                  }
                }]
              },
              elements: {
                line: {
                  fill: false,
                  tension: 0
                },
                point:{
                  radius: 0
                }
              },
              title: {
                display: true,
                text: 'Omset Total'
              }
            }

            var barChart = new Chart(barChartCanvas, {
              type: 'line', 
              data: chartdata,
              options: barChartOptions
            });

            $("#tahun_omset_value_line_sales").change(function() {
              if ($(this).val() == 1) {
                $('#chartOmsetValueLine').remove();
                $('#tempat-chart-omset-sales-line').append('<canvas id="chartOmsetValueLine" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: tanggal_label_line1,
                  datasets : [
                  {
                    label : 'Omset',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    lineTension: 0,
                    data: omset_line1
                  },
                  ]
                };

                document.getElementById('tempat-chart-omset-sales-line').style.width='940px';
                var barChartCanvas = $('#chartOmsetValueLine').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      type: 'time',
                      time: {
                        unit: 'month'
                      }
                    }]
                  },
                  elements: {
                    line: {
                      fill: false,
                      tension: 0
                    },
                    point:{
                      radius: 0
                    }
                  },
                  title: {
                    display: true,
                    text: 'Omset Total'
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'line', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 2) {
                $('#chartOmsetValueLine').remove();
                $('#tempat-chart-omset-sales-line').append('<canvas id="chartOmsetValueLine" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');

                var chartdata = {
                  labels: tanggal_label_line2,
                  datasets : [
                  {
                    label : 'Omset',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    lineTension: 0,
                    data: omset_line2
                  },
                  ]
                };

                document.getElementById('tempat-chart-omset-sales-line').style.width='1540px';
                var barChartCanvas = $('#chartOmsetValueLine').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      type: 'time',
                      time: {
                        unit: 'month'
                      }
                    }]
                  },
                  elements: {
                    line: {
                      fill: false,
                      tension: 0
                    },
                    point:{
                      radius: 0
                    }
                  },
                  title: {
                    display: true,
                    text: 'Omset Total'
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'line', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 3) {
                $('#chartOmsetValueLine').remove();
                $('#tempat-chart-omset-sales-line').append('<canvas id="chartOmsetValueLine" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');

                var chartdata = {
                  labels: tanggal_label_line3,
                  datasets : [
                  {
                    label : 'Omset',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    lineTension: 0,
                    data: omset_line3
                  },
                  ]
                };

                document.getElementById('tempat-chart-omset-sales-line').style.width='2140px';
                var barChartCanvas = $('#chartOmsetValueLine').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {

                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      type: 'time',
                      time: {
                        unit: 'month'
                      }
                    }]
                  },
                  elements: {
                    line: {
                      fill: false,
                      tension: 0
                    },
                    point:{
                      radius: 0
                    }
                  },
                  title: {
                    display: true,
                    text: 'Omset Total'
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'line', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 4) {
                $('#chartOmsetValueLine').remove();
                $('#tempat-chart-omset-sales-line').append('<canvas id="chartOmsetValueLine" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');

                var chartdata = {
                  labels: tanggal_label_line4,
                  datasets : [
                  {
                    label : 'Omset',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    lineTension: 0,
                    data: omset_line4
                  },
                  ]
                };

                document.getElementById('tempat-chart-omset-sales-line').style.width='2740px';
                var barChartCanvas = $('#chartOmsetValueLine').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      type: 'time',
                      time: {
                        unit: 'month'
                      }
                    }]
                  },
                  elements: {
                    line: {
                      fill: false,
                      tension: 0
                    },
                    point:{
                      radius: 0
                    }
                  },
                  title: {
                    display: true,
                    text: 'Omset Total'
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'line', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 5) {
                $('#chartOmsetValueLine').remove();
                $('#tempat-chart-omset-sales-line').append('<canvas id="chartOmsetValueLine" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');

                var chartdata = {
                  labels: tanggal_label_line5,
                  datasets : [
                  {
                    label : 'Omset',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    lineTension: 0,
                    data: omset_line5
                  },
                  ]
                };

                document.getElementById('tempat-chart-omset-sales-line').style.width='3340px';
                var barChartCanvas = $('#chartOmsetValueLine').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      type: 'time',
                      time: {
                        unit: 'month'
                      }
                    }]
                  },
                  elements: {
                    line: {
                      fill: false,
                      tension: 0
                    },
                    point:{
                      radius: 0
                    }
                  },
                  title: {
                    display: true,
                    text: 'Omset Total'
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'line', 
                  data: chartdata,
                  options: barChartOptions
                });
              }
            });

          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });

        $.ajax({
          type: "GET",
          url: "{{ url('get_omsetdppline_sales') }}",
          success: function (data) {
            var tanggal_label_line1 = [];
            var tanggal_label_line2 = [];
            var tanggal_label_line3 = [];
            var tanggal_label_line4 = [];
            var tanggal_label_line5 = [];
            var omset_line1 = [];
            var omset_line2 = [];
            var omset_line3 = [];
            var omset_line4 = [];
            var omset_line5 = [];
            tanggal_label_line1.splice(0, 0, '{{ date("Y-01-01") }}');
            tanggal_label_line1.join();
            omset_line1.splice(0, 0, 0);
            omset_line1.join();
            tanggal_label_line2.splice(0, 0, '{{ date("Y-01-01", strtotime("-1 year")) }}');
            tanggal_label_line2.join();
            omset_line2.splice(0, 0, 0);
            omset_line2.join();
            tanggal_label_line3.splice(0, 0, '{{ date("Y-01-01", strtotime("-2 year")) }}');
            tanggal_label_line3.join();
            omset_line3.splice(0, 0, 0);
            omset_line3.join();
            tanggal_label_line4.splice(0, 0, '{{ date("Y-01-01", strtotime("-3 year")) }}');
            tanggal_label_line4.join();
            omset_line4.splice(0, 0, 0);
            omset_line4.join();
            tanggal_label_line5.splice(0, 0, '{{ date("Y-01-01", strtotime("-4 year")) }}');
            tanggal_label_line5.join();
            omset_line5.splice(0, 0, 0);
            omset_line5.join();

            for(var i in data) {
              var d = new Date(data[i].tanggal);
              if(d.getFullYear() == '{{ date("Y") }}'){
                omset_line1.push(data[i].omset);
                tanggal_label_line1.push(data[i].tanggal);
              }else if(d.getFullYear() == '{{ date("Y", strtotime("-1 year")) }}'){
                omset_line2.push(data[i].omset);
                tanggal_label_line2.push(data[i].tanggal);
              }else if(d.getFullYear() == '{{ date("Y", strtotime("-2 year")) }}'){
                omset_line3.push(data[i].omset);
                tanggal_label_line3.push(data[i].tanggal);
              }else if(d.getFullYear() == '{{ date("Y", strtotime("-3 year")) }}'){
                omset_line4.push(data[i].omset);
                tanggal_label_line4.push(data[i].tanggal);
              }else if(d.getFullYear() == '{{ date("Y", strtotime("-4 year")) }}'){
                omset_line5.push(data[i].omset);
                tanggal_label_line5.push(data[i].tanggal);
              }
            }

            omset_line2 = omset_line2.concat(omset_line1);
            tanggal_label_line2 = tanggal_label_line2.concat(tanggal_label_line1);
            omset_line3 = omset_line3.concat(omset_line2);
            tanggal_label_line3 = tanggal_label_line3.concat(tanggal_label_line2);
            omset_line4 = omset_line4.concat(omset_line3);
            tanggal_label_line4 = tanggal_label_line4.concat(tanggal_label_line3);
            omset_line5 = omset_line5.concat(omset_line4);
            tanggal_label_line5 = tanggal_label_line5.concat(tanggal_label_line4);

            var chartdata = {
              labels: tanggal_label_line1,
              datasets : [
              {
                label : 'Omset',
                backgroundColor: 'rgba(178,34,34,0.9)',
                borderColor: 'rgba(178,34,34,0.9)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(178,34,34,0.9)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(178,34,34,0.9)',
                lineTension: 0,
                data: omset_line1
              },
              ]
            };

            document.getElementById('tempat-chart-omset-dpp-sales-line').style.width='940px';
            var barChartCanvas = $('#chartOmsetDPPLine').get(0).getContext('2d');
            var barChartOptions = {
              responsive              : true,
              maintainAspectRatio     : false,
              datasetFill             : false,
              tooltips: {
                mode: 'index',
                intersect: false,
                callbacks: {
                  label: function(tooltipItem, data) {
                    var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                    value = value.toString().split(".");
                    value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                  }
                }
              },
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero:true,
                    userCallback: function(value, index, values) {
                      value = value.toLocaleString('id-ID');
                      return value;
                    }
                  }
                }],
                xAxes: [{
                  type: 'time',
                  time: {
                    unit: 'month'
                  }
                }]
              },
              elements: {
                line: {
                  fill: false,
                  tension: 0
                },
                point:{
                  radius: 0
                }
              },
              title: {
                display: true,
                text: 'Omset DPP'
              }
            }

            var barChart = new Chart(barChartCanvas, {
              type: 'line', 
              data: chartdata,
              options: barChartOptions
            });

            $("#tahun_omset_value_line_sales").change(function() {
              if ($(this).val() == 1) {
                $('#chartOmsetDPPLine').remove();
                $('#tempat-chart-omset-dpp-sales-line').append('<canvas id="chartOmsetDPPLine" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: tanggal_label_line1,
                  datasets : [
                  {
                    label : 'Omset',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    lineTension: 0,
                    data: omset_line1
                  },
                  ]
                };

                document.getElementById('tempat-chart-omset-dpp-sales-line').style.width='940px';
                var barChartCanvas = $('#chartOmsetDPPLine').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      type: 'time',
                      time: {
                        unit: 'month'
                      }
                    }]
                  },
                  elements: {
                    line: {
                      fill: false,
                      tension: 0
                    },
                    point:{
                      radius: 0
                    }
                  },
                  title: {
                    display: true,
                    text: 'Omset DPP'
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'line', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 2) {
                $('#chartOmsetDPPLine').remove();
                $('#tempat-chart-omset-dpp-sales-line').append('<canvas id="chartOmsetDPPLine" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');

                var chartdata = {
                  labels: tanggal_label_line2,
                  datasets : [
                  {
                    label : 'Omset',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    lineTension: 0,
                    data: omset_line2
                  },
                  ]
                };

                document.getElementById('tempat-chart-omset-dpp-sales-line').style.width='1540px';
                var barChartCanvas = $('#chartOmsetDPPLine').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      type: 'time',
                      time: {
                        unit: 'month'
                      }
                    }]
                  },
                  elements: {
                    line: {
                      fill: false,
                      tension: 0
                    },
                    point:{
                      radius: 0
                    }
                  },
                  title: {
                    display: true,
                    text: 'Omset DPP'
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'line', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 3) {
                $('#chartOmsetDPPLine').remove();
                $('#tempat-chart-omset-dpp-sales-line').append('<canvas id="chartOmsetDPPLine" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');

                var chartdata = {
                  labels: tanggal_label_line3,
                  datasets : [
                  {
                    label : 'Omset',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    lineTension: 0,
                    data: omset_line3
                  },
                  ]
                };

                document.getElementById('tempat-chart-omset-dpp-sales-line').style.width='2140px';
                var barChartCanvas = $('#chartOmsetDPPLine').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {

                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      type: 'time',
                      time: {
                        unit: 'month'
                      }
                    }]
                  },
                  elements: {
                    line: {
                      fill: false,
                      tension: 0
                    },
                    point:{
                      radius: 0
                    }
                  },
                  title: {
                    display: true,
                    text: 'Omset DPP'
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'line', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 4) {
                $('#chartOmsetDPPLine').remove();
                $('#tempat-chart-omset-dpp-sales-line').append('<canvas id="chartOmsetDPPLine" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');

                var chartdata = {
                  labels: tanggal_label_line4,
                  datasets : [
                  {
                    label : 'Omset',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    lineTension: 0,
                    data: omset_line4
                  },
                  ]
                };

                document.getElementById('tempat-chart-omset-dpp-sales-line').style.width='2740px';
                var barChartCanvas = $('#chartOmsetDPPLine').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      type: 'time',
                      time: {
                        unit: 'month'
                      }
                    }]
                  },
                  elements: {
                    line: {
                      fill: false,
                      tension: 0
                    },
                    point:{
                      radius: 0
                    }
                  },
                  title: {
                    display: true,
                    text: 'Omset DPP'
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'line', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 5) {
                $('#chartOmsetDPPLine').remove();
                $('#tempat-chart-omset-dpp-sales-line').append('<canvas id="chartOmsetDPPLine" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');

                var chartdata = {
                  labels: tanggal_label_line5,
                  datasets : [
                  {
                    label : 'Omset',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    lineTension: 0,
                    data: omset_line5
                  },
                  ]
                };

                document.getElementById('tempat-chart-omset-dpp-sales-line').style.width='3340px';
                var barChartCanvas = $('#chartOmsetDPPLine').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      type: 'time',
                      time: {
                        unit: 'month'
                      }
                    }]
                  },
                  elements: {
                    line: {
                      fill: false,
                      tension: 0
                    },
                    point:{
                      radius: 0
                    }
                  },
                  title: {
                    display: true,
                    text: 'Omset DPP'
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'line', 
                  data: chartdata,
                  options: barChartOptions
                });
              }
            });

          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });

        $.ajax({
          type: "GET",
          url: "{{ url('get_omsetvalue_sales') }}",
          success: function (data) {
            var omset1 = new Array(12).fill(0);
            var omset2 = new Array(12).fill(0);
            var omset3 = new Array(12).fill(0);
            var omset4 = new Array(12).fill(0);
            var omset5 = new Array(12).fill(0);
            for(var i in data) {
              if(data[i].tahun == '{{ date("Y") }}'){
                omset1.splice(data[i].bulan - 1, 0, data[i].omset);
                omset1.join();
              }else if(data[i].tahun == '{{ date("Y", strtotime("-1 year")) }}'){
                omset2.splice(data[i].bulan - 1, 0, data[i].omset);
                omset2.join();
              }else if(data[i].tahun == '{{ date("Y", strtotime("-2 year")) }}'){
                omset3.splice(data[i].bulan - 1, 0, data[i].omset);
                omset3.join();
              }else if(data[i].tahun == '{{ date("Y", strtotime("-3 year")) }}'){
                omset4.splice(data[i].bulan - 1, 0, data[i].omset);
                omset4.join();
              }else if(data[i].tahun == '{{ date("Y", strtotime("-4 year")) }}'){
                omset5.splice(data[i].bulan - 1, 0, data[i].omset);
                omset5.join();
              }
            }

            var chartdata = {
              labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
              datasets : [
              {
                label: 'Tahun {{ date("Y") }}',
                backgroundColor: 'rgba(178,34,34,0.9)',
                borderColor: 'rgba(178,34,34,0.9)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(178,34,34,0.9)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(178,34,34,0.9)',
                data: omset1
              },
              ]
            };

            var barChartCanvas = $('#chartOmsetValue').get(0).getContext('2d');
            var barChartOptions = {
              responsive              : true,
              maintainAspectRatio     : false,
              datasetFill             : false,
              tooltips: {
                mode: 'index',
                intersect: false,
                callbacks: {
                  label: function(tooltipItem, data) {
                    var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                    value = value.toString().split(".");
                    value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                  }
                }
              },
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero:true,
                    userCallback: function(value, index, values) {
                      value = value.toLocaleString('id-ID');
                      return value;
                    }
                  }
                }],
                xAxes: [{
                  ticks: {
                  }
                }]
              },
              title: {
                display: true,
                text: 'Omset Total'
              }
            }

            var barChart = new Chart(barChartCanvas, {
              type: 'bar', 
              data: chartdata,
              options: barChartOptions
            });

            $("#tahun_omset_value_sales").change(function() {
              if ($(this).val() == 1) {
                $('#chartOmsetValue').remove();
                $('#tempat-chart-omset-sales').append('<canvas id="chartOmsetValue" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: omset1
                  },
                  ]
                };

                var barChartCanvas = $('#chartOmsetValue').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  },
                  title: {
                    display: true,
                    text: 'Omset Total'
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 2) {
                $('#chartOmsetValue').remove();
                $('#tempat-chart-omset-sales').append('<canvas id="chartOmsetValue" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: omset2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: omset1
                  },
                  ]
                };

                var barChartCanvas = $('#chartOmsetValue').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  },
                  title: {
                    display: true,
                    text: 'Omset Total'
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 3) {
                $('#chartOmsetValue').remove();
                $('#tempat-chart-omset-sales').append('<canvas id="chartOmsetValue" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-2 year")) }}',
                    backgroundColor: 'rgba(75,237,57,0.9)',
                    borderColor: 'rgba(75,237,57,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(75,237,57,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(75,237,57,0.9)',
                    data: omset3                                                
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: omset2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: omset1
                  },
                  ]
                };

                var barChartCanvas = $('#chartOmsetValue').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  },
                  title: {
                    display: true,
                    text: 'Omset Total'
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 4) {
                $('#chartOmsetValue').remove();
                $('#tempat-chart-omset-sales').append('<canvas id="chartOmsetValue" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-3 year")) }}',
                    backgroundColor: 'rgba(237,28,77,0.9)',
                    borderColor: 'rgba(237,28,77,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(237,28,77,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(237,28,77,0.9)',
                    data: omset4                                              
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-2 year")) }}',
                    backgroundColor: 'rgba(75,237,57,0.9)',
                    borderColor: 'rgba(75,237,57,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(75,237,57,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(75,237,57,0.9)',
                    data: omset3                                                
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: omset2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: omset1
                  },
                  ]
                };

                var barChartCanvas = $('#chartOmsetValue').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  },
                  title: {
                    display: true,
                    text: 'Omset Total'
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 5) {
                $('#chartOmsetValue').remove();
                $('#tempat-chart-omset-sales').append('<canvas id="chartOmsetValue" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-4 year")) }}',
                    backgroundColor: 'rgba(246,79,255,0.9)',
                    borderColor: 'rgba(246,79,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(246,79,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(246,79,255,0.9)',
                    data: omset5                                            
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-3 year")) }}',
                    backgroundColor: 'rgba(237,28,77,0.9)',
                    borderColor: 'rgba(237,28,77,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(237,28,77,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(237,28,77,0.9)',
                    data: omset4                                              
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-2 year")) }}',
                    backgroundColor: 'rgba(75,237,57,0.9)',
                    borderColor: 'rgba(75,237,57,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(75,237,57,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(75,237,57,0.9)',
                    data: omset3                                                
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: omset2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: omset1
                  },
                  ]
                };

                var barChartCanvas = $('#chartOmsetValue').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  },
                  title: {
                    display: true,
                    text: 'Omset Total'
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }
            });
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });

        $.ajax({
          type: "GET",
          url: "{{ url('get_omsetdpp_sales') }}",
          success: function (data) {
            var omset1 = new Array(12).fill(0);
            var omset2 = new Array(12).fill(0);
            var omset3 = new Array(12).fill(0);
            var omset4 = new Array(12).fill(0);
            var omset5 = new Array(12).fill(0);
            for(var i in data) {
              if(data[i].tahun == '{{ date("Y") }}'){
                omset1.splice(data[i].bulan - 1, 0, data[i].omset);
                omset1.join();
              }else if(data[i].tahun == '{{ date("Y", strtotime("-1 year")) }}'){
                omset2.splice(data[i].bulan - 1, 0, data[i].omset);
                omset2.join();
              }else if(data[i].tahun == '{{ date("Y", strtotime("-2 year")) }}'){
                omset3.splice(data[i].bulan - 1, 0, data[i].omset);
                omset3.join();
              }else if(data[i].tahun == '{{ date("Y", strtotime("-3 year")) }}'){
                omset4.splice(data[i].bulan - 1, 0, data[i].omset);
                omset4.join();
              }else if(data[i].tahun == '{{ date("Y", strtotime("-4 year")) }}'){
                omset5.splice(data[i].bulan - 1, 0, data[i].omset);
                omset5.join();
              }
            }

            var chartdata = {
              labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
              datasets : [
              {
                label: 'Tahun {{ date("Y") }}',
                backgroundColor: 'rgba(178,34,34,0.9)',
                borderColor: 'rgba(178,34,34,0.9)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(178,34,34,0.9)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(178,34,34,0.9)',
                data: omset1
              },
              ]
            };

            var barChartCanvas = $('#chartOmsetDPP').get(0).getContext('2d');
            var barChartOptions = {
              responsive              : true,
              maintainAspectRatio     : false,
              datasetFill             : false,
              tooltips: {
                mode: 'index',
                intersect: false,
                callbacks: {
                  label: function(tooltipItem, data) {
                    var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                    value = value.toString().split(".");
                    value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                  }
                }
              },
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero:true,
                    userCallback: function(value, index, values) {
                      value = value.toLocaleString('id-ID');
                      return value;
                    }
                  }
                }],
                xAxes: [{
                  ticks: {
                  }
                }]
              },
              title: {
                display: true,
                text: 'Omset DPP'
              }
            }

            var barChart = new Chart(barChartCanvas, {
              type: 'bar', 
              data: chartdata,
              options: barChartOptions
            });

            $("#tahun_omset_value_sales").change(function() {
              if ($(this).val() == 1) {
                $('#chartOmsetDPP').remove();
                $('#tempat-chart-omset-dpp-sales').append('<canvas id="chartOmsetDPP" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: omset1
                  },
                  ]
                };

                var barChartCanvas = $('#chartOmsetDPP').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  },
                  title: {
                    display: true,
                    text: 'Omset DPP'
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 2) {
                $('#chartOmsetDPP').remove();
                $('#tempat-chart-omset-dpp-sales').append('<canvas id="chartOmsetDPP" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: omset2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: omset1
                  },
                  ]
                };

                var barChartCanvas = $('#chartOmsetDPP').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  },
                  title: {
                    display: true,
                    text: 'Omset DPP'
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 3) {
                $('#chartOmsetDPP').remove();
                $('#tempat-chart-omset-dpp-sales').append('<canvas id="chartOmsetDPP" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-2 year")) }}',
                    backgroundColor: 'rgba(75,237,57,0.9)',
                    borderColor: 'rgba(75,237,57,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(75,237,57,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(75,237,57,0.9)',
                    data: omset3                                                
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: omset2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: omset1
                  },
                  ]
                };

                var barChartCanvas = $('#chartOmsetDPP').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  },
                  title: {
                    display: true,
                    text: 'Omset DPP'
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 4) {
                $('#chartOmsetDPP').remove();
                $('#tempat-chart-omset-dpp-sales').append('<canvas id="chartOmsetDPP" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-3 year")) }}',
                    backgroundColor: 'rgba(237,28,77,0.9)',
                    borderColor: 'rgba(237,28,77,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(237,28,77,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(237,28,77,0.9)',
                    data: omset4                                              
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-2 year")) }}',
                    backgroundColor: 'rgba(75,237,57,0.9)',
                    borderColor: 'rgba(75,237,57,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(75,237,57,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(75,237,57,0.9)',
                    data: omset3                                                
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: omset2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: omset1
                  },
                  ]
                };

                var barChartCanvas = $('#chartOmsetDPP').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  },
                  title: {
                    display: true,
                    text: 'Omset DPP'
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 5) {
                $('#chartOmsetDPP').remove();
                $('#tempat-chart-omset-dpp-sales').append('<canvas id="chartOmsetDPP" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-4 year")) }}',
                    backgroundColor: 'rgba(246,79,255,0.9)',
                    borderColor: 'rgba(246,79,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(246,79,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(246,79,255,0.9)',
                    data: omset5                                            
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-3 year")) }}',
                    backgroundColor: 'rgba(237,28,77,0.9)',
                    borderColor: 'rgba(237,28,77,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(237,28,77,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(237,28,77,0.9)',
                    data: omset4                                              
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-2 year")) }}',
                    backgroundColor: 'rgba(75,237,57,0.9)',
                    borderColor: 'rgba(75,237,57,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(75,237,57,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(75,237,57,0.9)',
                    data: omset3                                                
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: omset2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: omset1
                  },
                  ]
                };

                var barChartCanvas = $('#chartOmsetDPP').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  },
                  title: {
                    display: true,
                    text: 'Omset DPP'
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }
            });
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });

        $.ajax({
          type: "GET",
          url: "{{ url('get_omsettonase_sales') }}",
          success: function (data) {
            var tonase1 = new Array(12).fill(0);
            var tonase2 = new Array(12).fill(0);
            var tonase3 = new Array(12).fill(0);
            var tonase4 = new Array(12).fill(0);
            var tonase5 = new Array(12).fill(0);
            for(var i in data) {
              if(data[i].tahun == '{{ date("Y") }}'){
                tonase1.splice(data[i].bulan - 1, 0, parseFloat(data[i].tonase).toFixed(2));
                tonase1.join();
              }else if(data[i].tahun == "{{ date('Y', strtotime('-1 year')) }}"){
                tonase2.splice(data[i].bulan - 1, 0, parseFloat(data[i].tonase).toFixed(2));
                tonase2.join();
              }else if(data[i].tahun == "{{ date('Y', strtotime('-2 year')) }}"){
                tonase3.splice(data[i].bulan - 1, 0, parseFloat(data[i].tonase).toFixed(2));
                tonase3.join();
              }else if(data[i].tahun == "{{ date('Y', strtotime('-3 year')) }}"){
                tonase4.splice(data[i].bulan - 1, 0, parseFloat(data[i].tonase).toFixed(2));
                tonase4.join();
              }else if(data[i].tahun == "{{ date('Y', strtotime('-4 year')) }}"){
                tonase5.splice(data[i].bulan - 1, 0, parseFloat(data[i].tonase).toFixed(2));
                tonase5.join();
              }
            }

            var chartdata = {
              labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
              datasets : [
              {
                label: 'Tahun {{ date("Y") }}',
                backgroundColor: 'rgba(178,34,34,0.9)',
                borderColor: 'rgba(178,34,34,0.9)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(178,34,34,0.9)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(178,34,34,0.9)',
                data: tonase1
              },
              ]
            };

            var barChartCanvas = $('#chartOmsetTonase').get(0).getContext('2d');
            var barChartOptions = {
              responsive              : true,
              maintainAspectRatio     : false,
              datasetFill             : false,
              tooltips: {
                mode: 'index',
                intersect: false,
                callbacks: {
                  label: function(tooltipItem, data) {
                    var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                    value = value.toString().split(".");
                    value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                  }
                }
              },
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero:true,
                    userCallback: function(value, index, values) {
                      value = value.toLocaleString('id-ID');
                      return value;
                    }
                  }
                }],
                xAxes: [{
                  ticks: {
                  }
                }]
              }
            }

            var barChart = new Chart(barChartCanvas, {
              type: 'bar', 
              data: chartdata,
              options: barChartOptions
            });

            $("#tahun_omset_tonase_sales").change(function() {
              if ($(this).val() == 1) {
                $('#chartOmsetTonase').remove();
                $('#tempat-chart-tonase-sales').append('<canvas id="chartOmsetTonase" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: tonase1
                  },
                  ]
                };

                var barChartCanvas = $('#chartOmsetTonase').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 2) {
                $('#chartOmsetTonase').remove();
                $('#tempat-chart-tonase-sales').append('<canvas id="chartOmsetTonase" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: tonase2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: tonase1
                  },
                  ]
                };

                var barChartCanvas = $('#chartOmsetTonase').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 3) {
                $('#chartOmsetTonase').remove();
                $('#tempat-chart-tonase-sales').append('<canvas id="chartOmsetTonase" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-2 year")) }}',
                    backgroundColor: 'rgba(75,237,57,0.9)',
                    borderColor: 'rgba(75,237,57,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(75,237,57,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(75,237,57,0.9)',
                    data: tonase3                                                
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: tonase2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: tonase1
                  },
                  ]
                };

                var barChartCanvas = $('#chartOmsetTonase').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 4) {
                $('#chartOmsetTonase').remove();
                $('#tempat-chart-tonase-sales').append('<canvas id="chartOmsetTonase" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-3 year")) }}',
                    backgroundColor: 'rgba(237,28,77,0.9)',
                    borderColor: 'rgba(237,28,77,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(237,28,77,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(237,28,77,0.9)',
                    data: tonase4                                              
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-2 year")) }}',
                    backgroundColor: 'rgba(75,237,57,0.9)',
                    borderColor: 'rgba(75,237,57,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(75,237,57,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(75,237,57,0.9)',
                    data: tonase3                                                
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: tonase2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: tonase1
                  },
                  ]
                };

                var barChartCanvas = $('#chartOmsetTonase').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 5) {
                $('#chartOmsetTonase').remove();
                $('#tempat-chart-tonase-sales').append('<canvas id="chartOmsetTonase" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-4 year")) }}',
                    backgroundColor: 'rgba(246,79,255,0.9)',
                    borderColor: 'rgba(246,79,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(246,79,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(246,79,255,0.9)',
                    data: tonase5                                            
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-3 year")) }}',
                    backgroundColor: 'rgba(237,28,77,0.9)',
                    borderColor: 'rgba(237,28,77,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(237,28,77,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(237,28,77,0.9)',
                    data: tonase4                                              
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-2 year")) }}',
                    backgroundColor: 'rgba(75,237,57,0.9)',
                    borderColor: 'rgba(75,237,57,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(75,237,57,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(75,237,57,0.9)',
                    data: tonase3                                                
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: tonase2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: tonase1
                  },
                  ]
                };

                var barChartCanvas = $('#chartOmsetTonase').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }
            });
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
        
        $.ajax({
          type: "GET",
          url: "{{ url('get_transaksi_sales') }}",
          success: function (data) {
            var transaksi1 = new Array(12).fill(0);
            var transaksi2 = new Array(12).fill(0);
            var transaksi3 = new Array(12).fill(0);
            var transaksi4 = new Array(12).fill(0);
            var transaksi5 = new Array(12).fill(0);
            for(var i in data) {
              if(data[i].tahun == '{{ date("Y") }}'){
                transaksi1.splice(data[i].bulan - 1, 0, data[i].penjualan);
                transaksi1.join();
              }else if(data[i].tahun == '{{ date("Y", strtotime("-1 year")) }}'){
                transaksi2.splice(data[i].bulan - 1, 0, data[i].penjualan);
                transaksi2.join();
              }else if(data[i].tahun == '{{ date("Y", strtotime("-2 year")) }}'){
                transaksi3.splice(data[i].bulan - 1, 0, data[i].penjualan);
                transaksi3.join();
              }else if(data[i].tahun == '{{ date("Y", strtotime("-3 year")) }}'){
                transaksi4.splice(data[i].bulan - 1, 0, data[i].penjualan);
                transaksi4.join();
              }else if(data[i].tahun == '{{ date("Y", strtotime("-4 year")) }}'){
                transaksi5.splice(data[i].bulan - 1, 0, data[i].penjualan);
                transaksi5.join();
              }
            }

            var chartdata = {
              labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
              datasets : [
              {
                label: 'Tahun {{ date("Y") }}',
                backgroundColor: 'rgba(178,34,34,0.9)',
                borderColor: 'rgba(178,34,34,0.9)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(178,34,34,0.9)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(178,34,34,0.9)',
                data: transaksi1
              },
              ]
            };

            var barChartCanvas = $('#chartTransaksi').get(0).getContext('2d');
            var barChartOptions = {
              responsive              : true,
              maintainAspectRatio     : false,
              datasetFill             : false,
              tooltips: {
                mode: 'index',
                intersect: false,
                callbacks: {
                  label: function(tooltipItem, data) {
                    var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                    value = value.toString().split(".");
                    value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                  }
                }
              },
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero:true,
                    userCallback: function(value, index, values) {
                      value = value.toLocaleString('id-ID');
                      return value;
                    }
                  }
                }],
                xAxes: [{
                  ticks: {
                  }
                }]
              }
            }

            var barChart = new Chart(barChartCanvas, {
              type: 'bar', 
              data: chartdata,
              options: barChartOptions
            });

            $("#tahun_transaksi_sales").change(function() {
              if ($(this).val() == 1) {
                $('#chartTransaksi').remove();
                $('#tempat-chart-transaksi-sales').append('<canvas id="chartTransaksi" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: transaksi1
                  },
                  ]
                };

                var barChartCanvas = $('#chartTransaksi').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 2) {
                $('#chartTransaksi').remove();
                $('#tempat-chart-transaksi-sales').append('<canvas id="chartTransaksi" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: transaksi2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: transaksi1
                  },
                  ]
                };

                var barChartCanvas = $('#chartTransaksi').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 3) {
                $('#chartTransaksi').remove();
                $('#tempat-chart-transaksi-sales').append('<canvas id="chartTransaksi" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-2 year")) }}',
                    backgroundColor: 'rgba(75,237,57,0.9)',
                    borderColor: 'rgba(75,237,57,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(75,237,57,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(75,237,57,0.9)',
                    data: transaksi3                                                
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: transaksi2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: transaksi1
                  },
                  ]
                };

                var barChartCanvas = $('#chartTransaksi').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 4) {
                $('#chartTransaksi').remove();
                $('#tempat-chart-transaksi-sales').append('<canvas id="chartTransaksi" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-3 year")) }}',
                    backgroundColor: 'rgba(237,28,77,0.9)',
                    borderColor: 'rgba(237,28,77,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(237,28,77,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(237,28,77,0.9)',
                    data: transaksi4                                              
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-2 year")) }}',
                    backgroundColor: 'rgba(75,237,57,0.9)',
                    borderColor: 'rgba(75,237,57,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(75,237,57,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(75,237,57,0.9)',
                    data: transaksi3                                                
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: tonase2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: transaksi1
                  },
                  ]
                };

                var barChartCanvas = $('#chartTransaksi').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 5) {
                $('#chartTransaksi').remove();
                $('#tempat-chart-transaksi-sales').append('<canvas id="chartTransaksi" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-4 year")) }}',
                    backgroundColor: 'rgba(246,79,255,0.9)',
                    borderColor: 'rgba(246,79,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(246,79,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(246,79,255,0.9)',
                    data: transaksi5                                            
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-3 year")) }}',
                    backgroundColor: 'rgba(237,28,77,0.9)',
                    borderColor: 'rgba(237,28,77,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(237,28,77,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(237,28,77,0.9)',
                    data: transaksi4                                              
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-2 year")) }}',
                    backgroundColor: 'rgba(75,237,57,0.9)',
                    borderColor: 'rgba(75,237,57,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(75,237,57,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(75,237,57,0.9)',
                    data: transaksi3                                                
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: transaksi2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: transaksi1
                  },
                  ]
                };

                var barChartCanvas = $('#chartTransaksi').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }
            });
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });

        $.ajax({
          type: "GET",
          url: "{{ url('get_complaint_sales') }}",
          success: function (data) {
            var complaint1 = new Array(12).fill(0);
            var complaint2 = new Array(12).fill(0);
            var complaint3 = new Array(12).fill(0);
            var complaint4 = new Array(12).fill(0);
            var complaint5 = new Array(12).fill(0);

            for(var i in data) {
              if(data[i].tahun == '{{ date("Y") }}'){
                complaint1.splice(data[i].bulan - 1, 0, data[i].complaint);
                complaint1.join();
              }else if(data[i].tahun == '{{ date("Y", strtotime("-1 year")) }}'){
                complaint2.splice(data[i].bulan - 1, 0, data[i].complaint);
                complaint2.join();
              }else if(data[i].tahun == '{{ date("Y", strtotime("-2 year")) }}'){
                complaint3.splice(data[i].bulan - 1, 0, data[i].complaint);
                complaint3.join();
              }else if(data[i].tahun == '{{ date("Y", strtotime("-3 year")) }}'){
                complaint4.splice(data[i].bulan - 1, 0, data[i].complaint);
                complaint4.join();
              }else if(data[i].tahun == '{{ date("Y", strtotime("-4 year")) }}'){
                complaint5.splice(data[i].bulan - 1, 0, data[i].complaint);
                complaint5.join();
              }
            }

            var chartdata = {
              labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
              datasets : [
              {
                label: 'Tahun {{ date("Y") }}',
                backgroundColor: 'rgba(178,34,34,0.9)',
                borderColor: 'rgba(178,34,34,0.9)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(178,34,34,0.9)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(178,34,34,0.9)',
                data: complaint1
              },
              ]
            };

            var barChartCanvas = $('#chartComplaint').get(0).getContext('2d');
            var barChartOptions = {
              responsive              : true,
              maintainAspectRatio     : false,
              datasetFill             : false,
              tooltips: {
                mode: 'index',
                intersect: false,
                callbacks: {
                  label: function(tooltipItem, data) {
                    var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                    value = value.toString().split(".");
                    value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                  }
                }
              },
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero:true,
                    userCallback: function(value, index, values) {
                      value = value.toLocaleString('id-ID');
                      return value;
                    }
                  }
                }],
                xAxes: [{
                  ticks: {
                  }
                }]
              }
            }

            var barChart = new Chart(barChartCanvas, {
              type: 'bar', 
              data: chartdata,
              options: barChartOptions
            });

            $("#tahun_complaint_sales").change(function() {
              if ($(this).val() == 1) {
                $('#chartComplaint').remove();
                $('#tempat-chart-complaint-sales').append('<canvas id="chartComplaint" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: complaint1
                  },
                  ]
                };

                var barChartCanvas = $('#chartComplaint').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 2) {
                $('#chartComplaint').remove();
                $('#tempat-chart-complaint-sales').append('<canvas id="chartComplaint" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: complaint2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: complaint1
                  },
                  ]
                };

                var barChartCanvas = $('#chartComplaint').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 3) {
                $('#chartComplaint').remove();
                $('#tempat-chart-complaint-sales').append('<canvas id="chartComplaint" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-2 year")) }}',
                    backgroundColor: 'rgba(75,237,57,0.9)',
                    borderColor: 'rgba(75,237,57,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(75,237,57,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(75,237,57,0.9)',
                    data: complaint3                                                
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: complaint2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: complaint1
                  },
                  ]
                };

                var barChartCanvas = $('#chartComplaint').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 4) {
                $('#chartComplaint').remove();
                $('#tempat-chart-complaint-sales').append('<canvas id="chartComplaint" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-3 year")) }}',
                    backgroundColor: 'rgba(237,28,77,0.9)',
                    borderColor: 'rgba(237,28,77,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(237,28,77,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(237,28,77,0.9)',
                    data: complaint4                                              
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-2 year")) }}',
                    backgroundColor: 'rgba(75,237,57,0.9)',
                    borderColor: 'rgba(75,237,57,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(75,237,57,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(75,237,57,0.9)',
                    data: complaint3                                                
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: complaint2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: complaint1
                  },
                  ]
                };

                var barChartCanvas = $('#chartComplaint').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }else if ($(this).val() == 5) {
                $('#chartComplaint').remove();
                $('#tempat-chart-complaint-sales').append('<canvas id="chartComplaint" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
                var chartdata = {
                  labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                  datasets : [
                  {
                    label: 'Tahun {{ date("Y", strtotime("-4 year")) }}',
                    backgroundColor: 'rgba(246,79,255,0.9)',
                    borderColor: 'rgba(246,79,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(246,79,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(246,79,255,0.9)',
                    data: complaint5                                            
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-3 year")) }}',
                    backgroundColor: 'rgba(237,28,77,0.9)',
                    borderColor: 'rgba(237,28,77,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(237,28,77,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(237,28,77,0.9)',
                    data: complaint4                                              
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-2 year")) }}',
                    backgroundColor: 'rgba(75,237,57,0.9)',
                    borderColor: 'rgba(75,237,57,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(75,237,57,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(75,237,57,0.9)',
                    data: complaint3                                                
                  },
                  {
                    label: 'Tahun {{ date("Y", strtotime("-1 year")) }}',
                    backgroundColor: 'rgba(30,144,255,0.9)',
                    borderColor: 'rgba(30,144,255,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(30,144,255,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(30,144,255,0.9)',
                    data: complaint2
                  },
                  {
                    label: 'Tahun {{ date("Y") }}',
                    backgroundColor: 'rgba(178,34,34,0.9)',
                    borderColor: 'rgba(178,34,34,0.9)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(178,34,34,0.9)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(178,34,34,0.9)',
                    data: complaint1
                  },
                  ]
                };

                var barChartCanvas = $('#chartComplaint').get(0).getContext('2d');
                var barChartOptions = {
                  responsive              : true,
                  maintainAspectRatio     : false,
                  datasetFill             : false,
                  tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                      label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        value = value.toString().split(".");
                        value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        return data.datasets[tooltipItem.datasetIndex].label + ": " + value.join(",");
                      }
                    }
                  },
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                          value = value.toLocaleString('id-ID');
                          return value;
                        }
                      }
                    }],
                    xAxes: [{
                      ticks: {
                      }
                    }]
                  }
                }

                var barChart = new Chart(barChartCanvas, {
                  type: 'bar', 
                  data: chartdata,
                  options: barChartOptions
                });
              }
            });
          },
          error: function (data) {
            console.log('Error:', data);
            alert("Something Goes Wrong. Please Try Again");
          }
        });
      }
    });
  </script>

  <script>
    var msg = '{{ Session::get('alert') }}';
    var exist = '{{ Session::has('alert') }}';
    if(exist){
      alert(msg);
    }
  </script>
  @endsection
