<a href="javascript:void(0)" data-id="{{ $noar }}" class="view btn btn-sm btn-primary" id="view-data" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_view_data">
    <i class="fa fa-eye"></i>
</a>
@if(Session::get('tipe_user') == $user_type)
<a href="javascript:void(0)" data-id="{{ $noar }}" class="edit btn btn-sm btn-warning" id="edit-data" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_edit_data">
    <i class="fa fa-edit"></i>
</a>
<a href="{{ url('penagihan/pelunasan/print/invoice/' . Illuminate\Support\Facades\Crypt::encrypt($noar)) }}" target="_blank" class="print btn btn-sm btn-primary" id="print-data" style="margin-bottom: 10px;">
    <i class="fa fa-print"></i>
</a>
@endif