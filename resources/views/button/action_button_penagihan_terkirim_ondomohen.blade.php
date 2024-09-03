<a href="javascript:void(0)" data-id="{{ $tanggal_kirim_ondomohen }}" class="view btn btn-sm btn-primary" id="view-data-ondomohen" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_view_data_ondomohen">
    <i class="fa fa-eye"></i>
</a>
<a href="javascript:void(0)" data-id="{{ $tanggal_kirim_ondomohen }}" class="edit btn btn-sm btn-warning" id="edit-data-ondomohen-aft" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_edit_ondomohen_data_detail">
    <i class="fa fa-edit"></i>
</a>
<a href="{{ url('admin/dsgm/kirim_ondomohen/print/' . Illuminate\Support\Facades\Crypt::encrypt($tanggal_kirim_ondomohen)) }}" target="_blank" class="print btn btn-sm btn-primary" id="btn-print-ondomohen" style="margin-bottom: 10px;">
    <i class="fa fa-print"></i>
</a>