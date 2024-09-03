<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Lab Per Mesin</title>
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
                <th>Mesin : </th>
                <th>{{ $key }}</th>
            </tr>
        </table>

        <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
            <thead>
                <tr>
                    <th style="vertical-align : middle; text-align: center;">Tanggal</th>
                    <th style="vertical-align : middle; text-align: center;">SSA</th>
                    <th style="vertical-align : middle; text-align: center;"></th>
                    <th style="vertical-align : middle; text-align: center;">d-50%</th>
                    <th style="vertical-align : middle; text-align: center;"></th>
                    <th style="vertical-align : middle; text-align: center;">Whiteness</th>
                    <th style="vertical-align : middle; text-align: center;"></th>
                    <th style="vertical-align : middle; text-align: center;">Moisture</th>
                    <th style="vertical-align : middle; text-align: center;"></th>
                    <th style="vertical-align : middle; text-align: center;">Residue</th>
                    <th style="vertical-align : middle; text-align: center;"></th>
                    <th style="vertical-align : middle; text-align: center;">Type</th>
                    <th style="vertical-align : middle; text-align: center;">Time</th>
                    <th style="vertical-align : middle; text-align: center;">Rate</th>
                </tr>
                <tr>
                    <th style="vertical-align : middle; text-align: center;"></th>
                    <th style="vertical-align : middle; text-align: center;" colspan="2">14,000 cm2/gr</th>
                    <th style="vertical-align : middle; text-align: center;" colspan="2">1.80 um</th>
                    <th style="vertical-align : middle; text-align: center;" colspan="2">85.0 %</th>
                    <th style="vertical-align : middle; text-align: center;" colspan="2">0.40 %</th>
                    <th style="vertical-align : middle; text-align: center;" colspan="2">0.10 %</th>
                    <th style="vertical-align : middle; text-align: center;"></th>
                    <th style="vertical-align : middle; text-align: center;"></th>
                    <th style="vertical-align : middle; text-align: center;"></th>
                </tr>
                <tr>
                    <th style="vertical-align : middle; text-align: center;"></th>
                    <th style="vertical-align : middle; text-align: center;">Hasil</th>
                    <th style="vertical-align : middle; text-align: center;">Comment</th>
                    <th style="vertical-align : middle; text-align: center;">Hasil</th>
                    <th style="vertical-align : middle; text-align: center;">Comment</th>
                    <th style="vertical-align : middle; text-align: center;">Hasil</th>
                    <th style="vertical-align : middle; text-align: center;">Comment</th>
                    <th style="vertical-align : middle; text-align: center;">Hasil</th>
                    <th style="vertical-align : middle; text-align: center;">Comment</th>
                    <th style="vertical-align : middle; text-align: center;">Hasil</th>
                    <th style="vertical-align : middle; text-align: center;">Comment</th>
                    <th style="vertical-align : middle; text-align: center;"></th>
                    <th style="vertical-align : middle; text-align: center;">WIB</th>
                    <th style="vertical-align : middle; text-align: center;">( rpm )</th>
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
                            <tr>
                                <td style="vertical-align : middle; text-align: center;">{{ $key_det }}</td>
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
                                <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($jam_kecil)) }}</td>
                                <td style="vertical-align : middle; text-align: center;">-</td>
                            </tr>
                            <tr>
                                <td style="vertical-align : middle; text-align: center;">{{ $key_det }}</td>
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
                                <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($jam_besar)) }}</td>
                                <td style="vertical-align : middle; text-align: center;">-</td>
                            </tr>
                        @endif
                    @else
                        @if(count($value_det) == 0)
                            <tr>
                                <td style="vertical-align : middle; text-align: center;">{{ $key_det }}</td>
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
                                <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($jam_kecil)) }}</td>
                                <td style="vertical-align : middle; text-align: center;">-</td>
                            </tr>
                        @endif
                    @endif
                    @foreach($value_det as $key_det2 => $value_det2)
                        @if($key_det2 == 0)
                            @if(count($value_det) < $besar)
                                @if($value_det2['jam_waktu'] == $jam_kecil)
                                    <tr>
                                        <td style="vertical-align : middle; text-align: center;">{{ $key_det }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['ssa'] == 0  || $value_det2['ssa'] == null ? '-': $value_det2['ssa']) }}</td>
                                        <td style="vertical-align : middle; text-align: center;"></td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['d50'] == 0  || $value_det2['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d50'], 2, ',', '')) }}</td>
                                        <td style="vertical-align : middle; text-align: center;"></td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['cie86'] == 0  || $value_det2['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['cie86'], 1, ',', '')) }}</td>
                                        <td style="vertical-align : middle; text-align: center;"></td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['moisture'] == 0  || $value_det2['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det2['moisture'], 3, ',', '')) }}</td>
                                        <td style="vertical-align : middle; text-align: center;"></td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['residue'] == 0  || $value_det2['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['residue'], 2, ',', '')) }}</td>
                                        <td style="vertical-align : middle; text-align: center;"></td>
                                        <td style="vertical-align : middle; text-align: center;">{{ $value_det2['mesh'] }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det2['jam_waktu'])) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['rpm'] == 0  || $value_det2['rpm'] == null ? '-': $value_det2['rpm']) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align : middle; text-align: center;">{{ $key_det }}</td>
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
                                        <td style="vertical-align : middle; text-align: center;">{{ $value_det2['mesh'] }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($jam_besar)) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">-</td>
                                    </tr>
                                @elseif($value_det2['jam_waktu'] == $jam_besar)
                                    <tr>
                                        <td style="vertical-align : middle; text-align: center;">{{ $key_det }}</td>
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
                                        <td style="vertical-align : middle; text-align: center;">{{ $value_det2['mesh'] }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($jam_besar)) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">-</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align : middle; text-align: center;">{{ $key_det }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['ssa'] == 0  || $value_det2['ssa'] == null ? '-': $value_det2['ssa']) }}</td>
                                        <td style="vertical-align : middle; text-align: center;"></td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['d50'] == 0  || $value_det2['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d50'], 2, ',', '')) }}</td>
                                        <td style="vertical-align : middle; text-align: center;"></td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['cie86'] == 0  || $value_det2['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['cie86'], 1, ',', '')) }}</td>
                                        <td style="vertical-align : middle; text-align: center;"></td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['moisture'] == 0  || $value_det2['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det2['moisture'], 3, ',', '')) }}</td>
                                        <td style="vertical-align : middle; text-align: center;"></td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['residue'] == 0  || $value_det2['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['residue'], 2, ',', '')) }}</td>
                                        <td style="vertical-align : middle; text-align: center;"></td>
                                        <td style="vertical-align : middle; text-align: center;">{{ $value_det2['mesh'] }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det2['jam_waktu'])) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['rpm'] == 0  || $value_det2['rpm'] == null ? '-': $value_det2['rpm']) }}</td>
                                    </tr>
                                @endif
                            @else
                                <tr>
                                    <td style="vertical-align : middle; text-align: center;">{{ $key_det }}</td>
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['ssa'] == 0  || $value_det2['ssa'] == null ? '-': $value_det2['ssa']) }}</td>
                                    <td style="vertical-align : middle; text-align: center;"></td>
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['d50'] == 0  || $value_det2['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d50'], 2, ',', '')) }}</td>
                                    <td style="vertical-align : middle; text-align: center;"></td>
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['cie86'] == 0  || $value_det2['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['cie86'], 1, ',', '')) }}</td>
                                    <td style="vertical-align : middle; text-align: center;"></td>
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['moisture'] == 0  || $value_det2['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det2['moisture'], 3, ',', '')) }}</td>
                                    <td style="vertical-align : middle; text-align: center;"></td>
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['residue'] == 0  || $value_det2['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['residue'], 2, ',', '')) }}</td>
                                    <td style="vertical-align : middle; text-align: center;"></td>
                                    <td style="vertical-align : middle; text-align: center;">{{ $value_det2['mesh'] }}</td>
                                    <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det2['jam_waktu'])) }}</td>
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['rpm'] == 0  || $value_det2['rpm'] == null ? '-': $value_det2['rpm']) }}</td>
                                </tr>
                            @endif
                        @else
                            <tr>
                                <td style="vertical-align : middle; text-align: center;">{{ $key_det }}</td>
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['ssa'] == 0  || $value_det2['ssa'] == null ? '-': $value_det2['ssa']) }}</td>
                                <td style="vertical-align : middle; text-align: center;"></td>
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['d50'] == 0  || $value_det2['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['d50'], 2, ',', '')) }}</td>
                                <td style="vertical-align : middle; text-align: center;"></td>
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['cie86'] == 0  || $value_det2['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det2['cie86'], 1, ',', '')) }}</td>
                                <td style="vertical-align : middle; text-align: center;"></td>
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['moisture'] == 0  || $value_det2['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det2['moisture'], 3, ',', '')) }}</td>
                                <td style="vertical-align : middle; text-align: center;"></td>
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['residue'] == 0  || $value_det2['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det2['residue'], 2, ',', '')) }}</td>
                                <td style="vertical-align : middle; text-align: center;"></td>
                                <td style="vertical-align : middle; text-align: center;">{{ $value_det2['mesh'] }}</td>
                                <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det2['jam_waktu'])) }}</td>
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det2['rpm'] == 0  || $value_det2['rpm'] == null ? '-': $value_det2['rpm']) }}</td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach
            </tbody>
        </table>
        @if(count($value) > 27)
            @if(count($value) > 30)
            <br><br>
            @else
            <br><br><br>
            @endif
        @else
        <br><br><br><br><br>
        @endif
    @endforeach
</body>
</html>