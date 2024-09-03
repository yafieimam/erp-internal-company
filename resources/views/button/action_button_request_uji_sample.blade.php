
<a href="javascript:void(0)" data-id="{{ $nomor }}" class="view btn btn-sm btn-primary" id="view-data" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_view_data">
    <i class="fa fa-eye"></i>
</a>
@if($status == 3 && (Session::get('tipe_user') == 2 || Session::get('tipe_user') == 10))
<a href="javascript:void(0)" data-id="{{ $nomor }}" class="update btn btn-sm btn-primary" id="validasi-data" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_validasi_data">
    <i class="fa fa-edit"></i>
</a>
@endif
@if($status == 2 && (Session::get('tipe_user') == 10 || Session::get('tipe_user') == 2))
<a href="javascript:void(0)" data-id="{{ $nomor }}" class="update btn btn-sm btn-primary" id="update-data" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_update_data">
    <i class="fa fa-edit"></i>
</a>
@endif
@if($status > 0 && $status < 4 && (Session::get('tipe_user') == 10 || Session::get('tipe_user') == 2))
<a href="javascript:void(0)" data-id="{{ $nomor }}" class="edit btn btn-sm btn-warning" id="edit-data" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_edit_data">
    <i class="fa fa-edit"></i>
</a>
@endif
@if($status == 4)
<a href="{{ url('uji_sample/print/' . Illuminate\Support\Facades\Crypt::encrypt($nomor)) }}" target="_blank" class="print btn btn-sm btn-primary" id="btn-print" style="margin-bottom: 10px;">
    <i class="fa fa-print"></i>
</a>
@endif
@if($status < 4 && (Session::get('tipe_user') == 10 || Session::get('tipe_user') == 2))
<a href="javascript:void(0)" data-id="{{ $nomor }}" class="delete btn btn-sm btn-danger" id="delete-data" style="margin-bottom: 10px;">
    <i class="fa fa-trash"></i>
</a>
@endif