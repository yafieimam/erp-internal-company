<a href="javascript:void(0)" data-id="{{ $tanggal_laporan_produksi }}" class="view btn btn-sm btn-primary" id="view-data" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_view_data">
    <i class="fa fa-flask"></i>
</a>
@if($jumlah_data > 0)
<a href="javascript:void(0)" data-id="{{ $tanggal_laporan_produksi }}" class="view-prd btn btn-sm btn-primary" id="view-data-prd" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_view_data_prd">
    <i class="fa fa-eye"></i>
</a>
@endif
<a href="javascript:void(0)" data-id="{{ $tanggal_laporan_produksi }}" class="edit btn btn-sm btn-warning" id="edit-data" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_edit_data">
    <i class="fa fa-edit"></i>
</a>
<a href="{{ url('laporan_hasil_lab/print/' . Illuminate\Support\Facades\Crypt::encrypt($tanggal_laporan_produksi)) }}" target="_blank" class="print btn btn-sm btn-primary" id="btn-print" style="margin-bottom: 10px;">
    <i class="fa fa-print"></i>
</a>