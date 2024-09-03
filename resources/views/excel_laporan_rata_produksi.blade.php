<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Rata-Rata Produksi</title>
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
            <th>Laporan Rata-Rata Produksi Tahun {{ $tanggal_awal }} - {{ $tanggal_akhir }}</th>
        </tr>
    </table>
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
                $tahun = date('Y');
                $arr_mesin = ['SA', 'SB', 'Mixer', 'RA', 'RB', 'RC', 'RD', 'RE', 'RF', 'RG'];
                $arr_bulan = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
                $arr_tahun = [$tahun - 2, $tahun - 1, $tahun];

                $total = [];
                for($i = 0; $i < count($arr_tahun); $i++){
                    $total[$arr_tahun[$i]] = [];
                    for($j = 0; $j < count($arr_bulan); $j++){
                      $total[$arr_tahun[$i]][$arr_bulan[$j]] = 0;
                  }
              }
            ?>
            @for($i = 0; $i < count($arr_mesin); $i++)
                <tr>
                <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($arr_tahun) }}">{{ $arr_mesin[$i] }}</td>
                @for($j = 0; $j < count($arr_tahun); $j++)
                    @if($j == 0)
                        <td style="vertical-align : middle; text-align: center;">{{ $arr_tahun[$j] }}</td>
                        @for($k = 0; $k < count($arr_bulan); $k++)
                            @if($data[$arr_mesin[$i]][$arr_tahun[$j]][$arr_bulan[$k]] ==  null)
                            <td style="vertical-align : middle; text-align: center;"></td>
                            @else
                            <?php $total[$arr_tahun[$j]][$arr_bulan[$k]] += (int) $data[$arr_mesin[$i]][$arr_tahun[$j]][$arr_bulan[$k]]; ?>
                            <td style="vertical-align : middle; text-align: center;">{{ $data[$arr_mesin[$i]][$arr_tahun[$j]][$arr_bulan[$k]] }}</td>
                            @endif
                        @endfor
                        </tr>
                    @else
                        <tr>
                        <td style="vertical-align : middle; text-align: center;">{{ $arr_tahun[$j] }}</td>
                        @for($k = 0; $k < count($arr_bulan); $k++)
                            @if($data[$arr_mesin[$i]][$arr_tahun[$j]][$arr_bulan[$k]] ==  null)
                            <td style="vertical-align : middle; text-align: center;"></td>
                            @else
                            <?php $total[$arr_tahun[$j]][$arr_bulan[$k]] += (int) $data[$arr_mesin[$i]][$arr_tahun[$j]][$arr_bulan[$k]]; ?>
                            <td style="vertical-align : middle; text-align: center;">{{ $data[$arr_mesin[$i]][$arr_tahun[$j]][$arr_bulan[$k]] }}</td>
                            @endif
                        @endfor
                        </tr>
                    @endif
                @endfor
            @endfor
            <tr>
                <td style="vertical-align : middle; text-align: center;" rowspan="{{ count($arr_tahun) }}">Total</td>
                @for($j = 0; $j < count($arr_tahun); $j++)
                    @if($j == 0)
                        <td style="vertical-align : middle; text-align: center;">{{ $arr_tahun[$j] }}</td>
                        @for($k = 0; $k < count($arr_bulan); $k++)
                            @if($total[$arr_tahun[$j]][$arr_bulan[$k]] ==  null)
                            <td style="vertical-align : middle; text-align: center;"></td>
                            @else
                            <td style="vertical-align : middle; text-align: center;">{{ $total[$arr_tahun[$j]][$arr_bulan[$k]] }}</td>
                            @endif
                        @endfor
                        </tr>
                    @else
                        <tr>
                        <td style="vertical-align : middle; text-align: center;">{{ $arr_tahun[$j] }}</td>
                        @for($k = 0; $k < count($arr_bulan); $k++)
                            @if($total[$arr_tahun[$j]][$arr_bulan[$k]] ==  null)
                            <td style="vertical-align : middle; text-align: center;"></td>
                            @else
                            <td style="vertical-align : middle; text-align: center;">{{ $total[$arr_tahun[$j]][$arr_bulan[$k]] }}</td>
                            @endif
                        @endfor
                        </tr>
                    @endif
                @endfor
        </tbody>
    </table>
</body>
</html>