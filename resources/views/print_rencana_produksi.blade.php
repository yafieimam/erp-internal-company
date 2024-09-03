<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Rencana Produksi</title>
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
            <td colspan="2"><h2 style="margin:10px; margin-bottom: 0;">WORK ORDER SHEET</h2></td>
            <td width="10%"><h2 style="margin:10px; margin-bottom: 0;">FP</h2></td>
        </tr>
    </table>
    <table style="width: 100%;">
        <tr>
            <td><h4 style="margin:10px;">Tgl : {{ $data[0]->tanggal_rencana }}</h4></td>
            <td><h4 style="margin:10px;">WOS : {{ $data[0]->nomor_rencana_produksi }}</h4></td>
            <td width="40%" style="text-align: left;"><h4 style="margin:10px;">Keterangan : {{ $data[0]->keterangan }}</h4></td>
        </tr>
    </table>
    <table id="table_data" style="width: 100%;">
        <thead>
            <tr>
                <th rowspan="2">Nama Mesin</th>
                <th colspan="2">SA</th>
                <th colspan="2">SB</th>
                <th colspan="2">Mixer</th>
                <th colspan="2">RA</th>
                <th colspan="2">RB</th>
                <th colspan="2">RC</th>
                <th colspan="2">RD</th>
                <th colspan="2">RE</th>
                <th colspan="2">RF</th>
                <th colspan="2">RG</th>
            </tr>
            <tr>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Jenis</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @for($i = 0; $i < count($maxi); $i++)
                @if($i == 0)
                    <tr>
                        <td rowspan="{{ count($maxi) }}">Karung</td>
                        <td>{{ isset($sa[$i]->jenis_produk) ? $sa[$i]->jenis_produk : '' }}</td>
                        <td>{{ isset($sa[$i]->jumlah_sak) ? $sa[$i]->jumlah_sak : '' }}</td>
                        <td>{{ isset($sb[$i]->jenis_produk) ? $sb[$i]->jenis_produk : '' }}</td>
                        <td>{{ isset($sb[$i]->jumlah_sak) ? $sb[$i]->jumlah_sak : '' }}</td>
                        <td>{{ isset($mixer[$i]->jenis_produk) ? $mixer[$i]->jenis_produk : '' }}</td>
                        <td>{{ isset($mixer[$i]->jumlah_sak) ? $mixer[$i]->jumlah_sak : '' }}</td>
                        <td>{{ isset($ra[$i]->jenis_produk) ? $ra[$i]->jenis_produk : '' }}</td>
                        <td>{{ isset($ra[$i]->jumlah_sak) ? $ra[$i]->jumlah_sak : '' }}</td>
                        <td>{{ isset($rb[$i]->jenis_produk) ? $rb[$i]->jenis_produk : '' }}</td>
                        <td>{{ isset($rb[$i]->jumlah_sak) ? $rb[$i]->jumlah_sak : '' }}</td>
                        <td>{{ isset($rc[$i]->jenis_produk) ? $rc[$i]->jenis_produk : '' }}</td>
                        <td>{{ isset($rc[$i]->jumlah_sak) ? $rc[$i]->jumlah_sak : '' }}</td>
                        <td>{{ isset($rd[$i]->jenis_produk) ? $rd[$i]->jenis_produk : '' }}</td>
                        <td>{{ isset($rd[$i]->jumlah_sak) ? $rd[$i]->jumlah_sak : '' }}</td>
                        <td>{{ isset($re[$i]->jenis_produk) ? $re[$i]->jenis_produk : '' }}</td>
                        <td>{{ isset($re[$i]->jumlah_sak) ? $re[$i]->jumlah_sak : '' }}</td>
                        <td>{{ isset($rf[$i]->jenis_produk) ? $rf[$i]->jenis_produk : '' }}</td>
                        <td>{{ isset($rf[$i]->jumlah_sak) ? $rf[$i]->jumlah_sak : '' }}</td>
                        <td>{{ isset($rg[$i]->jenis_produk) ? $rg[$i]->jenis_produk : '' }}</td>
                        <td>{{ isset($rg[$i]->jumlah_sak) ? $rg[$i]->jumlah_sak : '' }}</td>
                    </tr>
                @else
                    <tr>
                        <td>{{ isset($sa[$i]->jenis_produk) ? $sa[$i]->jenis_produk : '' }}</td>
                        <td>{{ isset($sa[$i]->jumlah_sak) ? $sa[$i]->jumlah_sak : '' }}</td>
                        <td>{{ isset($sb[$i]->jenis_produk) ? $sb[$i]->jenis_produk : '' }}</td>
                        <td>{{ isset($sb[$i]->jumlah_sak) ? $sb[$i]->jumlah_sak : '' }}</td>
                        <td>{{ isset($mixer[$i]->jenis_produk) ? $mixer[$i]->jenis_produk : '' }}</td>
                        <td>{{ isset($mixer[$i]->jumlah_sak) ? $mixer[$i]->jumlah_sak : '' }}</td>
                        <td>{{ isset($ra[$i]->jenis_produk) ? $ra[$i]->jenis_produk : '' }}</td>
                        <td>{{ isset($ra[$i]->jumlah_sak) ? $ra[$i]->jumlah_sak : '' }}</td>
                        <td>{{ isset($rb[$i]->jenis_produk) ? $rb[$i]->jenis_produk : '' }}</td>
                        <td>{{ isset($rb[$i]->jumlah_sak) ? $rb[$i]->jumlah_sak : '' }}</td>
                        <td>{{ isset($rc[$i]->jenis_produk) ? $rc[$i]->jenis_produk : '' }}</td>
                        <td>{{ isset($rc[$i]->jumlah_sak) ? $rc[$i]->jumlah_sak : '' }}</td>
                        <td>{{ isset($rd[$i]->jenis_produk) ? $rd[$i]->jenis_produk : '' }}</td>
                        <td>{{ isset($rd[$i]->jumlah_sak) ? $rd[$i]->jumlah_sak : '' }}</td>
                        <td>{{ isset($re[$i]->jenis_produk) ? $re[$i]->jenis_produk : '' }}</td>
                        <td>{{ isset($re[$i]->jumlah_sak) ? $re[$i]->jumlah_sak : '' }}</td>
                        <td>{{ isset($rf[$i]->jenis_produk) ? $rf[$i]->jenis_produk : '' }}</td>
                        <td>{{ isset($rf[$i]->jumlah_sak) ? $rf[$i]->jumlah_sak : '' }}</td>
                        <td>{{ isset($rg[$i]->jenis_produk) ? $rg[$i]->jenis_produk : '' }}</td>
                        <td>{{ isset($rg[$i]->jumlah_sak) ? $rg[$i]->jumlah_sak : '' }}</td>
                    </tr>
                @endif
            @endfor
        </tbody>
    </table>
    <br>
    <table style="width: 100%;">
        <tr>
            <td><h2 style="margin:10px; margin-bottom: 0;">PROSES</h2></td>
        </tr>
    </table>
    <br>
    <table id="table_data" style="width: 100%;">
        <thead>
            <tr>
                <th>Nama Mesin</th>
                <th>SA</th>
                <th>SB</th>
                <th>Mixer</th>
                <th>RA</th>
                <th>RB</th>
                <th>RC</th>
                <th>RD</th>
                <th>RE</th>
                <th>RF</th>
                <th>RG</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>RPM Std</td>
                <td>{{ isset($sa_spek[0]->rpm) ? $sa_spek[0]->rpm : '' }}</td>
                <td>{{ isset($sb_spek[0]->rpm) ? $sb_spek[0]->rpm : '' }}</td>
                <td>{{ isset($mixer_spek[0]->rpm) ? $mixer_spek[0]->rpm : '' }}</td>
                <td>{{ isset($ra_spek[0]->rpm) ? $ra_spek[0]->rpm : '' }}</td>
                <td>{{ isset($rb_spek[0]->rpm) ? $rb_spek[0]->rpm : '' }}</td>
                <td>{{ isset($rc_spek[0]->rpm) ? $rc_spek[0]->rpm : '' }}</td>
                <td>{{ isset($rd_spek[0]->rpm) ? $rd_spek[0]->rpm : '' }}</td>
                <td>{{ isset($re_spek[0]->rpm) ? $re_spek[0]->rpm : '' }}</td>
                <td>{{ isset($rf_spek[0]->rpm) ? $rf_spek[0]->rpm : '' }}</td>
                <td>{{ isset($rg_spek[0]->rpm) ? $rg_spek[0]->rpm : '' }}</td>
            </tr>
        </tbody>
    </table>
    <br>
    <table style="width: 100%;">
        <tr>
            <td><h2 style="margin:10px; margin-bottom: 0;">SPESIFIKASI</h2></td>
        </tr>
    </table>
    <br>
    <table id="table_data" style="width: 100%;">
        <thead>
            <tr>
                <th>Nama Mesin</th>
                <th>SA</th>
                <th>SB</th>
                <th>Mixer</th>
                <th>RA</th>
                <th>RB</th>
                <th>RC</th>
                <th>RD</th>
                <th>RE</th>
                <th>RF</th>
                <th>RG</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Particle Size</td>
                <td>{{ isset($sa_spek[0]->particle_size) ? $sa_spek[0]->particle_size : '' }}</td>
                <td>{{ isset($sb_spek[0]->particle_size) ? $sb_spek[0]->particle_size : '' }}</td>
                <td>{{ isset($mixer_spek[0]->particle_size) ? $mixer_spek[0]->particle_size : '' }}</td>
                <td>{{ isset($ra_spek[0]->particle_size) ? $ra_spek[0]->particle_size : '' }}</td>
                <td>{{ isset($rb_spek[0]->particle_size) ? $rb_spek[0]->particle_size : '' }}</td>
                <td>{{ isset($rc_spek[0]->particle_size) ? $rc_spek[0]->particle_size : '' }}</td>
                <td>{{ isset($rd_spek[0]->particle_size) ? $rd_spek[0]->particle_size : '' }}</td>
                <td>{{ isset($re_spek[0]->particle_size) ? $re_spek[0]->particle_size : '' }}</td>
                <td>{{ isset($rf_spek[0]->particle_size) ? $rf_spek[0]->particle_size : '' }}</td>
                <td>{{ isset($rg_spek[0]->particle_size) ? $rg_spek[0]->particle_size : '' }}</td>
            </tr>
            <tr>
                <td>SSA</td>
                <td>{{ isset($sa_spek[0]->ssa) ? $sa_spek[0]->ssa : '' }}</td>
                <td>{{ isset($sb_spek[0]->ssa) ? $sb_spek[0]->ssa : '' }}</td>
                <td>{{ isset($mixer_spek[0]->ssa) ? $mixer_spek[0]->ssa : '' }}</td>
                <td>{{ isset($ra_spek[0]->ssa) ? $ra_spek[0]->ssa : '' }}</td>
                <td>{{ isset($rb_spek[0]->ssa) ? $rb_spek[0]->ssa : '' }}</td>
                <td>{{ isset($rc_spek[0]->ssa) ? $rc_spek[0]->ssa : '' }}</td>
                <td>{{ isset($rd_spek[0]->ssa) ? $rd_spek[0]->ssa : '' }}</td>
                <td>{{ isset($re_spek[0]->ssa) ? $re_spek[0]->ssa : '' }}</td>
                <td>{{ isset($rf_spek[0]->ssa) ? $rf_spek[0]->ssa : '' }}</td>
                <td>{{ isset($rg_spek[0]->ssa) ? $rg_spek[0]->ssa : '' }}</td>
            </tr>
            <tr>
                <td>Whiteness</td>
                <td>{{ isset($sa_spek[0]->whiteness) ? $sa_spek[0]->whiteness : '' }}</td>
                <td>{{ isset($sb_spek[0]->whiteness) ? $sb_spek[0]->whiteness : '' }}</td>
                <td>{{ isset($mixer_spek[0]->whiteness) ? $mixer_spek[0]->whiteness : '' }}</td>
                <td>{{ isset($ra_spek[0]->whiteness) ? $ra_spek[0]->whiteness : '' }}</td>
                <td>{{ isset($rb_spek[0]->whiteness) ? $rb_spek[0]->whiteness : '' }}</td>
                <td>{{ isset($rc_spek[0]->whiteness) ? $rc_spek[0]->whiteness : '' }}</td>
                <td>{{ isset($rd_spek[0]->whiteness) ? $rd_spek[0]->whiteness : '' }}</td>
                <td>{{ isset($re_spek[0]->whiteness) ? $re_spek[0]->whiteness : '' }}</td>
                <td>{{ isset($rf_spek[0]->whiteness) ? $rf_spek[0]->whiteness : '' }}</td>
                <td>{{ isset($rg_spek[0]->whiteness) ? $rg_spek[0]->whiteness : '' }}</td>
            </tr>
            <tr>
                <td>Residue</td>
                <td>{{ isset($sa_spek[0]->residue) ? $sa_spek[0]->residue : '' }}</td>
                <td>{{ isset($sb_spek[0]->residue) ? $sb_spek[0]->residue : '' }}</td>
                <td>{{ isset($mixer_spek[0]->residue) ? $mixer_spek[0]->residue : '' }}</td>
                <td>{{ isset($ra_spek[0]->residue) ? $ra_spek[0]->residue : '' }}</td>
                <td>{{ isset($rb_spek[0]->residue) ? $rb_spek[0]->residue : '' }}</td>
                <td>{{ isset($rc_spek[0]->residue) ? $rc_spek[0]->residue : '' }}</td>
                <td>{{ isset($rd_spek[0]->residue) ? $rd_spek[0]->residue : '' }}</td>
                <td>{{ isset($re_spek[0]->residue) ? $re_spek[0]->residue : '' }}</td>
                <td>{{ isset($rf_spek[0]->residue) ? $rf_spek[0]->residue : '' }}</td>
                <td>{{ isset($rg_spek[0]->residue) ? $rg_spek[0]->residue : '' }}</td>
            </tr>
            <tr>
                <td>Moisture</td>
                <td>{{ isset($sa_spek[0]->moisture) ? $sa_spek[0]->moisture : '' }}</td>
                <td>{{ isset($sb_spek[0]->moisture) ? $sb_spek[0]->moisture : '' }}</td>
                <td>{{ isset($mixer_spek[0]->moisture) ? $mixer_spek[0]->moisture : '' }}</td>
                <td>{{ isset($ra_spek[0]->moisture) ? $ra_spek[0]->moisture : '' }}</td>
                <td>{{ isset($rb_spek[0]->moisture) ? $rb_spek[0]->moisture : '' }}</td>
                <td>{{ isset($rc_spek[0]->moisture) ? $rc_spek[0]->moisture : '' }}</td>
                <td>{{ isset($rd_spek[0]->moisture) ? $rd_spek[0]->moisture : '' }}</td>
                <td>{{ isset($re_spek[0]->moisture) ? $re_spek[0]->moisture : '' }}</td>
                <td>{{ isset($rf_spek[0]->moisture) ? $rf_spek[0]->moisture : '' }}</td>
                <td>{{ isset($rg_spek[0]->moisture) ? $rg_spek[0]->moisture : '' }}</td>
            </tr>
        </tbody>
    </table>
    <br>
    <table style="width: 100%; border: 1px solid #000;">
        <tr>
            <td width="33%" style="height: 100px; border-right: 1px solid #000;">&nbsp;</td>
            <td width="33%" style="height: 100px; border-right: 1px solid #000;">&nbsp;</td>
            <td width="33%" style="height: 100px;">&nbsp;</td>
        </tr>
        <tr>
            <td style="border-right: 1px solid #000;"><h4 style="margin:10px; margin-bottom: 0;">Kabag. Batu</h4></td>
            <td style="border-right: 1px solid #000;"><h4 style="margin:10px; margin-bottom: 0;">Kabag. Teknik</h4></td>
            <td><h4 style="margin:10px; margin-bottom: 0;">Kabag. Produksi</h4></td>
        </tr>
    </table>
</body>
</html>