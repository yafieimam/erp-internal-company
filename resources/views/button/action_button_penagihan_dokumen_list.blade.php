<a href="javascript:void(0)" data-id="{{ $tanggal_terima_ondomohen }}" class="view btn btn-sm btn-primary" id="view-data" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_view_data">
    <i class="fa fa-eye"></i>
</a>
<a href="{{ url('ondomohen/dokumen_list/print/' . Illuminate\Support\Facades\Crypt::encrypt($tanggal_terima_ondomohen)) }}" target="_blank" class="print btn btn-sm btn-primary" id="btn-print" style="margin-bottom: 10px;">
    <i class="fa fa-print"></i>
</a>