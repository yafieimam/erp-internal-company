<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Customers Follow Up</title>
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
            <th></th>
            <th>Customers Follow Up</th>
            @if($from_date == $to_date)
            <th>Bulan : </th>
            <th id="td_tanggal">{{ date('m') }}</th>
            @else
            <th>Tanggal : </th>
            <th id="td_tanggal">{{ $from_date }} - {{ $to_date }}</th>
            @endif
        </tr>
    </table>
    <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
        <thead>
            <tr>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">No</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Customers</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Bidang Usaha</th>
                @for($i = 0; $i < $count_terbesar; $i++)
                <th style="vertical-align : middle; text-align: center;" colspan="5">Follow Up {{ $i + 1 }}</th>
                @endfor
            </tr>

            <tr>
                @for($i = 0; $i < $count_terbesar; $i++)
                <th style="vertical-align : middle; text-align: center;">Tanggal</th>
                <th style="vertical-align : middle; text-align: center;">Kunjungan</th>
                <th style="vertical-align : middle; text-align: center;">Keterangan</th>
                <th style="vertical-align : middle; text-align: center;">Perlu Follow Up</th>
                <th style="vertical-align : middle; text-align: center;">Penawaran</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            {{ $no = 0 }}
            {{ $count_follow_up = 0 }}
            @foreach($data as $key => $value)
                {{ $no++ }}
                <tr>
                    <td style="vertical-align : middle; text-align: center;">{{ $no }}</td>
                    <td style="vertical-align : middle; text-align: center;">{{ $value['nama'] }}</td>
                    <td style="vertical-align : middle; text-align: center;">{{ $value['bidang_usaha'] }}</td>
                    @foreach(array_slice($value, 2) as $key2 => $value2)
                        <td style="vertical-align : middle; text-align: center;">{{ $value2['tanggal'] }}</td>
                        @if($value2['offline'] == 1)
                        <td style="vertical-align : middle; text-align: center;">Ya</td>
                        @else
                        <td style="vertical-align : middle; text-align: center;">Tidak</td>
                        @endif
                        @if($value2['ket_follow_up'] != null || $value2['ket_follow_up'] != '')
                        <td style="vertical-align : middle; text-align: center;">{{ $value2['ket_follow_up'] }}</td>
                        @else
                        <td style="vertical-align : middle; text-align: center;">-</td>
                        @endif
                        @if($value2['follow_up'] == 1)
                        <td style="vertical-align : middle; text-align: center;">Ya</td>
                        @else
                        <td style="vertical-align : middle; text-align: center;">Tidak</td>
                        @endif
                        @if($value2['penawaran'] != null || $value2['penawaran'] != '')
                        <td style="vertical-align : middle; text-align: center;">Ya</td>
                        @else
                        <td style="vertical-align : middle; text-align: center;">Tidak</td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>