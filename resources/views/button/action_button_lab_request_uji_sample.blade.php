@if($status_uji_data_lab == 0)
<a href="javascript:void(0)" data-id="{{ $nomor }}" class="input btn btn-sm btn-primary" id="input-data" style="margin-bottom: 10px;">
    <i class="fa fa-edit"></i>
</a>
@else
<a href="javascript:void(0)" data-id="{{ $nomor }}" class="view btn btn-sm btn-primary" id="view-data" style="margin-bottom: 10px;">
    <i class="fa fa-eye"></i>
</a>
<a href="javascript:void(0)" data-id="{{ $nomor }}" class="edit btn btn-sm btn-warning" id="edit-data" style="margin-bottom: 10px;">
    <i class="fa fa-edit"></i>
</a>
@endif

@if($status == 2)
<a href="javascript:void(0)" data-id="{{ $nomor }}" class="update btn btn-sm btn-primary" id="update-data" style="margin-bottom: 10px;">
    <i class="fa fa-edit"></i>
</a>
@endif