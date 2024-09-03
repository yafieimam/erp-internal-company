<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Inventaris Batu</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        * { box-sizing: border-box; }
        body{
            margin-left: 30px;
            margin-right: 30px;
            margin-bottom: 25px;
            margin-top: 25px;
        }
        
    </style>
</head>
<body>
    <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
        <tr>
            <th>Inventaris Batu</th>
        </tr>
    </table>
    <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
        <thead>
            <tr>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Tanggal</th>
                @foreach($data_item as $data_item)
                <th style="vertical-align : middle; text-align: center;" colspan="3">{{ $data_item->nama_batu }}</th>
                @endforeach
            </tr>

            <tr>
                @for($i = 0; $i < $count_batu; $i++)
                <th style="vertical-align : middle; text-align: center;">Masuk</th>
                <th style="vertical-align : middle; text-align: center;">Keluar</th>
                <th style="vertical-align : middle; text-align: center;">Stok</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $value)
                <tr>
                    <td style="vertical-align : middle; text-align: center;">{{ $key }}</td>
                @foreach($value as $key2 => $value2)
                    @foreach($value2 as $key3 => $value3) 
                        <td style="vertical-align : middle; text-align: center;">{{ $value3['masuk'] }}</td>
                        <td style="vertical-align : middle; text-align: center;">{{ $value3['keluar'] }}</td>
                        <td style="vertical-align : middle; text-align: center;">{{ $value3['stok'] }}</td>
                    @endforeach
                @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>