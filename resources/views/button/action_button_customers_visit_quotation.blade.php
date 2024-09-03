@if($status == 3)
<a href="#" id="btn_quotation" data-id="{{ $id_schedule }}" data-cust="{{ $custid }}" data-toggle="modal" data-target="#modal_quotation" class="edit btn btn-sm btn-primary" style="margin-bottom: 10px;">
	<i class="fa fa-edit"></i>
</a>
@elseif($status > 5)
<a href="{{ url('sales/customers_visit/quotation/print/' . Illuminate\Support\Facades\Crypt::encrypt($nomor_quotation)) }}" target="_blank" class="print btn btn-sm btn-success" id="btn-print" style="margin-bottom: 10px;">
    <i class="fa fa-print"></i>
</a>
@endif