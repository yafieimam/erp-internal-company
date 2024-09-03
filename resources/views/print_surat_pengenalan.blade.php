<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Surat Pengenalan</title>
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
            <td style="width: 5%; text-align: left; font-size: 12px;">Hal</td>
            <td style="width: 5%; text-align: right; font-size: 12px;">:</td>
            <td style="width: 80%; text-align: left; font-size: 12px;">Perkenalan Perusahaan</td>
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
    <br><br>
    <table style="width: 100%;border-collapse: collapse;">
        <tr>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
            <td style="width: 80%; text-align: left; font-size: 12px;">Dengan hormat,</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
    </table>
    <br>
    <table style="width: 100%;border-collapse: collapse;">
        <tr>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
            <td style="width: 80%; text-align: justify; font-size: 12px;"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Selamat pagi dan salam sejahtera untuk kita semua. Mohon izin untuk memperkenalkan perusahaan kami kepada Bapak / Ibu. Nama perusahaan kami adalah PT. Dwi Selo Giri Mas yang beralamat di Jl. Raya Tebel No. 50, Sidoarjo. Perusahaan kami bergerak dalam bidang industri kalsium karbonat powder.</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
    </table>
    <br>
    <table style="width: 100%;border-collapse: collapse;">
        <tr>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
            <td style="width: 80%; text-align: justify; font-size: 12px;"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Kami ingin menawarkan produk kami untuk dijual / digunakan di fasilitas Bapak / Ibu. Adapun kegunaan produk kami biasa digunakan sebagai berikut: </td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
    </table>
    <table style="width: 100%;border-collapse: collapse;">
        <tr>
            <td style="width: 12%; text-align: left; font-size: 12px;"></td>
            <td style="width: 5%; text-align: center; font-size: 12px;">-</td>
            <td style="width: 73%; text-align: justify; font-size: 12px;">Bahan bangunan (pengeras beton, acian, dll)</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
        <tr>
            <td style="width: 12%; text-align: left; font-size: 12px;"></td>
            <td style="width: 5%; text-align: center; font-size: 12px;">-</td>
            <td style="width: 73%; text-align: justify; font-size: 12px;">Bahan perekat (industri semen, bahan mortar, sol sepatu dll)</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
        <tr>
            <td style="width: 12%; text-align: left; font-size: 12px;"></td>
            <td style="width: 5%; text-align: center; font-size: 12px;">-</td>
            <td style="width: 73%; text-align: justify; font-size: 12px;">Pelarut / solvent (insdustri cat, dll)</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
        <tr>
            <td style="width: 12%; text-align: left; font-size: 12px;"></td>
            <td style="width: 5%; text-align: center; font-size: 12px;">-</td>
            <td style="width: 73%; text-align: justify; font-size: 12px;">Fluk (pembuatan keramik, dll)</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
        <tr>
            <td style="width: 12%; text-align: left; font-size: 12px;"></td>
            <td style="width: 5%; text-align: center; font-size: 12px;">-</td>
            <td style="width: 73%; text-align: justify; font-size: 12px;">Untuk netralisasi (water treatment, dll)</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
        <tr>
            <td style="width: 12%; text-align: left; font-size: 12px;"></td>
            <td style="width: 5%; text-align: center; font-size: 12px;">-</td>
            <td style="width: 73%; text-align: justify; font-size: 12px;">Ban dalam</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
        <tr>
            <td style="width: 12%; text-align: left; font-size: 12px;"></td>
            <td style="width: 5%; text-align: center; font-size: 12px;">-</td>
            <td style="width: 73%; text-align: justify; font-size: 12px;">Pvc</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
        <tr>
            <td style="width: 12%; text-align: left; font-size: 12px;"></td>
            <td style="width: 5%; text-align: center; font-size: 12px;">-</td>
            <td style="width: 73%; text-align: justify; font-size: 12px;">Pupuk</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
        <tr>
            <td style="width: 12%; text-align: left; font-size: 12px;"></td>
            <td style="width: 5%; text-align: center; font-size: 12px;">-</td>
            <td style="width: 73%; text-align: justify; font-size: 12px;">Bahan baku industri kertas</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
        <tr>
            <td style="width: 12%; text-align: left; font-size: 12px;"></td>
            <td style="width: 5%; text-align: center; font-size: 12px;">-</td>
            <td style="width: 73%; text-align: justify; font-size: 12px;">Dan lain-lain</td>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
        </tr>
    </table>
    <br>
    <table style="width: 100%;border-collapse: collapse;">
        <tr>
            <td style="width: 10%; text-align: left; font-size: 12px;"></td>
            <td style="width: 80%; text-align: justify; font-size: 12px;"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Demikian isi surat dari kami. Besar harapan kami untuk bekerja sama dengan perusahaan anda. Atas perhatian dan kerjasamanya kami ucapkan terima kasih.</td>
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