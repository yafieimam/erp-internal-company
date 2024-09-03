<a href="javascript:void(0)" data-id="{{ $nomor }}" class="view btn btn-sm btn-primary" id="view-data" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_view_data">
    <i class="fa fa-eye"></i>
</a>
@if($status_uji_data_produksi == 0)
<a href="javascript:void(0)" data-id="{{ $nomor }}" class="input btn btn-sm btn-primary" id="input-data" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_input_data">
    <i class="fa fa-edit"></i>
</a>
@else
<a href="javascript:void(0)" data-id="{{ $nomor }}" class="edit btn btn-sm btn-warning" id="edit-data" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_edit_data">
    <i class="fa fa-edit"></i>
</a>
@endif
@if($status == 4)
<a href="{{ url('uji_sample/print/' . Illuminate\Support\Facades\Crypt::encrypt($nomor)) }}" target="_blank" class="print btn btn-sm btn-primary" id="btn-print" style="margin-bottom: 10px;">
    <i class="fa fa-print"></i>
</a>
@endif