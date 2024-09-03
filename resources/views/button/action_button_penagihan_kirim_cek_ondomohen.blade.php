<a href="javascript:void(0)" data-id="{{ $tanggal_terima_cek }}" class="send btn btn-sm btn-primary" id="send-data" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_send_data">
    <i class="fa fa-paper-plane"></i>
</a>
<a href="javascript:void(0)" data-id="{{ $tanggal_terima_cek }}" class="view btn btn-sm btn-primary" id="view-data" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_view_data">
    <i class="fa fa-eye"></i>
</a>
<a href="{{ url('penagihan/terima_cek/detail/print/' . Illuminate\Support\Facades\Crypt::encrypt($tanggal_terima_cek)) }}" target="_blank" class="print btn btn-sm btn-primary" id="btn-print-kirim" style="margin-bottom: 10px;">
    <i class="fa fa-print"></i>
</a>