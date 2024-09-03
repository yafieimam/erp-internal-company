<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Jadwal Follow Up</title>
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
            font-size: 12spx;
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
    <h2>Jadwal Follow Up Tanggal {{ date('j F Y', strtotime($data[0]->tanggal_schedule)) }}</h2>
    <?php $count = 0 ?>
    <table style="width: 100%;">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width:8%;">Tipe Cust</th>
                <th style="width:15%;">Customers</th>
                <th style="width:10%;">Perihal</th>
                <th style="width:7%;">Offline?</th>
                <th style="width:27%;">Alamat</th>
                <th style="width:7%;">Waktu</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $data)
            <tr>
                <td>{{ $data->order_sort }}</td>
                <td>{{ $data->tipe_customers }}</td>
                <td>{{ $data->customer }}</td>
                <td>{{ $data->perihal }}</td>
                <td>{{ $data->offline }}</td>
                <td>{{ $data->alamat }}</td>
                <td>{{ date('H:i', strtotime($data->waktu_schedule)) }}</td>
                @if($data->keterangan == '' || $data->keterangan == null)
                <td>---</td>
                @else
                <td>{{ $data->keterangan }}</td>
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