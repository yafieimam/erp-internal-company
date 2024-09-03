@if($no_status == 1 || $no_status == 2)
<a href="#" id="btn_proses_schedule" data-id="{{ $id_schedule }}" data-toggle="modal" data-target="#modal_proses_schedule" class="edit btn btn-sm btn-primary" style="margin-bottom: 10px;">
	<i class="fa fa-edit"></i>
</a>
@endif
<a href="#" id="btn_view_schedule" data-id="{{ $id_schedule }}" data-toggle="modal" data-target="#modal_view_schedule" class="view btn btn-sm btn-primary" style="margin-bottom: 10px;">
	<i class="fa fa-eye"></i>
</a>
@if($no_status == 1 || $no_status == 2)
<a href="#" id="btn_edit_schedule" data-id="{{ $id_schedule }}" data-toggle="modal" data-target="#modal_edit_schedule" class="edit btn btn-sm btn-warning" style="margin-bottom: 10px;">
	<i class="fa fa-edit"></i>
</a>
@endif
@if($no_status == 3)
<a href="{{ url('sales/customer_visit/print/' . Illuminate\Support\Facades\Crypt::encrypt($id_schedule)) }}" target="_blank" id="btn_print_schedule" class="print btn btn-sm btn-success" style="margin-bottom: 10px;">
    <i class="fa fa-print"></i>
</a>
@endif