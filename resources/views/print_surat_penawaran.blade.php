<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Surat Penawaran</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page { margin: 15px; }
        * { box-sizing: border-box; }
        body{
            margin-left: 30px;
            margin-right: 30px;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .header{
            font-weight: 900;
        }
        .page-break {
            page-break-after: always;
        }
        .serif {
            font-family: "Times New Roman", Times, serif;
        }
        .sansserif {
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>
<body>
    <?php
    function tgl_indo($tanggal){
        $bulan = array (
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);
        
        return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
    }
    ?>
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 20%; text-align: center;"><img src="{{ public_path('app-assets/images/logo_dsgm.jpg') }}" style="width: 120px; margin-left: 30%;"></td>
            <td style="width: 70%; text-align: center;">
                <p style="font-size: 20px; font-weight: 900; color: #2958a6; letter-spacing: 0.3px; margin: 0;">PT. DWI SELO GIRI MAS</p>
                <p style="font-size: 14px; font-weight: 900; color: #2958a6; letter-spacing: 0.3px; margin: 0;">JL. RAYA TEBEL NO. 50</p>
                <p style="font-size: 14px; font-weight: 900; color: #2958a6; letter-spacing: 0.3px; margin: 0;">GEDANGAN - SIDOARJO</p>
                <p style="font-size: 14px; font-weight: 900; color: #2958a6; letter-spacing: 0.3px; margin: 0;">TELP. (031) 8913030, 8912469, 8913051 &nbsp; FAX. 893052</p>
                <p style="font-size: 14px; font-weight: 900; color: #2958a6; letter-spacing: 0.3px; margin: 0;">HP / WA. 0888 320 5551 &nbsp; &nbsp; E-mail : sales@dwiselogirimas.com</p>
                <p style="font-size: 14px; font-weight: 900; color: #2958a6; letter-spacing: 0.3px; margin: 0;">Web : https://www.dwiselogirimas.com</p>
            </td>
            <td style="width: 10%; text-align: center;"></td>
        </tr>
    </table>
    <hr style="color: #2958a6; margin-bottom: 0px; border-top: 1px solid #2958a6;">
    <hr style="color: #ed3338; margin-top: 2px; border-top: 1px solid #ed3338;">
    <table style="width: 100%;border-collapse: collapse;">
        <tr>
            <td style="width: 60%; text-align: center;"></td>
            <td style="width: 30%; text-align: right; font-size: 12px;">Sidoarjo, {{ tgl_indo($data->tanggal) }}</td>
            <td style="width: 10%; text-align: center;"></td>
        </tr>
    </table>
    <br><br>
    <table style="width: 100%;border-collapse: collapse;">
        <tr>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
            <td style="width: 5%; text-align: left; font-size: 12px;">Nomor</td>
            <td style="width: 5%; text-align: right; font-size: 12px;">:</td>
            <td style="width: 80%; text-align: left; font-size: 12px;">{{ $data->nomor }}</td>
        </tr>
        <tr>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
            <td style="width: 5%; text-align: left; font-size: 12px;">Lampiran</td>
            <td style="width: 5%; text-align: right; font-size: 12px;">:</td>
            <td style="width: 80%; text-align: left; font-size: 12px;">-</td>
        </tr>
        <tr>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
            <td style="width: 5%; text-align: left; font-size: 12px;">Hal</td>
            <td style="width: 5%; text-align: right; font-size: 12px;">:</td>
            <td style="width: 80%; text-align: left; font-size: 12px;">Penawaran produk</td>
        </tr>
    </table>
    <br>
    <table style="width: 100%;border-collapse: collapse;">
        <tr>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
            <td style="width: 50%; text-align: left; font-size: 12px;">Kepada Yth,</td>
            <td style="width: 40%; text-align: left; font-size: 12px;"></td>
        </tr>
        <tr>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
            <td style="width: 50%; text-align: left; font-size: 12px;">Bapak / Ibu {{ $data->nama_cp }}</td>
            <td style="width: 40%; text-align: left; font-size: 12px;"></td>
        </tr>
        <tr>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
            <td style="width: 50%; text-align: left; font-size: 12px;">{{ $data->jabatan_cp }}</td>
            <td style="width: 40%; text-align: left; font-size: 12px;"></td>
        </tr>
        <tr>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
            <td style="width: 50%; text-align: left; font-size: 12px;">{{ strtoupper($data->nama) }}</td>
            <td style="width: 40%; text-align: left; font-size: 12px;"></td>
        </tr>
        <tr>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
            <td style="width: 50%; text-align: left; font-size: 12px;">{{ $data->address }}</td>
            <td style="width: 40%; text-align: left; font-size: 12px;"></td>
        </tr>
        <tr>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
            <td style="width: 50%; text-align: left; font-size: 12px;">{{ ucwords(strtolower($data->nama_kota)) }}</td>
            <td style="width: 40%; text-align: left; font-size: 12px;"></td>
        </tr>
    </table>
    <br>
    <table style="width: 100%;border-collapse: collapse;">
        <tr>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
            <td style="width: 80%; text-align: justify; font-size: 12px;">Bersama ini kami lampirkan penawaran harga PT. DWI SELO GIRI MAS sebagai berikut :</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
    </table>
    <br>
    <table style="width: 100%;border-collapse: collapse;">
        <tr>
            <th style="width: 10%; text-align: left; font-size: 12px;"></th>
            <th style="width: 20%; text-align: center; font-size: 12px; border: 1px solid; padding-bottom: 20px;">TYPE</th>
            <th style="width: 20%; text-align: center; font-size: 12px; border: 1px solid; padding-bottom: 20px;">PACKAGING</th>
            <th style="width: 40%; text-align: center; font-size: 12px; border: 1px solid; padding-bottom: 20px;">HARGA KIRIM KE SURABAYA <br> (HARGA / KG)</th>
            <th style="width: 10%; text-align: left; font-size: 12px;"></th>
        </tr>
        @foreach($data_produk as $prd)
        <tr>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
            <td style="width: 20%; text-align: center; font-size: 12px; border: 1px solid;">{{ $prd->tipe }}</td>
            <td style="width: 20%; text-align: center; font-size: 12px; border: 1px solid;">{{ $prd->packaging }} KG / Zak</td>
            <td style="width: 40%; text-align: center; font-size: 12px; border: 1px solid;">Rp {{ $prd->harga_kirim }},-/KG + PPN</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
        @endforeach
    </table>
    <br>
    <table style="width: 100%;border-collapse: collapse;">
        <tr>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
            <td style="width: 80%; text-align: justify; font-size: 12px;">Dengan keterangan sebagai berikut :</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
    </table>
    <table style="width: 100%;border-collapse: collapse;">
        <tr>
            <td style="width: 12%; text-align: left; font-size: 12px;"></td>
            <td style="width: 5%; text-align: center; font-size: 12px;">•</td>
            <td style="width: 73%; text-align: justify; font-size: 12px;">Harga di atas <b>BELUM</b> termasuk PPN 10%</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
        <tr>
            <td style="width: 12%; text-align: left; font-size: 12px;"></td>
            <td style="width: 5%; text-align: center; font-size: 12px;">•</td>
            <td style="width: 73%; text-align: justify; font-size: 12px;">Minimum order : {{ $data->minimum_order }} TON</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
        <tr>
            <td style="width: 12%; text-align: left; font-size: 12px;"></td>
            <td style="width: 5%; text-align: center; font-size: 12px;">•</td>
            <td style="width: 73%; text-align: justify; font-size: 12px;">Pembayaran : {{ $data->pembayaran }}</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
        <tr>
            <td style="width: 12%; text-align: left; font-size: 12px;"></td>
            <td style="width: 5%; text-align: center; font-size: 12px;">•</td>
            <td style="width: 73%; text-align: justify; font-size: 12px;"><b>Tidak Menyediakan</b> kuli untuk bongkar barang di tempat tujuan.</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
        <tr>
            <td style="width: 12%; text-align: left; font-size: 12px;"></td>
            <td style="width: 5%; text-align: center; font-size: 12px;">•</td>
            <td style="width: 73%; text-align: justify; font-size: 12px;">Harga tidak mengikat dan dapat berubah sewaktu-waktu</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
        <tr>
            <td style="width: 12%; text-align: left; font-size: 12px;"></td>
            <td style="width: 5%; text-align: center; font-size: 12px;">•</td>
            <td style="width: 73%; text-align: justify; font-size: 12px;">Dokumen yang diperlukan untuk customer baru adalah sebagai berikut :</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
    </table>
    <table style="width: 100%;border-collapse: collapse;">
        <tr>
            <td style="width: 19%; text-align: left; font-size: 12px;"></td>
            <td style="width: 5%; text-align: center; font-size: 12px;">1.</td>
            <td style="width: 66%; text-align: justify; font-size: 12px;">NPWP</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
        <tr>
            <td style="width: 19%; text-align: left; font-size: 12px;"></td>
            <td style="width: 5%; text-align: center; font-size: 12px;">2.</td>
            <td style="width: 66%; text-align: justify; font-size: 12px;">SIUP</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
        <tr>
            <td style="width: 19%; text-align: left; font-size: 12px;"></td>
            <td style="width: 5%; text-align: center; font-size: 12px;">3.</td>
            <td style="width: 66%; text-align: justify; font-size: 12px;">TDP</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
        <tr>
            <td style="width: 19%; text-align: left; font-size: 12px;"></td>
            <td style="width: 5%; text-align: center; font-size: 12px;">4.</td>
            <td style="width: 66%; text-align: justify; font-size: 12px;">SPPKP</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
        <tr>
            <td style="width: 19%; text-align: left; font-size: 12px;"></td>
            <td style="width: 5%; text-align: center; font-size: 12px;">5.</td>
            <td style="width: 66%; text-align: justify; font-size: 12px;">KTP</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
    </table>
    <table style="width: 100%;border-collapse: collapse;">
        <tr>
            <td style="width: 12%; text-align: left; font-size: 12px;"></td>
            <td style="width: 5%; text-align: center; font-size: 12px;">•</td>
            <td style="width: 73%; text-align: justify; font-size: 12px;">Penawaran harga ini mengacu pada ketentuan yang berlaku di PT. DWI SELO GIRI MAS</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
    </table>
    <br>
    <table style="width: 100%;border-collapse: collapse;">
        <tr>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
            <td style="width: 80%; text-align: justify; font-size: 12px;"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Demikian surat penawaran produk ini kami sampaikan. Besar harapan kami untuk bekerja sama dengan perusahaan anda. Atas perhatian dan kerjasamanya kami ucapkan terima kasih.</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
    </table>
    <br>
    <table style="width: 100%;border-collapse: collapse;">
        <tr>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
            <td style="width: 80%; text-align: left; font-size: 12px;">Hormat kami,</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
    </table>
    <br><br><br>
    <table style="width: 100%;border-collapse: collapse;">
        <tr>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
            <td style="width: 80%; text-align: left; font-size: 12px;">({{ $data->nama_admin }})</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
        <tr>
            <td style="width: 10%; text-align: left; font-size: 11px;"></td>
            <td style="width: 80%; text-align: left; font-size: 11px;">{{ $data->jabatan_admin }} {{ $data->dep_admin }}</td>
            <td style="width: 10%; text-align: left; font-size: 11px;"></td>
        </tr>
        <tr>
            <td style="width: 10%; text-align: left; font-size: 11px;"></td>
            <td style="width: 80%; text-align: left; font-size: 11px;">PT. Dwi Selo Giri Mas</td>
            <td style="width: 10%; text-align: left; font-size: 11px;"></td>
        </tr>
        <tr>
            <td style="width: 10%; text-align: left; font-size: 11px;"></td>
            <td style="width: 80%; text-align: left; font-size: 11px;">Jl. Raya Tebel No. 50, Sidoarjo</td>
            <td style="width: 10%; text-align: left; font-size: 11px;"></td>
        </tr>
        <tr>
            <td style="width: 10%; text-align: left; font-size: 11px;"></td>
            <td style="width: 80%; text-align: left; font-size: 11px;">Office : 031-8913030, 031-8912469, 031-8913052</td>
            <td style="width: 10%; text-align: left; font-size: 11px;"></td>
        </tr>
        <tr>
            <td style="width: 10%; text-align: left; font-size: 11px;"></td>
            <td style="width: 80%; text-align: left; font-size: 11px;">Handphone : 0812-1767-2188</td>
            <td style="width: 10%; text-align: left; font-size: 11px;"></td>
        </tr>
        <tr>
            <td style="width: 10%; text-align: left; font-size: 11px;"></td>
            <td style="width: 80%; text-align: left; font-size: 11px;">Email : sales2@dwiselogirimas.com</td>
            <td style="width: 10%; text-align: left; font-size: 11px;"></td>
        </tr>
    </table>
</body>
</html>