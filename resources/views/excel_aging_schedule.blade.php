<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Excel Aging Schedule</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        * { box-sizing: border-box; }
        body{
            margin-left: 30px;
            margin-right: 30px;
            margin-bottom: 25px;
            margin-top: 25px;
        }

        table{
            border: 1px solid #000;
        }
        
    </style>
</head>
<body>
    <table style="width: 100%;" class="table table-bordered table-hover responsive">
        <tr>
            <th style="text-align: left; font-size: 16px;">A G I N G </th>
            <th colspan="8" style="text-align: left; font-size: 16px;">S C H E D U L E </th>
        </tr>
        <tr>
            <th colspan="9" style="text-align: left; font-size: 12px;">PT. DWI SELO GIRI MAS</th>
        </tr>
        <tr>
            <td colspan="9" style="text-align: left; font-size: 12px;">Tanggal : {{ $from_date }} s.d {{ $to_date }}</td>
        </tr>
    </table>

    <table style="width: 100%;" class="table table-bordered table-hover responsive">
        <tr>
            <td style="font-size: 12px;">Tanggal Cetak : </td>
            <td style="font-size: 12px;">{{ date('j F Y') }}</td>
        </tr>
    </table>

    <table style="width: 100%;" class="table table-bordered table-hover responsive">
        <tr>
            <th style="width:10%; vertical-align : middle; text-align: center;" colspan="2">Data Pelanggan</th>
            <th style="width:10%; vertical-align : middle; text-align: center;" rowspan="2">Saldo Awal</th>
            <th style="width:10%; vertical-align : middle; text-align: center;" colspan="4">Data Penjualan Hari Ini</th>
            <th style="width:10%; vertical-align : middle; text-align: center;" rowspan="2">Pembayaran Periode Ini</th>
            <th style="width:10%; vertical-align : middle; text-align: center;" rowspan="2">Saldo Akhir</th>
            <th style="width:10%; vertical-align : middle; text-align: center;" colspan="4">Usia Piutang</th>
            <th style="width:10%; vertical-align : middle; text-align: center;" rowspan="2">BG Mundur</th>
            <th style="width:10%; vertical-align : middle; text-align: center;" rowspan="2">Saldo</th>
        </tr>
        <tr>
            <th style="width:10%; vertical-align : middle; text-align: center;">Kode</th>
            <th style="width:10%; vertical-align : middle; text-align: center;">Nama</th>
            <th style="width:10%; vertical-align : middle; text-align: center;">Jum.Fak</th>
            <th style="width:10%; vertical-align : middle; text-align: center;">Nilai</th>
            <th style="width:10%; vertical-align : middle; text-align: center;">Jum.Rtr</th>
            <th style="width:10%; vertical-align : middle; text-align: center;">Nilai</th>
            <th style="width:10%; vertical-align : middle; text-align: center;">0 - 2 Bulan</th>
            <th style="width:10%; vertical-align : middle; text-align: center;">2 - 3 Bulan</th>
            <th style="width:10%; vertical-align : middle; text-align: center;">3 - 4 Bulan</th>
            <th style="width:10%; vertical-align : middle; text-align: center;">>4 Bulan</th>
        </tr>
        <?php
        $sum_saldo_awal = 0;
        $sum_jumlah_fak = 0;
        $sum_nilai_fak = 0;
        $sum_pembayaran = 0;
        $sum_saldo_akhir = 0;
        $sum_nol_dua_bulan = 0;
        $sum_dua_tiga_bulan = 0;
        $sum_tiga_empat_bulan = 0;
        $sum_lebih_empat_bulan = 0;
        $sum_bg_mundur = 0;
        $sum_saldo = 0;
        ?>
        @foreach($data as $data)
        <tr>
            <td style="vertical-align : middle; text-align: center;">{{ $data->custid }}</td>
            <td style="vertical-align : middle; text-align: center;">{{ $data->custname }}</td>
            @if($data->saldo_awal == null || $data->saldo_awal == '')
            <td style="vertical-align : middle; text-align: center;">0.00</td>
            @else
            <?php $sum_saldo_awal += $data->saldo_awal; ?>
            <td style="vertical-align : middle; text-align: center;">{{ number_format($data->saldo_awal, 2, ".", ",") }}</td>
            @endif
            @if($data->jumlah_fak == null || $data->jumlah_fak == '')
            <td style="vertical-align : middle; text-align: center;">0</td>
            @else
            <?php $sum_jumlah_fak += $data->jumlah_fak; ?>
            <td style="vertical-align : middle; text-align: center;">{{ $data->jumlah_fak }}</td>
            @endif
            @if($data->nilai_fak == null || $data->nilai_fak == '')
            <td style="vertical-align : middle; text-align: center;">0.00</td>
            @else
            <?php $sum_nilai_fak += $data->nilai_fak; ?>
            <td style="vertical-align : middle; text-align: center;">{{ number_format($data->nilai_fak, 2, ".", ",") }}</td>
            @endif
            <td style="vertical-align : middle; text-align: center;">0.00</td>
            <td style="vertical-align : middle; text-align: center;">0.00</td>
            @if($data->pembayaran == null || $data->pembayaran == '')
            <td style="vertical-align : middle; text-align: center;">0.00</td>
            @else
            <?php $sum_pembayaran += $data->pembayaran; ?>
            <td style="vertical-align : middle; text-align: center;">{{ number_format($data->pembayaran, 2, ".", ",") }}</td>
            @endif
            @if($data->saldo_akhir == null || $data->saldo_akhir == '')
            <td style="vertical-align : middle; text-align: center;">0.00</td>
            @else
            <?php $sum_saldo_akhir += $data->saldo_akhir; ?>
            <td style="vertical-align : middle; text-align: center;">{{ number_format($data->saldo_akhir, 2, ".", ",") }}</td>
            @endif
            @if($data->nol_dua_bulan == null || $data->nol_dua_bulan == '')
            <td style="vertical-align : middle; text-align: center;">0.00</td>
            @else
            <?php $sum_nol_dua_bulan += $data->nol_dua_bulan; ?>
            <td style="vertical-align : middle; text-align: center;">{{ number_format($data->nol_dua_bulan, 2, ".", ",") }}</td>
            @endif
            @if($data->dua_tiga_bulan == null || $data->dua_tiga_bulan == '')
            <td style="vertical-align : middle; text-align: center;">0.00</td>
            @else
            <?php $sum_dua_tiga_bulan += $data->dua_tiga_bulan; ?>
            <td style="vertical-align : middle; text-align: center;">{{ number_format($data->dua_tiga_bulan, 2, ".", ",") }}</td>
            @endif
            @if($data->tiga_empat_bulan == null || $data->tiga_empat_bulan == '')
            <td style="vertical-align : middle; text-align: center;">0.00</td>
            @else
            <?php $sum_tiga_empat_bulan += $data->tiga_empat_bulan; ?>
            <td style="vertical-align : middle; text-align: center;">{{ number_format($data->tiga_empat_bulan, 2, ".", ",") }}</td>
            @endif
            @if($data->lebih_empat_bulan == null || $data->lebih_empat_bulan == '')
            <td style="vertical-align : middle; text-align: center;">0.00</td>
            @else
            <?php $sum_lebih_empat_bulan += $data->lebih_empat_bulan; ?>
            <td style="vertical-align : middle; text-align: center;">{{ number_format($data->lebih_empat_bulan, 2, ".", ",") }}</td>
            @endif
            @if($data->bg_mundur == null || $data->bg_mundur == '')
            <td style="vertical-align : middle; text-align: center;">0.00</td>
            @else
            <?php $sum_bg_mundur += $data->bg_mundur; ?>
            <td style="vertical-align : middle; text-align: center;">{{ number_format($data->bg_mundur, 2, ".", ",") }}</td>
            @endif
            @if($data->saldo == null || $data->saldo == '')
            <td style="vertical-align : middle; text-align: center;">0.00</td>
            @else
            <?php $sum_saldo += $data->saldo; ?>
            <td style="vertical-align : middle; text-align: center;">{{ number_format($data->saldo, 2, ".", ",") }}</td>
            @endif
        </tr>
        @endforeach
        <tr>
            <td colspan="2" style="text-align: right; vertical-align : middle; text-align: center;">Total</td>
            <td style="vertical-align : middle; text-align: center;">{{ number_format($sum_saldo_awal, 2, ".", ",") }}</td>
            <td style="vertical-align : middle; text-align: center;">{{ number_format($sum_jumlah_fak, 2, ".", ",") }}</td>
            <td style="vertical-align : middle; text-align: center;">{{ number_format($sum_nilai_fak, 2, ".", ",") }}</td>
            <td style="vertical-align : middle; text-align: center;">0.00</td>
            <td style="vertical-align : middle; text-align: center;">0.00</td>
            <td style="vertical-align : middle; text-align: center;">{{ number_format($sum_pembayaran, 2, ".", ",") }}</td>
            <td style="vertical-align : middle; text-align: center;">{{ number_format($sum_saldo_akhir, 2, ".", ",") }}</td>
            <td style="vertical-align : middle; text-align: center;">{{ number_format($sum_nol_dua_bulan, 2, ".", ",") }}</td>
            <td style="vertical-align : middle; text-align: center;">{{ number_format($sum_dua_tiga_bulan, 2, ".", ",") }}</td>
            <td style="vertical-align : middle; text-align: center;">{{ number_format($sum_tiga_empat_bulan, 2, ".", ",") }}</td>
            <td style="vertical-align : middle; text-align: center;">{{ number_format($sum_lebih_empat_bulan, 2, ".", ",") }}</td>
            <td style="vertical-align : middle; text-align: center;">{{ number_format($sum_bg_mundur, 2, ".", ",") }}</td>
            <td style="vertical-align : middle; text-align: center;">{{ number_format($sum_saldo, 2, ".", ",") }}</td>
        </tr>
    </table>
</body>
</html>