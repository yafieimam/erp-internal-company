@if($no_status == 5)
<a href="javascript:void(0)" data-id="{{ $id_schedule }}" data-custid="{{ $custid }}" data-tipe="{{ $tipe }}" class="realisasi btn btn-sm btn-primary" id="realisasi-data" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_isi_question">
    <i class="fa fa-edit"></i>
</a>
@endif

@if($no_status == 3 || $no_status > 5)
<a href="#" id="btn_view_question" data-id="{{ $id_schedule }}" data-toggle="modal" data-target="#modal_view_question" class="view btn btn-sm btn-primary" style="margin-bottom: 10px;">
	<i class="fa fa-eye"></i>
</a>
<a href="javascript:void(0)" data-id="{{ $id_schedule }}" data-custid="{{ $custid }}" class="realisasi btn btn-sm btn-warning" id="edit-realisasi-data" style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal_edit_question">
    <i class="fa fa-edit"></i>
</a>
@endif