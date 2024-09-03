@if($status == 1)
<a href="#" id="update-data" data-id="{{ $nomor }}" data-toggle="modal" data-target="#modal_update_data" class="update btn btn-sm btn-primary" style="margin-bottom: 10px;">
	<i class="fa fa-edit"></i>
</a>
@elseif($status == 2)
<a href="javascript:void(0)" data-id="{{ $nomor }}" class="view btn btn-sm btn-primary" id="view-data" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_view_data">
    <i class="fa fa-eye"></i>
</a>
<a href="javascript:void(0)" data-id="{{ $nomor }}" class="edit btn btn-sm btn-warning" id="edit-data" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_edit_data">
    <i class="fa fa-edit"></i>
</a>
<a href="{{ url('surat_penawaran/print/' . Illuminate\Support\Facades\Crypt::encrypt($nomor)) }}" target="_blank" class="print btn btn-sm btn-primary" id="btn-print" style="margin-bottom: 10px;">
    <i class="fa fa-print"></i>
</a>
@endif