<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Form Customers Visit</title>
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
</head>
<body>
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td rowspan="2" style="width: 25%; border: 1px solid;"><img src="{{ public_path('app-assets/asset/images/logo-footer.png') }}" style="width: 50px; margin-left: 35%;"><br><div style="font-size: 14px; text-align: center; margin-top: 10px; font-weight: 900;">PT. DWI SELO GIRI MAS</div></td>
            <td rowspan="2" style="width: 50%; border: 1px solid; text-align: center; font-size: 20px; font-family: 'Times New Roman'; font-weight: 900;">FORM CUSTOMERS VISIT</td>
            <td style="width: 25%; border: 1px solid; font-size: 12px;">No. Customers Visit :<br><div style="font-size: 16px; margin-top: 10px; text-align: center;">{{ $data->id_schedule }}</div></td>
        </tr>
        <tr>
            <td style="width: 25%; border: 1px solid; font-size: 12px;">Tanggal Schedule :<br><div style="font-size: 16px; margin-top: 10px; text-align: center;">{{ $data->tanggal_schedule }} 
                @if($data->waktu_schedule != null || $data->waktu_schedule != '')
                    ({{ $data->waktu_schedule }})
                @endif
            </div>
            </td>
        </tr>
    </table>
    <table style="width: 100%; border-left: 1px solid; border-right: 1px solid; border-collapse: collapse; font-size: 14px;">
        <tr style="">
            <td colspan="7" style="height: 20px;"></td>
        </tr>
        <tr>
            <td style="width: 16%; padding: 5px; padding-bottom: 0;">Visit By Sales :</td>
            <td colspan="2" style="width: 14%; border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ Session::get('nama_admin') }}</td>
            <td style="width: 16%; padding: 5px; padding-bottom: 0;">Tipe Customers :</td>
            <td colspan="2" style="width: 12%; border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->tipe_customers }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 16%; padding: 5px; padding-bottom: 0;">Customers :</td>
            <td colspan="5" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->customer }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 16%; padding: 5px; padding-bottom: 0;">Perihal :</td>
            <td colspan="5" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->perihal }} ({{ $data->offline }})</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 16%; padding: 5px; padding-bottom: 0;">Keterangan :</td>
            <td colspan="5" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->keterangan }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 16%; padding: 5px; padding-bottom: 0;">Alasan Suspend :</td>
            <td colspan="5" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->alasan_suspend }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 16%; padding: 5px; padding-bottom: 0;">Hasil Visit :</td>
            <td colspan="5" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->hasil_visit }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 16%; padding: 5px; padding-bottom: 0;">Tgl Delivery :</td>
            <td style="width: 14%; border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->tanggal_poin_delivery }}</td>
            <td style="width: 12%; padding: 5px; padding-bottom: 0;">Tgl Paid :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->tanggal_poin_paid }}</td>
            <td style="width: 16%; padding: 5px; padding-bottom: 0;">Tgl Input :</td>
            <td style="width: 12%; border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->tanggal_input }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 21%; padding: 5px; padding-bottom: 0;">Kegiatan :</td>
            <td colspan="5" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->kegiatan }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 16%; border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td colspan="5" style="border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td style="width: 2%; border-bottom: 1px solid; padding-bottom: 0;"></td>
        </tr>
    </table>
    <table style="width: 100%; border-left: 1px solid; border-right: 1px solid; border-collapse: collapse; font-size: 14px;">
        <tr style="">
            <td colspan="5" style="height: 20px; padding: 5px; padding-bottom: 0;">Pengenalan DSGM</td>
        </tr>
        <tr>
            <td style="width: 22%; padding: 5px; padding-bottom: 0;">Company Profile :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">
                @if($detail->company_profile == 'yes')
                    Ya
                @else
                    Tidak
                @endif
            </td>
            <td style="width: 20%; padding: 5px; padding-bottom: 0;">Pengenalan Produk :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">
                @if($detail->pengenalan_produk == 'yes')
                    Ya
                @else
                    Tidak
                @endif
            </td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 22%; padding: 5px; padding-bottom: 0;">Sumber Kenal DSGM :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->sumber_kenal_dsgm }}</td>
            <td style="width: 20%; padding: 5px; padding-bottom: 0;">No Surat Pengenalan :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->nomor_surat_pengenalan  }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
    </table>
    <table style="width: 100%; border-left: 1px solid; border-right: 1px solid; border-collapse: collapse; font-size: 14px;">
        <tr style="">
            <td colspan="7" style="height: 20px; padding: 5px; padding-bottom: 0;">Janji Visit</td>
        </tr>
        <tr>
            <td style="width: 10%; padding: 5px; padding-bottom: 0;">Nama :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->nama_janji_visit }}</td>
            <td style="width: 10%; padding: 5px; padding-bottom: 0;">PIC :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->pic_janji_visit  }}</td>
            <td style="width: 10%; padding: 5px; padding-bottom: 0;">Jabatan :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->jabatan_janji_visit  }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
    </table>
    <table style="width: 100%; border-left: 1px solid; border-right: 1px solid; border-collapse: collapse; font-size: 14px;">
        <tr>
            <td style="width: 10%; padding: 5px; padding-bottom: 0;">Alamat :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->alamat_janji_visit }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 10%; padding: 5px; padding-bottom: 0;">Tanggal :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->tanggal_janji_visit  }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
    </table>
    <table style="width: 100%; border-left: 1px solid; border-right: 1px solid; border-collapse: collapse; font-size: 14px;">
        <tr style="">
            <td colspan="7" style="height: 20px; padding: 5px; padding-bottom: 0;">Latar Belakang Customer</td>
        </tr>
        <tr>
            <td style="width: 15%; padding: 5px; padding-bottom: 0;">Bisnis Cust:</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->bisnis_perusahaan }}</td>
            <td style="width: 10%; padding: 5px; padding-bottom: 0;">Owner :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->owner_perusahaan  }}</td>
            <td style="width: 15%; padding: 5px; padding-bottom: 0;">Tahun Berdiri :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->tahun_berdiri_perusahaan  }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 15%; padding: 5px; padding-bottom: 0;">Jenis Usaha :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->jenis_usaha_perusahaan }}</td>
            <td style="width: 10%; padding: 5px; padding-bottom: 0;">Jangkauan Wilayah :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->jangkauan_wilayah_perusahaan  }}</td>
            <td style="width: 15%; padding: 5px; padding-bottom: 0;">TOP Cust :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->top_cust_perusahaan  }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
    </table>
    <table style="width: 100%; border-left: 1px solid; border-right: 1px solid; border-collapse: collapse; font-size: 14px;">
        <tr style="">
            <td colspan="7" style="height: 20px; padding: 5px; padding-bottom: 0;">Penggunaan Kalsium</td>
        </tr>
        <tr>
            <td style="width: 13%; padding: 5px; padding-bottom: 0;">Tipe CaCo3:</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->tipe_kalsium }}</td>
            <td style="width: 13%; padding: 5px; padding-bottom: 0;">Qty CaCo3 :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->qty_kalsium  }}</td>
            <td style="width: 13%; padding: 5px; padding-bottom: 0;">Pengiriman :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->kegunaan_kalsium  }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 13%; padding: 5px; padding-bottom: 0;">Merk Kompetitor :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->merk_kompetitor }}</td>
            <td style="width: 13%; padding: 5px; padding-bottom: 0;">Harga Kompetitor :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->harga_kompetitor  }}</td>
            <td style="width: 13%; padding: 5px; padding-bottom: 0;">Pembayaran Supplier :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">
                @if($detail->pembayaran_supplier == 1)
                    Cash
                @elseif($detail->pembayaran_supplier == 2)
                    TOP
                @endif
            </td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
    </table>
    <table style="width: 100%; border-left: 1px solid; border-right: 1px solid; border-collapse: collapse; font-size: 14px;">
        <tr style="">
            <td colspan="5" style="height: 20px; padding: 5px; padding-bottom: 0;">Permintaan Sample</td>
        </tr>
        <tr>
            <td style="width: 13%; padding: 5px; padding-bottom: 0;">Tipe Sample:</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->tipe_sample }}</td>
            <td style="width: 13%; padding: 5px; padding-bottom: 0;">Qty Sample :</td>
            <td style="width: 13%; border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->qty_sample  }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 20%; padding: 5px; padding-bottom: 0;">Feedback Uji Sample :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->feedback_uji_sample }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
    </table>
    <table style="width: 100%; border-left: 1px solid; border-right: 1px solid; border-collapse: collapse; font-size: 14px;">
        <tr style="">
            <td colspan="9" style="height: 20px; padding: 5px; padding-bottom: 0;">Penawaran Harga</td>
        </tr>
        <tr>
            <td style="width: 17%; padding: 5px; padding-bottom: 0;">Penawaran Harga:</td>
            <td style="width: 9%; border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">
                @if($detail->penawaran_harga == 'yes')
                    Ya
                @else
                    Tidak
                @endif
            </td>
            <td style="width: 13%; padding: 5px; padding-bottom: 0;">Nego Harga :</td>
            <td style="width: 9%; border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">
                @if($detail->nego_harga == 'yes')
                    Ya
                @else
                    Tidak
                @endif
            </td>
            <td style="width: 13%; padding: 5px; padding-bottom: 0;">Pembayaran :</td>
            <td style="width: 9%; border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">
                @if($detail->pembayaran == 'yes')
                    Ya
                @else
                    Tidak
                @endif
            </td>
            <td style="width: 13%; padding: 5px; padding-bottom: 0;">Pengiriman :</td>
            <td style="width: 9%; border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">
                @if($detail->pengiriman == 'yes')
                    Ya
                @else
                    Tidak
                @endif
            </td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
    </table>
    <table style="width: 100%; border-left: 1px solid; border-right: 1px solid; border-collapse: collapse; font-size: 14px;">
        <tr>
            <td style="width: 15%; padding: 5px; padding-bottom: 0;">Nominal Harga:</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->nominal_harga }}</td>
            <td style="width: 15%; padding: 5px; padding-bottom: 0;">No Penawaran :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->nomor_penawaran  }}</td>
            <td style="width: 15%; padding: 5px; padding-bottom: 0;">Dokumen Pengiriman :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->dokumen_pengiriman  }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
    </table>
    <table style="width: 100%; border-left: 1px solid; border-right: 1px solid; border-collapse: collapse; font-size: 14px;">
        <tr style="">
            <td colspan="7" style="height: 20px; padding: 5px; padding-bottom: 0;">Pembayaran</td>
        </tr>
        <tr>
            <td style="width: 15%; padding: 5px; padding-bottom: 0;">Cash / TOP:</td>
            <td style="width: 10%; border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">
                @if($detail->cash_top == 1)
                    Cash
                @else($detail->cash_top == 2)
                    TOP
                @endif
            </td>
            <td style="width: 17%; padding: 5px; padding-bottom: 0;">Tempat Tukar TT :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->tempat_tukar_tt }}</td>
            <td style="width: 17%; padding: 5px; padding-bottom: 0;">Jadwal Tukar TT :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->jadwal_tukar_tt }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
    </table>
    <table style="width: 100%; border-left: 1px solid; border-right: 1px solid; border-collapse: collapse; font-size: 14px;">
        <tr>
            <td style="width: 15%; padding: 5px; padding-bottom: 0;">Jadwal Pembayaran:</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->jadwal_pembayaran }}</td>
            <td style="width: 15%; padding: 5px; padding-bottom: 0;">Metode Pembayaran :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->metode_pembayaran }}</td>
            <td style="width: 15%; padding: 5px; padding-bottom: 0;">PIC Penagihan :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->pic_penagihan }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
    </table>
    <table style="width: 100%; border-left: 1px solid; border-right: 1px solid; border-collapse: collapse; font-size: 14px;">
        <tr style="">
            <td colspan="7" style="height: 20px; padding: 5px; padding-bottom: 0;">PO dan Dokumen Customer</td>
        </tr>
        <tr>
            <td style="width: 12%; padding: 5px; padding-bottom: 0;">No PO:</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->nomor_po }}</td>
            <td style="width: 12%; padding: 5px; padding-bottom: 0;">No KTP  :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->nomor_ktp }}</td>
            <td style="width: 12%; padding: 5px; padding-bottom: 0;">No NPWP :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->nomor_npwp }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 15%; padding: 5px; padding-bottom: 0;">No SIUP:</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->nomor_siup }}</td>
            <td style="width: 15%; padding: 5px; padding-bottom: 0;">No TDP :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->nomor_tdp }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
    </table>
    <table style="width: 100%; border-left: 1px solid; border-right: 1px solid; border-collapse: collapse; font-size: 14px;">
        <tr>
            <td style="width: 15%; padding: 5px; padding-bottom: 0;">File KTP:</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->image_ktp }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 15%; padding: 5px; padding-bottom: 0;">File NPWP:</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->image_npwp }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 15%; padding: 5px; padding-bottom: 0;">File SIUP:</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->image_siup }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 15%; padding: 5px; padding-bottom: 0;">File TDP:</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $detail->image_tdp }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 16%; border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td style="border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td style="width: 2%; border-bottom: 1px solid; padding-bottom: 0;"></td>
        </tr>
    </table>
</body>
</html>