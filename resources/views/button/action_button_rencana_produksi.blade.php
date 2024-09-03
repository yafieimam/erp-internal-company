<a href="javascript:void(0)" data-id="{{ $nomor_rencana_produksi }}" class="view btn btn-sm btn-primary" id="view-data" style="margin-bottom: 10px;">
    <i class="fa fa-eye"></i>
</a>
<a href="javascript:void(0)" data-id="{{ $nomor_rencana_produksi }}" class="edit btn btn-sm btn-warning" id="edit-data" style="margin-bottom: 10px;">
    <i class="fa fa-edit"></i>
</a>
<a href="javascript:void(0)" data-id="{{ $nomor_rencana_produksi }}" class="delete btn btn-sm btn-danger" id="delete-data" style="margin-bottom: 10px;">
    <i class="fa fa-trash"></i>
</a>
@if($status == 1)
<a href="javascript:void(0)" data-id="{{ $nomor_rencana_produksi }}" class="input-spek-data-rencana-produksi btn btn-sm btn-primary" id="input-data-spek-rencana-produksi" style="margin-bottom: 10px;">
    <i class="fa fa-pen"></i>
</a>
@else
<a href="javascript:void(0)" data-id="{{ $nomor_rencana_produksi }}" class="edit-spek-data-rencana-produksi btn btn-sm btn-warning" id="edit-data-spek-rencana-produksi" style="margin-bottom: 10px;">
    <i class="fa fa-pen"></i>
</a>
@endif
<a href="{{ url('rencana_produksi/print/' . Illuminate\Support\Facades\Crypt::encrypt($nomor_rencana_produksi)) }}" target="_blank" class="print btn btn-sm btn-primary" id="btn-print" style="margin-bottom: 10px;">
    <i class="fa fa-print"></i>
</a>
