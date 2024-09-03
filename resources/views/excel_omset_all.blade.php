<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Omset All</title>
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
        @if($from_date == $to_date)
        <tr>
            <th>Bulan : </th>
            <th id="td_tanggal">{{ date('m') }}</th>
        </tr>
        @else
        <tr>
            <th>Tanggal : </th>
            <th id="td_tanggal">{{ $from_date }} - {{ $to_date }}</th>
        </tr>
        @endif
    </table>

    <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Total Customers</th>
                <th>Total Tonase (TON)</th>
                <th>Total Omset (RP)</th>
            </tr>
        </thead>
        <tbody id="tbody_view">
            @foreach($data as $value)
            <tr>
                <td>{{ $value['tanggal'] }}</td>
                <td>{{ $value['jumlah_customer'] }}</td>
                <td>{{ $value['jumlah_tonase'] }}</td>
                <td>{{ $value['total_omset'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>