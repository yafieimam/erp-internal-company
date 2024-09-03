<a href="javascript:void(0)" data-id="{{ $tanggal_do }}" class="view btn btn-sm btn-primary" id="view-data-rencana" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_view_data_rencana">
    <i class="fa fa-eye"></i>
</a>
<a href="{{ url('admin/trunojoyo/rencana_penyerahan/print/' . Illuminate\Support\Facades\Crypt::encrypt($tanggal_do)) }}" target="_blank" class="print btn btn-sm btn-primary" id="btn-print-rencana" style="margin-bottom: 10px;">
    <i class="fa fa-print"></i>
</a>
<a href="javascript:void(0)" data-id="{{ $tanggal_do }}" class="sorting btn btn-sm btn-primary" id="sorting-data" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_sorting_data">
    <i class="fa fa-edit"></i>
</a>