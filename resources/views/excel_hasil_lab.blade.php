<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Excel Hasil Lab</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        * { box-sizing: border-box; }
        body{
            margin-left: 30px;
            margin-right: 30px;
            margin-bottom: 25px;
            margin-top: 25px;
        }

        table{
            border: 1px solid #000;
        }
        
    </style>
</head>
<body>
    <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
        <tr>
            <th>Tanggal : </th>
            <th id="td_tanggal">{{ $tanggal }}</th>
        </tr>
    </table>

    <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
        <thead>
            <tr>
                <th style="vertical-align : middle; text-align: center;" rowspan="2" colspan="2">Mesin</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2"></th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Mesh</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">RPM</th>
                <th style="vertical-align : middle; text-align: center;" colspan="2">SSA</th>
                <th style="vertical-align : middle; text-align: center;" colspan="2">D-50</th>
                <th style="vertical-align : middle; text-align: center;" colspan="2">D-98</th>
                <th style="text-align: center;" colspan="2">Whiteness</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Moisture</th>
                <th style="vertical-align : middle; text-align: center;" colspan="2">Residue</th>
            </tr>
            <tr>
                <th style="vertical-align : middle; text-align: center;">Standart</th>
                <th style="vertical-align : middle; text-align: center;">Hasil</th>
                <th style="vertical-align : middle; text-align: center;">Standart</th>
                <th style="vertical-align : middle; text-align: center;">Hasil</th>
                <th style="vertical-align : middle; text-align: center;">Standart</th>
                <th style="vertical-align : middle; text-align: center;">Hasil</th>
                <th style="vertical-align : middle; text-align: center;">CIE 86</th>
                <th style="vertical-align : middle; text-align: center;">ISO 2470</th>
                <th style="vertical-align : middle; text-align: center;">Standart</th>
                <th style="vertical-align : middle; text-align: center;">Hasil</th>
            </tr>
        </thead>
        <tbody id="tbody_view">
            <?php
            $besar = 0;
            $jam_kecil = null;
            $jam_besar = null;
            foreach($data as $key => $value){
                if($besar < count($value)){
                    $besar = count($value);
                    if(count($value) == 2){
                      $jam_kecil = $value[0]['jam_waktu'];
                      $jam_besar = $value[1]['jam_waktu'];
                  }else if(count($value) == 1){
                      $jam_kecil = $value[0]['jam_waktu'];
                  }
              }
            }
            ?>
            @foreach($data as $key => $value)
                @if($besar > 1)
                    @if(count($value) == 0)
                        @if($key == "RB")
                            <tr>
                                <td style="vertical-align : middle; text-align: center;" rowspan="2">{{ $key }}</td>
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
                                <td style="vertical-align : middle; text-align: center;" rowspan="2">{{ $key }}</td>
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
                    @if(count($value) == 0)
                        @if($key == "RB")
                            <tr>
                                <td style="vertical-align : middle; text-align: center;">{{ $key }}</td>
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
                                <td style="vertical-align : middle; text-align: center;">{{ $key }}</td>
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
                @foreach($value as $key_det => $value_det)
                    @if($key_det == 0)
                        @if(count($value) < $besar)
                            @if($value_det['jam_waktu'] == $jam_kecil)
                                @if($key == "RB")
                                    <tr>
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">{{ $key }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det['jam_waktu'])) }}</td>
                                        <td style="vertical-align : middle; text-align: center;"></td>
                                        <td style="vertical-align : middle; text-align: center;">{{ $value_det['mesh'] }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['rpm'] == 0  || $value_det['rpm'] == null ? '-': $value_det['rpm']) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_ssa'] == 0  || $value_det['std_ssa'] == null ? '-': $value_det['std_ssa']) }}</td>
                                        @if($value_det['std_ssa'] > $value_det['ssa'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                        @endif
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_d50'] == 0  || $value_det['std_d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['std_d50'], 2, ',', '')) }}</td>
                                        @if($value_det['std_d50'] < $value_det['d50'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                        @endif
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_d98'] == 0  || $value_det['std_d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['std_d98'], 2, ',', '')) }}</td>
                                        @if($value_det['std_d98'] < $value_det['d98'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
                                        @endif
                                        @if($value_det['spek_whiteness'] > $value_det['cie86'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['cie86'] == 0  || $value_det['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['cie86'], 1, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['cie86'] == 0  || $value_det['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['cie86'], 1, ',', '')) }}</td>
                                        @endif
                                        @if($value_det['spek_whiteness'] > $value_det['iso2470'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['iso2470'] == 0  || $value_det['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['iso2470'], 1, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['iso2470'] == 0  || $value_det['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['iso2470'], 1, ',', '')) }}</td>
                                        @endif
                                        @if($value_det['spek_moisture'] < $value_det['moisture'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['moisture'] == 0  || $value_det['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det['moisture'], 3, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['moisture'] == 0  || $value_det['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det['moisture'], 3, ',', '')) }}</td>
                                        @endif
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['standart_residue'] == 0  || $value_det['standart_residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['standart_residue'], 2, ',', '')) }}</td>
                                        @if($value_det['spek_residue'] < $value_det['residue'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['residue'] == 0  || $value_det['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['residue'], 2, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['residue'] == 0  || $value_det['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['residue'], 2, ',', '')) }}</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($jam_besar)) }}</td>
                                        <td style="vertical-align : middle; text-align: center;"></td>
                                        <td style="vertical-align : middle; text-align: center;">{{ $value_det['mesh'] }}</td>
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
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">{{ $key }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det['jam_waktu'])) }}</td>
                                        <td style="vertical-align : middle; text-align: center;"></td>
                                        <td style="vertical-align : middle; text-align: center;">{{ $value_det['mesh'] }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['rpm'] == 0  || $value_det['rpm'] == null ? '-': $value_det['rpm']) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_ssa'] == 0  || $value_det['std_ssa'] == null ? '-': $value_det['std_ssa']) }}</td>
                                        @if($value_det['std_ssa'] > $value_det['ssa'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                        @endif
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_d50'] == 0  || $value_det['std_d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['std_d50'], 2, ',', '')) }}</td>
                                        @if($value_det['std_d50'] < $value_det['d50'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                        @endif
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_d98'] == 0  || $value_det['std_d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['std_d98'], 2, ',', '')) }}</td>
                                        @if($value_det['std_d98'] < $value_det['d98'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
                                        @endif
                                        @if($value_det['spek_whiteness'] > $value_det['cie86'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['cie86'] == 0  || $value_det['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['cie86'], 1, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['cie86'] == 0  || $value_det['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['cie86'], 1, ',', '')) }}</td>
                                        @endif
                                        @if($value_det['spek_whiteness'] > $value_det['iso2470'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['iso2470'] == 0  || $value_det['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['iso2470'], 1, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['iso2470'] == 0  || $value_det['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['iso2470'], 1, ',', '')) }}</td>
                                        @endif
                                        @if($value_det['spek_moisture'] < $value_det['moisture'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['moisture'] == 0  || $value_det['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det['moisture'], 3, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['moisture'] == 0  || $value_det['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det['moisture'], 3, ',', '')) }}</td>
                                        @endif
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['standart_residue'] == 0  || $value_det['standart_residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['standart_residue'], 2, ',', '')) }}</td>
                                        @if($value_det['spek_residue'] < $value_det['residue'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['residue'] == 0  || $value_det['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['residue'], 2, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['residue'] == 0  || $value_det['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['residue'], 2, ',', '')) }}</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($jam_besar)) }}</td>
                                        <td style="vertical-align : middle; text-align: center;"></td>
                                        <td style="vertical-align : middle; text-align: center;">{{ $value_det['mesh'] }}</td>
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
                            @elseif($value_det['jam_waktu'] == $jam_besar)
                                @if($key == "RB")
                                    <tr>
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">{{ $key }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($jam_besar)) }}</td>
                                        <td style="vertical-align : middle; text-align: center;"></td>
                                        <td style="vertical-align : middle; text-align: center;">{{ $value_det['mesh'] }}</td>
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
                                        <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det['jam_waktu'])) }}</td>
                                        <td style="vertical-align : middle; text-align: center;"></td>
                                        <td style="vertical-align : middle; text-align: center;">{{ $value_det['mesh'] }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['rpm'] == 0  || $value_det['rpm'] == null ? '-': $value_det['rpm']) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_ssa'] == 0  || $value_det['std_ssa'] == null ? '-': $value_det['std_ssa']) }}</td>
                                        @if($value_det['std_ssa'] > $value_det['ssa'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                        @endif
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_d50'] == 0  || $value_det['std_d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['std_d50'], 2, ',', '')) }}</td>
                                        @if($value_det['std_d50'] < $value_det['d50'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                        @endif
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_d98'] == 0  || $value_det['std_d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['std_d98'], 2, ',', '')) }}</td>
                                        @if($value_det['std_d98'] < $value_det['d98'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
                                        @endif
                                        @if($value_det['spek_whiteness'] > $value_det['cie86'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['cie86'] == 0  || $value_det['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['cie86'], 1, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['cie86'] == 0  || $value_det['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['cie86'], 1, ',', '')) }}</td>
                                        @endif
                                        @if($value_det['spek_whiteness'] > $value_det['iso2470'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['iso2470'] == 0  || $value_det['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['iso2470'], 1, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['iso2470'] == 0  || $value_det['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['iso2470'], 1, ',', '')) }}</td>
                                        @endif
                                        @if($value_det['spek_moisture'] < $value_det['moisture'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['moisture'] == 0  || $value_det['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det['moisture'], 3, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['moisture'] == 0  || $value_det['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det['moisture'], 3, ',', '')) }}</td>
                                        @endif
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['standart_residue'] == 0  || $value_det['standart_residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['standart_residue'], 2, ',', '')) }}</td>
                                        @if($value_det['spek_residue'] < $value_det['residue'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['residue'] == 0  || $value_det['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['residue'], 2, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['residue'] == 0  || $value_det['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['residue'], 2, ',', '')) }}</td>
                                        @endif
                                    </tr>
                                    <tr colspan="16"></tr>
                                @else
                                    <tr>
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">{{ $key }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($jam_besar)) }}</td>
                                        <td style="vertical-align : middle; text-align: center;"></td>
                                        <td style="vertical-align : middle; text-align: center;">{{ $value_det['mesh'] }}</td>
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
                                        <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det['jam_waktu'])) }}</td>
                                        <td style="vertical-align : middle; text-align: center;"></td>
                                        <td style="vertical-align : middle; text-align: center;">{{ $value_det['mesh'] }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['rpm'] == 0  || $value_det['rpm'] == null ? '-': $value_det['rpm']) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_ssa'] == 0  || $value_det['std_ssa'] == null ? '-': $value_det['std_ssa']) }}</td>
                                        @if($value_det['std_ssa'] > $value_det['ssa'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                        @endif
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_d50'] == 0  || $value_det['std_d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['std_d50'], 2, ',', '')) }}</td>
                                        @if($value_det['std_d50'] < $value_det['d50'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                        @endif
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_d98'] == 0  || $value_det['std_d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['std_d98'], 2, ',', '')) }}</td>
                                        @if($value_det['std_d98'] < $value_det['d98'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
                                        @endif
                                        @if($value_det['spek_whiteness'] > $value_det['cie86'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['cie86'] == 0  || $value_det['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['cie86'], 1, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['cie86'] == 0  || $value_det['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['cie86'], 1, ',', '')) }}</td>
                                        @endif
                                        @if($value_det['spek_whiteness'] > $value_det['iso2470'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['iso2470'] == 0  || $value_det['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['iso2470'], 1, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['iso2470'] == 0  || $value_det['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['iso2470'], 1, ',', '')) }}</td>
                                        @endif
                                        @if($value_det['spek_moisture'] < $value_det['moisture'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['moisture'] == 0  || $value_det['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det['moisture'], 3, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['moisture'] == 0  || $value_det['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det['moisture'], 3, ',', '')) }}</td>
                                        @endif
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['standart_residue'] == 0  || $value_det['standart_residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['standart_residue'], 2, ',', '')) }}</td>
                                        @if($value_det['spek_residue'] < $value_det['residue'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['residue'] == 0  || $value_det['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['residue'], 2, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['residue'] == 0  || $value_det['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['residue'], 2, ',', '')) }}</td>
                                        @endif
                                    </tr>
                                @endif
                            @endif
                        @else
                            @if($key == "RB")
                                @if(count($value) == 1)
                                    <tr>
                                        <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">{{ $key }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det['jam_waktu'])) }}</td>
                                        <td style="vertical-align : middle; text-align: center;"></td>
                                        <td style="vertical-align : middle; text-align: center;">{{ $value_det['mesh'] }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['rpm'] == 0  || $value_det['rpm'] == null ? '-': $value_det['rpm']) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_ssa'] == 0  || $value_det['std_ssa'] == null ? '-': $value_det['std_ssa']) }}</td>
                                        @if($value_det['std_ssa'] > $value_det['ssa'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                        @endif
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_d50'] == 0  || $value_det['std_d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['std_d50'], 2, ',', '')) }}</td>
                                        @if($value_det['std_d50'] < $value_det['d50'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                        @endif
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_d98'] == 0  || $value_det['std_d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['std_d98'], 2, ',', '')) }}</td>
                                        @if($value_det['std_d98'] < $value_det['d98'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
                                        @endif
                                        @if($value_det['spek_whiteness'] > $value_det['cie86'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['cie86'] == 0  || $value_det['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['cie86'], 1, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['cie86'] == 0  || $value_det['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['cie86'], 1, ',', '')) }}</td>
                                        @endif
                                        @if($value_det['spek_whiteness'] > $value_det['iso2470'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['iso2470'] == 0  || $value_det['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['iso2470'], 1, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['iso2470'] == 0  || $value_det['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['iso2470'], 1, ',', '')) }}</td>
                                        @endif
                                        @if($value_det['spek_moisture'] < $value_det['moisture'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['moisture'] == 0  || $value_det['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det['moisture'], 3, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['moisture'] == 0  || $value_det['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det['moisture'], 3, ',', '')) }}</td>
                                        @endif
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['standart_residue'] == 0  || $value_det['standart_residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['standart_residue'], 2, ',', '')) }}</td>
                                        @if($value_det['spek_residue'] < $value_det['residue'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['residue'] == 0  || $value_det['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['residue'], 2, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['residue'] == 0  || $value_det['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['residue'], 2, ',', '')) }}</td>
                                        @endif
                                    </tr>
                                    <tr colspan="16"></tr>
                                @else
                                    <tr>
                                        <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">{{ $key }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det['jam_waktu'])) }}</td>
                                        <td style="vertical-align : middle; text-align: center;"></td>
                                        <td style="vertical-align : middle; text-align: center;">{{ $value_det['mesh'] }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['rpm'] == 0  || $value_det['rpm'] == null ? '-': $value_det['rpm']) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_ssa'] == 0  || $value_det['std_ssa'] == null ? '-': $value_det['std_ssa']) }}</td>
                                        @if($value_det['std_ssa'] > $value_det['ssa'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                        @endif
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_d50'] == 0  || $value_det['std_d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['std_d50'], 2, ',', '')) }}</td>
                                        @if($value_det['std_d50'] < $value_det['d50'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                        @endif
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_d98'] == 0  || $value_det['std_d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['std_d98'], 2, ',', '')) }}</td>
                                        @if($value_det['std_d98'] < $value_det['d98'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
                                        @endif
                                        @if($value_det['spek_whiteness'] > $value_det['cie86'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['cie86'] == 0  || $value_det['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['cie86'], 1, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['cie86'] == 0  || $value_det['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['cie86'], 1, ',', '')) }}</td>
                                        @endif
                                        @if($value_det['spek_whiteness'] > $value_det['iso2470'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['iso2470'] == 0  || $value_det['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['iso2470'], 1, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['iso2470'] == 0  || $value_det['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['iso2470'], 1, ',', '')) }}</td>
                                        @endif
                                        @if($value_det['spek_moisture'] < $value_det['moisture'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['moisture'] == 0  || $value_det['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det['moisture'], 3, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['moisture'] == 0  || $value_det['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det['moisture'], 3, ',', '')) }}</td>
                                        @endif
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['standart_residue'] == 0  || $value_det['standart_residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['standart_residue'], 2, ',', '')) }}</td>
                                        @if($value_det['spek_residue'] < $value_det['residue'])
                                        <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['residue'] == 0  || $value_det['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['residue'], 2, ',', '')) }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['residue'] == 0  || $value_det['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['residue'], 2, ',', '')) }}</td>
                                        @endif
                                    </tr>
                                @endif
                            @else
                                <tr>
                                    <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">{{ $key }}</td>
                                    <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det['jam_waktu'])) }}</td>
                                    <td style="vertical-align : middle; text-align: center;"></td>
                                    <td style="vertical-align : middle; text-align: center;">{{ $value_det['mesh'] }}</td>
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det['rpm'] == 0  || $value_det['rpm'] == null ? '-': $value_det['rpm']) }}</td>
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_ssa'] == 0  || $value_det['std_ssa'] == null ? '-': $value_det['std_ssa']) }}</td>
                                    @if($value_det['std_ssa'] > $value_det['ssa'])
                                    <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                    @else
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                    @endif
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_d50'] == 0  || $value_det['std_d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['std_d50'], 2, ',', '')) }}</td>
                                    @if($value_det['std_d50'] < $value_det['d50'])
                                    <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                    @else
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                    @endif
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_d98'] == 0  || $value_det['std_d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['std_d98'], 2, ',', '')) }}</td>
                                    @if($value_det['std_d98'] < $value_det['d98'])
                                    <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
                                    @else
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
                                    @endif
                                    @if($value_det['spek_whiteness'] > $value_det['cie86'])
                                    <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['cie86'] == 0  || $value_det['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['cie86'], 1, ',', '')) }}</td>
                                    @else
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det['cie86'] == 0  || $value_det['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['cie86'], 1, ',', '')) }}</td>
                                    @endif
                                    @if($value_det['spek_whiteness'] > $value_det['iso2470'])
                                    <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['iso2470'] == 0  || $value_det['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['iso2470'], 1, ',', '')) }}</td>
                                    @else
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det['iso2470'] == 0  || $value_det['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['iso2470'], 1, ',', '')) }}</td>
                                    @endif
                                    @if($value_det['spek_moisture'] < $value_det['moisture'])
                                    <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['moisture'] == 0  || $value_det['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det['moisture'], 3, ',', '')) }}</td>
                                    @else
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det['moisture'] == 0  || $value_det['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det['moisture'], 3, ',', '')) }}</td>
                                    @endif
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det['standart_residue'] == 0  || $value_det['standart_residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['standart_residue'], 2, ',', '')) }}</td>
                                    @if($value_det['spek_residue'] < $value_det['residue'])
                                    <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['residue'] == 0  || $value_det['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['residue'], 2, ',', '')) }}</td>
                                    @else
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det['residue'] == 0  || $value_det['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['residue'], 2, ',', '')) }}</td>
                                    @endif
                                </tr>
                            @endif
                        @endif
                    @else
                        @if($key == "RB")
                            <tr>
                                <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det['jam_waktu'])) }}</td>
                                <td style="vertical-align : middle; text-align: center;"></td>
                                <td style="vertical-align : middle; text-align: center;">{{ $value_det['mesh'] }}</td>
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['rpm'] == 0  || $value_det['rpm'] == null ? '-': $value_det['rpm']) }}</td>
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_ssa'] == 0  || $value_det['std_ssa'] == null ? '-': $value_det['std_ssa']) }}</td>
                                @if($value_det['std_ssa'] > $value_det['ssa'])
                                <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                @else
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                @endif
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_d50'] == 0  || $value_det['std_d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['std_d50'], 2, ',', '')) }}</td>
                                @if($value_det['std_d50'] < $value_det['d50'])
                                <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                @else
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                @endif
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_d98'] == 0  || $value_det['std_d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['std_d98'], 2, ',', '')) }}</td>
                                @if($value_det['std_d98'] < $value_det['d98'])
                                <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
                                @else
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
                                @endif
                                @if($value_det['spek_whiteness'] > $value_det['cie86'])
                                <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['cie86'] == 0  || $value_det['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['cie86'], 1, ',', '')) }}</td>
                                @else
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['cie86'] == 0  || $value_det['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['cie86'], 1, ',', '')) }}</td>
                                @endif
                                @if($value_det['spek_whiteness'] > $value_det['iso2470'])
                                <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['iso2470'] == 0  || $value_det['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['iso2470'], 1, ',', '')) }}</td>
                                @else
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['iso2470'] == 0  || $value_det['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['iso2470'], 1, ',', '')) }}</td>
                                @endif
                                @if($value_det['spek_moisture'] < $value_det['moisture'])
                                <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['moisture'] == 0  || $value_det['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det['moisture'], 3, ',', '')) }}</td>
                                @else
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['moisture'] == 0  || $value_det['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det['moisture'], 3, ',', '')) }}</td>
                                @endif
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['standart_residue'] == 0  || $value_det['standart_residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['standart_residue'], 2, ',', '')) }}</td>
                                @if($value_det['spek_residue'] < $value_det['residue'])
                                <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['residue'] == 0  || $value_det['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['residue'], 2, ',', '')) }}</td>
                                @else
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['residue'] == 0  || $value_det['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['residue'], 2, ',', '')) }}</td>
                                @endif
                            </tr>
                            <tr colspan="16"></tr>
                        @else
                            <tr>
                                <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det['jam_waktu'])) }}</td>
                                <td style="vertical-align : middle; text-align: center;"></td>
                                <td style="vertical-align : middle; text-align: center;">{{ $value_det['mesh'] }}</td>
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['rpm'] == 0  || $value_det['rpm'] == null ? '-': $value_det['rpm']) }}</td>
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_ssa'] == 0  || $value_det['std_ssa'] == null ? '-': $value_det['std_ssa']) }}</td>
                                @if($value_det['std_ssa'] > $value_det['ssa'])
                                <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                @else
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                @endif
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_d50'] == 0  || $value_det['std_d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['std_d50'], 2, ',', '')) }}</td>
                                @if($value_det['std_d50'] < $value_det['d50'])
                                <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                @else
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                @endif
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['std_d98'] == 0  || $value_det['std_d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['std_d98'], 2, ',', '')) }}</td>
                                @if($value_det['std_d98'] < $value_det['d98'])
                                <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
                                @else
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
                                @endif
                                @if($value_det['spek_whiteness'] > $value_det['cie86'])
                                <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['cie86'] == 0  || $value_det['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['cie86'], 1, ',', '')) }}</td>
                                @else
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['cie86'] == 0  || $value_det['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['cie86'], 1, ',', '')) }}</td>
                                @endif
                                @if($value_det['spek_whiteness'] > $value_det['iso2470'])
                                <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['iso2470'] == 0  || $value_det['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['iso2470'], 1, ',', '')) }}</td>
                                @else
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['iso2470'] == 0  || $value_det['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value_det['iso2470'], 1, ',', '')) }}</td>
                                @endif
                                @if($value_det['spek_moisture'] < $value_det['moisture'])
                                <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['moisture'] == 0  || $value_det['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det['moisture'], 3, ',', '')) }}</td>
                                @else
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['moisture'] == 0  || $value_det['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value_det['moisture'], 3, ',', '')) }}</td>
                                @endif
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['standart_residue'] == 0  || $value_det['standart_residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['standart_residue'], 2, ',', '')) }}</td>
                                @if($value_det['spek_residue'] < $value_det['residue'])
                                <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['residue'] == 0  || $value_det['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['residue'], 2, ',', '')) }}</td>
                                @else
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['residue'] == 0  || $value_det['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['residue'], 2, ',', '')) }}</td>
                                @endif
                            </tr>
                        @endif
                    @endif
                @endforeach
            @endforeach
        </tbody>
    </table>
    <br><br><br>
    <table style="width: 100%; font-size: 20px;" class="table table-bordered table-hover responsive">
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <th style="vertical-align : middle; text-align: center;" colspan="9">HASIL PENGUJIAN SAMPEL</th>
            <th style="vertical-align : middle; text-align: center;" colspan="9">HASIL PENGUJIAN SAMPEL</th>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>

            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <th colspan="6">PRODUKSI PT DSGM</th>
            <td></td>
            <td></td>

            <td></td>
            <th colspan="6">PRODUKSI PT DSGM</th>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>

            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2">Hari</td>
            <td>:</td>
            <?php setlocale(LC_TIME, 'INDONESIA'); ?>
            <td colspan="2">{{ date('l', strtotime($tanggal)) }}</td>
            <td></td>
            <td></td>
            <td></td>

            <td></td>
            <td colspan="2">Hari</td>
            <td>:</td>
            <?php setlocale(LC_TIME, 'INDONESIA'); ?>
            <td colspan="2">{{ date('l', strtotime($tanggal)) }}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2">Tanggal</td>
            <td>:</td>
            <td colspan="3">{{ date('j F Y', strtotime($tanggal)) }}</td>
            <td></td>
            <td></td>

            <td></td>
            <td colspan="2">Tanggal</td>
            <td>:</td>
            <td colspan="3">{{ date('j F Y', strtotime($tanggal)) }}</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2">Jam</td>
            <td>:</td>
            <td colspan="2">{{ date("H:i", strtotime($jam_kecil)) }}</td>
            <td></td>
            <td></td>
            <td></td>

            <td></td>
            <td colspan="2">Jam</td>
            <td>:</td>
            @if($jam_besar == null)
            <td colspan="2"></td>
            @else
            <td colspan="2">{{ date("H:i", strtotime($jam_besar)) }}</td>
            @endif
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
    <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
        <thead>
            <tr>
                <th rowspan="3"></th>
                <th rowspan="3"></th>
                <th rowspan="3"></th>
                <th rowspan="3"></th>
                <th rowspan="3"></th>
                <th rowspan="3"></th>
                <th rowspan="3"></th>
                <th rowspan="3"></th>
                <th rowspan="3"></th>
                <th rowspan="3"></th>
                <th rowspan="3"></th>
                <th rowspan="3"></th>
                <th rowspan="3"></th>
                <th rowspan="3"></th>
                <th rowspan="3"></th>
                <th rowspan="3"></th>
                <th rowspan="3"></th>
                <th rowspan="3"></th>
                <th rowspan="3"></th>
                <th style="vertical-align : middle; text-align: center; writing-mode: sideways-lr;" rowspan="3">MESIN</th>
                <th style="vertical-align : middle; text-align: center;" colspan="3">WHITENESS</th>
                <th style="vertical-align : middle; text-align: center;">MOISTURE</th>
                <th style="vertical-align : middle; text-align: center;" colspan="2">RESIDU (%)</th>
                <th rowspan="3"></th>
                <th rowspan="3"></th>
                <th style="vertical-align : middle; text-align: center; writing-mode: sideways-lr;" rowspan="3">MESIN</th>
                <th style="vertical-align : middle; text-align: center;" colspan="3">WHITENESS</th>
                <th style="vertical-align : middle; text-align: center;">MOISTURE</th>
                <th style="vertical-align : middle; text-align: center;" colspan="2">RESIDU (%)</th>
            </tr>
            <tr>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Std</th>
                <th style="vertical-align : middle; text-align: center;" colspan="2">Hasil</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">0,3 +- 0,1</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Std</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Hasil</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Std</th>
                <th style="vertical-align : middle; text-align: center;" colspan="2">Hasil</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">0,3 +- 0,1</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Std</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Hasil</th>
            </tr>
            <tr>
                <th style="vertical-align : middle; text-align: center;">CIE 86</th>
                <th style="vertical-align : middle; text-align: center;">ISO 2470</th>
                <th style="vertical-align : middle; text-align: center;">CIE 86</th>
                <th style="vertical-align : middle; text-align: center;">ISO 2470</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $value)
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="vertical-align : middle; text-align: center;">{{ $key }}</td>
                @if(count($value) >= 1)
                    <td style="vertical-align : middle; text-align: center;">{{ ($value[0]['spek_whiteness'] == 0  || $value[0]['spek_whiteness'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value[0]['spek_whiteness'], 1, ',', '')) }}</td>
                    @if($value[0]['spek_whiteness'] > $value[0]['cie86'])
                    <td style="vertical-align : middle; text-align: center; color: #399cbd;">{{ ($value[0]['cie86'] == 0  || $value[0]['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value[0]['cie86'], 1, ',', '')) }}</td>
                    @else
                    <td style="vertical-align : middle; text-align: center;">{{ ($value[0]['cie86'] == 0  || $value[0]['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value[0]['cie86'], 1, ',', '')) }}</td>
                    @endif
                    @if($value[0]['spek_whiteness'] > $value[0]['iso2470'])
                    <td style="vertical-align : middle; text-align: center; color: #399cbd;">{{ ($value[0]['iso2470'] == 0  || $value[0]['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value[0]['iso2470'], 1, ',', '')) }}</td>
                    @else
                    <td style="vertical-align : middle; text-align: center;">{{ ($value[0]['iso2470'] == 0  || $value[0]['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value[0]['iso2470'], 1, ',', '')) }}</td>
                    @endif
                    @if($value[0]['spek_moisture'] < $value[0]['moisture'])
                    <td style="vertical-align : middle; text-align: center; color: #399cbd;">{{ ($value[0]['moisture'] == 0  || $value[0]['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value[0]['moisture'], 3, ',', '')) }}</td>
                    @else
                    <td style="vertical-align : middle; text-align: center;">{{ ($value[0]['moisture'] == 0  || $value[0]['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value[0]['moisture'], 3, ',', '')) }}</td>
                    @endif
                    <td style="vertical-align : middle; text-align: center;">{{ ($value[0]['standart_residue'] == 0  || $value[0]['standart_residue'] == null ? "" . number_format(0, 2, ',', '') : "" .  number_format($value[0]['standart_residue'], 2, ',', '')) }}</td>
                    @if($value[0]['spek_residue'] < $value[0]['residue'])
                    <td style="vertical-align : middle; text-align: center; color: #399cbd;">{{ ($value[0]['residue'] == 0  || $value[0]['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value[0]['residue'], 2, ',', '')) }}</td>
                    @else
                    <td style="vertical-align : middle; text-align: center;">{{ ($value[0]['residue'] == 0  || $value[0]['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value[0]['residue'], 2, ',', '')) }}</td>
                    @endif
                @else
                    <td style="vertical-align : middle; text-align: center;">-</td>
                    <td style="vertical-align : middle; text-align: center;">-</td>
                    <td style="vertical-align : middle; text-align: center;">-</td>
                    <td style="vertical-align : middle; text-align: center;">-</td>
                    <td style="vertical-align : middle; text-align: center;">-</td>
                    <td style="vertical-align : middle; text-align: center;">-</td>
                @endif

                <td></td>
                <td></td>
                <td style="vertical-align : middle; text-align: center;">{{ $key }}</td>
                @if(count($value) > 1)
                    <td style="vertical-align : middle; text-align: center;">{{ ($value[0]['spek_whiteness'] == 0  || $value[0]['spek_whiteness'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value[0]['spek_whiteness'], 1, ',', '')) }}</td>
                    @if($value[0]['spek_whiteness'] > $value[0]['cie86'])
                    <td style="vertical-align : middle; text-align: center; color: #399cbd;">{{ ($value[0]['cie86'] == 0  || $value[0]['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value[0]['cie86'], 1, ',', '')) }}</td>
                    @else
                    <td style="vertical-align : middle; text-align: center;">{{ ($value[0]['cie86'] == 0  || $value[0]['cie86'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value[0]['cie86'], 1, ',', '')) }}</td>
                    @endif
                    @if($value[0]['spek_whiteness'] > $value[0]['iso2470'])
                    <td style="vertical-align : middle; text-align: center; color: #399cbd;">{{ ($value[0]['iso2470'] == 0  || $value[0]['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value[0]['iso2470'], 1, ',', '')) }}</td>
                    @else
                    <td style="vertical-align : middle; text-align: center;">{{ ($value[0]['iso2470'] == 0  || $value[0]['iso2470'] == null ? "" . number_format(0, 1, ',', '') : "" . number_format($value[0]['iso2470'], 1, ',', '')) }}</td>
                    @endif
                    @if($value[0]['spek_moisture'] < $value[0]['moisture'])
                    <td style="vertical-align : middle; text-align: center; color: #399cbd;">{{ ($value[0]['moisture'] == 0  || $value[0]['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value[0]['moisture'], 3, ',', '')) }}</td>
                    @else
                    <td style="vertical-align : middle; text-align: center;">{{ ($value[0]['moisture'] == 0  || $value[0]['moisture'] == null ? "" . number_format(0, 3, ',', '') : "" . number_format($value[0]['moisture'], 3, ',', '')) }}</td>
                    @endif
                    <td style="vertical-align : middle; text-align: center;">{{ ($value[0]['standart_residue'] == 0  || $value[0]['standart_residue'] == null ? "" . number_format(0, 2, ',', '') : "" .  number_format($value[0]['standart_residue'], 2, ',', '')) }}</td>
                    @if($value[0]['spek_residue'] < $value[0]['residue'])
                    <td style="vertical-align : middle; text-align: center; color: #399cbd;">{{ ($value[0]['residue'] == 0  || $value[0]['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value[0]['residue'], 2, ',', '')) }}</td>
                    @else
                    <td style="vertical-align : middle; text-align: center;">{{ ($value[0]['residue'] == 0  || $value[0]['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value[0]['residue'], 2, ',', '')) }}</td>
                    @endif
                @else
                    <td style="vertical-align : middle; text-align: center;">-</td>
                    <td style="vertical-align : middle; text-align: center;">-</td>
                    <td style="vertical-align : middle; text-align: center;">-</td>
                    <td style="vertical-align : middle; text-align: center;">-</td>
                    <td style="vertical-align : middle; text-align: center;">-</td>
                    <td style="vertical-align : middle; text-align: center;">-</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>