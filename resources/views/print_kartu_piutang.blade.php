<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Kartu Piutang</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        * { box-sizing: border-box; }
        body{
            margin-left: 30px;
            margin-right: 30px;
            margin-bottom: 25px;
            margin-top: 25px;
        }
        table {
            border-collapse: collapse;
        }
        table, th, td {
            text-align: left;
            padding: 5px;
        }
        h2, h4 {
            text-align: center; 
            margin: 0;
            padding: 0;
            line-height: 30px;
            height: 25px;
        }
        td input[type="checkbox"] {
            float: left;
            margin: 0 auto;
            width: 100%;
        }1
    </style>
</head>
<body>
    <h2>PT. DWI SELO GIRI MAS</h2>
    <h4>Laporan Kartu Piutang</h4>
    <div style="text-align: center; padding-top: 10px;">Tanggal : {{ date('j F Y', strtotime($from_date)) }} s.d. {{ date('j F Y', strtotime($to_date)) }}</div>
    <div style="text-align: left; padding-top: 30px;">Tanggal Cetak : {{ date('j F Y') }}</div>
    <div style="text-align: left; padding-top: 15px;">KETERANGAN PELANGGAN</div>
    <table style="width: 100%; padding-left: 0px; font-size: 14px; border: none;">
        @if($cek_cust)
            <tr>
                <td style="width:15%; text-align: left; border: none;">{{ $data_cust->custid }}</td>
                <td style="width:50%; text-align: left; border: none;">{{ $data_cust->custname }}</td>
                <td style="width:35%; text-align: left; border: none;">Kontak Phone : {{ $data_cust->phone }}</td>
            </tr>
            <tr>
                <td style="width:15%; text-align: left; border: none;"></td>
                <td style="width:50%; text-align: left; border: none;">{{ $data_cust->address }}</td>
                <td style="width:35%; text-align: left; border: none;"></td>
            </tr>
        @else
            <tr>
                <td style="width:15%; text-align: left; border: none;">SEMUA PELANGGAN</td>
            </tr>
        @endif
    </table>
    @if($cek_cust)
        <table style="width: 100%; font-size: 12px;">
            <thead style="border: 1px solid #000;">
                <tr>
                    <th style="width:10%;">Tanggal</th>
                    <th style="width:10%;">No SJ</th>
                    <th style="width:10%;">No AR</th>
                    <th style="width:10%;">No Inv</th>
                    <th style="width:30%;">Keterangan</th>
                    <th style="width:10%;">Diff Date</th>
                    <th style="width:10%;">Sub Amount</th>
                    <th style="width:10%;">Total Amount</th>
                    <th style="width:10%;">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $data)
                <tr>
                    <td>
                        @if($data->lunas == 1)
                            * {{ date('j M Y', strtotime($data->tanggal)) }}
                        @else
                            {{ date('j M Y', strtotime($data->tanggal)) }}
                        @endif
                    </td>
                    <td>
                        @if($data->nosj == null || $data->nosj == '')
                            --
                        @else
                            {{ $data->nosj }}
                        @endif
                    </td>
                    <td>
                        @if($data->noar == null || $data->noar == '')
                            --
                        @else
                            {{ $data->noar }}
                        @endif
                    </td>
                    <td>
                        @if($data->noinv == null || $data->noinv == '')
                            --
                        @else
                            {{ $data->noinv }}
                        @endif
                    </td>
                    <td>
                        @if($data->keterangan == null || $data->keterangan == '')
                            --
                        @else
                            @if($data->nosj != null || $data->nosj != '')
                                {{ $data->keterangan }} &nbsp;&nbsp;&nbsp; {{ number_format($data_arr[$data->nosj][0]['qty'], 0, ".", ".") }} &nbsp;&nbsp;&nbsp; {{ number_format($data_arr[$data->nosj][0]['price'], 0, ".", ".") }}
                            @else
                                {{ $data->keterangan }}
                            @endif
                        @endif
                    </td>
                    <td>
                        @if($data->selisih_hari == null || $data->selisih_hari == '')
                            --
                        @else
                            {{ round($data->selisih_hari) }} Hari
                        @endif
                    </td>
                    <td>
                        @if($data->sub_nominal == null || $data->sub_nominal == '')
                            --
                        @else
                            Rp {{ number_format($data->sub_nominal, 0, ".", ".") }}
                        @endif
                    </td>
                    <td>Rp {{ number_format($data->total_nominal, 0, ".", ".") }}</td>
                    <td>Rp {{ number_format($data->saldo, 0, ".", ".") }}</td>
                </tr>
                    @if($data->nosj != null || $data->nosj != '')
                        <tr>
                            <td colspan="4"></td>
                            <td>Discount</td>
                            <td></td>
                            <td>Rp {{ number_format($data_arr[$data->nosj][0]['diskon'], 0, ".", ".")  }}</td>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td>Pajak (PPn 10%)</td>
                            <td></td>
                            <td>Rp {{ number_format($data_arr[$data->nosj][0]['pajak'], 0, ".", ".")  }}</td>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td>Biaya Lain-lain</td>
                            <td colspan="4"></td>
                        </tr>
                    @else
                    <tr>
                        <table style="width: 100%; padding-left: 20px; border: none; font-size: 12px;">
                            <thead>
                                <tr>
                                    <th style="width:10%;">Tanggal</th>
                                    <th style="width:10%;">No SJ</th>
                                    <th style="width:10%;">No Inv</th>
                                    <th style="width:30%;">Produk</th>
                                    <th style="width:10%;">Diff Date</th>
                                    <th style="width:10%;">Sub Amount</th>
                                    <th style="width:10%;">Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data_arr[$data->noar] as $d)
                                    <tr>
                                        <td style="width:10%; text-align: left; border: none;">{{ $d['tanggal_do'] }}</td>
                                        <td style="width:10%; text-align: left; border: none;">{{ $d['nosj'] }}</td>
                                        <td style="width:10%; text-align: left; border: none;">{{ $d['noinv'] }}</td>
                                        <td style="width:30%; text-align: left; border: none;">{{ $d['itemname'] }}</td>
                                        <td style="width:10%; text-align: left; border: none;">{{ $d['selisih_hari'] }}</td>
                                        <td style="width:10%; text-align: left; border: none;">{{ ($d['qty'] * $d['price']) }}</td>
                                        <td style="width:10%; text-align: left; border: none;">{{ $d['tagihan'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @else    
    <?php $count = 0; ?>
    @foreach($data as $data)
    <?php $count++; ?>
    <table style="width: 100%; font-size: 12px;">
        <tbody>
            <tr>
                <th style="width: 5%">{{ $count }}</th>
                <th style="width: 70%">Customers : {{ $data->customers }}</th>
                <th style="width: 20%">Saldo : Rp {{ number_format($data->saldo, 0, ".", ".") }}</th>
            </tr>
        </tbody>
    </table>
    <table style="width: 100%; padding-left: 20px; border: none; font-size: 10px; page-break-inside: auto;">
        <thead>
            <tr>
                <th style="width:10%;">Tanggal</th>
                <th style="width:10%;">No SJ</th>
                <th style="width:10%;">No AR</th>
                <th style="width:10%;">No Inv</th>
                <th style="width:30%;">Keterangan</th>
                <th style="width:10%;">Diff Date</th>
                <th style="width:10%;">Sub Amount</th>
                <th style="width:10%;">Total Amount</th>
                <th style="width:10%;">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data_arr[$data->custid] as $d)
            <tr>
                <td>
                    @if($d['lunas'] == 1)
                    * {{ date('j M Y', strtotime($d['tanggal'])) }}
                    @else
                    {{ date('j M Y', strtotime($d['tanggal'])) }}
                    @endif
                </td>
                <td>
                    @if($d['nosj'] == null || $d['nosj'] == '')
                    --
                    @else
                    {{ $d['nosj'] }}
                    @endif
                </td>
                <td>
                    @if($d['noar'] == null || $d['noar'] == '')
                    --
                    @else
                    {{ $d['noar'] }}
                    @endif
                </td>
                <td>
                    @if($d['noinv'] == null || $d['noinv'] == '')
                    --
                    @else
                    {{ $d['noinv'] }}
                    @endif
                </td>
                <td>
                    @if($d['keterangan'] == null || $d['keterangan'] == '')
                    --
                    @else
                    @if($d['nosj'] != null || $d['nosj'] != '')
                    {{ $d['keterangan'] }} &nbsp;&nbsp;&nbsp; {{ number_format($data_arr2[$d['nosj']][0]['qty'], 0, ".", ".") }} &nbsp;&nbsp;&nbsp; {{ number_format($data_arr2[$d['nosj']][0]['price'], 0, ".", ".") }}
                    @else
                    {{ $d['keterangan'] }}
                    @endif
                    @endif
                </td>
                <td>
                    @if($d['selisih_hari'] == null || $d['selisih_hari'] == '')
                    --
                    @else
                    {{ round($d['selisih_hari']) }} Hari
                    @endif
                </td>
                <td>
                    @if($d['sub_nominal'] == null || $d['sub_nominal'] == '')
                    --
                    @else
                    Rp {{ number_format($d['sub_nominal'], 0, ".", ".") }}
                    @endif
                </td>
                <td>Rp {{ number_format($d['total_nominal'], 0, ".", ".") }}</td>
                <td>Rp {{ number_format($d['saldo'], 0, ".", ".") }}</td>
            </tr>
            @if($d['nosj'] != null || $d['nosj'] != '')
            <tr>
                <td colspan="4"></td>
                <td>Discount</td>
                <td></td>
                <td>Rp {{ number_format($data_arr2[$d['nosj']][0]['diskon'], 0, ".", ".")  }}</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td>Pajak (PPn 10%)</td>
                <td></td>
                <td>Rp {{ number_format($data_arr2[$d['nosj']][0]['pajak'], 0, ".", ".")  }}</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td>Biaya Lain-lain</td>
                <td colspan="4"></td>
            </tr>
            @else
            <tr>
                <table style="width: 100%; padding-left: 40px; border: none;">
                    <thead>
                        <tr>
                            <th style="width:10%;">Tanggal</th>
                            <th style="width:10%;">No SJ</th>
                            <th style="width:10%;">No Inv</th>
                            <th style="width:30%;">Produk</th>
                            <th style="width:10%;">Diff Date</th>
                            <th style="width:10%;">Sub Amount</th>
                            <th style="width:10%;">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data_arr2[$d['noar']] as $d2)
                        <tr>
                            <td style="width:10%; text-align: left; border: none;">{{ $d2['tanggal_do'] }}</td>
                            <td style="width:10%; text-align: left; border: none;">{{ $d2['nosj'] }}</td>
                            <td style="width:10%; text-align: left; border: none;">{{ $d2['noinv'] }}</td>
                            <td style="width:30%; text-align: left; border: none;">{{ $d2['itemname'] }}</td>
                            <td style="width:10%; text-align: left; border: none;">{{ $d2['selisih_hari'] }}</td>
                            <td style="width:10%; text-align: left; border: none;">{{ ($d2['qty'] * $d2['price']) }}</td>
                            <td style="width:10%; text-align: left; border: none;">{{ $d2['tagihan'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
    @endforeach
    @endif
    <script type="text/php">
    if (isset($pdf)) {
        $pdf->page_script('
            $text = sprintf(_("Page %d/%d"),  $PAGE_NUM, $PAGE_COUNT);
            // Uncomment the following line if you use a Laravel-based i18n
            //$text = __("Page :pageNum/:pageCount", ["pageNum" => $PAGE_NUM, "pageCount" => $PAGE_COUNT]);
            $font = null;
            $size = 9;
            $color = array(0,0,0);
            $word_space = 0.0;  //  default
            $char_space = 0.0;  //  default
            $angle = 0.0;   //  default

            // Compute text width to center correctly
            $textWidth = $fontMetrics->getTextWidth($text, $font, $size);

            $x = ($pdf->get_width() - $textWidth) / 2;
            $y = $pdf->get_height() - 35;

            $pdf->text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        '); // End of page_script
    }
    </script>
</body>
</html>