<a href="javascript:void(0)" data-id="{{ $tanggal_kirim_cek_ondomohen }}" class="view btn btn-sm btn-primary" id="view-data-ondomohen" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_view_data_ondomohen">
    <i class="fa fa-eye"></i>
</a>
<a href="{{ url('penagihan/kirim_cek/ondomohen/print/' . Illuminate\Support\Facades\Crypt::encrypt($tanggal_kirim_cek_ondomohen)) }}" target="_blank" class="print btn btn-sm btn-primary" id="btn-print" style="margin-bottom: 10px;">
    <i class="fa fa-print"></i>
</a>