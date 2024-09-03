@if($no_status == 8)
<a href="javascript:void(0)" data-id="{{ $nomor_complaint }}" id="validate-complaint" class="proses btn btn-primary" style="margin-bottom: 10px;">
    Validate
</a>
@endif
<a href="#" data-id="{{ $nomor_complaint }}" class="lihat btn btn-primary" id="lihat-complaint" data-toggle="modal" data-target="#modalshow" style="margin-bottom: 10px;">
    Lihat
</a>