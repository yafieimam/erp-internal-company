<a href="javascript:void(0)" data-id="{{ $tanggal_jadwal_penagihan }}" class="view btn btn-sm btn-primary" id="view-data-final" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_view_data_final">
    <i class="fa fa-eye"></i>
</a>
<a href="{{ url('admin/trunojoyo/penagihan_final/print/' . Illuminate\Support\Facades\Crypt::encrypt($tanggal_jadwal_penagihan)) }}" target="_blank" class="print btn btn-sm btn-primary" id="btn-print-final" style="margin-bottom: 10px;">
    <i class="fa fa-print"></i>
</a>
<a href="javascript:void(0)" data-id="{{ $tanggal_jadwal_penagihan }}" class="update btn btn-sm btn-primary" id="update-data" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_update_data">
    <i class="fa fa-edit"></i>
</a>
<a href="javascript:void(0)" data-id="{{ $tanggal_jadwal_penagihan }}" class="edit btn btn-sm btn-warning" id="edit-data" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_edit_data">
    <i class="fa fa-edit"></i>
</a>