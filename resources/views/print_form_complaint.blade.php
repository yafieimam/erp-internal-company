<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Form Complaint</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page { margin: 15px; }
        * { box-sizing: border-box; }
        body{
            margin-left: 30px;
            margin-right: 30px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
    @if($data->no_div == 2)
    <style>
        .td-utama{
            font-size: 11px;
        }
        .td-solusi{
            width: 92px;
            font-size: 11px;
        }
        .div-approved{
            vertical-align: all; 
            font-size: 16px; 
            font-weight: 900; 
            font-style: oblique; 
            transform: rotate(-10deg); 
            margin-left: 10px; 
        }
        .div-approved-solusi{
            vertical-align: all; 
            font-size: 16px; 
            font-weight: 900; 
            font-style: oblique; 
            transform: rotate(-10deg); 
            margin-left: 10px; 
        }
    </style>
    @endif
    @if($data->no_div == 4)
    <style>
        .td-utama{
            font-size: 11px;
        }
        .td-solusi{
            width: 92px;
            font-size: 11px;
        }
        .div-approved{
            vertical-align: all; 
            font-size: 15px; 
            font-weight: 900; 
            font-style: oblique; 
            transform: rotate(-20deg); 
            margin-left: 10px; 
        }
        .div-approved-solusi{
            vertical-align: all; 
            font-size: 15px; 
            font-weight: 900; 
            font-style: oblique; 
            transform: rotate(-20deg); 
            margin-left: 10px; 
        }
    </style>
    @endif
    @if($data->no_div == 6)
    <style>
        .td-utama{
            font-size: 10px;
        }
        .td-solusi{
            width: 82px;
            font-size: 10px;
        }
        .div-approved{
            vertical-align: all; 
            font-size: 15px; 
            font-weight: 900; 
            font-style: oblique; 
            transform: rotate(-20deg); 
            margin-left: 10px; 
            margin-bottom: 2px;
        }
        .div-approved-solusi{
            vertical-align: all; 
            font-size: 15px; 
            font-weight: 900; 
            font-style: oblique; 
            transform: rotate(-20deg); 
            margin-left: 10px; 
            margin-bottom: 2px;
        }
    </style>
    @endif
    @if($data->no_div == 8)
    <style>
        .td-utama{
            font-size: 9px;
        }
        .td-solusi{
            width: 76.8px;
            font-size: 9px; 
        }
        .div-approved{
            vertical-align: all; 
            font-size: 13px; 
            font-weight: 900; 
            font-style: oblique; 
            transform: rotate(-20deg); 
            margin-left: 10px; 
            margin-bottom: 5px;
        }
        .div-approved-solusi{
            vertical-align: all; 
            font-size: 13px; 
            font-weight: 900; 
            font-style: oblique; 
            transform: rotate(-20deg); 
            margin-left: 10px; 
            margin-bottom: 5px;
        }
    </style>
    @endif
    @if($data->no_div == 10)
    <style>
        .td-utama{
            font-size: 12px;
        }
        .td-solusi{
            width: 76.8px;
            font-size: 12px; 
        }
        .div-approved{
            vertical-align: all; 
            font-size: 15px; 
            font-weight: 900; 
            font-style: oblique; 
            transform: rotate(-10deg); 
            margin-left: 10px; 
        }
        .div-approved-solusi{
            vertical-align: all; 
            font-size: 13px; 
            font-weight: 900; 
            font-style: oblique; 
            transform: rotate(-20deg); 
            margin-left: 10px; 
            margin-bottom: 5px;
        }
    </style>
    @endif
    @if($data->no_div == 12)
    <style>
        .td-utama{
            font-size: 12px;
        }
        .td-solusi{
            width: 76.8px;
            font-size: 11px; 
        }
        .div-approved{
            vertical-align: all; 
            font-size: 16px; 
            font-weight: 900; 
            font-style: oblique; 
            transform: rotate(-10deg); 
            margin-left: 10px; 
        }
        .div-approved-solusi{
            vertical-align: all; 
            font-size: 13px; 
            font-weight: 900; 
            font-style: oblique; 
            transform: rotate(-20deg); 
            margin-left: 10px; 
            margin-bottom: 5px;
        }
    </style>
    @endif
    @if($data->no_div == 14)
    <style>
        .td-utama{
            font-size: 12px;
        }
        .td-solusi{
            width: 76.8px;
            font-size: 9px; 
        }
        .div-approved{
            vertical-align: all; 
            font-size: 16px; 
            font-weight: 900; 
            font-style: oblique; 
            transform: rotate(-10deg); 
            margin-left: 10px; 
        }
        .div-approved-solusi{
            vertical-align: all; 
            font-size: 13px; 
            font-weight: 900; 
            font-style: oblique; 
            transform: rotate(-20deg); 
            margin-left: 10px; 
            margin-bottom: 5px;
        }
    </style>
    @endif
</head>
<body>
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td rowspan="2" style="width: 25%; border: 1px solid;"><img src="{{ public_path('app-assets/asset/images/logo-footer.png') }}" style="width: 50px; margin-left: 35%;"><br><div style="font-size: 14px; text-align: center; margin-top: 10px; font-weight: 900;">PT. DWI SELO GIRI MAS</div></td>
            <td rowspan="2" style="width: 50%; border: 1px solid; text-align: center; font-size: 20px; font-family: 'Times New Roman'; font-weight: 900;">FORM KOMPLAIN</td>
            <td style="width: 25%; border: 1px solid; font-size: 12px;">No. Komplain :<br><div style="font-size: 16px; margin-top: 10px; text-align: center;">{{ $data->nomor_complaint }}</div></td>
        </tr>
        <tr>
            <td style="width: 25%; border: 1px solid; font-size: 12px;">Tanggal Komplain :<br><div style="font-size: 16px; margin-top: 10px; text-align: center;">{{ $data->tanggal_complaint }}</div></td>
        </tr>
    </table>
    <table style="width: 100%; border-left: 1px solid; border-right: 1px solid; border-collapse: collapse; font-size: 14px;">
        <tr style="">
            <td colspan="7" style="height: 20px;"></td>
        </tr>
        <tr>
            <td style="width: 18%; padding: 5px; padding-bottom: 0;">Nama Pelanggan :</td>
            <td colspan="5" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->nama_customer }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 18%; padding: 5px; padding-bottom: 0;">No Surat Jalan :</td>
            <td colspan="5" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->nomor_surat_jalan }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 18%; padding: 5px; padding-bottom: 0;">Order By Sales :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->sales_order }}</td>
            <td style="width: 12%; padding: 5px; text-align: right; padding-bottom: 0;">SPV Sales :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->supervisor_sales }}</td>
            <td style="width: 10%; padding: 5px; text-align: right; padding-bottom: 0;">Pelapor :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->pelapor }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 18%; padding: 5px; padding-bottom: 0;">Perihal Komplaint :</td>
            <td colspan="5" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->complaint }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 18%; padding: 5px; padding-bottom: 0;">Solusi Customer :</td>
            <td colspan="5" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $sales->solusi_customer }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 18%; padding: 5px; padding-bottom: 0;">Tanggal Customer Menyetujui :</td>
            <td colspan="5" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $sales->tanggal_customer_setuju }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 18%; padding: 5px; padding-bottom: 0;">Lampiran :</td>
            <td colspan="5" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->lampiran }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 18%; padding: 5px; padding-bottom: 0;">Divisi Yang Dituju :</td>
            <td colspan="5" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->divisi }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 21%; padding: 5px; padding-bottom: 0;">Quantity :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->quantity }}</td>
            <td style="width: 11%; padding: 5px; text-align: left; padding-bottom: 0;">Jenis Karung :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->jenis_karung }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 21%; padding: 5px; padding-bottom: 0;">Jumlah Karung :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->jumlah_karung }}</td>
            <td style="width: 11%; padding: 5px; text-align: left; padding-bottom: 0;">Berat Timbangan :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->berat_timbangan }}</td>
            <td style="width: 11%; padding: 5px; text-align: left; padding-bottom: 0;">Berat Perhitungan :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->berat_aktual }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr style="">
            <td colspan="7" style="height: 20px; padding: 5px; padding-bottom: 0;">Data Logistik</td>
        </tr>
        <tr>
            <td style="width: 21%; padding: 5px; padding-bottom: 0;">Tanggal Pengiriman :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data_logistik->tanggal_pengiriman }}</td>
            <td style="width: 11%; padding: 5px; text-align: right; padding-bottom: 0;">Area :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data_logistik->area }}</td>
            <td style="width: 11%; padding: 5px; text-align: right; padding-bottom: 0;">Supervisor :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data_logistik->supervisor }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 18%; padding: 5px; padding-bottom: 0;">Nama Supir :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data_logistik->nama_supir }}</td>
            <td style="width: 11%; padding: 5px; text-align: right; padding-bottom: 0;">Nama Kernet :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data_logistik->nama_kernet }}</td>
            <td style="width: 12%; padding: 5px; text-align: right; padding-bottom: 0;">No Kendaraan :</td>
            <td style="width: 10%; border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data_logistik->no_kendaraan }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 11%; padding: 5px; padding-bottom: 0;">Nama Kuli :</td>
            <td colspan="5" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">
            <?php
            if($data_logistik->kuli1 != null){
            ?>
                {{ $data_logistik->kuli1 }}
            <?php
            }
            if($data_logistik->kuli2 != null){
            ?>
                , {{ $data_logistik->kuli2 }}
            <?php
            }
            if($data_logistik->kuli3 != null){
            ?>
                , {{ $data_logistik->kuli3 }}
            <?php
            }
            ?>
            </td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 11%; padding: 5px; padding-bottom: 0;">Nama Stapel :</td>
            <td colspan="5" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">
            <?php
            if($data_logistik->stapel1 != null){
            ?>
                {{ $data_logistik->stapel1 }}
            <?php
            }
            if($data_logistik->stapel2 != null){
            ?>
                , {{ $data_logistik->stapel2 }}
            <?php
            }
            if($data_logistik->stapel3 != null){
            ?>
                , {{ $data_logistik->stapel3 }}
            <?php
            }
            if($data_logistik->stapel4 != null){
            ?>
                , {{ $data_logistik->stapel4 }}
            <?php
            }
            if($data_logistik->stapel5 != null){
            ?>
                , {{ $data_logistik->stapel5 }}
            <?php
            }
            ?>
            </td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 18%; padding: 5px; padding-bottom: 0;">Pengiriman :</td>
            <td colspan="5" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data_logistik->pengiriman . ' ' . $data_logistik->pengiriman_lain }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 18%; padding: 5px; padding-bottom: 0;">Jenis Kendaraan :</td>
            <td colspan="5" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data_logistik->jenis_kendaraan . ' ' . $data_logistik->jenis_kendaraan_lain }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <?php
        for($i = 1; $i <= count($data_produksi); $i++){
        ?>
        <tr style="">
            <td colspan="7" style="height: 20px; padding: 5px; padding-bottom: 0;">Data Produksi {{ $i }}</td>
        </tr>
        <tr>
            <td style="width: 20%; padding: 5px; padding-bottom: 0;">Tanggal Produksi {{ $i }} :</td>
            <td style="width: 14%; border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data_produksi[$i-1]->tanggal_produksi }}</td>
            <td style="width: 9%; padding: 5px; text-align: right; padding-bottom: 0;">No Lot {{ $i }} :</td>
            <td style="width: 10%; border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data_produksi[$i-1]->no_lot }}</td>
            <td style="width: 12%; padding: 5px; text-align: right; padding-bottom: 0;">Mesin {{ $i }} :</td>
            <td style="width: 9%; border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data_produksi[$i-1]->mesin }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 16%; padding: 5px; padding-bottom: 0;">Area {{ $i }} :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data_produksi[$i-1]->area }}</td>
            
            <td style="width: 11%; padding: 5px; text-align: right; padding-bottom: 0;">Supervisor {{ $i }} :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data_produksi[$i-1]->supervisor }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 11%; padding: 5px; padding-bottom: 0;">Petugas {{ $i }} :</td>
            <td colspan="5" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">
            <?php
            if($data_produksi[$i-1]->petugas1 != null){
            ?>
                {{ $data_produksi[$i-1]->petugas1 }}
            <?php
            }
            if($data_produksi[$i-1]->petugas2 != null){
            ?>
                , {{ $data_produksi[$i-1]->petugas2 }}
            <?php
            }
            if($data_produksi[$i-1]->petugas3 != null){
            ?>
                , {{ $data_produksi[$i-1]->petuga3 }}
            <?php
            }
            if($data_produksi[$i-1]->petugas4 != null){
            ?>
                , {{ $data_produksi[$i-1]->petugas4 }}
            <?php
            }
            if($data_produksi[$i-1]->petugas5 != null){
            ?>
                , {{ $data_produksi[$i-1]->petugas5 }}
            <?php
            }
            ?>
            </td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 13%; padding: 5px; padding-bottom: 0;">Produk {{ $i }} :</td>
            <td colspan="5" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data_produksi[$i-1]->nama_produk }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <?php
        }
        ?>
        <tr>
            <td style="width: 18%; border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td colspan="5" style="border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td style="width: 2%; border-bottom: 1px solid; padding-bottom: 0;"></td>
        </tr>
    </table>
    <?php
    $tmp_count = 0;
    ?>
    @if($produksi)
    <table style="width: 100%; border-left: 1px solid; border-top: 1px solid; border-right: 1px solid; border-collapse: collapse; font-size: 14px;">
        <?php $tmp_count++; ?>
        <tr style="">
            <td colspan="9" style="height: 20px; padding: 5px; padding-bottom: 0;">Produksi</td>
        </tr>
        <tr style="display: none;">
            <td style="width: 16%; padding: 5px; padding-bottom: 0;">Evaluasi :</td>
            <td colspan="7" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $produksi->evaluasi }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 16%; padding: 5px; padding-bottom: 0;">Solusi Internal :</td>
            <td colspan="7" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $produksi->solusi_internal }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 16%; padding: 5px; padding-bottom: 0;">Lampiran :</td>
            <td  colspan="7"style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $produksi->lampiran }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        @if($komitmen_produksi)
        <?php
        for($i = 1; $i <= count($komitmen_produksi); $i++){
        ?>
        <tr>
            <td style="width: 19%; padding: 5px; padding-bottom: 0;">Komitmen {{ $i }} :</td>
            <td colspan="4" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $komitmen_produksi[$i-1]->komitmen }}</td>
            <td colspan="2" style="width: 20%; padding: 5px; text-align: right; padding-bottom: 0;">Selesai Tanggal {{ $i }} :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $komitmen_produksi[$i-1]->selesai_tanggal_komitmen }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <?php
        }
        ?>
        @endif
        <tr>
            <td style="width: 16%; border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td colspan="7" style="border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td style="width: 2%; border-bottom: 1px solid; padding-bottom: 0;"></td>
        </tr>
    </table>
    @endif
    
    @if($logistik)
    <?php $tmp_count++; ?>
    <table style="width: 100%; border-left: 1px solid; border-top: 1px solid; border-right: 1px solid; border-collapse: collapse; font-size: 14px;">
        <tr style="">
            <td colspan="5" style="height: 20px; padding: 5px; padding-bottom: 0;">Logistik</td>
        </tr>
        <tr>
            <td style="width: 18%; padding: 5px; padding-bottom: 0;">Evaluasi :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $logistik->evaluasi }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 18%; padding: 5px; padding-bottom: 0;">Solusi Internal :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $logistik->solusi_internal }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 18%; padding: 5px; padding-bottom: 0;">Lampiran :</td>
            <td  colspan="3"style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $logistik->lampiran }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
    @if($komitmen_logistik)
    <?php
    for($i = 1; $i <= count($komitmen_logistik); $i++){
        ?>
        <tr>
            <td style="width: 19%; padding: 5px; padding-bottom: 0;">Komitmen {{ $i }} :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $komitmen_logistik[$i-1]->komitmen }}</td>
            <td style="width: 20%; padding: 5px; text-align: right; padding-bottom: 0;">Selesai Tanggal {{ $i }} :</td>
            <td style="width: 18%; border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $komitmen_logistik[$i-1]->selesai_tanggal_komitmen }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <?php
    }
    ?>
    @endif
        <tr>
            <td style="width: 18%; border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td colspan="3" style="border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td style="width: 2%; border-bottom: 1px solid; padding-bottom: 0;"></td>
        </tr>
    </table>
    @endif
    @if($data->no_div >= 6 && $tmp_count == 2)
        <div class="page-break"></div>
    @endif
    <?php $tmp_count++; ?>
    <table style="width: 100%; border-left: 1px solid; border-top: 1px solid; border-right: 1px solid; border-collapse: collapse; font-size: 14px;">
        <tr style="">
            <td colspan="5" style="height: 20px; padding: 5px; padding-bottom: 0;">Sales</td>
        </tr>
        <tr>
            <td style="width: 19%; padding: 5px; padding-bottom: 0;">Evaluasi :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $sales->evaluasi }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 16%; padding: 5px; padding-bottom: 0;">Solusi Internal :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $sales->solusi_internal }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 16%; padding: 5px; padding-bottom: 0;">Lampiran :</td>
            <td  colspan="3"style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $sales->lampiran }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        @if($komitmen_sales)
        <?php
        for($i = 1; $i <= count($komitmen_sales); $i++){
            ?>
            <tr>
                <td style="width: 19%; padding: 5px; padding-bottom: 0;">Komitmen {{ $i }} :</td>
                <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $komitmen_sales[$i-1]->komitmen }}</td>
                <td style="width: 20%; padding: 5px; text-align: right; padding-bottom: 0;">Selesai Tanggal {{ $i }} :</td>
                <td style="width: 18%; border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $komitmen_sales[$i-1]->selesai_tanggal_komitmen }}</td>
                <td style="width: 2%; padding-bottom: 0;"></td>
            </tr>
            <?php
        }
        ?>
        @endif
        <tr>
            <td style="width: 16%; border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td colspan="3" style="border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td style="width: 2%; border-bottom: 1px solid; padding-bottom: 0;"></td>
        </tr>
    </table>
    @if($data->no_div >= 6 && $tmp_count == 2)
        <div class="page-break"></div>
    @endif
    @if($timbangan)
    <?php $tmp_count++; ?>
    <table style="width: 100%; border-left: 1px solid; border-top: 1px solid; border-right: 1px solid; border-collapse: collapse; font-size: 14px;">
        <tr style="">
            <td colspan="5" style="height: 20px; padding: 5px; padding-bottom: 0;">Timbangan</td>
        </tr>
        <tr>
            <td style="width: 19%; padding: 5px; padding-bottom: 0;">Evaluasi :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $timbangan->evaluasi }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 18%; padding: 5px; padding-bottom: 0;">Solusi Internal :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $timbangan->solusi_internal }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 18%; padding: 5px; padding-bottom: 0;">Lampiran :</td>
            <td  colspan="3"style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $timbangan->lampiran }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        @if($komitmen_timbangan)
        <?php
        for($i = 1; $i <= count($komitmen_timbangan); $i++){
            ?>
            <tr>
                <td style="width: 19%; padding: 5px; padding-bottom: 0;">Komitmen {{ $i }} :</td>
                <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $komitmen_timbangan[$i-1]->komitmen }}</td>
                <td style="width: 20%; padding: 5px; text-align: right; padding-bottom: 0;">Selesai Tanggal {{ $i }} :</td>
                <td style="width: 18%; border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $komitmen_timbangan[$i-1]->selesai_tanggal_komitmen }}</td>
                <td style="width: 2%; padding-bottom: 0;"></td>
            </tr>
            <?php
        }
        ?>
        @endif
        <tr>
            <td style="width: 18%; border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td colspan="3" style="border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td style="width: 2%; border-bottom: 1px solid; padding-bottom: 0;"></td>
        </tr>
    </table>
    @endif
    @if($data->no_div >= 6 && $tmp_count == 2)
        <div class="page-break"></div>
    @endif
    @if($warehouse)
    <?php $tmp_count++; ?>
    <table style="width: 100%; border-left: 1px solid; border-top: 1px solid; border-right: 1px solid; border-collapse: collapse; font-size: 14px;">
        <tr style="">
            <td colspan="5" style="height: 20px; padding: 5px; padding-bottom: 0;">Warehouse</td>
        </tr>
        <tr>
            <td style="width: 19%; padding: 5px; padding-bottom: 0;">Evaluasi :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $warehouse->evaluasi }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 18%; padding: 5px; padding-bottom: 0;">Solusi Internal :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $warehouse->solusi_internal }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 18%; padding: 5px; padding-bottom: 0;">Lampiran :</td>
            <td  colspan="3"style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $warehouse->lampiran }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        @if($komitmen_warehouse)
        <?php
        for($i = 1; $i <= count($komitmen_warehouse); $i++){
            ?>
            <tr>
                <td style="width: 19%; padding: 5px; padding-bottom: 0;">Komitmen {{ $i }} :</td>
                <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $komitmen_warehouse[$i-1]->komitmen }}</td>
                <td style="width: 20%; padding: 5px; text-align: right; padding-bottom: 0;">Selesai Tanggal {{ $i }} :</td>
                <td style="width: 18%; border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $komitmen_warehouse[$i-1]->selesai_tanggal_komitmen }}</td>
                <td style="width: 2%; padding-bottom: 0;"></td>
            </tr>
            <?php
        }
        ?>
        @endif
        <tr>
            <td style="width: 18%; border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td colspan="3" style="border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td style="width: 2%; border-bottom: 1px solid; padding-bottom: 0;"></td>
        </tr>
    </table>
    @endif
    @if($data->no_div >= 6 && $tmp_count == 2)
        <div class="page-break"></div>
    @endif
    @if($lab)
    <?php $tmp_count++; ?>
    <table style="width: 100%; border-left: 1px solid; border-top: 1px solid; border-right: 1px solid; border-collapse: collapse; font-size: 14px;">
        <tr style="">
            <td colspan="7" style="height: 20px; padding: 5px; padding-bottom: 0;">Lab</td>
        </tr>
        <tr>
            <td style="width: 13%; padding: 5px; padding-bottom: 0;">Suggestion :</td>
            <td colspan="5" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $lab->suggestion }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 13%; padding: 5px; padding-bottom: 0;">Lampiran :</td>
            <td  colspan="5"style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $lab->lampiran }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        @if($data_lab)
        <?php
        for($i = 1; $i <= count($data_lab); $i++){
        ?>
            <tr style="">
                <td colspan="7" style="height: 20px; padding: 5px; padding-bottom: 0;">Data Sample {{ $i }}</td>
            </tr>
            <tr>
                <td style="width: 15%; padding: 5px; padding-bottom: 0;">No Lot {{ $i }} :</td>
                <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data_lab[$i-1]->no_lot }}</td>
                <td style="width: 18%; padding: 5px; padding-bottom: 0;">Keterangan {{ $i }} :</td>
                <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data_lab[$i-1]->keterangan }}</td>
                <td style="width: 2%; padding-bottom: 0;"></td>
            </tr>
            @if($data_quality_lab)
            <?php
            for($j = 1; $j <= count($data_quality_lab); $j++){
                if($data_lab[$i-1]->nomor_sample_lab == $data_quality_lab[$j-1]->nomor_sample_lab){
                    if($data_quality_lab[$j-1]->quality_name != 8 ){
            ?>
            <tr style="">
                <td colspan="7" style="height: 20px; padding: 5px; padding-bottom: 0;">{{ $data_quality_lab[$j-1]->quality }}</td>
            </tr>
            <?php
                    }else{
            ?>
            <tr style="">
                <td colspan="7" style="height: 20px; padding: 5px; padding-bottom: 0;">{{ $data_quality_lab[$j-1]->quality_name_lainnya }}</td>
            </tr>
            <?php
                    }
            ?>
            <tr>
                <td style="width: 15%; padding: 5px; padding-bottom: 0;">No Lot {{ $i }} :</td>
                <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data_quality_lab[$i-1]->no_lot }}</td>
                <td style="width: 18%; padding: 5px; padding-bottom: 0;">Metode / Mesin {{ $i }} :</td>
                <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data_quality_lab[$i-1]->metode_mesin }}</td>
                <td style="width: 10%; padding: 5px; padding-bottom: 0;">Hasil {{ $i }} :</td>
                <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data_quality_lab[$i-1]->hasil }} {{ $data_quality_lab[$i-1]->satuan }}</td>
                <td style="width: 2%; padding-bottom: 0;"></td>
            </tr>
            <?php
                }
            }
            ?>
            @endif
        <?php
        }
        ?>
        @endif
        <tr>
            <td style="width: 13%; border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td colspan="5" style="border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td style="width: 2%; border-bottom: 1px solid; padding-bottom: 0;"></td>
        </tr>
    </table>
    @endif
    @if($data->no_div >= 6 && $tmp_count == 2)
        <div class="page-break"></div>
    @endif
    @if($lainnya)
    <?php $tmp_count++; ?>
    <table style="width: 100%; border-left: 1px solid; border-top: 1px solid; border-right: 1px solid; border-collapse: collapse; font-size: 14px;">
        <tr style="">
            <td colspan="5" style="height: 20px; padding: 5px; padding-bottom: 0;">Lainnya</td>
        </tr>
        <tr>
            <td style="width: 19%; padding: 5px; padding-bottom: 0;">Divisi :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $lainnya->divisi }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 16%; padding: 5px; padding-bottom: 0;">Evaluasi :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $lainnya->evaluasi }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 16%; padding: 5px; padding-bottom: 0;">Solusi Internal :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $lainnya->solusi_internal }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 16%; padding: 5px; padding-bottom: 0;">Lampiran :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $lainnya->lampiran }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        @if($komitmen_lainnya)
        <?php
        for($i = 1; $i <= count($komitmen_lainnya); $i++){
            ?>
            <tr>
                <td style="width: 19%; padding: 5px; padding-bottom: 0;">Komitmen {{ $i }} :</td>
                <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $komitmen_lainnya[$i-1]->komitmen }}</td>
                <td style="width: 20%; padding: 5px; text-align: right; padding-bottom: 0;">Selesai Tanggal {{ $i }} :</td>
                <td style="width: 18%; border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $komitmen_lainnya[$i-1]->selesai_tanggal_komitmen }}</td>
                <td style="width: 2%; padding-bottom: 0;"></td>
            </tr>
            <?php
        }
        ?>
        @endif
        <tr>
            <td style="width: 16%; border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td colspan="3" style="border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td style="width: 2%; border-bottom: 1px solid; padding-bottom: 0;"></td>
        </tr>
    </table>
    @endif
    @if($data->no_div < 10)
    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
        <tr style="">
            <td style="width:5px; padding: 5px; border: 1px solid #000;"></td>
            <td style="padding: 5px; border: 1px solid #000; text-align: center;">Dibuat</td>
            <td style="padding: 5px; border: 1px solid #000; text-align: center;">Disetujui</td>
            @if($data->no_div == 2)
            <td style="padding: 5px; border: 1px solid #000; text-align: center;">Solusi</td>
            @endif
            @if($data->no_div == 4)
            <td colspan="2" style="padding: 5px; border: 1px solid #000; text-align: center;">Solusi</td>
            @endif
            @if($data->no_div == 6)
            <td colspan="3" style="padding: 5px; border: 1px solid #000; text-align: center;">Solusi</td>
            @endif
            @if($data->no_div == 8)
            <td colspan="4" style="padding: 5px; border: 1px solid #000; text-align: center;">Solusi</td>
            @endif
            <td style="padding: 5px; border: 1px solid #000; text-align: center;">Disetujui</td>
        </tr>
        <tr style="">
            <td style="width:5px; padding: 5px; border: 1px solid #000; height: 70px; font-size: 12px;">Approved By</td>
            <td class="td-utama" style="width: 80px; padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved">APPROVED</div>(Cien Cien)<br>Manager Marketing</td>
            <td class="td-utama" style="width: 90px; padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved">APPROVED</div>(Inggrid Chandranata)<br>Direktur</td>
            @if($produksi)
            <td class="td-solusi" style="padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved-solusi">APPROVED</div>( {{ $produksi->nama_admin }} )<br>{{ $produksi->jabatan }}</td>
            @endif
            @if($logistik)
            <td class="td-solusi" style="padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved-solusi">APPROVED</div>( {{ $logistik->nama_admin }} )<br>{{ $logistik->jabatan }}</td>
            @endif
            @if($sales)
            <td class="td-solusi" style="padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved-solusi">APPROVED</div>( {{ $sales->nama_admin }} )<br>{{ $sales->jabatan }}</td>
            @endif
            @if($timbangan)
            <td class="td-solusi" style="padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved-solusi">APPROVED</div>( {{ $timbangan->nama_admin }} )<br>{{ $timbangan->jabatan }}</td>
            @endif
            @if($warehouse)
            <td class="td-solusi" style="padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved-solusi">APPROVED</div>( {{ $warehouse->nama_admin }} )<br>{{ $warehouse->jabatan }}</td>
            @endif
            @if($lab)
            <td class="td-solusi" style="padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved-solusi">APPROVED</div>( {{ $lab->nama_admin }} )<br>{{ $lab->jabatan }}</td>
            @endif
            @if($lainnya)
            <td class="td-solusi" style="padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved-solusi">APPROVED</div>( {{ $lainnya->nama_admin }} )<br>{{ $lainnya->jabatan }}</td>
            @endif
            <td class="td-utama" style="width: 90px; padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved">APPROVED</div>(Inggrid Chandranata)<br>Direktur</td>
        </tr>
        <tr style="">
            <td style="width:5px; padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">Tanggal</td>
            <td style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $data->tanggal_complaint }}</td>
            <td style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $data->tanggal_choose_div }}</td>
            @if($produksi)
            <td style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $produksi->tanggal_input }}</td>
            @endif
            @if($logistik)
            <td style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $logistik->tanggal_input }}</td>
            @endif
            @if($sales)
            <td style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $sales->tanggal_input }}</td>
            @endif
            @if($timbangan)
            <td style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $timbangan->tanggal_input }}</td>
            @endif
            @if($warehouse)
            <td style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $warehouse->tanggal_input }}</td>
            @endif
            @if($lab)
            <td style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $lab->tanggal_input }}</td>
            @endif
            @if($lainnya)
            <td style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $lainnya->tanggal_input }}</td>
            @endif
            <td style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $data->tanggal_validasi }}</td>
        </tr>
    </table>
    @else
    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
        <tr style="">
            <td style="width:5px; padding: 5px; border: 1px solid #000;"></td>
            @if($data->no_div == 10)
            <td colspan="5" style="padding: 5px; border: 1px solid #000; text-align: center;">Solusi</td>
            @endif
            @if($data->no_div == 12)
            <td colspan="6" style="padding: 5px; border: 1px solid #000; text-align: center;">Solusi</td>
            @endif
            @if($data->no_div == 14)
            <td colspan="7" style="padding: 5px; border: 1px solid #000; text-align: center;">Solusi</td>
            @endif
        </tr>
        <tr style="">
            <td style="width:5px; padding: 5px; border: 1px solid #000; height: 70px; font-size: 12px;">Approved By</td>
            @if($produksi)
            <td class="td-solusi" style="padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved-solusi">APPROVED</div>( {{ $produksi->nama_admin }} )<br>{{ $produksi->jabatan }}</td>
            @endif
            @if($logistik)
            <td class="td-solusi" style="padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved-solusi">APPROVED</div>( {{ $logistik->nama_admin }} )<br>{{ $logistik->jabatan }}</td>
            @endif
            @if($sales)
            <td class="td-solusi" style="padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved-solusi">APPROVED</div>( {{ $sales->nama_admin }} )<br>{{ $sales->jabatan }}</td>
            @endif
            @if($timbangan)
            <td class="td-solusi" style="padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved-solusi">APPROVED</div>( {{ $timbangan->nama_admin }} )<br>{{ $timbangan->jabatan }}</td>
            @endif
            @if($warehouse)
            <td class="td-solusi" style="padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved-solusi">APPROVED</div>( {{ $warehouse->nama_admin }} )<br>{{ $warehouse->jabatan }}</td>
            @endif
            @if($lab)
            <td class="td-solusi" style="padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved-solusi">APPROVED</div>( {{ $lab->nama_admin }} )<br>{{ $lab->jabatan }}</td>
            @endif
            @if($lainnya)
            <td class="td-solusi" style="padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved-solusi">APPROVED</div>( {{ $lainnya->nama_admin }} )<br>{{ $produksi->jabatan }}</td>
            @endif
        </tr>
        <tr style="">
            <td style="width:5px; padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">Tanggal</td>
            @if($produksi)
            <td style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $produksi->tanggal_input }}</td>
            @endif
            @if($logistik)
            <td style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $logistik->tanggal_input }}</td>
            @endif
            @if($sales)
            <td style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $sales->tanggal_input }}</td>
            @endif
            @if($timbangan)
            <td style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $timbangan->tanggal_input }}</td>
            @endif
            @if($warehouse)
            <td style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $warehouse->tanggal_input }}</td>
            @endif
            @if($lab)
            <td style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $lab->tanggal_input }}</td>
            @endif
            @if($lainnya)
            <td style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $lainnya->tanggal_input }}</td>
            @endif
        </tr>
        <tr style="">
            <td style="width:5px; padding: 5px; border: 1px solid #000;"></td>
            @if($data->no_div == 10)
            <td style="padding: 5px; border: 1px solid #000; text-align: center;">Dibuat</td>
            <td colspan="2" style="padding: 5px; border: 1px solid #000; text-align: center;">Disetujui</td>
            <td colspan="2" style="padding: 5px; border: 1px solid #000; text-align: center;">Disetujui</td>
            @endif
            @if($data->no_div == 12)
            <td colspan="2" style="padding: 5px; border: 1px solid #000; text-align: center;">Dibuat</td>
            <td colspan="2" style="padding: 5px; border: 1px solid #000; text-align: center;">Disetujui</td>
            <td colspan="2" style="padding: 5px; border: 1px solid #000; text-align: center;">Disetujui</td>
            @endif
            @if($data->no_div == 14)
            <td colspan="2" style="padding: 5px; border: 1px solid #000; text-align: center;">Dibuat</td>
            <td colspan="2" style="padding: 5px; border: 1px solid #000; text-align: center;">Disetujui</td>
            <td colspan="3" style="padding: 5px; border: 1px solid #000; text-align: center;">Disetujui</td>
            @endif
        </tr>
        <tr style="">
            <td style="width:5px; padding: 5px; border: 1px solid #000; height: 70px; font-size: 12px;">Approved By</td>
            @if($data->no_div == 10)
            <td class="td-utama" style="padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved">APPROVED</div>(Cien Cien)<br>Manager Marketing</td>
            <td colspan="2" class="td-utama" style="padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved">APPROVED</div>(Inggrid Chandranata)<br>Direktur</td>
            <td colspan="2" class="td-utama" style="padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved">APPROVED</div>(Inggrid Chandranata)<br>Direktur</td>
            @endif
            @if($data->no_div == 12)
            <td colspan="2" class="td-utama" style="padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved">APPROVED</div>(Cien Cien)<br>Manager Marketing</td>
            <td colspan="2" class="td-utama" style="padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved">APPROVED</div>(Inggrid Chandranata)<br>Direktur</td>
            <td colspan="2" class="td-utama" style="padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved">APPROVED</div>(Inggrid Chandranata)<br>Direktur</td>
            @endif
            @if($data->no_div == 14)
            <td colspan="2" class="td-utama" style="padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved">APPROVED</div>(Cien Cien)<br>Manager Marketing</td>
            <td colspan="2" class="td-utama" style="padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved">APPROVED</div>(Inggrid Chandranata)<br>Direktur</td>
            <td colspan="3" class="td-utama" style="padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved">APPROVED</div>(Inggrid Chandranata)<br>Direktur</td>
            @endif
        </tr>
        <tr style="">
            <td style="width:5px; padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">Tanggal</td>
            @if($data->no_div == 10)
            <td style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $data->tanggal_complaint }}</td>
            <td colspan="2" style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $data->tanggal_choose_div }}</td>
            <td colspan="2" style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $data->tanggal_validasi }}</td>
            @endif
            @if($data->no_div == 12)
            <td colspan="2" style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $data->tanggal_complaint }}</td>
            <td colspan="2" style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $data->tanggal_choose_div }}</td>
            <td colspan="2" style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $data->tanggal_validasi }}</td>
            @endif
            @if($data->no_div == 14)
            <td colspan="2" style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $data->tanggal_complaint }}</td>
            <td colspan="2" style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $data->tanggal_choose_div }}</td>
            <td colspan="3" style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $data->tanggal_validasi }}</td>
            @endif
        </tr>
    </table>
    @endif
</body>
</html>