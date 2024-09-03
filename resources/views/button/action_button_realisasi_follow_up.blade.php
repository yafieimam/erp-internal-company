@if($tampil_proses)
	@if($no_status == 1 || $no_status == 2)
		<a href="#" id="btn_proses_schedule" data-id="{{ $id_schedule }}" data-toggle="modal" data-target="#modal_proses_schedule" class="proses btn btn-sm btn-primary" style="margin-bottom: 10px;">
		    <i class="fa fa-edit"></i>
		</a>
	@endif
@endif