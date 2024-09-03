@if($status_order == 1)
<a href="#" data-id="{{ $custid }}" data-sj="{{ $nomor_order_receipt }}" class="btn btn-primary" id="add-price-orders" data-toggle="modal" data-target="#modal_detail_order" style="margin-bottom: 10px;">
    Add Price
</a>
<a href="#" data-id="{{ $nomor_order_receipt }}" class="delete btn btn-danger" id="delete-orders"style="margin-bottom: 10px;">
    <i class="fa fa-trash"></i>
</a>
@endif

@if($status_order == 3)
<a href="#" data-id="{{ $nomor_order_receipt }}" class="btn btn-primary" id="add-produksi-orders"style="margin-bottom: 10px;">
    Make SP
</a>
@endif

@if($status_order == 2 || $status_order == 4 || $status_order == 5 || $status_order == 6)
<a href="#" data-id="{{ $custid }}" data-sj="{{ $nomor_order_receipt }}" class="btn btn-primary" id="add-price-orders" data-toggle="modal" data-target="#modal_detail_order" style="margin-bottom: 10px;">
    Lihat
</a>
<a href="#" data-id="{{ $custid }}" data-sj="{{ $nomor_order_receipt }}" class="btn btn-warning" id="edit-orders" data-toggle="modal" data-target="#modal_edit_order" style="margin-bottom: 10px;">
    Edit
</a>
@endif