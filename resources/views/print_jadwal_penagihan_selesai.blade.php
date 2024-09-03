<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Aktivitas Penagihan Selesai</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        * { box-sizing: border-box; }
        body{
            margin-left: 30px;
            margin-right: 30px;
            margin-bottom: 25px;
            margin-top: 25px;
        }
        table {
            border-collapse: collapse;
            font-size: 12px;
        }
        table, th, td {
            border: 1px solid #000;
            text-align: center;
            padding: 10px;
        }
        h2 {
            text-align: center; 
        }
    </style>
</head>
<body>
    <h2>Aktivitas Penagihan Selesai Tanggal {{ date('j F Y', strtotime($data[0]->tanggal_tagih_cust)) }}</h2>
    <?php $count = 0 ?>
    <table style="width: 100%;">
        <thead>
            <tr>
                <th style="width:3%;">No</th>
                <th style="width:10%;">Nomor SJ</th>
                <th style="width:10%;">No Invoice</th>
                <th style="width:10%;">Tanggal SJ</th>
                <th>Customers</th>
                <th style="width:10%;">Tagihan (Rp)</th>
                <th style="width:12%;">Metode</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $data)
            <?php $count++ ?>
            <tr>
                <td>{{ $count }}</td>
                <td>{{ $data->nosj }}</td>
                @if($data->noinv == null || $data->noinv == '')
                <td>--</td>
                @else
                <td>{{ $data->noinv }}</td>
                @endif
                <td>{{ date('j M Y', strtotime($data->tanggal_do)) }}</td>
                <td>{{ $data->custname }}</td>
                <td>{{ number_format($data->tagihan, 0, ".", ".") }}</td>
                <td>{{ $data->metode }} <br>
                    @if($data->nomor_metode_pembayaran)
                        No. {{ $data->nomor_metode_pembayaran }} <br>
                    @endif

                    @if($data->nominal_bayar)
                        Rp {{ number_format($data->nominal_bayar, 0, ".", ".") }}
                    @endif
                </td>
                @if($data->keterangan_penagihan == null || $data->keterangan_penagihan == '')
                <td>--</td>
                @else
                <td>{{ $data->keterangan_penagihan }}</td>
                @endif
            </tr>
            @endforeach
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