<a href="{{ url('schedule/jadwal_followup/print/' . Illuminate\Support\Facades\Crypt::encrypt($tanggal_schedule)) }}" target="_blank" class="print btn btn-sm btn-primary" id="btn-print" style="margin-bottom: 10px;">
    <i class="fa fa-print"></i>
</a>
<a href="javascript:void(0)" data-id="{{ $tanggal_schedule }}" class="edit btn btn-sm btn-warning" id="edit-data" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_edit_data">
    <i class="fa fa-edit"></i>
</a>