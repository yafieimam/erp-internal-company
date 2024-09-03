<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Laporan Hasil Produksi</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page { margin: 15px; }
        * { box-sizing: border-box; }
        body{
            margin-left: 10px;
            margin-right: 10px;
        }
        table {
            border-collapse: collapse;
            font-size: 14px;
        }
        table, th, td {
            text-align: center;
        }
        #table_data th, #table_data td {
            border: 1px solid #000;
        }
        h2 {
            text-align: center; 
        }
    </style>
</head>
<body>
    <table style="width: 100%;">
        <tr>
            <td style="width: 80%"><h2 style="margin:10px; margin-bottom: 0;">LAPORAN HASIL PRODUKSI</h2></td>
        </tr>
        <tr>
            <td><h4 style="margin:10px;">Tanggal : {{ $data[0]->tanggal_laporan_produksi }}</h4></td>
        </tr>
    </table>
    <?php 
    $total_jumlah = 0;
    $total_tonase = 0;
    ?>
    <table id="table_data" style="width: 100%;">
        <thead>
            <tr>
                <th>Mesin</th>
                <th>Jenis</th>
                <th>Jumlah (Sak)</th>
                <th>Tonase (KG)</th>
                <th>No WOS</th>
            </tr>
        </thead>
        <tbody>
            @if(count($sa) > 0)
                <tr>
                    <td rowspan="{{ count($sa) }}">SA</td>
                    <td>{{ $sa[0]->jenis_produk }}</td>
                    <td>{{ number_format($sa[0]->jumlah_sak, 0, ".", ".") }}</td>
                    <td>{{ number_format($sa[0]->jumlah_tonase, 0, ".", ".") }}</td>
                    <td></td>
                </tr>
                {{ $total_jumlah += $sa[0]->jumlah_sak }}
                {{ $total_tonase += $sa[0]->jumlah_tonase }}

                @foreach($sa as $index => $sa)
                <?php
                if($index == 0) {
                    continue;
                }else{
                ?>
                <tr>
                    <td>{{ $sa->jenis_produk }}</td>
                    <td>{{ number_format($sa->jumlah_sak, 0, ".", ".") }}</td>
                    <td>{{ number_format($sa->jumlah_tonase, 0, ".", ".") }}</td>
                    <td></td>
                </tr>
                {{ $total_jumlah += $sa->jumlah_sak }}
                {{ $total_tonase += $sa->jumlah_tonase }}
                <?php
                }
                ?>
                @endforeach
            @endif
            @if(count($sb) > 0)
                <tr>
                    <td rowspan="{{ count($sb) }}">SB</td>
                    <td>{{ $sb[0]->jenis_produk }}</td>
                    <td>{{ number_format($sb[0]->jumlah_sak, 0, ".", ".") }}</td>
                    <td>{{ number_format($sb[0]->jumlah_tonase, 0, ".", ".") }}</td>
                    <td></td>
                </tr>
                {{ $total_jumlah += $sb[0]->jumlah_sak }}
                {{ $total_tonase += $sb[0]->jumlah_tonase }}
                @foreach($sb as $index => $sb)
                <?php
                if($index == 0) {
                    continue;
                }else{
                ?>
                <tr>
                    <td>{{ $sb->jenis_produk }}</td>
                    <td>{{ number_format($sb->jumlah_sak, 0, ".", ".") }}</td>
                    <td>{{ number_format($sb->jumlah_tonase, 0, ".", ".") }}</td>
                    <td></td>
                </tr>
                {{ $total_jumlah += $sb->jumlah_sak }}
                {{ $total_tonase += $sb->jumlah_tonase }}
                <?php
                }
                ?>
                @endforeach
            @endif
            @if(count($mixer) > 0)
                <tr>
                    <td rowspan="{{ count($mixer) }}">Mixer</td>
                    <td>{{ $mixer[0]->jenis_produk }}</td>
                    <td>{{ number_format($mixer[0]->jumlah_sak, 0, ".", ".") }}</td>
                    <td>{{ number_format($mixer[0]->jumlah_tonase, 0, ".", ".") }}</td>
                    <td></td>
                </tr>
                {{ $total_jumlah += $mixer[0]->jumlah_sak }}
                {{ $total_tonase += $mixer[0]->jumlah_tonase }}
                @foreach($mixer as $index => $mixer)
                <?php
                if($index == 0) {
                    continue;
                }else{
                ?>
                <tr>
                    <td>{{ $mixer->jenis_produk }}</td>
                    <td>{{ number_format($mixer->jumlah_sak, 0, ".", ".") }}</td>
                    <td>{{ number_format($mixer->jumlah_tonase, 0, ".", ".") }}</td>
                    <td></td>
                </tr>
                {{ $total_jumlah += $mixer->jumlah_sak }}
                {{ $total_tonase += $mixer->jumlah_tonase }}
                <?php
                }
                ?>
                @endforeach
            @endif
            @if(count($ra) > 0)
                <tr>
                    <td rowspan="{{ count($ra) }}">RA</td>
                    <td>{{ $ra[0]->jenis_produk }}</td>
                    <td>{{ number_format($ra[0]->jumlah_sak, 0, ".", ".") }}</td>
                    <td>{{ number_format($ra[0]->jumlah_tonase, 0, ".", ".") }}</td>
                    <td></td>
                </tr>
                {{ $total_jumlah += $ra[0]->jumlah_sak }}
                {{ $total_tonase += $ra[0]->jumlah_tonase }}
                @foreach($ra as $index => $ra)
                <?php
                if($index == 0) {
                    continue;
                }else{
                ?>
                <tr>
                    <td>{{ $ra->jenis_produk }}</td>
                    <td>{{ number_format($ra->jumlah_sak, 0, ".", ".") }}</td>
                    <td>{{ number_format($ra->jumlah_tonase, 0, ".", ".") }}</td>
                    <td></td>
                </tr>
                {{ $total_jumlah += $ra->jumlah_sak }}
                {{ $total_tonase += $ra->jumlah_tonase }}
                <?php
                }
                ?>
                @endforeach
            @endif
            @if(count($rb) > 0)
                <tr>
                    <td rowspan="{{ count($rb) }}">RB</td>
                    <td>{{ $rb[0]->jenis_produk }}</td>
                    <td>{{ number_format($rb[0]->jumlah_sak, 0, ".", ".") }}</td>
                    <td>{{ number_format($rb[0]->jumlah_tonase, 0, ".", ".") }}</td>
                    <td></td>
                </tr>
                {{ $total_jumlah += $rb[0]->jumlah_sak }}
                {{ $total_tonase += $rb[0]->jumlah_tonase }}
                @foreach($rb as $index => $rb)
                <?php
                if($index == 0) {
                    continue;
                }else{
                ?>
                <tr>
                    <td>{{ $rb->jenis_produk }}</td>
                    <td>{{ number_format($rb->jumlah_sak, 0, ".", ".") }}</td>
                    <td>{{ number_format($rb->jumlah_tonase, 0, ".", ".") }}</td>
                    <td></td>
                </tr>
                {{ $total_jumlah += $rb->jumlah_sak }}
                {{ $total_tonase += $rb->jumlah_tonase }}
                <?php
                }
                ?>
                @endforeach
            @endif
            @if(count($rc) > 0)
                <tr>
                    <td rowspan="{{ count($rc) }}">RC</td>
                    <td>{{ $rc[0]->jenis_produk }}</td>
                    <td>{{ number_format($rc[0]->jumlah_sak, 0, ".", ".") }}</td>
                    <td>{{ number_format($rc[0]->jumlah_tonase, 0, ".", ".") }}</td>
                    <td></td>
                </tr>
                {{ $total_jumlah += $rc[0]->jumlah_sak }}
                {{ $total_tonase += $rc[0]->jumlah_tonase }}
                @foreach($rc as $index => $rc)
                <?php
                if($index == 0) {
                    continue;
                }else{
                ?>
                <tr>
                    <td>{{ $rc->jenis_produk }}</td>
                    <td>{{ number_format($rc->jumlah_sak, 0, ".", ".") }}</td>
                    <td>{{ number_format($rc->jumlah_tonase, 0, ".", ".") }}</td>
                    <td></td>
                </tr>
                {{ $total_jumlah += $rc->jumlah_sak }}
                {{ $total_tonase += $rc->jumlah_tonase }}
                <?php
                }
                ?>
                @endforeach
            @endif
            @if(count($rd) > 0)
                <tr>
                    <td rowspan="{{ count($rd) }}">RD</td>
                    <td>{{ $rd[0]->jenis_produk }}</td>
                    <td>{{ number_format($rd[0]->jumlah_sak, 0, ".", ".") }}</td>
                    <td>{{ number_format($rd[0]->jumlah_tonase, 0, ".", ".") }}</td>
                    <td></td>
                </tr>
                {{ $total_jumlah += $rd[0]->jumlah_sak }}
                {{ $total_tonase += $rd[0]->jumlah_tonase }}
                @foreach($rd as $index => $rd)
                <?php
                if($index == 0) {
                    continue;
                }else{
                ?>
                <tr>
                    <td>{{ $rd->jenis_produk }}</td>
                    <td>{{ number_format($rd->jumlah_sak, 0, ".", ".") }}</td>
                    <td>{{ number_format($rd->jumlah_tonase, 0, ".", ".") }}</td>
                    <td></td>
                </tr>
                {{ $total_jumlah += $rd->jumlah_sak }}
                {{ $total_tonase += $rd->jumlah_tonase }}
                <?php
                }
                ?>
                @endforeach
            @endif
            @if(count($re) > 0)
                <tr>
                    <td rowspan="{{ count($re) }}">RE</td>
                    <td>{{ $re[0]->jenis_produk }}</td>
                    <td>{{ number_format($re[0]->jumlah_sak, 0, ".", ".") }}</td>
                    <td>{{ number_format($re[0]->jumlah_tonase, 0, ".", ".") }}</td>
                    <td></td>
                </tr>
                {{ $total_jumlah += $re[0]->jumlah_sak }}
                {{ $total_tonase += $re[0]->jumlah_tonase }}
                @foreach($re as $index => $re)
                <?php
                if($index == 0) {
                    continue;
                }else{
                ?>
                <tr>
                    <td>{{ $re->jenis_produk }}</td>
                    <td>{{ number_format($re->jumlah_sak, 0, ".", ".") }}</td>
                    <td>{{ number_format($re->jumlah_tonase, 0, ".", ".") }}</td>
                    <td></td>
                </tr>
                {{ $total_jumlah += $re->jumlah_sak }}
                {{ $total_tonase += $re->jumlah_tonase }}
                <?php
                }
                ?>
                @endforeach
            @endif
            @if(count($rf) > 0)
                <tr>
                    <td rowspan="{{ count($rf) }}">RF</td>
                    <td>{{ $rf[0]->jenis_produk }}</td>
                    <td>{{ number_format($rf[0]->jumlah_sak, 0, ".", ".") }}</td>
                    <td>{{ number_format($rf[0]->jumlah_tonase, 0, ".", ".") }}</td>
                    <td></td>
                </tr>
                {{ $total_jumlah += $rf[0]->jumlah_sak }}
                {{ $total_tonase += $rf[0]->jumlah_tonase }}
                @foreach($rf as $index => $rf)
                <?php
                if($index == 0) {
                    continue;
                }else{
                ?>
                <tr>
                    <td>{{ $rf->jenis_produk }}</td>
                    <td>{{ number_format($rf->jumlah_sak, 0, ".", ".") }}</td>
                    <td>{{ number_format($rf->jumlah_tonase, 0, ".", ".") }}</td>
                    <td></td>
                </tr>
                {{ $total_jumlah += $rf->jumlah_sak }}
                {{ $total_tonase += $rf->jumlah_tonase }}
                <?php
                }
                ?>
                @endforeach
            @endif
            @if(count($rg) > 0)
                <tr>
                    <td rowspan="{{ count($rg) }}">RG</td>
                    <td>{{ $rg[0]->jenis_produk }}</td>
                    <td>{{ number_format($rg[0]->jumlah_sak, 0, ".", ".") }}</td>
                    <td>{{ number_format($rg[0]->jumlah_tonase, 0, ".", ".") }}</td>
                    <td></td>
                </tr>
                {{ $total_jumlah += $rg[0]->jumlah_sak }}
                {{ $total_tonase += $rg[0]->jumlah_tonase }}
                @foreach($rg as $index => $rg)
                <?php
                if($index == 0) {
                    continue;
                }else{
                ?>
                <tr>
                    <td>{{ $rg->jenis_produk }}</td>
                    <td>{{ number_format($rg->jumlah_sak, 0, ".", ".") }}</td>
                    <td>{{ number_format($rg->jumlah_tonase, 0, ".", ".") }}</td>
                    <td></td>
                </tr>
                {{ $total_jumlah += $rg->jumlah_sak }}
                {{ $total_tonase += $rg->jumlah_tonase }}
                <?php
                }
                ?>
                @endforeach
            @endif
            @if(count($coating) > 0)
                <tr>
                    <td rowspan="{{ count($coating) }}">coating</td>
                    <td>{{ $coating[0]->jenis_produk }}</td>
                    <td>{{ number_format($coating[0]->jumlah_sak, 0, ".", ".") }}</td>
                    <td>{{ number_format($coating[0]->jumlah_tonase, 0, ".", ".") }}</td>
                    <td></td>
                </tr>
                {{ $total_jumlah += $coating[0]->jumlah_sak }}
                {{ $total_tonase += $coating[0]->jumlah_tonase }}
                @foreach($coating as $index => $coating)
                <?php
                if($index == 0) {
                    continue;
                }else{
                ?>
                <tr>
                    <td>{{ $coating->jenis_produk }}</td>
                    <td>{{ number_format($coating->jumlah_sak, 0, ".", ".") }}</td>
                    <td>{{ number_format($coating->jumlah_tonase, 0, ".", ".") }}</td>
                    <td></td>
                </tr>
                {{ $total_jumlah += $coating->jumlah_sak }}
                {{ $total_tonase += $coating->jumlah_tonase }}
                <?php
                }
                ?>
                @endforeach
            @endif
            <tr>
                <td colspan="2">Total</td>
                <td>{{ number_format($total_jumlah, 0, ".", ".") }}</td>
                <td>{{ number_format($total_tonase, 0, ".", ".") }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3">Kabag. Produksi</td>
                <td colspan="2">Mandor</td>
            </tr>
            <tr>
                <td colspan="3" style="height: 50px; vertical-align: bottom;"></td>
                <td colspan="2" style="height: 50px;"></td>
            </tr>
        </tbody>
    </table>
</body>
</html>