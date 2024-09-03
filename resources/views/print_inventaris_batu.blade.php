<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Inventaris Batu</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page { margin: 15px; }
        * { box-sizing: border-box; }
        body{
            margin-left: 10px;
            margin-right: 10px;
        }
        table {
            border-collapse: collapse;
            font-size: 14px;
        }
        table, th, td {
            text-align: center;
        }
        #table_data th, #table_data td {
            border: 1px solid #000;
        }
        h2 {
            text-align: center; 
        }
    </style>
</head>
<body>
    <table style="width: 100%;">
        <tr>
            <td><h2 style="margin:10px; margin-bottom: 0;">INVENTARIS BATU</h2></td>
        </tr>
        <tr>
            <td><h4 style="margin:10px;">Tanggal : {{ $tanggal }}</h4></td>
        </tr>
    </table>
    <table id="table_data" style="width: 100%;">
        <thead>
            <tr>
                <th>Batu</th>
                <th>Masuk (KG)</th>
                <th>Keluar (KG)</th>
                <th>Stok (KG)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $data)
                <tr>
                    <td>{{ $data->nama_batu }}</td>
                    <td>{{ $data->masuk }}</td>
                    <td>{{ $data->keluar }}</td>
                    <td>{{ $data->stok }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>