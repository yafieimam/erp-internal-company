<a href="javascript:void(0)" data-id="{{ $tanggal_terima_dokumen_cust }}" class="view btn btn-sm btn-primary" id="view-data-selesai" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_view_data_selesai">
    <i class="fa fa-eye"></i>
</a>
<a href="{{ url('admin/trunojoyo/penyerahan_selesai/print/' . Illuminate\Support\Facades\Crypt::encrypt($tanggal_terima_dokumen_cust)) }}" target="_blank" class="print btn btn-sm btn-primary" id="btn-print-selesai" style="margin-bottom: 10px;">
    <i class="fa fa-print"></i>
</a>