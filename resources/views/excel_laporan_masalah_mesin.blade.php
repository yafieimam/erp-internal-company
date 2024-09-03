<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $mesin }}</title>
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
    @if($no_mesin <= 10)
    <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
        <thead>
            <tr>
                <th style="vertical-align : middle; text-align: center;" rowspan="2" colspan="2">Tanggal</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Mesh</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">RPM</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">SSA</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">D-50</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">D-98</th>
                <th style="text-align: center;" colspan="2">Whiteness</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Moisture</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Residue</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Tonase (KG)</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Masalah Major</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Masalah Minor</th>
                <th style="vertical-align : middle; text-align: center;" rowspan="2">Masalah Lain</th>
            </tr>
            <tr>
                <th style="vertical-align : middle; text-align: center;">CIE 86</th>
                <th style="vertical-align : middle; text-align: center;">ISO 2470</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $besar = 0;
            $jam_kecil = null;
            $jam_besar = null;
            $total_major = 0;
            $total_minor = 0;
            $total_lain = 0;
            $ada_lanjutan_major = 0; 
            $ada_lanjutan_minor = 0; 
            $ada_lanjutan_lain = 0; 
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
                            @if(array_key_exists("tonase", $data_lain_a[$key]))
                            <td style="vertical-align : middle; text-align: center;" rowspan="2">{{ $data_lain_a[$key]['tonase'] }}</td>
                            @else
                            <td style="vertical-align : middle; text-align: center;" rowspan="2">-</td>
                            @endif
                            @if(array_key_exists("masalah", $data_lain_a[$key]))
                            <td style="vertical-align : middle; text-align: center;" rowspan="2">
                                @foreach($data_lain_a[$key]['masalah'] as $key_masalah => $value_masalah)
                                @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                <?php $total_major += strtotime($value_masalah['jam_akhir']) - strtotime($value_masalah['jam_awal']); ?>
                                {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                <br>
                                @endif
                                @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                <?php 
                                $total_major += strtotime('24:00:00') - strtotime($value_masalah['jam_awal']); 
                                $ada_lanjutan_major = 1;
                                ?>
                                {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                <br>
                                @endif
                                @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                <?php
                                if($ada_lanjutan_major == 1){
                                    $total_major += strtotime($value_masalah['jam_akhir']) - strtotime('00:00:00'); 
                                    $ada_lanjutan_major = 0;
                                }
                                ?>
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
                            <?php
                            if($ada_lanjutan_major == 1){
                                $total_major += strtotime('24:00:00') - strtotime('00:00:00');
                            }
                            ?>
                            @endif
                            @if(array_key_exists("masalah", $data_lain_b[$key]))
                            <td style="vertical-align : middle; text-align: center;" rowspan="2">
                                @foreach($data_lain_b[$key]['masalah'] as $key_masalah => $value_masalah)
                                @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                <?php $total_minor += strtotime($value_masalah['jam_akhir']) - strtotime($value_masalah['jam_awal']); ?>
                                {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                <br>
                                @endif
                                @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                <?php 
                                $total_minor += strtotime('24:00:00') - strtotime($value_masalah['jam_awal']);
                                $ada_lanjutan_minor = 1;
                                ?>
                                {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                <br>
                                @endif
                                @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                <?php
                                if($ada_lanjutan_minor == 1){
                                    $total_minor += strtotime($value_masalah['jam_akhir']) - strtotime('00:00:00'); 
                                    $ada_lanjutan_minor = 0;
                                }
                                ?>
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
                            <?php
                            if($ada_lanjutan_minor == 1){
                                $total_minor += strtotime('24:00:00') - strtotime('00:00:00');
                            }
                            ?>
                            @endif
                            @if(array_key_exists("masalah", $data_lain_c[$key]))
                            <td style="vertical-align : middle; text-align: center;" rowspan="2">
                                @foreach($data_lain_c[$key]['masalah'] as $key_masalah => $value_masalah)
                                @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                <?php $total_lain += strtotime($value_masalah['jam_akhir']) - strtotime($value_masalah['jam_awal']); ?>
                                {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                <br>
                                @endif
                                @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                <?php 
                                $total_lain += strtotime('24:00:00') - strtotime($value_masalah['jam_awal']); 
                                $ada_lanjutan_lain = 1;
                                ?>
                                {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                <br>
                                @endif
                                @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                <?php
                                if($ada_lanjutan_lain == 1){
                                    $total_lain += strtotime($value_masalah['jam_akhir']) - strtotime('00:00:00'); 
                                    $ada_lanjutan_lain = 0;
                                }
                                ?>
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
                            <?php
                            if($ada_lanjutan_lain == 1){
                                $total_lain += strtotime('24:00:00') - strtotime('00:00:00');
                            }
                            ?>
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
                        </tr>
                    @endif
                @else
                    @if(count($value) == 0)
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
                            @if(array_key_exists("tonase", $data_lain_a[$key]))
                            <td style="vertical-align : middle; text-align: center;">{{ $data_lain_a[$key]['tonase'] }}</td>
                            @else
                            <td style="vertical-align : middle; text-align: center;">-</td>
                            @endif
                            @if(array_key_exists("masalah", $data_lain_a[$key]))
                            <td style="vertical-align : middle; text-align: center;">
                                @foreach($data_lain_a[$key]['masalah'] as $key_masalah => $value_masalah)
                                @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                <?php $total_major += strtotime($value_masalah['jam_akhir']) - strtotime($value_masalah['jam_awal']); ?>
                                {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                <br>
                                @endif
                                @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                <?php 
                                $total_major += strtotime('24:00:00') - strtotime($value_masalah['jam_awal']); 
                                $ada_lanjutan_major = 1;
                                ?>
                                {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                <br>
                                @endif
                                @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                <?php
                                if($ada_lanjutan_major == 1){
                                    $total_major += strtotime($value_masalah['jam_akhir']) - strtotime('00:00:00'); 
                                    $ada_lanjutan_major = 0;
                                }
                                ?>
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
                            <?php
                            if($ada_lanjutan_major == 1){
                                $total_major += strtotime('24:00:00') - strtotime('00:00:00');
                            }
                            ?>
                            @endif
                            @if(array_key_exists("masalah", $data_lain_b[$key]))
                            <td style="vertical-align : middle; text-align: center;">
                                @foreach($data_lain_b[$key]['masalah'] as $key_masalah => $value_masalah)
                                @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                <?php $total_minor += strtotime($value_masalah['jam_akhir']) - strtotime($value_masalah['jam_awal']); ?>
                                {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                <br>
                                @endif
                                @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                <?php 
                                $total_minor += strtotime('24:00:00') - strtotime($value_masalah['jam_awal']); 
                                $ada_lanjutan_minor = 1;
                                ?>
                                {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                <br>
                                @endif
                                @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                <?php
                                if($ada_lanjutan_minor == 1){
                                    $total_minor += strtotime($value_masalah['jam_akhir']) - strtotime('00:00:00'); 
                                    $ada_lanjutan_minor = 0;
                                }
                                ?>
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
                            <?php
                            if($ada_lanjutan_minor == 1){
                                $total_minor += strtotime('24:00:00') - strtotime('00:00:00');
                            }
                            ?>
                            @endif
                            @if(array_key_exists("masalah", $data_lain_c[$key]))
                            <td style="vertical-align : middle; text-align: center;">
                                @foreach($data_lain_c[$key]['masalah'] as $key_masalah => $value_masalah)
                                @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                <?php $total_lain += strtotime($value_masalah['jam_akhir']) - strtotime($value_masalah['jam_awal']); ?>
                                {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                <br>
                                @endif
                                @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                <?php 
                                $total_lain += strtotime('24:00:00') - strtotime($value_masalah['jam_awal']); 
                                $ada_lanjutan_lain = 1;
                                ?>
                                {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                <br>
                                @endif
                                @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                <?php
                                if($ada_lanjutan_lain == 1){
                                    $total_lain += strtotime($value_masalah['jam_akhir']) - strtotime('00:00:00'); 
                                    $ada_lanjutan_lain = 0;
                                }
                                ?>
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
                            <?php
                            if($ada_lanjutan_lain == 1){
                                $total_lain += strtotime('24:00:00') - strtotime('00:00:00');
                            }
                            ?>
                            @endif
                        </tr>
                    @endif
                @endif
                @foreach($value as $key_det => $value_det)
                    @if($key_det == 0)
                        @if(count($value) < $besar)
                            @if($value_det['jam_waktu'] == $jam_kecil)
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
                                        <?php $total_major += strtotime($value_masalah['jam_akhir']) - strtotime($value_masalah['jam_awal']); ?>
                                        {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                        <br>
                                        @endif
                                        @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                        <?php 
                                        $total_major += strtotime('24:00:00') - strtotime($value_masalah['jam_awal']); 
                                        $ada_lanjutan_major = 1;
                                        ?>
                                        {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                        <br>
                                        @endif
                                        @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                        <?php
                                        if($ada_lanjutan_major == 1){
                                            $total_major += strtotime($value_masalah['jam_akhir']) - strtotime('00:00:00'); 
                                            $ada_lanjutan_major = 0;
                                        }
                                        ?>
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
                                    <?php
                                    if($ada_lanjutan_major == 1){
                                        $total_major += strtotime('24:00:00') - strtotime('00:00:00');
                                    }
                                    ?>
                                    @endif
                                    @if(array_key_exists("masalah", $data_lain_b[$key]))
                                    <td style="vertical-align : middle; text-align: center;" rowspan="2">
                                        @foreach($data_lain_b[$key]['masalah'] as $key_masalah => $value_masalah)
                                        @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                        <?php $total_minor += strtotime($value_masalah['jam_akhir']) - strtotime($value_masalah['jam_awal']); ?>
                                        {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                        <br>
                                        @endif
                                        @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                        <?php 
                                        $total_minor += strtotime('24:00:00') - strtotime($value_masalah['jam_awal']); 
                                        $ada_lanjutan_minor = 1;
                                        ?>
                                        {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                        <br>
                                        @endif
                                        @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                        <?php
                                        if($ada_lanjutan_minor == 1){
                                            $total_minor += strtotime($value_masalah['jam_akhir']) - strtotime('00:00:00'); 
                                            $ada_lanjutan_minor = 0;
                                        }
                                        ?>
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
                                    <?php
                                    if($ada_lanjutan_minor == 1){
                                        $total_minor += strtotime('24:00:00') - strtotime('00:00:00');
                                    }
                                    ?>
                                    @endif
                                    @if(array_key_exists("masalah", $data_lain_c[$key]))
                                    <td style="vertical-align : middle; text-align: center;" rowspan="2">
                                        @foreach($data_lain_c[$key]['masalah'] as $key_masalah => $value_masalah)
                                        @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                        <?php $total_lain += strtotime($value_masalah['jam_akhir']) - strtotime($value_masalah['jam_awal']); ?>
                                        {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                        <br>
                                        @endif
                                        @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                        <?php 
                                        $total_lain += strtotime('24:00:00') - strtotime($value_masalah['jam_awal']); 
                                        $ada_lanjutan_lain = 1;
                                        ?>
                                        {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                        <br>
                                        @endif
                                        @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                        <?php
                                        if($ada_lanjutan_lain == 1){
                                            $total_lain += strtotime($value_masalah['jam_akhir']) - strtotime('00:00:00'); 
                                            $ada_lanjutan_lain = 0;
                                        }
                                        ?>
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
                                    <?php
                                    if($ada_lanjutan_lain == 1){
                                        $total_lain += strtotime('24:00:00') - strtotime('00:00:00');
                                    }
                                    ?>
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
                                </tr>
                            @elseif($value_det['jam_waktu'] == $jam_besar)
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
                                    @if(array_key_exists("tonase", $data_lain_a[$key]))
                                    <td style="vertical-align : middle; text-align: center;" rowspan="2">{{ $data_lain_a[$key]['tonase'] }}</td>
                                    @else
                                    <td style="vertical-align : middle; text-align: center;" rowspan="2">-</td>
                                    @endif
                                    @if(array_key_exists("masalah", $data_lain_a[$key]))
                                    <td style="vertical-align : middle; text-align: center;" rowspan="2">
                                        @foreach($data_lain_a[$key]['masalah'] as $key_masalah => $value_masalah)
                                        @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                        <?php $total_major += strtotime($value_masalah['jam_akhir']) - strtotime($value_masalah['jam_awal']); ?>
                                        {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                        <br>
                                        @endif
                                        @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                        <?php 
                                        $total_major += strtotime('24:00:00') - strtotime($value_masalah['jam_awal']); 
                                        $ada_lanjutan_major = 1;
                                        ?>
                                        {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                        <br>
                                        @endif
                                        @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                        <?php
                                        if($ada_lanjutan_major == 1){
                                            $total_major += strtotime($value_masalah['jam_akhir']) - strtotime('00:00:00'); 
                                            $ada_lanjutan_major = 0;
                                        }
                                        ?>
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
                                    <?php
                                    if($ada_lanjutan_major == 1){
                                        $total_major += strtotime('24:00:00') - strtotime('00:00:00');
                                    }
                                    ?>
                                    @endif
                                    @if(array_key_exists("masalah", $data_lain_b[$key]))
                                    <td style="vertical-align : middle; text-align: center;" rowspan="2">
                                        @foreach($data_lain_b[$key]['masalah'] as $key_masalah => $value_masalah)
                                        @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                        <?php $total_minor += strtotime($value_masalah['jam_akhir']) - strtotime($value_masalah['jam_awal']); ?>
                                        {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                        <br>
                                        @endif
                                        @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                        <?php 
                                        $total_minor += strtotime('24:00:00') - strtotime($value_masalah['jam_awal']); 
                                        $ada_lanjutan_minor = 1;
                                        ?>
                                        {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                        <br>
                                        @endif
                                        @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                        <?php
                                        if($ada_lanjutan_minor == 1){
                                            $total_minor += strtotime($value_masalah['jam_akhir']) - strtotime('00:00:00'); 
                                            $ada_lanjutan_minor = 0;
                                        }
                                        ?>
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
                                    <?php
                                    if($ada_lanjutan_minor == 1){
                                        $total_minor += strtotime('24:00:00') - strtotime('00:00:00');
                                    }
                                    ?>
                                    @endif
                                    @if(array_key_exists("masalah", $data_lain_c[$key]))
                                    <td style="vertical-align : middle; text-align: center;" rowspan="2">
                                        @foreach($data_lain_c[$key]['masalah'] as $key_masalah => $value_masalah)
                                        @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                        <?php $total_lain += strtotime($value_masalah['jam_akhir']) - strtotime($value_masalah['jam_awal']); ?>
                                        {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                        <br>
                                        @endif
                                        @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                        <?php 
                                        $total_lain += strtotime('24:00:00') - strtotime($value_masalah['jam_awal']); 
                                        $ada_lanjutan_lain = 1;
                                        ?>
                                        {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                        @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                        <br>
                                        @endif
                                        @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                        <?php
                                        if($ada_lanjutan_lain == 1){
                                            $total_lain += strtotime($value_masalah['jam_akhir']) - strtotime('00:00:00'); 
                                            $ada_lanjutan_lain = 0;
                                        }
                                        ?>
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
                                    <?php
                                    if($ada_lanjutan_lain == 1){
                                        $total_lain += strtotime('24:00:00') - strtotime('00:00:00');
                                    }
                                    ?>
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
                                    <?php $total_major += strtotime($value_masalah['jam_akhir']) - strtotime($value_masalah['jam_awal']); ?>
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                    @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                    <br>
                                    @endif
                                    @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                    <?php 
                                    $total_major += strtotime('24:00:00') - strtotime($value_masalah['jam_awal']); 
                                    $ada_lanjutan_major = 1;
                                    ?>
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                    @if(($key_masalah + 1) != count($data_lain_a[$key]['masalah']))
                                    <br>
                                    @endif
                                    @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                    <?php
                                    if($ada_lanjutan_major == 1){
                                        $total_major += strtotime($value_masalah['jam_akhir']) - strtotime('00:00:00'); 
                                        $ada_lanjutan_major = 0;
                                    }
                                    ?>
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
                                <?php
                                if($ada_lanjutan_major == 1){
                                    $total_major += strtotime('24:00:00') - strtotime('00:00:00');
                                }
                                ?>
                                @endif
                                @if(array_key_exists("masalah", $data_lain_b[$key]))
                                <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">
                                    @foreach($data_lain_b[$key]['masalah'] as $key_masalah => $value_masalah)
                                    @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                    <?php $total_minor += strtotime($value_masalah['jam_akhir']) - strtotime($value_masalah['jam_awal']); ?>
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                    @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                    <br>
                                    @endif
                                    @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                    <?php 
                                    $total_minor += strtotime('24:00:00') - strtotime($value_masalah['jam_awal']); 
                                    $ada_lanjutan_minor = 1;
                                    ?>
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                    @if(($key_masalah + 1) != count($data_lain_b[$key]['masalah']))
                                    <br>
                                    @endif
                                    @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                    <?php
                                    if($ada_lanjutan_minor == 1){
                                        $total_minor += strtotime($value_masalah['jam_akhir']) - strtotime('00:00:00'); 
                                        $ada_lanjutan_minor = 0;
                                    }
                                    ?>
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
                                <?php
                                if($ada_lanjutan_minor == 1){
                                    $total_minor += strtotime('24:00:00') - strtotime('00:00:00');
                                }
                                ?>
                                @endif
                                @if(array_key_exists("masalah", $data_lain_c[$key]))
                                <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($value) }}">
                                    @foreach($data_lain_c[$key]['masalah'] as $key_masalah => $value_masalah)
                                    @if($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] != null)
                                    <?php $total_lain += strtotime($value_masalah['jam_akhir']) - strtotime($value_masalah['jam_awal']); ?>
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} - {{ date("H:i", strtotime($value_masalah['jam_akhir'])) }} )
                                    @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                    <br>
                                    @endif
                                    @elseif($value_masalah['jam_awal'] != null && $value_masalah['jam_akhir'] == null)
                                    <?php 
                                    $total_lain += strtotime('24:00:00') - strtotime($value_masalah['jam_awal']); 
                                    $ada_lanjutan_lain = 1;
                                    ?>
                                    {{ ($key_masalah +1) }}. {{ $value_masalah['masalah'] }} ( {{ date("H:i", strtotime($value_masalah['jam_awal'])) }} )
                                    @if(($key_masalah + 1) != count($data_lain_c[$key]['masalah']))
                                    <br>
                                    @endif
                                    @elseif($value_masalah['jam_awal'] == null && $value_masalah['jam_akhir'] != null)
                                    <?php
                                    if($ada_lanjutan_lain == 1){
                                        $total_lain += strtotime($value_masalah['jam_akhir']) - strtotime('00:00:00'); 
                                        $ada_lanjutan_lain = 0;
                                    }
                                    ?>
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
                                <?php
                                if($ada_lanjutan_lain == 1){
                                    $total_lain += strtotime('24:00:00') - strtotime('00:00:00');
                                }
                                ?>
                                @endif
                            </tr>
                        @endif
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
                            @if($value_det['spek_residue'] < $value_det['residue'])
                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">{{ ($value_det['residue'] == 0  || $value_det['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['residue'], 2, ',', '')) }}</td>
                            @else
                            <td style="vertical-align : middle; text-align: center;">{{ ($value_det['residue'] == 0  || $value_det['residue'] == null ? "" . number_format(0, 2, ',', '') : "" . number_format($value_det['residue'], 2, ',', '')) }}</td>
                            @endif
                        </tr>
                    @endif
                @endforeach
            @endforeach
        </tbody>
    </table>
    <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
        <tr>
            <th>Total Masalah Major</th>
            <th>Total Masalah Minor</th>
            <th>Total Masalah Lain-Lain</th>
        </tr>
        <tr>
            <td style="vertical-align : middle; text-align: center;">{{ floor($total_major / 3600) }} Jam {{ ($total_major / 60) % 60 }} Menit</td>
            <td style="vertical-align : middle; text-align: center;">{{ floor($total_minor / 3600) }} Jam {{ ($total_minor / 60) % 60 }} Menit</td>
            <td style="vertical-align : middle; text-align: center;">{{ floor($total_lain / 3600) }} Jam {{ ($total_lain / 60) % 60 }} Menit</td>
        </tr>
    </table>
    @else
        @if($no_mesin >= 11 && $no_mesin <= 13)
            <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
                <thead>
                    <tr>
                        <th style="vertical-align : middle; text-align: center;"></th>
                        <th style="vertical-align : middle; text-align: center;">Tahun</th>
                        <th style="vertical-align : middle; text-align: center;">Januari</th>
                        <th style="vertical-align : middle; text-align: center;">Februari</th>
                        <th style="vertical-align : middle; text-align: center;">Maret</th>
                        <th style="vertical-align : middle; text-align: center;">April</th>
                        <th style="vertical-align : middle; text-align: center;">Mei</th>
                        <th style="vertical-align : middle; text-align: center;">Juni</th>
                        <th style="vertical-align : middle; text-align: center;">Juli</th>
                        <th style="vertical-align : middle; text-align: center;">Agustus</th>
                        <th style="vertical-align : middle; text-align: center;">September</th>
                        <th style="vertical-align : middle; text-align: center;">Oktober</th>
                        <th style="vertical-align : middle; text-align: center;">November</th>
                        <th style="vertical-align : middle; text-align: center;">Desember</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $arr_mesin = ['SA', 'SB', 'Mixer', 'RA', 'RB', 'RC', 'RD', 'RE', 'RF', 'RG'];

                        foreach($arr_mesin as $key => $value){
                            $ada_lanjutan1 = array_fill(0, 12, 0);
                            $ada_lanjutan2 = array_fill(0, 12, 0);
                            $ada_lanjutan3 = array_fill(0, 12, 0);
                            $total_nilai1 = array_fill(0, 12, 0);
                            $total_nilai2 = array_fill(0, 12, 0);
                            $total_nilai3 = array_fill(0, 12, 0);

                            foreach(${$arr_mesin[$key]} as $key2 => $value2){
                                if(!(array_key_exists("masalah",$value2))){
                                    foreach($ada_lanjutan1 as $key3 => $value3){
                                        if($value3 == 1){
                                            $total_nilai1[$key3] += strtotime('24:00:00') - strtotime('00:00:00');
                                        }
                                    }
                                    foreach($ada_lanjutan2 as $key3 => $value3){
                                        if($value3 == 1){
                                            $total_nilai2[$key3] += strtotime('24:00:00') - strtotime('00:00:00');
                                        }
                                    }
                                    foreach($ada_lanjutan3 as $key3 => $value3){
                                        if($value3 == 1){
                                            $total_nilai3[$key3] += strtotime('24:00:00') - strtotime('00:00:00');
                                        }
                                    }
                                }else{
                                    foreach($value2['masalah'] as $key3 => $value3){
                                        if($value3['tahun'] == date("Y")){
                                            if($value3['jam_awal'] != null && $value3['jam_akhir'] != null){
                                                $total_nilai1[$value3['bulan'] - 1] += strtotime($value3['jam_akhir']) - strtotime($value3['jam_awal']);
                                            }else if($value3['jam_awal'] != null && $value3['jam_akhir'] == null){
                                                $total_nilai1[$value3['bulan'] - 1] += strtotime('24:00:00') - strtotime($value3['jam_awal']);
                                                $ada_lanjutan1[$value3['bulan'] - 1] = 1;
                                            }else if($value3['jam_akhir'] != null && $value3['jam_awal'] == null){
                                                if($ada_lanjutan1[$value3['bulan'] - 1] == 1){
                                                    $total_nilai1[$value3['bulan'] - 1] += strtotime($value3['jam_akhir']) - strtotime('00:00:00');
                                                    $ada_lanjutan1[$value3['bulan'] - 1] = 0;
                                                }
                                            }
                                        }else if($value3['tahun'] == date("Y", strtotime('-1 year'))){
                                            if($value3['jam_awal'] != null && $value3['jam_akhir'] != null){
                                                $total_nilai2[$value3['bulan'] - 1] += strtotime($value3['jam_akhir']) - strtotime($value3['jam_awal']);
                                            }else if($value3['jam_awal'] != null && $value3['jam_akhir'] == null){
                                                $total_nilai2[$value3['bulan'] - 1] += strtotime('24:00:00') - strtotime($value3['jam_awal']);
                                                $ada_lanjutan2[$value3['bulan'] - 1] = 1;
                                            }else if($value3['jam_akhir'] != null && $value3['jam_awal'] == null){
                                                if($ada_lanjutan2[$value3['bulan'] - 1] == 1){
                                                    $total_nilai2[$value3['bulan'] - 1] += strtotime($value3['jam_akhir']) - strtotime('00:00:00');
                                                    $ada_lanjutan2[$value3['bulan'] - 1] = 0;
                                                }
                                            }
                                        }else if($value3['tahun'] == date("Y", strtotime('-2 year'))){
                                            if($value3['jam_awal'] != null && $value3['jam_akhir'] != null){
                                                $total_nilai3[$value3['bulan'] - 1] += strtotime($value3['jam_akhir']) - strtotime($value3['jam_awal']);
                                            }else if($value3['jam_awal'] != null && $value3['jam_akhir'] == null){
                                                $total_nilai3[$value3['bulan'] - 1] += strtotime('24:00:00') - strtotime($value3['jam_awal']);
                                                $ada_lanjutan3[$value3['bulan'] - 1] = 1;
                                            }else if($value3['jam_akhir'] != null && $value3['jam_awal'] == null){
                                                if($ada_lanjutan3[$value3['bulan'] - 1] == 1){
                                                    $total_nilai3[$value3['bulan'] - 1] += strtotime($value3['jam_akhir']) - strtotime('00:00:00');
                                                    $ada_lanjutan3[$value3['bulan'] - 1] = 0;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                    ?>
                    <tr>
                        <td style="vertical-align : middle; text-align: center;" rowspan="3">{{ $value }}</td>
                        <td style="vertical-align : middle; text-align: center;">{{ date("Y", strtotime('-2 year')) }}</td>
                        @foreach($total_nilai3 as $key2 => $value2)
                            @if($value2 == 0)
                                <td style="vertical-align : middle; text-align: center;">0</td>
                            @else
                                <td style="vertical-align : middle; text-align: center;">{{ ($value2 / 60) }}</td>
                            @endif
                        @endforeach
                    </tr>
                    <tr>
                        <td style="vertical-align : middle; text-align: center;">{{ date("Y", strtotime('-1 year')) }}</td>
                        @foreach($total_nilai2 as $key2 => $value2)
                            @if($value2 == 0)
                                <td style="vertical-align : middle; text-align: center;">0</td>
                            @else
                                <td style="vertical-align : middle; text-align: center;">{{ ($value2 / 60) }}</td>
                            @endif
                        @endforeach
                    </tr>
                    <tr>
                        <td style="vertical-align : middle; text-align: center;">{{ date("Y") }}</td>
                        @foreach($total_nilai1 as $key2 => $value2)
                            @if($value2 == 0)
                                <td style="vertical-align : middle; text-align: center;">0</td>
                            @else
                                <td style="vertical-align : middle; text-align: center;">{{ ($value2 / 60) }}</td>
                            @endif
                        @endforeach
                    </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
        @elseif($no_mesin == 14)
            <table style="width: 100%; font-size: 14px;" class="table table-bordered table-hover responsive">
                <thead>
                    <tr>
                        <th style="vertical-align : middle; text-align: center;"></th>
                        <th style="vertical-align : middle; text-align: center;">Tahun</th>
                        <th style="vertical-align : middle; text-align: center;">Januari</th>
                        <th style="vertical-align : middle; text-align: center;">Februari</th>
                        <th style="vertical-align : middle; text-align: center;">Maret</th>
                        <th style="vertical-align : middle; text-align: center;">April</th>
                        <th style="vertical-align : middle; text-align: center;">Mei</th>
                        <th style="vertical-align : middle; text-align: center;">Juni</th>
                        <th style="vertical-align : middle; text-align: center;">Juli</th>
                        <th style="vertical-align : middle; text-align: center;">Agustus</th>
                        <th style="vertical-align : middle; text-align: center;">September</th>
                        <th style="vertical-align : middle; text-align: center;">Oktober</th>
                        <th style="vertical-align : middle; text-align: center;">November</th>
                        <th style="vertical-align : middle; text-align: center;">Desember</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $arr_mesin = ['SA', 'SB', 'Mixer', 'RA', 'RB', 'RC', 'RD', 'RE', 'RF', 'RG'];
                        $arr_kategori = ['Major', 'Minor', 'Lain'];

                        foreach($arr_mesin as $key => $value){
                            $total1 = array_fill(0, 12, 0);
                            $total2 = array_fill(0, 12, 0);
                            $total3 = array_fill(0, 12, 0);

                            foreach($arr_kategori as $key2 => $value2){
                                $ada_lanjutan1 = array_fill(0, 12, 0);
                                $ada_lanjutan2 = array_fill(0, 12, 0);
                                $ada_lanjutan3 = array_fill(0, 12, 0);
                                $total_nilai1 = array_fill(0, 12, 0);
                                $total_nilai2 = array_fill(0, 12, 0);
                                $total_nilai3 = array_fill(0, 12, 0);

                                foreach(${$arr_mesin[$key]}[$key2] as $key3 => $value3){
                                    if(!(array_key_exists("masalah",$value3))){
                                        foreach($ada_lanjutan1 as $key4 => $value4){
                                            if($value4 == 1){
                                                $total_nilai1[$key4] += strtotime('24:00:00') - strtotime('00:00:00');
                                            }
                                        }
                                        foreach($ada_lanjutan2 as $key4 => $value4){
                                            if($value4 == 1){
                                                $total_nilai2[$key4] += strtotime('24:00:00') - strtotime('00:00:00');
                                            }
                                        }
                                        foreach($ada_lanjutan3 as $key4 => $value4){
                                            if($value4 == 1){
                                                $total_nilai3[$key4] += strtotime('24:00:00') - strtotime('00:00:00');
                                            }
                                        }
                                    }else{
                                        foreach($value3['masalah'] as $key4 => $value4){
                                            if($value4['tahun'] == date("Y")){
                                                if($value4['jam_awal'] != null && $value4['jam_akhir'] != null){
                                                    $total_nilai1[$value4['bulan'] - 1] += strtotime($value4['jam_akhir']) - strtotime($value4['jam_awal']);
                                                }else if($value4['jam_awal'] != null && $value4['jam_akhir'] == null){
                                                    $total_nilai1[$value4['bulan'] - 1] += strtotime('24:00:00') - strtotime($value4['jam_awal']);
                                                    $ada_lanjutan1[$value4['bulan'] - 1] = 1;
                                                }else if($value4['jam_akhir'] != null && $value4['jam_awal'] == null){
                                                    if($ada_lanjutan1[$value4['bulan'] - 1] == 1){
                                                        $total_nilai1[$value4['bulan'] - 1] += strtotime($value4['jam_akhir']) - strtotime('00:00:00');
                                                        $ada_lanjutan1[$value4['bulan'] - 1] = 0;
                                                    }
                                                }
                                            }else if($value4['tahun'] == date("Y", strtotime('-1 year'))){
                                                if($value4['jam_awal'] != null && $value4['jam_akhir'] != null){
                                                    $total_nilai2[$value4['bulan'] - 1] += strtotime($value4['jam_akhir']) - strtotime($value4['jam_awal']);
                                                }else if($value4['jam_awal'] != null && $value4['jam_akhir'] == null){
                                                    $total_nilai2[$value4['bulan'] - 1] += strtotime('24:00:00') - strtotime($value4['jam_awal']);
                                                    $ada_lanjutan2[$value4['bulan'] - 1] = 1;
                                                }else if($value4['jam_akhir'] != null && $value4['jam_awal'] == null){
                                                    if($ada_lanjutan2[$value4['bulan'] - 1] == 1){
                                                        $total_nilai2[$value4['bulan'] - 1] += strtotime($value4['jam_akhir']) - strtotime('00:00:00');
                                                        $ada_lanjutan2[$value4['bulan'] - 1] = 0;
                                                    }
                                                }
                                            }else if($value4['tahun'] == date("Y", strtotime('-2 year'))){
                                                if($value4['jam_awal'] != null && $value4['jam_akhir'] != null){
                                                    $total_nilai3[$value4['bulan'] - 1] += strtotime($value4['jam_akhir']) - strtotime($value4['jam_awal']);
                                                }else if($value4['jam_awal'] != null && $value4['jam_akhir'] == null){
                                                    $total_nilai3[$value4['bulan'] - 1] += strtotime('24:00:00') - strtotime($value4['jam_awal']);
                                                    $ada_lanjutan3[$value4['bulan'] - 1] = 1;
                                                }else if($value4['jam_akhir'] != null && $value4['jam_awal'] == null){
                                                    if($ada_lanjutan3[$value4['bulan'] - 1] == 1){
                                                        $total_nilai3[$value4['bulan'] - 1] += strtotime($value4['jam_akhir']) - strtotime('00:00:00');
                                                        $ada_lanjutan3[$value4['bulan'] - 1] = 0;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }

                                foreach($total_nilai1 as $key3 => $value3){
                                    $total1[$key3] += ($value3 / 60);
                                }
                                foreach($total_nilai2 as $key3 => $value3){
                                    $total2[$key3] += ($value3 / 60);
                                }
                                foreach($total_nilai3 as $key3 => $value3){
                                    $total3[$key3] += ($value3 / 60);
                                }
                            }
                    ?>
                    <tr>
                        <td style="vertical-align : middle; text-align: center;" rowspan="3">{{ $value }}</td>
                        <td style="vertical-align : middle; text-align: center;">{{ date("Y", strtotime('-2 year')) }}</td>
                        @foreach($total3 as $key2 => $value2)
                            @if($value2 == 0)
                                <td style="vertical-align : middle; text-align: center;">0</td>
                            @else
                                <td style="vertical-align : middle; text-align: center;">{{ $value2 }}</td>
                            @endif
                        @endforeach
                    </tr>
                    <tr>
                        <td style="vertical-align : middle; text-align: center;">{{ date("Y", strtotime('-1 year')) }}</td>
                        @foreach($total2 as $key2 => $value2)
                            @if($value2 == 0)
                                <td style="vertical-align : middle; text-align: center;">0</td>
                            @else
                                <td style="vertical-align : middle; text-align: center;">{{ $value2 }}</td>
                            @endif
                        @endforeach
                    </tr>
                    <tr>
                        <td style="vertical-align : middle; text-align: center;">{{ date("Y") }}</td>
                        @foreach($total1 as $key2 => $value2)
                            @if($value2 == 0)
                                <td style="vertical-align : middle; text-align: center;">0</td>
                            @else
                                <td style="vertical-align : middle; text-align: center;">{{ $value2 }}</td>
                            @endif
                        @endforeach
                    </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
        @endif
    @endif
</body>
</html>