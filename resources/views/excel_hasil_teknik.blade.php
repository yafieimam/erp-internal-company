<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Excel Hasil Lab dan Teknik</title>
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
            <th>Tanggal : </th>
            <th id="td_tanggal">{{ $tanggal }}</th>
        </tr>
    </table>
    <br>
    <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
        <thead>
            <tr>
                <th style="vertical-align : middle; text-align: center;" rowspan="2" colspan="2">Mesin</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Mesh</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">RPM</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">SSA</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">D-50</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">D-98</th>
                <th style="text-align: center;" colspan="2">Whiteness</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Moisture</th>
                <th style="vertical-align : middle; text-align: center;" colspan="2">Residue</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Tonase (KG)</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Masalah Major</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Masalah Minor</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Masalah Lain</th>
            </tr>
            <tr>
                <th style="vertical-align : middle; text-align: center;">CIE 86</th>
                <th style="vertical-align : middle; text-align: center;">ISO 2470</th>
                <th style="vertical-align : middle; text-align: center;">Standart</th>
                <th style="vertical-align : middle; text-align: center;">Hasil</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $besar = 0;
            $jam_kecil = null;
            $jam_besar = null;
            foreach($data_lab as $key => $value){
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
            @foreach($data_lab as $key => $value)
                @if($besar > 1)
                    @if(count($value) == 0)
                        @if($key == "RB")
                            <tr>
                                <td style="vertical-align : middle; text-align: center;" rowspan="2">{{ $key }}</td>
                                <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($jam_kecil)) }}</td>
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
                                @if(array_key_exists("tonase", $data_lain_a[$key]))
                                <td style="vertical-align : middle; text-align: center;" rowspan="2">{{ $data_lain_a[$key]['tonase'] }}</td>
                                @else
                                <td style="vertical-align : middle; text-align: center;" rowspan="2">-</td>
                                @endif
                                @if(array_key_exists("masalah", $data_lain_a[$key]))
                                <td style="vertical-align : middle; text-align: center;" rowspan="2">
                                    @foreach($data_lain_a[$key]['masalah'] as $key_masalah => $value_masalah)
                                    @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                    {{ ($key_masalah + 1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                        <br>
                                        @endif
                                    @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                        <br>
                                        @endif
                                    @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                        <br>
                                        @endif
                                    @else
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                        @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                        <br>
                                        @endif
                                    @endif
                                    @endforeach
                                </td>
                                @else
                                <td style="vertical-align : middle; text-align: center;" rowspan="2">-</td>
                                @endif
                                @if(array_key_exists("masalah", $data_lain_b[$key]))
                                <td style="vertical-align : middle; text-align: center;" rowspan="2">
                                    @foreach($data_lain_b[$key]['masalah'] as $key_masalah => $value_masalah)
                                    @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                        <br>
                                        @endif
                                    @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                        <br>
                                        @endif
                                    @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} ) 
                                        @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                        <br>
                                        @endif
                                    @else
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                        @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                        <br>
                                        @endif
                                    @endif
                                    @endforeach
                                </td>
                                @else
                                <td style="vertical-align : middle; text-align: center;" rowspan="2">-</td>
                                @endif
                                @if(array_key_exists("masalah", $data_lain_c[$key]))
                                <td style="vertical-align : middle; text-align: center;" rowspan="2">
                                    @foreach($data_lain_c[$key]['masalah'] as $key_masalah => $value_masalah)
                                    @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                        <br>
                                        @endif
                                    @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                        <br>
                                        @endif
                                    @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                        <br>
                                        @endif
                                    @else
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                        @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                        <br>
                                        @endif
                                    @endif
                                    @endforeach
                                </td>
                                @else
                                <td style="vertical-align : middle; text-align: center;" rowspan="2">-</td>
                                @endif
                            </tr>
                            <tr>
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
                            </tr>
                            <tr colspan="16"></tr>
                        @else
                            <tr>
                                <td style="vertical-align : middle; text-align: center;" rowspan="2">{{ $key }}</td>
                                <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($jam_kecil)) }}</td>
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
                                @if(array_key_exists("tonase", $data_lain_a[$key]))
                                <td style="vertical-align : middle; text-align: center;" rowspan="2">{{ $data_lain_a[$key]['tonase'] }}</td>
                                @else
                                <td style="vertical-align : middle; text-align: center;" rowspan="2">-</td>
                                @endif
                                @if(array_key_exists("masalah", $data_lain_a[$key]))
                                <td style="vertical-align : middle; text-align: center;" rowspan="2">
                                    @foreach($data_lain_a[$key]['masalah'] as $key_masalah => $value_masalah)
                                    @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                        <br>
                                        @endif
                                    @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                        <br>
                                        @endif
                                    @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                        <br>
                                        @endif
                                    @else
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                        @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                        <br>
                                        @endif
                                    @endif
                                    @endforeach
                                </td>
                                @else
                                <td style="vertical-align : middle; text-align: center;" rowspan="2">-</td>
                                @endif
                                @if(array_key_exists("masalah", $data_lain_b[$key]))
                                <td style="vertical-align : middle; text-align: center;" rowspan="2">
                                    @foreach($data_lain_b[$key]['masalah'] as $key_masalah => $value_masalah)
                                    @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                        <br>
                                        @endif
                                    @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                        <br>
                                        @endif
                                    @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} ) 
                                        @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                        <br>
                                        @endif
                                    @else
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                        @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                        <br>
                                        @endif
                                    @endif
                                    @endforeach
                                </td>
                                @else
                                <td style="vertical-align : middle; text-align: center;" rowspan="2">-</td>
                                @endif
                                @if(array_key_exists("masalah", $data_lain_c[$key]))
                                <td style="vertical-align : middle; text-align: center;" rowspan="2">
                                    @foreach($data_lain_c[$key]['masalah'] as $key_masalah => $value_masalah)
                                    @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                        <br>
                                        @endif
                                    @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                        <br>
                                        @endif
                                    @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                        <br>
                                        @endif
                                    @else
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                        @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                        <br>
                                        @endif
                                    @endif
                                    @endforeach
                                </td>
                                @else
                                <td style="vertical-align : middle; text-align: center;" rowspan="2">-</td>
                                @endif
                            </tr>
                            <tr>
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
                            </tr>
                        @endif
                    @endif
                @else
                    @if(count($value) == 0)
                        @if($key == "RB")
                            <tr>
                                <td style="vertical-align : middle; text-align: center;">{{ $key }}</td>
                                <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($jam_kecil)) }}</td>
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
                                @if(array_key_exists("tonase", $data_lain_a[$key]))
                                <td style="vertical-align : middle; text-align: center;">{{ $data_lain_a[$key]['tonase'] }}</td>
                                @else
                                <td style="vertical-align : middle; text-align: center;">-</td>
                                @endif
                                @if(array_key_exists("masalah", $data_lain_a[$key]))
                                <td style="vertical-align : middle; text-align: center;">
                                    @foreach($data_lain_a[$key]['masalah'] as $key_masalah => $value_masalah)
                                    @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                        <br>
                                        @endif
                                    @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                        <br>
                                        @endif
                                    @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                        <br>
                                        @endif
                                    @else
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                        @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                        <br>
                                        @endif
                                    @endif
                                    @endforeach
                                </td>
                                @else
                                <td style="vertical-align : middle; text-align: center;">-</td>
                                @endif
                                @if(array_key_exists("masalah", $data_lain_b[$key]))
                                <td style="vertical-align : middle; text-align: center;">
                                    @foreach($data_lain_b[$key]['masalah'] as $key_masalah => $value_masalah)
                                    @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                        <br>
                                        @endif
                                    @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                        <br>
                                        @endif
                                    @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} ) 
                                        @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                        <br>
                                        @endif
                                    @else
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                        @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                        <br>
                                        @endif
                                    @endif
                                    @endforeach
                                </td>
                                @else
                                <td style="vertical-align : middle; text-align: center;">-</td>
                                @endif
                                @if(array_key_exists("masalah", $data_lain_c[$key]))
                                <td style="vertical-align : middle; text-align: center;">
                                    @foreach($data_lain_c[$key]['masalah'] as $key_masalah => $value_masalah)
                                    @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                        @if((($key_masalah + 1) + 1) != count($data_lain_c[$key]['masalah']))
                                        <br>
                                        @endif
                                    @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                        @if((($key_masalah + 1) + 1) != count($data_lain_c[$key]['masalah']))
                                        <br>
                                        @endif
                                    @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                        @if((($key_masalah + 1) + 1) != count($data_lain_c[$key]['masalah']))
                                        <br>
                                        @endif
                                    @else
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                        @if((($key_masalah + 1) + 1) != count($data_lain_c[$key]['masalah']))
                                        <br>
                                        @endif
                                    @endif
                                    @endforeach
                                </td>
                                @else
                                <td style="vertical-align : middle; text-align: center;">-</td>
                                @endif
                            </tr>
                            <tr colspan="16"></tr>
                        @else
                            <tr>
                                <td style="vertical-align : middle; text-align: center;">{{ $key }}</td>
                                <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($jam_kecil)) }}</td>
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
                                @if(array_key_exists("tonase", $data_lain_a[$key]))
                                <td style="vertical-align : middle; text-align: center;">{{ $data_lain_a[$key]['tonase'] }}</td>
                                @else
                                <td style="vertical-align : middle; text-align: center;">-</td>
                                @endif
                                @if(array_key_exists("masalah", $data_lain_a[$key]))
                                <td style="vertical-align : middle; text-align: center;">
                                    @foreach($data_lain_a[$key]['masalah'] as $key_masalah => $value_masalah)
                                    @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                        <br>
                                        @endif
                                    @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                        <br>
                                        @endif
                                    @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                        <br>
                                        @endif
                                    @else
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                        @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                        <br>
                                        @endif
                                    @endif
                                    @endforeach
                                </td>
                                @else
                                <td style="vertical-align : middle; text-align: center;">-</td>
                                @endif
                                @if(array_key_exists("masalah", $data_lain_b[$key]))
                                <td style="vertical-align : middle; text-align: center;">
                                    @foreach($data_lain_b[$key]['masalah'] as $key_masalah => $value_masalah)
                                    @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                        <br>
                                        @endif
                                    @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                        <br>
                                        @endif
                                    @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} ) 
                                        @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                        <br>
                                        @endif
                                    @else
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                        @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                        <br>
                                        @endif
                                    @endif
                                    @endforeach
                                </td>
                                @else
                                <td style="vertical-align : middle; text-align: center;">-</td>
                                @endif
                                @if(array_key_exists("masalah", $data_lain_c[$key]))
                                <td style="vertical-align : middle; text-align: center;">
                                    @foreach($data_lain_c[$key]['masalah'] as $key_masalah => $value_masalah)
                                    @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                        <br>
                                        @endif
                                    @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                        <br>
                                        @endif
                                    @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                        <br>
                                        @endif
                                    @else
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                        @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                        <br>
                                        @endif
                                    @endif
                                    @endforeach
                                </td>
                                @else
                                <td style="vertical-align : middle; text-align: center;">-</td>
                                @endif
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
                                        <td style="vertical-align : middle; text-align: center;">{{ $value_det['mesh'] }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['rpm'] == 0  || $value_det['rpm'] == null ? '-': $value_det['rpm']) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
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
                                        @if(array_key_exists("tonase", $data_lain_a[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">{{ $data_lain_a[$key]['tonase'] }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">-</td>
                                        @endif
                                        @if(array_key_exists("masalah", $data_lain_a[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">
                                            @foreach($data_lain_a[$key]['masalah'] as $key_masalah => $value_masalah)
                                            @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @else
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @endif
                                            @endforeach
                                        </td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">-</td>
                                        @endif
                                        @if(array_key_exists("masalah", $data_lain_b[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">
                                            @foreach($data_lain_b[$key]['masalah'] as $key_masalah => $value_masalah)
                                            @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} ) 
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @else
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @endif
                                            @endforeach
                                        </td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">-</td>
                                        @endif
                                        @if(array_key_exists("masalah", $data_lain_c[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">
                                            @foreach($data_lain_c[$key]['masalah'] as $key_masalah => $value_masalah)
                                            @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @else
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @endif
                                            @endforeach
                                        </td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">-</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($jam_besar)) }}</td>
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
                                    </tr>
                                    <tr colspan="16"></tr>
                                @else
                                    <tr>
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">{{ $key }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det['jam_waktu'])) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ $value_det['mesh'] }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['rpm'] == 0  || $value_det['rpm'] == null ? '-': $value_det['rpm']) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
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
                                        @if(array_key_exists("tonase", $data_lain_a[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">{{ $data_lain_a[$key]['tonase'] }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">-</td>
                                        @endif
                                        @if(array_key_exists("masalah", $data_lain_a[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">
                                            @foreach($data_lain_a[$key]['masalah'] as $key_masalah => $value_masalah)
                                            @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @else
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @endif
                                            @endforeach
                                        </td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">-</td>
                                        @endif
                                        @if(array_key_exists("masalah", $data_lain_b[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">
                                            @foreach($data_lain_b[$key]['masalah'] as $key_masalah => $value_masalah)
                                            @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} ) 
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @else
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @endif
                                            @endforeach
                                        </td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">-</td>
                                        @endif
                                        @if(array_key_exists("masalah", $data_lain_c[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">
                                            @foreach($data_lain_c[$key]['masalah'] as $key_masalah => $value_masalah)
                                            @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @else
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @endif
                                            @endforeach
                                        </td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">-</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($jam_besar)) }}</td>
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
                                    </tr>
                                @endif
                            @elseif($value_det['jam_waktu'] == $jam_besar)
                                @if($key == "RB")
                                    <tr>
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">{{ $key }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($jam_besar)) }}</td>
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
                                        @if(array_key_exists("tonase", $data_lain_a[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">{{ $data_lain_a[$key]['tonase'] }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">-</td>
                                        @endif
                                        @if(array_key_exists("masalah", $data_lain_a[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">
                                            @foreach($data_lain_a[$key]['masalah'] as $key_masalah => $value_masalah)
                                            @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @else
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @endif
                                            @endforeach
                                        </td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">-</td>
                                        @endif
                                        @if(array_key_exists("masalah", $data_lain_b[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">
                                            @foreach($data_lain_b[$key]['masalah'] as $key_masalah => $value_masalah)
                                            @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} ) 
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @else
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @endif
                                            @endforeach
                                        </td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">-</td>
                                        @endif
                                        @if(array_key_exists("masalah", $data_lain_c[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">
                                            @foreach($data_lain_c[$key]['masalah'] as $key_masalah => $value_masalah)
                                            @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @else
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @endif
                                            @endforeach
                                        </td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">-</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det['jam_waktu'])) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ $value_det['mesh'] }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['rpm'] == 0  || $value_det['rpm'] == null ? '-': $value_det['rpm']) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
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
                                        @if(array_key_exists("tonase", $data_lain_a[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">{{ $data_lain_a[$key]['tonase'] }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">-</td>
                                        @endif
                                        @if(array_key_exists("masalah", $data_lain_a[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">
                                            @foreach($data_lain_a[$key]['masalah'] as $key_masalah => $value_masalah)
                                            @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @else
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @endif
                                            @endforeach
                                        </td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">-</td>
                                        @endif
                                        @if(array_key_exists("masalah", $data_lain_b[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">
                                            @foreach($data_lain_b[$key]['masalah'] as $key_masalah => $value_masalah)
                                            @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} ) 
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @else
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @endif
                                            @endforeach
                                        </td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">-</td>
                                        @endif
                                        @if(array_key_exists("masalah", $data_lain_c[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">
                                            @foreach($data_lain_c[$key]['masalah'] as $key_masalah => $value_masalah)
                                            @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @else
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @endif
                                            @endforeach
                                        </td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="2">-</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det['jam_waktu'])) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ $value_det['mesh'] }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['rpm'] == 0  || $value_det['rpm'] == null ? '-': $value_det['rpm']) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
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
                                        <td style="vertical-align : middle; text-align: center;">{{ $value_det['mesh'] }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['rpm'] == 0  || $value_det['rpm'] == null ? '-': $value_det['rpm']) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
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
                                        @if(array_key_exists("tonase", $data_lain_a[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">{{ $data_lain_a[$key]['tonase'] }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">-</td>
                                        @endif
                                        @if(array_key_exists("masalah", $data_lain_a[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">
                                            @foreach($data_lain_a[$key]['masalah'] as $key_masalah => $value_masalah)
                                            @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @else
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @endif
                                            @endforeach
                                        </td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">-</td>
                                        @endif
                                        @if(array_key_exists("masalah", $data_lain_b[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">
                                            @foreach($data_lain_b[$key]['masalah'] as $key_masalah => $value_masalah)
                                            @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} ) 
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @else
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @endif
                                            @endforeach
                                        </td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">-</td>
                                        @endif
                                        @if(array_key_exists("masalah", $data_lain_c[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">
                                            @foreach($data_lain_c[$key]['masalah'] as $key_masalah => $value_masalah)
                                            @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @else
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @endif
                                            @endforeach
                                        </td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">-</td>
                                        @endif
                                    </tr>
                                    <tr colspan="16"></tr>
                                @else
                                    <tr>
                                        <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">{{ $key }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det['jam_waktu'])) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ $value_det['mesh'] }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['rpm'] == 0  || $value_det['rpm'] == null ? '-': $value_det['rpm']) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                        <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
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
                                        @if(array_key_exists("tonase", $data_lain_a[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">{{ $data_lain_a[$key]['tonase'] }}</td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">-</td>
                                        @endif
                                        @if(array_key_exists("masalah", $data_lain_a[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">
                                            @foreach($data_lain_a[$key]['masalah'] as $key_masalah => $value_masalah)
                                            @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @else
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @endif
                                            @endforeach
                                        </td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">-</td>
                                        @endif
                                        @if(array_key_exists("masalah", $data_lain_b[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">
                                            @foreach($data_lain_b[$key]['masalah'] as $key_masalah => $value_masalah)
                                            @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} ) 
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @else
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @endif
                                            @endforeach
                                        </td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">-</td>
                                        @endif
                                        @if(array_key_exists("masalah", $data_lain_c[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">
                                            @foreach($data_lain_c[$key]['masalah'] as $key_masalah => $value_masalah)
                                            @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @else
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @endif
                                            @endforeach
                                        </td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">-</td>
                                        @endif
                                    </tr>
                                @endif
                            @else
                                <tr>
                                    <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">{{ $key }}</td>
                                    <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det['jam_waktu'])) }}</td>
                                    <td style="vertical-align : middle; text-align: center;">{{ $value_det['mesh'] }}</td>
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det['rpm'] == 0  || $value_det['rpm'] == null ? '-': $value_det['rpm']) }}</td>
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                    <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
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
                                    @if(array_key_exists("tonase", $data_lain_a[$key]))
                                    <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">{{ $data_lain_a[$key]['tonase'] }}</td>
                                    @else
                                    <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">-</td>
                                    @endif
                                    @if(array_key_exists("masalah", $data_lain_a[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">
                                            @foreach($data_lain_a[$key]['masalah'] as $key_masalah => $value_masalah)
                                            @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @else
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                                <br>
                                                @endif
                                            @endif
                                            @endforeach
                                        </td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">-</td>
                                        @endif
                                        @if(array_key_exists("masalah", $data_lain_b[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">
                                            @foreach($data_lain_b[$key]['masalah'] as $key_masalah => $value_masalah)
                                            @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} ) 
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @else
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                                <br>
                                                @endif
                                            @endif
                                            @endforeach
                                        </td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">-</td>
                                        @endif
                                        @if(array_key_exists("masalah", $data_lain_c[$key]))
                                        <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">
                                            @foreach($data_lain_c[$key]['masalah'] as $key_masalah => $value_masalah)
                                            @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @else
                                            {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }}
                                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                                <br>
                                                @endif
                                            @endif
                                            @endforeach
                                        </td>
                                        @else
                                        <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">-</td>
                                        @endif
                                </tr>
                            @endif
                        @endif
                    @else
                        @if($key == "RB")
                            <tr>
                                <td style="vertical-align : middle; text-align: center;">{{ date("H:i", strtotime($value_det['jam_waktu'])) }}</td>
                                <td style="vertical-align : middle; text-align: center;">{{ $value_det['mesh'] }}</td>
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['rpm'] == 0  || $value_det['rpm'] == null ? '-': $value_det['rpm']) }}</td>
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
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
                                <td style="vertical-align : middle; text-align: center;">{{ $value_det['mesh'] }}</td>
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['rpm'] == 0  || $value_det['rpm'] == null ? '-': $value_det['rpm']) }}</td>
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['ssa'] == 0  || $value_det['ssa'] == null ? '-': $value_det['ssa']) }}</td>
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d50'] == 0  || $value_det['d50'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d50'], 2, ',', '')) }}</td>
                                <td style="vertical-align : middle; text-align: center;">{{ ($value_det['d98'] == 0  || $value_det['d98'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['d98'], 2, ',', '')) }}</td>
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
</body>
</html>