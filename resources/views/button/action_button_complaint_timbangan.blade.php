@if($stat_timbangan == 0)
<a href="#" data-id="{{ $nomor_complaint }}" data-toggle="modal" data-target="#modal_proses_complaint_timbangan" id="proses_complaint_timbangan" data-divisi="{{ $div_timbangan }}" data-custid="{{ $custid }}" class="buka btn btn-primary" style="margin-bottom: 10px;">
    Proses
</a>
@endif
<a href="javascript:void(0)" data-id="{{ $nomor_complaint }}" id="rollback_complaint_timbangan" class="proses btn btn-danger" style="margin-bottom: 10px; display: none;">
    Back
</a>
@if($stat_timbangan == 2 && $stat_validasi == 0)
<a href="#" data-id="{{ $nomor_complaint }}" class="lihat btn btn-primary" id="lihat_complaint_produksi" data-toggle="modal" data-target="#modal_lihat_complaint_produksi" style="margin-bottom: 10px;">
    Lihat
</a>
<a href="#" data-id="{{ $nomor_complaint }}" data-toggle="modal" data-target="#modal_edit_complaint_timbangan" id="edit_complaint_timbangan" data-custid="{{ $custid }}" class="edit btn btn-warning" style="margin-bottom: 10px;">
    Edit
</a>
@endif
@if($stat_validasi == 2)
<a href="{{ url('print_form_complaint/' . Illuminate\Support\Facades\Crypt::encrypt($nomor_complaint)) }}" target="_blank" id="print_complaint" class="proses btn btn-success" style="margin-bottom: 10px;">
    Selesai - Print
</a>
<a href="#" data-id="{{ $nomor_complaint }}" class="lihat btn btn-primary" id="lihat_complaint_produksi" data-toggle="modal" data-target="#modal_lihat_complaint_produksi" style="margin-bottom: 10px;">
    Lihat
</a>
@endif
@if($stat_validasi == 1 && Session::get('tipe_user') == 1)
<a href="#" data-id="{{ $nomor_complaint }}" id="validate_complaint_timbangan" class="proses btn btn-warning" data-toggle="modal" data-target="#modal_validasi_complaint" style="margin-bottom: 10px;">
    Validate
</a>
<a href="#" data-id="{{ $nomor_complaint }}" class="lihat btn btn-primary" id="lihat_complaint_produksi" data-toggle="modal" data-target="#modal_lihat_complaint_produksi" style="margin-bottom: 10px;">
    Lihat
</a>
<a href="#" data-id="{{ $nomor_complaint }}" data-toggle="modal" data-target="#modal_edit_complaint_timbangan" id="edit_complaint_timbangan" data-custid="{{ $custid }}" class="edit btn btn-warning" style="margin-bottom: 10px;">
    Edit
</a>
@endif
@if($stat_validasi == 1 && Session::get('tipe_user') != 1)
    @if($div_timbangan == 1)
    <a href="#" data-id="{{ $nomor_complaint }}" data-toggle="modal" data-target="#modal_proses_complaint_timbangan" id="proses_complaint_timbangan" data-divisi="{{ $div_timbangan }}" data-custid="{{ $custid }}" class="buka btn btn-primary" style="margin-bottom: 10px;">
    Proses
    </a>
    @endif
<a href="#" data-id="{{ $nomor_complaint }}" class="lihat btn btn-primary" id="lihat_complaint_produksi" data-toggle="modal" data-target="#modal_lihat_complaint_produksi" style="margin-bottom: 10px;">
    Lihat
</a>
<a href="#" data-id="{{ $nomor_complaint }}" data-toggle="modal" data-target="#modal_edit_complaint_timbangan" id="edit_complaint_timbangan" data-custid="{{ $custid }}" class="edit btn btn-warning" style="margin-bottom: 10px;">
    Edit
</a>
@endif
<a href="#" data-id="{{ $nomor_complaint }}" data-toggle="modal" data-target="#modal_logbook" id="btn-logbook" class="btn btn-primary" style="margin-bottom: 10px;">
    Logbook
</a>