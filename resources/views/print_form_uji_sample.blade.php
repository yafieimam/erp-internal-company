<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Form Uji Sample</title>
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
        .td-utama{
            font-size: 10px;
        }
        .div-approved{
            vertical-align: all; 
            font-size: 12px; 
            font-weight: 900; 
            font-style: oblique; 
            transform: rotate(-10deg); 
            margin-left: 8px; 
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td rowspan="2" style="width: 25%; border: 1px solid;"><img src="{{ public_path('app-assets/asset/images/logo-footer.png') }}" style="width: 50px; margin-left: 35%;"><br><div style="font-size: 14px; text-align: center; margin-top: 10px; font-weight: 900;">PT. DWI SELO GIRI MAS</div></td>
            <td rowspan="2" style="width: 50%; border: 1px solid; text-align: center; font-size: 20px; font-family: 'Times New Roman'; font-weight: 900;">FORM UJI SAMPLE <br> KOMPETITOR</td>
            <td style="width: 25%; border: 1px solid; font-size: 12px;">Nomor :<br><div style="font-size: 16px; margin-top: 10px; text-align: center;">{{ $data->nomor_uji_sample }}</div></td>
        </tr>
        <tr>
            <td style="width: 25%; border: 1px solid; font-size: 12px;">Tanggal :<br><div style="font-size: 16px; margin-top: 10px; text-align: center;">{{ $data->tanggal }}</div></td>
        </tr>
    </table>
    <table style="width: 100%; border-left: 1px solid; border-right: 1px solid; border-collapse: collapse; font-size: 14px;">
        <tr style="">
            <td colspan="5" style="height: 20px;"></td>
        </tr>
        <tr>
            <td style="width: 18%; padding: 5px; padding-bottom: 0;">Nama Pelanggan :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->custname }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 18%; padding: 5px; padding-bottom: 0;">Bidang Usaha :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->bidang_usaha }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 18%; padding: 5px; padding-bottom: 0;">Merk :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->merk }}</td>
            <td style="width: 12%; padding: 5px; text-align: right; padding-bottom: 0;">Tipe :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->tipe }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 18%; border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td colspan="3" style="border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td style="width: 2%; border-bottom: 1px solid; padding-bottom: 0;"></td>
        </tr>
    </table>
    <table style="width: 100%; border: 1px solid; border-collapse: collapse; font-size: 14px;">
        <tr>
            <td colspan="{{ $data_count + 3 }}" style="height: 20px; padding: 5px;">Data Lab</td>
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid; padding: 5px;"></td>
            <td style="width: 20%; border: 1px solid; padding: 5px; text-align: center;">KOMPETITOR</td>
            <td colspan="{{ $data_count }}" style="width: 20%; border: 1px solid; padding: 5px; text-align: center;">PEMBANDING</td>
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid; padding: 5px; text-align: center;">JENIS PRODUK</td>
            <td style="width: 20%; border: 1px solid; padding: 5px; text-align: center;">{{ $detail[0]->jenis_produk }}</td>
            @for($i = 1; $i <= $data_count; $i++)
            <td style="width: 20%; border: 1px solid; padding: 5px; text-align: center;">{{ $detail[$i]->jenis_produk }}</td>
            @endfor
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid; padding: 5px; text-align: center;">KADAR KALSIUM</td>
            <td style="width: 20%; border: 1px solid; padding: 5px; text-align: center;">{{ $detail[0]->kalsium }}</td>
            @for($i = 1; $i <= $data_count; $i++)
            <td style="width: 20%; border: 1px solid; padding: 5px; text-align: center;">{{ $detail[$i]->kalsium }}</td>
            @endfor
        </tr>
        <tr>
            <td rowspan="2" style="border: 1px solid; padding: 5px; text-align: center;">WHITENESS</td>
            <td style="border: 1px solid; padding: 5px; text-align: center;">CIE 86</td>
            <td style="width: 20%; border: 1px solid; padding: 5px; text-align: center;">{{ $detail[0]->cie86 }}</td>
            @for($i = 1; $i <= $data_count; $i++)
            <td style="width: 20%; border: 1px solid; padding: 5px; text-align: center;">{{ $detail[$i]->cie86 }}</td>
            @endfor
        </tr>
        <tr>
            <td style="border: 1px solid; padding: 5px; text-align: center;">ISO 2470</td>
            <td style="width: 20%; border: 1px solid; padding: 5px; text-align: center;">{{ $detail[0]->iso2470 }}</td>
            @for($i = 1; $i <= $data_count; $i++)
            <td style="width: 20%; border: 1px solid; padding: 5px; text-align: center;">{{ $detail[$i]->iso2470 }}</td>
            @endfor
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid; padding: 5px; text-align: center;">MOISTURE</td>
            <td style="width: 20%; border: 1px solid; padding: 5px; text-align: center;">{{ $detail[0]->moisture }}</td>
            @for($i = 1; $i <= $data_count; $i++)
            <td style="width: 20%; border: 1px solid; padding: 5px; text-align: center;">{{ $detail[$i]->moisture }}</td>
            @endfor
        </tr>
        <tr>
            <td rowspan="4" style="border: 1px solid; padding: 5px; text-align: center;">KEHALUSAN</td>
            <td style="border: 1px solid; padding: 5px; text-align: center;">SSA</td>
            <td style="width: 20%; border: 1px solid; padding: 5px; text-align: center;">{{ $detail[0]->ssa }}</td>
            @for($i = 1; $i <= $data_count; $i++)
            <td style="width: 20%; border: 1px solid; padding: 5px; text-align: center;">{{ $detail[$i]->ssa }}</td>
            @endfor
        </tr>
        <tr>
            <td style="border: 1px solid; padding: 5px; text-align: center;">D (v, 0.50)</td>
            <td style="width: 20%; border: 1px solid; padding: 5px; text-align: center;">{{ $detail[0]->d50 }}</td>
            @for($i = 1; $i <= $data_count; $i++)
            <td style="width: 20%; border: 1px solid; padding: 5px; text-align: center;">{{ $detail[$i]->d50 }}</td>
            @endfor
        </tr>
        <tr>
            <td style="border: 1px solid; padding: 5px; text-align: center;">D (v, 0.98)</td>
            <td style="width: 20%; border: 1px solid; padding: 5px; text-align: center;">{{ $detail[0]->d98 }}</td>
            @for($i = 1; $i <= $data_count; $i++)
            <td style="width: 20%; border: 1px solid; padding: 5px; text-align: center;">{{ $detail[$i]->d98 }}</td>
            @endfor
        </tr>
        <tr>
            <td style="border: 1px solid; padding: 5px; text-align: center;">RESIDUE</td>
            <td style="width: 20%; border: 1px solid; padding: 5px; text-align: center;">{{ $detail[0]->residue }}</td>
            @for($i = 1; $i <= $data_count; $i++)
            <td style="width: 20%; border: 1px solid; padding: 5px; text-align: center;">{{ $detail[$i]->residue }}</td>
            @endfor
        </tr>
        @if(Session::get('tipe_user') == 2 || Session::get('tipe_user') == 10)
        <tr>
            <td colspan="2" style="border: 1px solid; padding: 5px; text-align: center;">HARGA</td>
            <td style="width: 20%; border: 1px solid; padding: 5px; text-align: center;">Rp {{ number_format($detail[0]->harga, 0, ".", ".") }}</td>
            @for($i = 1; $i <= $data_count; $i++)
            <td style="width: 20%; border: 1px solid; padding: 5px; text-align: center;">Rp {{ number_format($detail[$i]->harga, 0, ".", ".") }}</td>
            @endfor
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid; padding: 5px; text-align: center;">KELAS HARGA</td>
            <td style="width: 20%; border: 1px solid; padding: 5px; text-align: center;">{{ $detail[0]->kelas }}</td>
            @for($i = 1; $i <= $data_count; $i++)
            <td style="width: 20%; border: 1px solid; padding: 5px; text-align: center;"></td>
            @endfor
        </tr>
        @endif
    </table>
    <table style="width: 100%; border-left: 1px solid; border-right: 1px solid; border-collapse: collapse; font-size: 14px;">
        <tr style="">
            <td colspan="3" style="height: 10px;"></td>
        </tr>
        <tr>
            <td style="width: 20%; padding: 5px; padding-bottom: 0;">Komentar Produksi :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->komentar_produksi }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 20%; padding: 5px; padding-bottom: 0;">Analisa :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->analisa }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 20%; padding: 5px; padding-bottom: 0;">Solusi :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->solusi }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 20%; padding: 5px; padding-bottom: 0;">Lampiran :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->lampiran }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 20%; border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td style="border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td style="width: 2%; border-bottom: 1px solid; padding-bottom: 0;"></td>
        </tr>
    </table>
    <table style="width: 100%; margin-right: 48px; border-left: 1px solid; border-right: 1px solid; border-collapse: collapse; font-size: 14px;">
        <tr>
            <td style="width: 5px; padding: 5px; border: 1px solid #000;"></td>
            <td style="padding: 5px; border: 1px solid #000; text-align: center;">Pengajuan</td>
            <td style="padding: 5px; border: 1px solid #000; text-align: center;">Diketahui</td>
            <td style="padding: 5px; border: 1px solid #000; text-align: center;">Menguji</td>
            <td style="padding: 5px; border: 1px solid #000; text-align: center;">Analisa</td>
        </tr>
        <tr>
            <td style="padding: 5px; border: 1px solid #000; height: 70px; font-size: 12px;">Approved By</td>
            <td class="td-utama" style="width: 100px; padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved">APPROVED</div>(Hari Prayogi Santosa)<br>Marketing</td>
            <td class="td-utama" style="width: 130px; padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved">APPROVED</div>(Cien Cien)<br>Kepala Marketing</td>
            <td class="td-utama" style="padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved">APPROVED</div>(Adib)<br>Kepala Lab</td>
            <td class="td-utama" style="padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved">APPROVED</div>(Evan)<br>Kepala Produksi</td>
        </tr>
        <tr>
            <td style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">Tanggal</td>
            <td style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $data->tanggal }}</td>
            <td style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $data->tanggal_validasi_sales }}</td>
            <td style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $data->tanggal_pengujian_sample }}</td>
            <td style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ $data->tanggal_analisa }}</td>
        </tr>
    </table>
</body>
</html>