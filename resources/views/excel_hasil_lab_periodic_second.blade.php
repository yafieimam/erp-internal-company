<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Lab Per Tanggal</title>
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
    @foreach($data as $key => $value)
        <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
            <tr>
                <?php setlocale(LC_TIME, 'INDONESIA'); ?>
                <th style="vertical-align : middle; text-align: center;">{{ date('l', strtotime($key)) }}</th>
                <th style="vertical-align : middle; text-align: center;"></th>
                <th style="vertical-align : middle; text-align: center;">:</th>
                <th style="vertical-align : middle; text-align: center;">{{ $key }}</th>
                <th style="vertical-align : middle; text-align: center;"></th>
                <th style="vertical-align : middle; text-align: center;"></th>
                <th style="vertical-align : middle; text-align: center;"></th>
                <th style="vertical-align : middle; text-align: center;"></th>
                <th style="vertical-align : middle; text-align: center;"></th>
                <th style="vertical-align : middle; text-align: center;"></th>
                <th style="vertical-align : middle; text-align: center;"></th>
                <th style="vertical-align : middle; text-align: center;"></th>
                <th style="vertical-align : middle; text-align: center;"></th>
                <th style="vertical-align : middle; text-align: center;">JD</th>
                <th style="vertical-align : middle; text-align: center;"></th>
                <th style="vertical-align : middle; text-align: center;">123</th>
            </tr>
        </table>
        <br>
        <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
            <tr>
                <th style="vertical-align : middle; text-align: center;">RENCANA</th>
                <th style="vertical-align : middle; text-align: center;"></th>
                <th style="vertical-align : middle; text-align: center;"></th>
                <th style="vertical-align : middle; text-align: center;"></th>
                <th style="vertical-align : middle; text-align: center;" colspan="5">Mesin</th>
                <th style="vertical-align : middle; text-align: center;" colspan="3">Mesin</th>
                <th style="vertical-align : middle; text-align: center;" colspan="4">Mesin</th>
            </tr>
            <tr>
                <th style="vertical-align : middle; text-align: center;"></th>
                <th style="vertical-align : middle; text-align: center;">PRODUKSI</th>
                <th style="vertical-align : middle; text-align: center;"></th>
                <th style="vertical-align : middle; text-align: center;"></th>
                <th style="vertical-align : middle; text-align: center;" colspan="5">SA, SB, dan Mix</th>
                <th style="vertical-align : middle; text-align: center;" colspan="3">RA dan RB</th>
                <th style="vertical-align : middle; text-align: center;" colspan="4">RC, RD, RE, RF, dan RG</th>
            </tr>
            <tr>
                <td style="vertical-align : middle; text-align: center;">Whiteness (%)</td>
                <td style="vertical-align : middle; text-align: center;"></td>
                <td style="vertical-align : middle; text-align: center;"></td>
                <td style="vertical-align : middle; text-align: center;"></td>
                <td style="vertical-align : middle; text-align: center;" colspan="5">> 85,0</td>
                <td style="vertical-align : middle; text-align: center;" colspan="3">> 80,0</td>
                <td style="vertical-align : middle; text-align: center;" colspan="4">> 70,0</td>
            </tr>
            <tr>
                <td style="vertical-align : middle; text-align: center;">Moisture (%)</td>
                <td style="vertical-align : middle; text-align: center;"></td>
                <td style="vertical-align : middle; text-align: center;"></td>
                <td style="vertical-align : middle; text-align: center;"></td>
                <td style="vertical-align : middle; text-align: center;" colspan="5">0,3 +- 0,1</td>
                <td style="vertical-align : middle; text-align: center;" colspan="3">0,3 +- 0,1</td>
                <td style="vertical-align : middle; text-align: center;" colspan="4">0,3 +- 0,1</td>
            </tr>
            <tr>
                <td style="vertical-align : middle; text-align: center;">Residue Max (%)</td>
                <td style="vertical-align : middle; text-align: center;"></td>
                <td style="vertical-align : middle; text-align: center;"></td>
                <td style="vertical-align : middle; text-align: center;"></td>
                <td style="vertical-align : middle; text-align: center;" colspan="5">0,10</td>
                <td style="vertical-align : middle; text-align: center;" colspan="3">0,30</td>
                <td style="vertical-align : middle; text-align: center;" colspan="4">0,30</td>
            </tr>
        </table>
        <br>
        <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
            <thead>
                <tr>
                    <th style="vertical-align : middle; text-align: center;">Mesin</th>
                    <th style="vertical-align : middle; text-align: center;"></th>
                    <th style="vertical-align : middle; text-align: center;"></th>
                    <th style="vertical-align : middle; text-align: center;">Mesh</th>
                    <th style="vertical-align : middle; text-align: center;">Rate</th>
                    <th style="vertical-align : middle; text-align: center;" colspan="2">SSA</th>
                    <th style="vertical-align : middle; text-align: center;" colspan="2"> d-50</th>
                    <th style="vertical-align : middle; text-align: center;" colspan="2"> d-98</th>
                    <th style="vertical-align : middle; text-align: center;" colspan="2">Whiteness</th>
                    <th style="vertical-align : middle; text-align: center;">Moist</th>
                    <th style="vertical-align : middle; text-align: center;" colspan="2">Residue</th>
                </tr>
                <tr>
                    <th style="vertical-align : middle; text-align: center;"></th>
                    <th style="vertical-align : middle; text-align: center;"></th>
                    <th style="vertical-align : middle; text-align: center;"></th>
                    <th style="vertical-align : middle; text-align: center;"></th>
                    <th style="vertical-align : middle; text-align: center;">rpm</th>
                    <th style="vertical-align : middle; text-align: center;">Std</th>
                    <th style="vertical-align : middle; text-align: center;">Hasil</th>
                    <th style="vertical-align : middle; text-align: center;">Std</th>
                    <th style="vertical-align : middle; text-align: center;">Hasil</th>
                    <th style="vertical-align : middle; text-align: center;">Std</th>
                    <th style="vertical-align : middle; text-align: center;">Hasil</th>
                    <th style="vertical-align : middle; text-align: center;">CIE86</th>
                    <th style="vertical-align : middle; text-align: center;">ISO 2470</th>
                    <th style="vertical-align : middle; text-align: center;"></th>
                    <th style="vertical-align : middle; text-align: center;">Std</th>
                    <th style="vertical-align : middle; text-align: center;">Hasil</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $besar = 0;
                    $jam_kecil = null;
                    $jam_besar = null;
                    foreach($value as $key_det => $value_det){
                        if($besar < count($value_det)){
                            $besar = count($value_det);
                            if(count($value_det) == 2){
                                $jam_kecil = $value_det[0]['jam_waktu'];
                                $jam_besar = $value_det[1]['jam_waktu'];
                            }else if(count($value_det) == 1){
                                $jam_kecil = $value_det[0]['jam_waktu'];
                            }
                        }
                    }
                ?>
                @foreach($value as $key_det => $value_det)
                    @if($besar > 1)
                        @if(count($value_det) == 0)
                            @if($key_det == "RB")
                                <tr>
                                    <td style="vertical-align : middle; text-align: center;">{{ $key_det }}</td>
                                    <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($jam_kecil)) }}</td>
                                    <td style="vertical-align : middle; text-align: center;"></td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                </tr>
                                <tr>
                                    <td style="vertical-align : middle; text-align: center;"></td>
                                    <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($jam_besar)) }}</td>
                                    <td style="vertical-align : middle; text-align: center;"></td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                </tr>
                                <tr colspan="16"></tr>
                            @else
                                <tr>
                                    <td style="vertical-align : middle; text-align: center;">{{ $key_det }}</td>
                                    <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($jam_kecil)) }}</td>
                                    <td style="vertical-align : middle; text-align: center;"></td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                </tr>
                                <tr>
                                    <td style="vertical-align : middle; text-align: center;"></td>
                                    <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($jam_besar)) }}</td>
                                    <td style="vertical-align : middle; text-align: center;"></td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                </tr>
                            @endif
                        @endif
                    @else
                        @if(count($value_det) == 0)
                            @if($key_det == "RB")
                                <tr>
                                    <td style="vertical-align : middle; text-align: center;">{{ $key_det }}</td>
                                    <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($jam_kecil)) }}</td>
                                    <td style="vertical-align : middle; text-align: center;"></td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                </tr>
                                <tr colspan="16"></tr>
                            @else
                                <tr>
                                    <td style="vertical-align : middle; text-align: center;">{{ $key_det }}</td>
                                    <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($jam_kecil)) }}</td>
                                    <td style="vertical-align : middle; text-align: center;"></td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                    <td style="vertical-align : middle; text-align: center;">-</td>
                                </tr>
                            @endif
                        @endif
                    @endif
                    @foreach($value_det as $key_det2 => $value_det2)
                        @if($key_det2 == 0)
                            @if(count($value_det) < $besar)
                                @if($value_det2['jam_waktu'] == $jam_kecil)
                                    @if($key_det == "RB")
                                        <tr>
                                            <td style="vertical-align : middle; text-align: center;">{{ $key_det }}</td>
                                            <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det2['jam_waktu'])) }}</td>
                                            <td style="vertical-align : middle; text-align: center;"></td>
                                            <td style="vertical-align : middle; text-align: center;">{{ $value_det2['mesh'] }}</td>
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['rpm'] == 0  || $value_det2['rpm'] == null ? '-': $value_det2['rpm']) }}</td>
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_ssa'] == 0  || $value_det2['std_ssa'] == null ? '-': $value_det2['std_ssa']) }}</td>
                                            @if($value_det2['std_ssa'] > $value_det2['ssa'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['ssa'] == 0  || $value_det2['ssa'] == null ? '-': $value_det2['ssa']) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['ssa'] == 0  || $value_det2['ssa'] == null ? '-': $value_det2['ssa']) }}</td>
                                            @endif
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_d50'] == 0  || $value_det2['std_d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['std_d50'], 2, ',', '')) }}</td>
                                            @if($value_det2['std_d50'] < $value_det2['d50'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['d50'] == 0  || $value_det2['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d50'], 2, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['d50'] == 0  || $value_det2['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d50'], 2, ',', '')) }}</td>
                                            @endif
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_d98'] == 0  || $value_det2['std_d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['std_d98'], 2, ',', '')) }}</td>
                                            @if($value_det2['std_d98'] < $value_det2['d98'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['d98'] == 0  || $value_det2['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d98'], 2, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['d98'] == 0  || $value_det2['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d98'], 2, ',', '')) }}</td>
                                            @endif
                                            @if($value_det2['spek_whiteness'] > $value_det2['cie86'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['cie86'] == 0  || $value_det2['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['cie86'], 1, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['cie86'] == 0  || $value_det2['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['cie86'], 1, ',', '')) }}</td>
                                            @endif
                                            @if($value_det2['spek_whiteness'] > $value_det2['iso2470'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['iso2470'] == 0  || $value_det2['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['iso2470'], 1, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['iso2470'] == 0  || $value_det2['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['iso2470'], 1, ',', '')) }}</td>
                                            @endif
                                            @if($value_det2['spek_moisture'] < $value_det2['moisture'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['moisture'] == 0  || $value_det2['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det2['moisture'], 3, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['moisture'] == 0  || $value_det2['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det2['moisture'], 3, ',', '')) }}</td>
                                            @endif
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['standart_residue'] == 0  || $value_det2['standart_residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['standart_residue'], 2, ',', '')) }}</td>
                                            @if($value_det2['spek_residue'] < $value_det2['residue'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['residue'] == 0  || $value_det2['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['residue'], 2, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['residue'] == 0  || $value_det2['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['residue'], 2, ',', '')) }}</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td style="vertical-align : middle; text-align: center;"></td>
                                            <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($jam_besar)) }}</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                        </tr>
                                        <tr colspan="16"></tr>
                                    @else
                                        <tr>
                                            <td style="vertical-align : middle; text-align: center;">{{ $key_det }}</td>
                                            <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det2['jam_waktu'])) }}</td>
                                            <td style="vertical-align : middle; text-align: center;"></td>
                                            <td style="vertical-align : middle; text-align: center;">{{ $value_det2['mesh'] }}</td>
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['rpm'] == 0  || $value_det2['rpm'] == null ? '-': $value_det2['rpm']) }}</td>
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_ssa'] == 0  || $value_det2['std_ssa'] == null ? '-': $value_det2['std_ssa']) }}</td>
                                            @if($value_det2['std_ssa'] > $value_det2['ssa'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['ssa'] == 0  || $value_det2['ssa'] == null ? '-': $value_det2['ssa']) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['ssa'] == 0  || $value_det2['ssa'] == null ? '-': $value_det2['ssa']) }}</td>
                                            @endif
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_d50'] == 0  || $value_det2['std_d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['std_d50'], 2, ',', '')) }}</td>
                                            @if($value_det2['std_d50'] < $value_det2['d50'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['d50'] == 0  || $value_det2['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d50'], 2, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['d50'] == 0  || $value_det2['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d50'], 2, ',', '')) }}</td>
                                            @endif
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_d98'] == 0  || $value_det2['std_d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['std_d98'], 2, ',', '')) }}</td>
                                            @if($value_det2['std_d98'] < $value_det2['d98'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['d98'] == 0  || $value_det2['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d98'], 2, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['d98'] == 0  || $value_det2['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d98'], 2, ',', '')) }}</td>
                                            @endif
                                            @if($value_det2['spek_whiteness'] > $value_det2['cie86'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['cie86'] == 0  || $value_det2['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['cie86'], 1, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['cie86'] == 0  || $value_det2['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['cie86'], 1, ',', '')) }}</td>
                                            @endif
                                            @if($value_det2['spek_whiteness'] > $value_det2['iso2470'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['iso2470'] == 0  || $value_det2['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['iso2470'], 1, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['iso2470'] == 0  || $value_det2['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['iso2470'], 1, ',', '')) }}</td>
                                            @endif
                                            @if($value_det2['spek_moisture'] < $value_det2['moisture'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['moisture'] == 0  || $value_det2['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det2['moisture'], 3, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['moisture'] == 0  || $value_det2['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det2['moisture'], 3, ',', '')) }}</td>
                                            @endif
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['standart_residue'] == 0  || $value_det2['standart_residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['standart_residue'], 2, ',', '')) }}</td>
                                            @if($value_det2['spek_residue'] < $value_det2['residue'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['residue'] == 0  || $value_det2['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['residue'], 2, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['residue'] == 0  || $value_det2['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['residue'], 2, ',', '')) }}</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td style="vertical-align : middle; text-align: center;"></td>
                                            <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($jam_besar)) }}</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                        </tr>
                                    @endif
                                @elseif($value_det2['jam_waktu'] == $jam_besar)
                                    @if($key_det == "RB")
                                        <tr>
                                            <td style="vertical-align : middle; text-align: center;">{{ $key_det }}</td>
                                            <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($jam_besar)) }}</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align : middle; text-align: center;"></td>
                                            <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det2['jam_waktu'])) }}</td>
                                            <td style="vertical-align : middle; text-align: center;"></td>
                                            <td style="vertical-align : middle; text-align: center;">{{ $value_det2['mesh'] }}</td>
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['rpm'] == 0  || $value_det2['rpm'] == null ? '-': $value_det2['rpm']) }}</td>
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_ssa'] == 0  || $value_det2['std_ssa'] == null ? '-': $value_det2['std_ssa']) }}</td>
                                            @if($value_det2['std_ssa'] > $value_det2['ssa'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['ssa'] == 0  || $value_det2['ssa'] == null ? '-': $value_det2['ssa']) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['ssa'] == 0  || $value_det2['ssa'] == null ? '-': $value_det2['ssa']) }}</td>
                                            @endif
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_d50'] == 0  || $value_det2['std_d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['std_d50'], 2, ',', '')) }}</td>
                                            @if($value_det2['std_d50'] < $value_det2['d50'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['d50'] == 0  || $value_det2['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d50'], 2, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['d50'] == 0  || $value_det2['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d50'], 2, ',', '')) }}</td>
                                            @endif
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_d98'] == 0  || $value_det2['std_d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['std_d98'], 2, ',', '')) }}</td>
                                            @if($value_det2['std_d98'] < $value_det2['d98'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['d98'] == 0  || $value_det2['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d98'], 2, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['d98'] == 0  || $value_det2['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d98'], 2, ',', '')) }}</td>
                                            @endif
                                            @if($value_det2['spek_whiteness'] > $value_det2['cie86'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['cie86'] == 0  || $value_det2['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['cie86'], 1, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['cie86'] == 0  || $value_det2['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['cie86'], 1, ',', '')) }}</td>
                                            @endif
                                            @if($value_det2['spek_whiteness'] > $value_det2['iso2470'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['iso2470'] == 0  || $value_det2['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['iso2470'], 1, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['iso2470'] == 0  || $value_det2['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['iso2470'], 1, ',', '')) }}</td>
                                            @endif
                                            @if($value_det2['spek_moisture'] < $value_det2['moisture'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['moisture'] == 0  || $value_det2['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det2['moisture'], 3, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['moisture'] == 0  || $value_det2['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det2['moisture'], 3, ',', '')) }}</td>
                                            @endif
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['standart_residue'] == 0  || $value_det2['standart_residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['standart_residue'], 2, ',', '')) }}</td>
                                            @if($value_det2['spek_residue'] < $value_det2['residue'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['residue'] == 0  || $value_det2['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['residue'], 2, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['residue'] == 0  || $value_det2['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['residue'], 2, ',', '')) }}</td>
                                            @endif
                                        </tr>
                                        <tr colspan="16"></tr>
                                    @else
                                        <tr>
                                            <td style="vertical-align : middle; text-align: center;">{{ $key_det }}</td>
                                            <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($jam_besar)) }}</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                            <td style="vertical-align : middle; text-align: center;">-</td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align : middle; text-align: center;"></td>
                                            <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det2['jam_waktu'])) }}</td>
                                            <td style="vertical-align : middle; text-align: center;"></td>
                                            <td style="vertical-align : middle; text-align: center;">{{ $value_det2['mesh'] }}</td>
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['rpm'] == 0  || $value_det2['rpm'] == null ? '-': $value_det2['rpm']) }}</td>
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_ssa'] == 0  || $value_det2['std_ssa'] == null ? '-': $value_det2['std_ssa']) }}</td>
                                            @if($value_det2['std_ssa'] > $value_det2['ssa'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['ssa'] == 0  || $value_det2['ssa'] == null ? '-': $value_det2['ssa']) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['ssa'] == 0  || $value_det2['ssa'] == null ? '-': $value_det2['ssa']) }}</td>
                                            @endif
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_d50'] == 0  || $value_det2['std_d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['std_d50'], 2, ',', '')) }}</td>
                                            @if($value_det2['std_d50'] < $value_det2['d50'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['d50'] == 0  || $value_det2['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d50'], 2, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['d50'] == 0  || $value_det2['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d50'], 2, ',', '')) }}</td>
                                            @endif
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_d98'] == 0  || $value_det2['std_d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['std_d98'], 2, ',', '')) }}</td>
                                            @if($value_det2['std_d98'] < $value_det2['d98'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['d98'] == 0  || $value_det2['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d98'], 2, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['d98'] == 0  || $value_det2['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d98'], 2, ',', '')) }}</td>
                                            @endif
                                            @if($value_det2['spek_whiteness'] > $value_det2['cie86'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['cie86'] == 0  || $value_det2['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['cie86'], 1, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['cie86'] == 0  || $value_det2['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['cie86'], 1, ',', '')) }}</td>
                                            @endif
                                            @if($value_det2['spek_whiteness'] > $value_det2['iso2470'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['iso2470'] == 0  || $value_det2['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['iso2470'], 1, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['iso2470'] == 0  || $value_det2['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['iso2470'], 1, ',', '')) }}</td>
                                            @endif
                                            @if($value_det2['spek_moisture'] < $value_det2['moisture'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['moisture'] == 0  || $value_det2['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det2['moisture'], 3, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['moisture'] == 0  || $value_det2['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det2['moisture'], 3, ',', '')) }}</td>
                                            @endif
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['standart_residue'] == 0  || $value_det2['standart_residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['standart_residue'], 2, ',', '')) }}</td>
                                            @if($value_det2['spek_residue'] < $value_det2['residue'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['residue'] == 0  || $value_det2['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['residue'], 2, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['residue'] == 0  || $value_det2['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['residue'], 2, ',', '')) }}</td>
                                            @endif
                                        </tr>
                                    @endif
                                @endif
                            @else
                                @if($key_det == "RB")
                                    @if(count($value_det) == 1)
                                        <tr>
                                            <td style="vertical-align : middle; text-align: center;">{{ $key_det }}</td>
                                            <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det2['jam_waktu'])) }}</td>
                                            <td style="vertical-align : middle; text-align: center;"></td>
                                            <td style="vertical-align : middle; text-align: center;">{{ $value_det2['mesh'] }}</td>
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['rpm'] == 0  || $value_det2['rpm'] == null ? '-': $value_det2['rpm']) }}</td>
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_ssa'] == 0  || $value_det2['std_ssa'] == null ? '-': $value_det2['std_ssa']) }}</td>
                                            @if($value_det2['std_ssa'] > $value_det2['ssa'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['ssa'] == 0  || $value_det2['ssa'] == null ? '-': $value_det2['ssa']) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['ssa'] == 0  || $value_det2['ssa'] == null ? '-': $value_det2['ssa']) }}</td>
                                            @endif
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_d50'] == 0  || $value_det2['std_d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['std_d50'], 2, ',', '')) }}</td>
                                            @if($value_det2['std_d50'] < $value_det2['d50'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['d50'] == 0  || $value_det2['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d50'], 2, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['d50'] == 0  || $value_det2['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d50'], 2, ',', '')) }}</td>
                                            @endif
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_d98'] == 0  || $value_det2['std_d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['std_d98'], 2, ',', '')) }}</td>
                                            @if($value_det2['std_d98'] < $value_det2['d98'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['d98'] == 0  || $value_det2['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d98'], 2, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['d98'] == 0  || $value_det2['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d98'], 2, ',', '')) }}</td>
                                            @endif
                                            @if($value_det2['spek_whiteness'] > $value_det2['cie86'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['cie86'] == 0  || $value_det2['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['cie86'], 1, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['cie86'] == 0  || $value_det2['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['cie86'], 1, ',', '')) }}</td>
                                            @endif
                                            @if($value_det2['spek_whiteness'] > $value_det2['iso2470'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['iso2470'] == 0  || $value_det2['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['iso2470'], 1, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['iso2470'] == 0  || $value_det2['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['iso2470'], 1, ',', '')) }}</td>
                                            @endif
                                            @if($value_det2['spek_moisture'] < $value_det2['moisture'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['moisture'] == 0  || $value_det2['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det2['moisture'], 3, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['moisture'] == 0  || $value_det2['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det2['moisture'], 3, ',', '')) }}</td>
                                            @endif
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['standart_residue'] == 0  || $value_det2['standart_residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['standart_residue'], 2, ',', '')) }}</td>
                                            @if($value_det2['spek_residue'] < $value_det2['residue'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['residue'] == 0  || $value_det2['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['residue'], 2, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['residue'] == 0  || $value_det2['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['residue'], 2, ',', '')) }}</td>
                                            @endif
                                        </tr>
                                        <tr colspan="16"></tr>
                                    @else
                                        <tr>
                                            <td style="vertical-align : middle; text-align: center;">{{ $key_det }}</td>
                                            <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det2['jam_waktu'])) }}</td>
                                            <td style="vertical-align : middle; text-align: center;"></td>
                                            <td style="vertical-align : middle; text-align: center;">{{ $value_det2['mesh'] }}</td>
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['rpm'] == 0  || $value_det2['rpm'] == null ? '-': $value_det2['rpm']) }}</td>
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_ssa'] == 0  || $value_det2['std_ssa'] == null ? '-': $value_det2['std_ssa']) }}</td>
                                            @if($value_det2['std_ssa'] > $value_det2['ssa'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['ssa'] == 0  || $value_det2['ssa'] == null ? '-': $value_det2['ssa']) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['ssa'] == 0  || $value_det2['ssa'] == null ? '-': $value_det2['ssa']) }}</td>
                                            @endif
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_d50'] == 0  || $value_det2['std_d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['std_d50'], 2, ',', '')) }}</td>
                                            @if($value_det2['std_d50'] < $value_det2['d50'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['d50'] == 0  || $value_det2['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d50'], 2, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['d50'] == 0  || $value_det2['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d50'], 2, ',', '')) }}</td>
                                            @endif
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_d98'] == 0  || $value_det2['std_d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['std_d98'], 2, ',', '')) }}</td>
                                            @if($value_det2['std_d98'] < $value_det2['d98'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['d98'] == 0  || $value_det2['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d98'], 2, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['d98'] == 0  || $value_det2['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d98'], 2, ',', '')) }}</td>
                                            @endif
                                            @if($value_det2['spek_whiteness'] > $value_det2['cie86'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['cie86'] == 0  || $value_det2['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['cie86'], 1, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['cie86'] == 0  || $value_det2['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['cie86'], 1, ',', '')) }}</td>
                                            @endif
                                            @if($value_det2['spek_whiteness'] > $value_det2['iso2470'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['iso2470'] == 0  || $value_det2['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['iso2470'], 1, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['iso2470'] == 0  || $value_det2['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['iso2470'], 1, ',', '')) }}</td>
                                            @endif
                                            @if($value_det2['spek_moisture'] < $value_det2['moisture'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['moisture'] == 0  || $value_det2['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det2['moisture'], 3, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['moisture'] == 0  || $value_det2['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det2['moisture'], 3, ',', '')) }}</td>
                                            @endif
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['standart_residue'] == 0  || $value_det2['standart_residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['standart_residue'], 2, ',', '')) }}</td>
                                            @if($value_det2['spek_residue'] < $value_det2['residue'])
                                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['residue'] == 0  || $value_det2['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['residue'], 2, ',', '')) }}</td>
                                            @else
                                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['residue'] == 0  || $value_det2['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['residue'], 2, ',', '')) }}</td>
                                            @endif
                                        </tr>
                                    @endif
                                @else
                                    <tr>
                                        <td style="vertical-align : middle; text-align: center;">{{ $key_det }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det2['jam_waktu'])) }}</td>
                                        <td style="vertical-align : middle; text-align: center;"></td>
                                        <td style="vertical-align : middle; text-align: center;">{{ $value_det2['mesh'] }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['rpm'] == 0  || $value_det2['rpm'] == null ? '-': $value_det2['rpm']) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_ssa'] == 0  || $value_det2['std_ssa'] == null ? '-': $value_det2['std_ssa']) }}</td>
                                        @if($value_det2['std_ssa'] > $value_det2['ssa'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['ssa'] == 0  || $value_det2['ssa'] == null ? '-': $value_det2['ssa']) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['ssa'] == 0  || $value_det2['ssa'] == null ? '-': $value_det2['ssa']) }}</td>
                                        @endif
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_d50'] == 0  || $value_det2['std_d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['std_d50'], 2, ',', '')) }}</td>
                                        @if($value_det2['std_d50'] < $value_det2['d50'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['d50'] == 0  || $value_det2['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d50'], 2, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['d50'] == 0  || $value_det2['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d50'], 2, ',', '')) }}</td>
                                        @endif
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_d98'] == 0  || $value_det2['std_d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['std_d98'], 2, ',', '')) }}</td>
                                        @if($value_det2['std_d98'] < $value_det2['d98'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['d98'] == 0  || $value_det2['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d98'], 2, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['d98'] == 0  || $value_det2['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d98'], 2, ',', '')) }}</td>
                                        @endif
                                        @if($value_det2['spek_whiteness'] > $value_det2['cie86'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['cie86'] == 0  || $value_det2['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['cie86'], 1, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['cie86'] == 0  || $value_det2['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['cie86'], 1, ',', '')) }}</td>
                                        @endif
                                        @if($value_det2['spek_whiteness'] > $value_det2['iso2470'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['iso2470'] == 0  || $value_det2['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['iso2470'], 1, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['iso2470'] == 0  || $value_det2['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['iso2470'], 1, ',', '')) }}</td>
                                        @endif
                                        @if($value_det2['spek_moisture'] < $value_det2['moisture'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['moisture'] == 0  || $value_det2['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det2['moisture'], 3, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['moisture'] == 0  || $value_det2['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det2['moisture'], 3, ',', '')) }}</td>
                                        @endif
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['standart_residue'] == 0  || $value_det2['standart_residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['standart_residue'], 2, ',', '')) }}</td>
                                        @if($value_det2['spek_residue'] < $value_det2['residue'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['residue'] == 0  || $value_det2['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['residue'], 2, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['residue'] == 0  || $value_det2['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['residue'], 2, ',', '')) }}</td>
                                        @endif
                                    </tr>
                                @endif
                            @endif
                        @else
                            @if($key_det == "RB")
                                <tr>
                                    <td style="vertical-align : middle; text-align: center;"></td>
                                    <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det2['jam_waktu'])) }}</td>
                                    <td style="vertical-align : middle; text-align: center;"></td>
                                    <td style="vertical-align : middle; text-align: center;">{{ $value_det2['mesh'] }}</td>
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['rpm'] == 0  || $value_det2['rpm'] == null ? '-': $value_det2['rpm']) }}</td>
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_ssa'] == 0  || $value_det2['std_ssa'] == null ? '-': $value_det2['std_ssa']) }}</td>
                                    @if($value_det2['std_ssa'] > $value_det2['ssa'])
                                    <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['ssa'] == 0  || $value_det2['ssa'] == null ? '-': $value_det2['ssa']) }}</td>
                                    @else
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['ssa'] == 0  || $value_det2['ssa'] == null ? '-': $value_det2['ssa']) }}</td>
                                    @endif
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_d50'] == 0  || $value_det2['std_d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['std_d50'], 2, ',', '')) }}</td>
                                    @if($value_det2['std_d50'] < $value_det2['d50'])
                                    <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['d50'] == 0  || $value_det2['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d50'], 2, ',', '')) }}</td>
                                    @else
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['d50'] == 0  || $value_det2['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d50'], 2, ',', '')) }}</td>
                                    @endif
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_d98'] == 0  || $value_det2['std_d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['std_d98'], 2, ',', '')) }}</td>
                                    @if($value_det2['std_d98'] < $value_det2['d98'])
                                    <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['d98'] == 0  || $value_det2['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d98'], 2, ',', '')) }}</td>
                                    @else
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['d98'] == 0  || $value_det2['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d98'], 2, ',', '')) }}</td>
                                    @endif
                                    @if($value_det2['spek_whiteness'] > $value_det2['cie86'])
                                    <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['cie86'] == 0  || $value_det2['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['cie86'], 1, ',', '')) }}</td>
                                    @else
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['cie86'] == 0  || $value_det2['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['cie86'], 1, ',', '')) }}</td>
                                    @endif
                                    @if($value_det2['spek_whiteness'] > $value_det2['iso2470'])
                                    <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['iso2470'] == 0  || $value_det2['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['iso2470'], 1, ',', '')) }}</td>
                                    @else
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['iso2470'] == 0  || $value_det2['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['iso2470'], 1, ',', '')) }}</td>
                                    @endif
                                    @if($value_det2['spek_moisture'] < $value_det2['moisture'])
                                    <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['moisture'] == 0  || $value_det2['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det2['moisture'], 3, ',', '')) }}</td>
                                    @else
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['moisture'] == 0  || $value_det2['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det2['moisture'], 3, ',', '')) }}</td>
                                    @endif
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['standart_residue'] == 0  || $value_det2['standart_residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['standart_residue'], 2, ',', '')) }}</td>
                                    @if($value_det2['spek_residue'] < $value_det2['residue'])
                                    <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['residue'] == 0  || $value_det2['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['residue'], 2, ',', '')) }}</td>
                                    @else
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['residue'] == 0  || $value_det2['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['residue'], 2, ',', '')) }}</td>
                                    @endif
                                </tr>
                                <tr colspan="16"></tr>
                            @else
                                <tr>
                                    <td style="vertical-align : middle; text-align: center;"></td>
                                    <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det2['jam_waktu'])) }}</td>
                                    <td style="vertical-align : middle; text-align: center;"></td>
                                    <td style="vertical-align : middle; text-align: center;">{{ $value_det2['mesh'] }}</td>
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['rpm'] == 0  || $value_det2['rpm'] == null ? '-': $value_det2['rpm']) }}</td>
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_ssa'] == 0  || $value_det2['std_ssa'] == null ? '-': $value_det2['std_ssa']) }}</td>
                                    @if($value_det2['std_ssa'] > $value_det2['ssa'])
                                    <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['ssa'] == 0  || $value_det2['ssa'] == null ? '-': $value_det2['ssa']) }}</td>
                                    @else
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['ssa'] == 0  || $value_det2['ssa'] == null ? '-': $value_det2['ssa']) }}</td>
                                    @endif
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_d50'] == 0  || $value_det2['std_d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['std_d50'], 2, ',', '')) }}</td>
                                    @if($value_det2['std_d50'] < $value_det2['d50'])
                                    <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['d50'] == 0  || $value_det2['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d50'], 2, ',', '')) }}</td>
                                    @else
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['d50'] == 0  || $value_det2['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d50'], 2, ',', '')) }}</td>
                                    @endif
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['std_d98'] == 0  || $value_det2['std_d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['std_d98'], 2, ',', '')) }}</td>
                                    @if($value_det2['std_d98'] < $value_det2['d98'])
                                    <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['d98'] == 0  || $value_det2['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d98'], 2, ',', '')) }}</td>
                                    @else
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['d98'] == 0  || $value_det2['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d98'], 2, ',', '')) }}</td>
                                    @endif
                                    @if($value_det2['spek_whiteness'] > $value_det2['cie86'])
                                    <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['cie86'] == 0  || $value_det2['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['cie86'], 1, ',', '')) }}</td>
                                    @else
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['cie86'] == 0  || $value_det2['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['cie86'], 1, ',', '')) }}</td>
                                    @endif
                                    @if($value_det2['spek_whiteness'] > $value_det2['iso2470'])
                                    <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['iso2470'] == 0  || $value_det2['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['iso2470'], 1, ',', '')) }}</td>
                                    @else
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['iso2470'] == 0  || $value_det2['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['iso2470'], 1, ',', '')) }}</td>
                                    @endif
                                    @if($value_det2['spek_moisture'] < $value_det2['moisture'])
                                    <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['moisture'] == 0  || $value_det2['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det2['moisture'], 3, ',', '')) }}</td>
                                    @else
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['moisture'] == 0  || $value_det2['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det2['moisture'], 3, ',', '')) }}</td>
                                    @endif
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['standart_residue'] == 0  || $value_det2['standart_residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['standart_residue'], 2, ',', '')) }}</td>
                                    @if($value_det2['spek_residue'] < $value_det2['residue'])
                                    <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det2['residue'] == 0  || $value_det2['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['residue'], 2, ',', '')) }}</td>
                                    @else
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['residue'] == 0  || $value_det2['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['residue'], 2, ',', '')) }}</td>
                                    @endif
                                </tr>
                            @endif
                        @endif
                    @endforeach
                @endforeach
            </tbody>
        </table>
        <br><br>
    @endforeach
</body>
</html>