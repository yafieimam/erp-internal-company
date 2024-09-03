<a href="javascript:void(0)" data-id="{{ $tanggal_kirim_trunojoyo }}" class="view btn btn-sm btn-primary" id="view-data" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_view_data">
    <i class="fa fa-eye"></i>
</a>
<a href="javascript:void(0)" data-id="{{ $tanggal_kirim_trunojoyo }}" class="edit btn btn-sm btn-warning" id="edit-data-aft" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_edit_data_detail">
    <i class="fa fa-edit"></i>
</a>
<a href="{{ url('admin/dsgm/kirim_trunojoyo/print/' . Illuminate\Support\Facades\Crypt::encrypt($tanggal_kirim_trunojoyo)) }}" target="_blank" class="print btn btn-sm btn-primary" id="btn-print" style="margin-bottom: 10px;">
    <i class="fa fa-print"></i>
</a>