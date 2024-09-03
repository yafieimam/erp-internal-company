<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Aging Schedule</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        * { box-sizing: border-box; }
        body{
            margin-left: 0px;
            margin-right: 0px;
            margin-bottom: 5px;
            margin-top: 5px;
        }
        table {
            border-collapse: collapse;
        }
        table, th, td {
            text-align: left;
            padding: 5px;
        }
        h3, h5 {
            text-align: left; 
            margin: 0;
            padding: 0;
            line-height: 30px;
            height: 25px;
        }
        td input[type="checkbox"] {
            float: left;
            margin: 0 auto;
            width: 100%;
        }1
    </style>
</head>
<body>
    <h3>A G I N G &nbsp;&nbsp; S C H E D U L E</h3>
    <h5>PT. DWI SELO GIRI MAS</h5>
    <div style="text-align: left; padding-top: 10px; font-size: 10px;">Tanggal : {{ date('j F Y', strtotime($from_date)) }} s.d. {{ date('j F Y', strtotime($to_date)) }}</div>
    <div style="text-align: left; padding-top: 10px; padding-bottom: 30px; font-size: 10px;">Tanggal Pencetakan Laporan : {{ date('j F Y') }}</div>
    <table style="width: 100%; font-size: 10px;">
        <thead style="border: 1px solid #000;">
            <tr>
                <th style="border-bottom: 1px solid #000;" colspan="2">Data Pelanggan</th>
                <th style="vertical-align: bottom;" rowspan="2">Saldo Awal</th>
                <th style="border-bottom: 1px solid #000;" colspan="4">Data Penjualan Bulan Ini</th>
                <th style="vertical-align: bottom;" rowspan="2">Pembayaran Periode Ini</th>
                <th style="vertical-align: bottom;" rowspan="2">Saldo Akhir</th>
                <th style="border-bottom: 1px solid #000; text-align: center;" colspan="4">U S I A &nbsp;&nbsp; P I U T A N G</th>
                <th style="vertical-align: bottom;" rowspan="2">BG Mundur</th>
                <th style="vertical-align: bottom;" rowspan="2">Saldo</th>
            </tr>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Jum.Fak</th>
                <th>Nilai</th>
                <th>Jum.Rtr</th>
                <th>Nilai</th>
                <th>0 - 2 Bulan</th>
                <th>2 - 3 Bulan</th>
                <th>3 - 4 Bulan</th>
                <th>>4 Bulan</th>
            </tr>
        </thead>
        <tbody>
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
                <td>{{ $data->custid }}</td>
                <td>{{ $data->custname }}</td>
                @if($data->saldo_awal == null || $data->saldo_awal == '')
                <td>0.00</td>
                @else
                {{ $sum_saldo_awal += $data->saldo_awal }}
                <td>{{ number_format($data->saldo_awal, 2, ".", ",") }}</td>
                @endif
                @if($data->jumlah_fak == null || $data->jumlah_fak == '')
                <td>0</td>
                @else
                {{ $sum_jumlah_fak += $data->jumlah_fak }}
                <td>{{ $data->jumlah_fak }}</td>
                @endif
                @if($data->nilai_fak == null || $data->nilai_fak == '')
                <td>0.00</td>
                @else
                {{ $sum_nilai_fak += $data->nilai_fak }}
                <td>{{ number_format($data->nilai_fak, 2, ".", ",") }}</td>
                @endif
                <td>0.00</td>
                <td>0.00</td>
                @if($data->pembayaran == null || $data->pembayaran == '')
                <td>0.00</td>
                @else
                {{ $sum_pembayaran += $data->pembayaran }}
                <td>{{ number_format($data->pembayaran, 2, ".", ",") }}</td>
                @endif
                @if($data->saldo_akhir == null || $data->saldo_akhir == '')
                <td>0.00</td>
                @else
                {{ $sum_saldo_akhir += $data->saldo_akhir }}
                <td>{{ number_format($data->saldo_akhir, 2, ".", ",") }}</td>
                @endif
                @if($data->nol_dua_bulan == null || $data->nol_dua_bulan == '')
                <td>0.00</td>
                @else
                {{ $sum_nol_dua_bulan += $data->nol_dua_bulan }}
                <td>{{ number_format($data->nol_dua_bulan, 2, ".", ",") }}</td>
                @endif
                @if($data->dua_tiga_bulan == null || $data->dua_tiga_bulan == '')
                <td>0.00</td>
                @else
                {{ $sum_dua_tiga_bulan += $data->dua_tiga_bulan }}
                <td>{{ number_format($data->dua_tiga_bulan, 2, ".", ",") }}</td>
                @endif
                @if($data->tiga_empat_bulan == null || $data->tiga_empat_bulan == '')
                <td>0.00</td>
                @else
                {{ $sum_tiga_empat_bulan += $data->tiga_empat_bulan }}
                <td>{{ number_format($data->tiga_empat_bulan, 2, ".", ",") }}</td>
                @endif
                @if($data->lebih_empat_bulan == null || $data->lebih_empat_bulan == '')
                <td>0.00</td>
                @else
                {{ $sum_lebih_empat_bulan += $data->lebih_empat_bulan }}
                <td>{{ number_format($data->lebih_empat_bulan, 2, ".", ",") }}</td>
                @endif
                @if($data->bg_mundur == null || $data->bg_mundur == '')
                <td>0.00</td>
                @else
                {{ $sum_bg_mundur += $data->bg_mundur }}
                <td>{{ number_format($data->bg_mundur, 2, ".", ",") }}</td>
                @endif
                @if($data->saldo == null || $data->saldo == '')
                <td>0.00</td>
                @else
                {{ $sum_saldo += $data->saldo }}
                <td>{{ number_format($data->saldo, 2, ".", ",") }}</td>
                @endif
            </tr>
            @endforeach
            <tr>
                <td colspan="2" style="text-align: right">Total</td>
                <td>{{ number_format($sum_saldo_awal, 2, ".", ",") }}</td>
                <td>{{ number_format($sum_jumlah_fak, 2, ".", ",") }}</td>
                <td>{{ number_format($sum_nilai_fak, 2, ".", ",") }}</td>
                <td>0.00</td>
                <td>0.00</td>
                <td>{{ number_format($sum_pembayaran, 2, ".", ",") }}</td>
                <td>{{ number_format($sum_saldo_akhir, 2, ".", ",") }}</td>
                <td>{{ number_format($sum_nol_dua_bulan, 2, ".", ",") }}</td>
                <td>{{ number_format($sum_dua_tiga_bulan, 2, ".", ",") }}</td>
                <td>{{ number_format($sum_tiga_empat_bulan, 2, ".", ",") }}</td>
                <td>{{ number_format($sum_lebih_empat_bulan, 2, ".", ",") }}</td>
                <td>{{ number_format($sum_bg_mundur, 2, ".", ",") }}</td>
                <td>{{ number_format($sum_saldo, 2, ".", ",") }}</td>
            </tr>
        </tbody>
    </table>
    <script type="text/php">
    if (isset($pdf)) {
        $pdf->page_script('
            $text = sprintf(_("Page %d/%d"),  $PAGE_NUM, $PAGE_COUNT);
            // Uncomment the following line if you use a Laravel-based i18n
            //$text = __("Page :pageNum/:pageCount", ["pageNum" => $PAGE_NUM, "pageCount" => $PAGE_COUNT]);
            $font = null;
            $size = 9;
            $color = array(0,0,0);
            $word_space = 0.0;  //  default
            $char_space = 0.0;  //  default
            $angle = 0.0;   //  default

            // Compute text width to center correctly
            $textWidth = $fontMetrics->getTextWidth($text, $font, $size);

            $x = ($pdf->get_width() - $textWidth) / 2;
            $y = $pdf->get_height() - 35;

            $pdf->text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        '); // End of page_script
    }
    </script>
</body>
</html>