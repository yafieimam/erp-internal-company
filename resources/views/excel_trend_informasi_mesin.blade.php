<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Trend Informasi Mesin</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        * { box-sizing: border-box; }
        body{
            margin-left: 30px;
            margin-right: 30px;
            margin-bottom: 25px;
            margin-top: 25px;
        }

        table tr td, table tr th {
            border: 1px dashed #CCC;
        }
        
    </style>
</head>
<body>
    <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
        <tr>
            <th>Trend Informasi Mesh Selama {{ $periode }} Bulan Dari Tanggal {{ $tanggal_awal }} - {{ $tanggal_akhir }}</th>
        </tr>
    </table>
    @foreach($data as $key => $value)
        <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
            <tr>
                <th>Mesin : </th>
                <th>{{ $key }}</th>
            </tr>
        </table>

        <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
            <thead>
                <tr>
                    <th style="vertical-align : middle; text-align: center;">Mesh</th>
                    <th style="vertical-align : middle; text-align: center;">RPM</th>
                    <th style="vertical-align : middle; text-align: center;">SSA</th>
                    <th style="vertical-align : middle; text-align: center;">D-50</th>
                    <th style="vertical-align : middle; text-align: center;">D-98</th>
                    <th style="vertical-align : middle; text-align: center;">CIE 86</th>
                    <th style="vertical-align : middle; text-align: center;">ISO 2470</th>
                    <th style="vertical-align : middle; text-align: center;">Moisture</th>
                    <th style="vertical-align : middle; text-align: center;">Residue</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="vertical-align : middle; text-align: center;">{{ $value['mesh'] }}</td>
                    <td style="vertical-align : middle; text-align: center;">{{ $value['rpm'] }}</td>
                    <td style="vertical-align : middle; text-align: center;">{{ $value['ssa'] }}</td>
                    <td style="vertical-align : middle; text-align: center;">{{ $value['d50'] }}</td>
                    <td style="vertical-align : middle; text-align: center;">{{ $value['d98'] }}</td>
                    <td style="vertical-align : middle; text-align: center;">{{ $value['cie86'] }}</td>
                    <td style="vertical-align : middle; text-align: center;">{{ $value['iso2470'] }}</td>
                    <td style="vertical-align : middle; text-align: center;">{{ $value['moisture'] }}</td>
                    <td style="vertical-align : middle; text-align: center;">{{ $value['residue'] }}</td>
                </tr>
            </tbody>
        </table>
        
    @endforeach
</body>
</html>