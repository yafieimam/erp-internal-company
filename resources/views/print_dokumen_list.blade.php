<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Dokumen List</title>
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
            font-size: 14px;
        }
        table, th, td {
            border: 1px solid #000;
            text-align: center;
            padding: 10px;
        }
        h2 {
            text-align: center; 
        }
        td input[type="checkbox"] {
            float: left;
            margin: 0 auto;
            width: 100%;
        }
    </style>
</head>
<body>
    <h2>List Dokumen Dari DSGM Tgl {{ date('j F Y', strtotime($data[0]->tanggal_terima_ondomohen)) }}</h2>
    <?php $count = 0 ?>
    <table style="width: 100%;">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width:12%;">Nomor SJ</th>
                <th style="width:12%;">No Invoice</th>
                <th style="width:12%;">Tanggal SJ</th>
                <th>Customers</th>
                <th style="width:13%;">Tagihan</th>
                <th>Keterangan</th>
                <th style="width:6%;">Dibayar</th>
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
                <td>Rp {{ number_format($data->tagihan, 0, ".", ".") }}</td>
                @if($data->ket_ondomohen == 1)
                    @if($data->check_dibayar_admin == 1)
                        <td>{{ $data->keterangan_penagihan }}</td>
                        <td><input type="checkbox" checked></td>
                    @else
                        @if($data->check_diserahkan_admin == 1)
                            <td>{{ $data->keterangan_penerimaan }}</td>
                            <td><input type="checkbox"></td>
                        @else
                            <td>{{ $data->keterangan }}</td>
                            <td><input type="checkbox"></td>
                        @endif
                    @endif
                @else
                    <td>-</td>
                    @if($data->check_dibayar_admin == 1)
                    <td><input type="checkbox" checked></td>
                    @else
                    <td><input type="checkbox"></td>
                    @endif
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