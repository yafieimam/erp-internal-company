@if($kode == 1)
<a href="{{ url('inventaris/print/' . Illuminate\Support\Facades\Crypt::encrypt($tanggal)) }}" target="_blank" class="print btn btn-sm btn-primary" id="btn-print" style="margin-bottom: 10px;">
    <i class="fa fa-print"></i>
</a>
@endif