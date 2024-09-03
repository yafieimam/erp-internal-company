<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mesh {{ $mesh }} Mesin {{ $mesin }}</title>
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
            <th>Mesh {{ $mesh }} Mesin {{ $mesin }}</th>
        </tr>
    </table>
    <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
        <thead>
            <tr>
                <th style="vertical-align : middle; text-align: center;">SSA</th>
                <th style="vertical-align : middle; text-align: center;">D50</th>
                <th style="vertical-align : middle; text-align: center;">Whiteness</th>
                <th style="vertical-align : middle; text-align: center;">Moisture</th>
                <th style="vertical-align : middle; text-align: center;">Residue</th>
            </tr>
        </thead>
        <tbody>
            @for($i = 0; $i < count($data_ssa); $i++)    
                <tr>
                    <td style="vertical-align : middle; text-align: center;">{{ ($data_ssa[$i] == 0  || $data_ssa[$i] == null ? '0': $data_ssa[$i]) }}</td>
                    <td style="vertical-align : middle; text-align: center;">{{ ($data_d50[$i] == 0  || $data_d50[$i] == null ? '0': $data_d50[$i]) }}</td>
                    <td style="vertical-align : middle; text-align: center;">{{ ($data_whiteness[$i] == 0  || $data_whiteness[$i] == null ? '0': $data_whiteness[$i]) }}</td>
                    <td style="vertical-align : middle; text-align: center;">{{ ($data_moisture[$i] == 0  || $data_moisture[$i] == null ? '0': $data_moisture[$i]) }}</td>
                    <td style="vertical-align : middle; text-align: center;">{{ ($data_residue[$i] == 0  || $data_residue[$i] == null ? '0': $data_residue[$i]) }}</td>
                </tr>
            @endfor
        </tbody>
    </table>
</body>
</html>