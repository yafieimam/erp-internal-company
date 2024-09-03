<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $judul }}</title>
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
            <th>Potongan Batu {{ $judul }}</th>
        </tr>
    </table>
    <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
        <thead>
            <tr>
                <th style="vertical-align : middle; text-align: center;">No</th>
                <th style="vertical-align : middle; text-align: center;">GRNO</th>
                <th style="vertical-align : middle; text-align: center;">Tanggal</th>
                <th style="vertical-align : middle; text-align: center;">Vendor</th>
                <th style="vertical-align : middle; text-align: center;">Qpur</th>
                <th style="vertical-align : middle; text-align: center;">Sat</th>
                <th style="vertical-align : middle; text-align: center;">Itemname</th>
                <th style="vertical-align : middle; text-align: center;">Potongan</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $count = 0; 
            ?>
            @foreach($data as $data)
                <?php 
                    $count++; 
                ?> 
                <tr>
                    <td style="vertical-align : middle; text-align: center;">{{ $count }}</td>
                    <td style="vertical-align : middle; text-align: center;">{{ $data->grno }}</td>
                    <td style="vertical-align : middle; text-align: center;">{{ $data->tanggal }}</td>
                    <td style="vertical-align : middle; text-align: center;">{{ $data->nama_vendor }}</td>
                    <td style="vertical-align : middle; text-align: center;">{{ $data->qpur }}</td>
                    <td style="vertical-align : middle; text-align: center;">{{ $data->sat }}</td>
                    <td style="vertical-align : middle; text-align: center;">{{ $data->nama_batu }}</td>
                    @if($count % 2 == 0)
                    <td style="vertical-align : middle; text-align: center;"></td>
                    @else
                    <td style="vertical-align : middle; text-align: center;">{{ $data->potongan_batu }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>