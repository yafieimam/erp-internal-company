@if($kode == 1)
<a href="javascript:void(0)" data-id="{{ $tanggal }}" class="view btn btn-sm btn-primary" id="view-data" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_lihat_inventaris">
    <i class="fa fa-eye"></i>
</a>
<a href="{{ url('raw_material/inventaris_batu/print/' . Illuminate\Support\Facades\Crypt::encrypt($tanggal)) }}" target="_blank" class="print btn btn-sm btn-primary" id="btn-print" style="margin-bottom: 10px;">
    <i class="fa fa-print"></i>
</a>
@endif