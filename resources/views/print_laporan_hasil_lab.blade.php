<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Laporan Hasil Lab</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page { margin: 15px; }
        * { box-sizing: border-box; }
        body{
            margin-left: 30px;
            margin-right: 30px;
        }
        .header-comp{
            margin-top: 2px;
            margin-bottom: 0;
        }
        .header{
            font-weight: 900;
        }
        .page-break {
            page-break-after: always;
        }
        table {
            border-collapse: collapse;
            font-size: 9px;
        }
        table, th, td {
            border: 1px solid #000;
            padding: 5px;
        }
    </style>
</head>
<body>
    <table style="width: 100%;">
        <tr>
            <td style="border-right: none;">Tanggal : {{ $tanggal }}</td>
            <td style="border-right: none;">Referensi : {{ $referensi }}</td>
            <td style="width: 50%; text-align: center;">Laporan Hasil Lab</td>
        </tr>
    </table>
    <table style="width: 100%;">
        <thead>
            <tr>
              <th style="text-align: center; width: 25%;">Rencana Produksi</th>
              <th style="text-align: center;">Mesin<br>SA, SB, Mixer</th>
              <th style="text-align: center;">Mesin<br>RA, RB</th>
              <th style="text-align: center; width: 30%;">Mesin<br>RC, RD, RE, RF, RG</th>
          </tr>
        </thead>
        <tbody>
            <tr>
                <th style="vertical-align : middle; text-align: center;">Whiteness (%)</th>
                <td style="vertical-align : middle; text-align: center;">{{ str_replace('.', ',', $data_spek_a->whiteness) }}</td>
                <td style="vertical-align : middle; text-align: center;">{{ str_replace('.', ',', $data_spek_b->whiteness) }}</td>
                <td style="vertical-align : middle; text-align: center;">{{ str_replace('.', ',', $data_spek_c->whiteness) }}</td>
            </tr>
            <tr>
                <th style="vertical-align : middle; text-align: center;">Moisture (%)</th>
                <td style="vertical-align : middle; text-align: center;">{{ str_replace('.', ',', $data_spek_a->moisture) }}</td>
                <td style="vertical-align : middle; text-align: center;">{{ str_replace('.', ',', $data_spek_b->moisture) }}</td>
                <td style="vertical-align : middle; text-align: center;">{{ str_replace('.', ',', $data_spek_c->moisture) }}</td>
            </tr>
            <tr>
                <th style="vertical-align : middle; text-align: center;">Residue Max (%)</th>
                <td style="vertical-align : middle; text-align: center;">{{ str_replace('.', ',', $data_spek_a->residue_max) }}</td>
                <td style="vertical-align : middle; text-align: center;">{{ str_replace('.', ',', $data_spek_b->residue_max) }}</td>
                <td style="vertical-align : middle; text-align: center;">{{ str_replace('.', ',', $data_spek_c->residue_max) }}</td>
            </tr>
      </tbody>
    </table>
    <table style="width: 100%; font-size: 8px;">
        <thead>
            <tr>
                <th style="vertical-align : middle; text-align: center;" rowspan="2" colspan="2">Mesin</th>
                <th style="vertical-align : middle; text-align: center; width:10%;" rowspan="2">Mesh</th>
                <th style="vertical-align : middle; text-align: center; width:10%;" rowspan="2">RPM</th>
                <th style="vertical-align : middle; text-align: center; width:12%;" colspan="2">SSA</th>
                <th style="vertical-align : middle; text-align: center;" colspan="2">D-50</th>
                <th style="vertical-align : middle; text-align: center;" colspan="2">D-98</th>
                <th style="text-align: center;" colspan="2">Whiteness</th>
                <th style="vertical-align : middle; text-align: center; width:10%;" rowspan="2">Moisture</th>
                <th style="vertical-align : middle; text-align: center; width:10%;" colspan="2">Residue</th>
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
        <tbody>
            @foreach($data_lab as $key=>$value)
                @foreach($value as $keys=>$values)
                    @if($keys == 0)
                        <tr>
                            <td style="vertical-align : middle; text-align: center; width: 8%;" rowspan="{{ count($value) }}">{{ $key }}</td>
                            <td style="vertical-align : middle; text-align: center;">{{ date("h:i", strtotime($values['jam_waktu'])) }}</td>
                            <td style="vertical-align : middle; text-align: center;">{{ number_format($values['mesh'], 0, ".", ".") }}</td>
                            <td style="vertical-align : middle; text-align: center;">
                                @if($values['rpm'] == null || $values['rpm'] == 0)
                                    -
                                @else
                                    {{ number_format($values['rpm'], 0, ".", ".") }}
                                @endif
                            </td>
                            <td style="vertical-align : middle; text-align: center;">
                                @if($values['std_ssa'] == null || $values['std_ssa'] == 0)
                                    -
                                @else
                                    {{ number_format($values['std_ssa'], 0, ".", ".") }}
                                @endif
                            </td>
                            @if($values['std_ssa'] > $values['ssa'])
                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">
                                @if($values['ssa'] == null || $values['ssa'] == 0)
                                    -
                                @else
                                    {{ number_format($values['ssa'], 0, ".", ".") }}
                                @endif
                            </td>
                            @else
                            <td style="vertical-align : middle; text-align: center;">
                                @if($values['ssa'] == null || $values['ssa'] == 0)
                                    -
                                @else
                                    {{ number_format($values['ssa'], 0, ".", ".") }}
                                @endif
                            </td>
                            @endif
                            <td style="vertical-align : middle; text-align: center;">
                                @if($values['std_d50'] == null || $values['std_d50'] == 0)
                                    -
                                @else
                                    @if(floor($values['std_d50']))
                                        {{ str_replace('.', ',', number_format($values['std_d50'], 1)) }}
                                    @else
                                        {{ str_replace('.', ',', $values['std_d50']) }}
                                    @endif
                                @endif
                            </td>
                            @if($values['std_d50'] < $values['d50'])
                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">
                                @if($values['d50'] == null || $values['d50'] == 0)
                                    -
                                @else
                                    @if(floor($values['d50']))
                                        {{ str_replace('.', ',', number_format($values['d50'], 1)) }}
                                    @else
                                        {{ str_replace('.', ',', $values['d50']) }}
                                    @endif
                                @endif
                            </td>
                            @else
                            <td style="vertical-align : middle; text-align: center;">
                                @if($values['d50'] == null || $values['d50'] == 0)
                                    -
                                @else
                                    @if(floor($values['d50']))
                                        {{ str_replace('.', ',', number_format($values['d50'], 1)) }}
                                    @else
                                        {{ str_replace('.', ',', $values['d50']) }}
                                    @endif
                                @endif
                            </td>
                            @endif
                            <td style="vertical-align : middle; text-align: center;">
                                @if($values['std_d98'] == null || $values['std_d98'] == 0)
                                    -
                                @else
                                    @if(floor($values['std_d98']))
                                        {{ str_replace('.', ',', number_format($values['std_d98'], 1)) }}
                                    @else
                                        {{ str_replace('.', ',', $values['std_d98']) }}
                                    @endif
                                @endif
                            </td>
                            @if($values['std_d98'] < $values['d98'])
                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">
                                @if($values['d98'] == null || $values['d98'] == 0)
                                    -
                                @else
                                    @if(floor($values['d98']))
                                        {{ str_replace('.', ',', number_format($values['d98'], 1)) }}
                                    @else
                                        {{ str_replace('.', ',', $values['d98']) }}
                                    @endif
                                @endif
                            </td>
                            @else
                            <td style="vertical-align : middle; text-align: center;">
                                @if($values['d98'] == null || $values['d98'] == 0)
                                    -
                                @else
                                    @if(floor($values['d98']))
                                        {{ str_replace('.', ',', number_format($values['d98'], 1)) }}
                                    @else
                                        {{ str_replace('.', ',', $values['d98']) }}
                                    @endif
                                @endif
                            </td>
                            @endif
                            @if($values['spek_whiteness'] > $values['cie86'])
                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">
                                @if($values['cie86'] == null || $values['cie86'] == 0)
                                    -
                                @else
                                    @if(floor($values['cie86']))
                                        {{ str_replace('.', ',', number_format($values['cie86'], 1)) }}
                                    @else
                                        {{ str_replace('.', ',', $values['cie86']) }}
                                    @endif
                                @endif
                            </td>
                            @else
                            <td style="vertical-align : middle; text-align: center;">
                                @if($values['cie86'] == null || $values['cie86'] == 0)
                                    -
                                @else
                                    @if(floor($values['cie86']))
                                        {{ str_replace('.', ',', number_format($values['cie86'], 1)) }}
                                    @else
                                        {{ str_replace('.', ',', $values['cie86']) }}
                                    @endif
                                @endif
                            </td>
                            @endif
                            @if($values['spek_whiteness'] > $values['iso2470'])
                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">
                                @if($values['iso2470'] == null || $values['iso2470'] == 0)
                                    -
                                @else
                                    @if(floor($values['iso2470']))
                                        {{ str_replace('.', ',', number_format($values['iso2470'], 1)) }}
                                    @else
                                        {{ str_replace('.', ',', $values['iso2470']) }}
                                    @endif
                                @endif
                            </td>
                            @else
                            <td style="vertical-align : middle; text-align: center;">
                                @if($values['iso2470'] == null || $values['iso2470'] == 0)
                                    -
                                @else
                                    @if(floor($values['iso2470']))
                                        {{ str_replace('.', ',', number_format($values['iso2470'], 1)) }}
                                    @else
                                        {{ str_replace('.', ',', $values['iso2470']) }}
                                    @endif
                                @endif
                            </td>
                            @endif
                            @if($values['spek_moisture'] < $values['moisture'])
                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">
                                @if($values['moisture'] == null || $values['moisture'] == 0)
                                    -
                                @else
                                    @if(floor($values['moisture']))
                                        {{ str_replace('.', ',', number_format($values['moisture'], 1)) }}
                                    @else
                                        {{ str_replace('.', ',', $values['moisture']) }}
                                    @endif
                                @endif
                            </td>
                            @else
                            <td style="vertical-align : middle; text-align: center;">
                                @if($values['moisture'] == null || $values['moisture'] == 0)
                                    -
                                @else
                                    @if(floor($values['moisture']))
                                        {{ str_replace('.', ',', number_format($values['moisture'], 1)) }}
                                    @else
                                        {{ str_replace('.', ',', $values['moisture']) }}
                                    @endif
                                @endif
                            </td>
                            @endif
                            <td style="vertical-align : middle; text-align: center;">
                                @if($values['standart_residue'] == null || $values['standart_residue'] == 0)
                                    -
                                @else
                                    {{ str_replace('.', ',', $values['standart_residue']) }}
                                @endif
                            </td>
                            @if($values['spek_residue'] < $values['residue'])
                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">
                                @if($values['residue'] == null || $values['residue'] == 0)
                                    -
                                @else
                                    @if(floor($values['residue']))
                                        {{ str_replace('.', ',', number_format($values['residue'], 1)) }}
                                    @else
                                        {{ str_replace('.', ',', $values['residue']) }}
                                    @endif
                                @endif
                            </td>
                            @else
                            <td style="vertical-align : middle; text-align: center;">
                                @if($values['residue'] == null || $values['residue'] == 0)
                                    -
                                @else
                                    @if(floor($values['residue']))
                                        {{ str_replace('.', ',', number_format($values['residue'], 1)) }}
                                    @else
                                        {{ str_replace('.', ',', $values['residue']) }}
                                    @endif
                                @endif
                            </td>
                            @endif
                        </tr>
                    @else
                        <tr>
                            <td style="vertical-align : middle; text-align: center;">{{ date("h:i", strtotime($values['jam_waktu'])) }}</td>
                            <td style="vertical-align : middle; text-align: center;">{{ number_format($values['mesh'], 0, ".", ".") }}</td>
                            <td style="vertical-align : middle; text-align: center;">
                                @if($values['rpm'] == null || $values['rpm'] == 0)
                                    -
                                @else
                                    {{ number_format($values['rpm'], 0, ".", ".") }}
                                @endif
                            </td>
                            <td style="vertical-align : middle; text-align: center;">
                                @if($values['ssa'] == null || $values['ssa'] == 0)
                                    -
                                @else
                                    {{ number_format($values['ssa'], 0, ".", ".") }}
                                @endif
                            </td>
                            <td style="vertical-align : middle; text-align: center;">
                                @if($values['d50'] == null || $values['d50'] == 0)
                                    -
                                @else
                                    @if(floor($values['d50']))
                                        {{ str_replace('.', ',', number_format($values['d50'], 1)) }}
                                    @else
                                        {{ str_replace('.', ',', $values['d50']) }}
                                    @endif
                                @endif
                            </td>
                            <td style="vertical-align : middle; text-align: center;">
                                @if($values['d98'] == null || $values['d98'] == 0)
                                    -
                                @else
                                   @if(floor($values['d98']))
                                        {{ str_replace('.', ',', number_format($values['d98'], 1)) }}
                                    @else
                                        {{ str_replace('.', ',', $values['d98']) }}
                                    @endif
                                @endif
                            </td>
                            @if($values['spek_whiteness'] > $values['cie86'])
                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">
                                @if($values['cie86'] == null || $values['cie86'] == 0)
                                    -
                                @else
                                    @if(floor($values['cie86']))
                                        {{ str_replace('.', ',', number_format($values['cie86'], 1)) }}
                                    @else
                                        {{ str_replace('.', ',', $values['cie86']) }}
                                    @endif
                                @endif
                            </td>
                            @else
                            <td style="vertical-align : middle; text-align: center;">
                                @if($values['cie86'] == null || $values['cie86'] == 0)
                                    -
                                @else
                                    @if(floor($values['cie86']))
                                        {{ str_replace('.', ',', number_format($values['cie86'], 1)) }}
                                    @else
                                        {{ str_replace('.', ',', $values['cie86']) }}
                                    @endif
                                @endif
                            </td>
                            @endif
                            @if($values['spek_whiteness'] > $values['iso2470'])
                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">
                                @if($values['iso2470'] == null || $values['iso2470'] == 0)
                                    -
                                @else
                                    @if(floor($values['iso2470']))
                                        {{ str_replace('.', ',', number_format($values['iso2470'], 1)) }}
                                    @else
                                        {{ str_replace('.', ',', $values['iso2470']) }}
                                    @endif
                                @endif
                            </td>
                            @else
                            <td style="vertical-align : middle; text-align: center;">
                                @if($values['iso2470'] == null || $values['iso2470'] == 0)
                                    -
                                @else
                                    @if(floor($values['iso2470']))
                                        {{ str_replace('.', ',', number_format($values['iso2470'], 1)) }}
                                    @else
                                        {{ str_replace('.', ',', $values['iso2470']) }}
                                    @endif
                                @endif
                            </td>
                            @endif
                            @if($values['spek_moisture'] < $values['moisture'])
                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">
                                @if($values['moisture'] == null || $values['moisture'] == 0)
                                    -
                                @else
                                    @if(floor($values['moisture']))
                                        {{ str_replace('.', ',', number_format($values['moisture'], 1)) }}
                                    @else
                                        {{ str_replace('.', ',', $values['moisture']) }}
                                    @endif
                                @endif
                            </td>
                            @else
                            <td style="vertical-align : middle; text-align: center;">
                                @if($values['moisture'] == null || $values['moisture'] == 0)
                                    -
                                @else
                                    @if(floor($values['moisture']))
                                        {{ str_replace('.', ',', number_format($values['moisture'], 1)) }}
                                    @else
                                        {{ str_replace('.', ',', $values['moisture']) }}
                                    @endif
                                @endif
                            </td>
                            @endif
                            <td style="vertical-align : middle; text-align: center;">
                                @if($values['standart_residue'] == null || $values['standart_residue'] == 0)
                                    -
                                @else
                                    {{ str_replace('.', ',', $values['standart_residue']) }}
                                @endif
                            </td>
                            @if($values['spek_residue'] < $values['residue'])
                            <td style="vertical-align : middle; text-align: center; background-color: #ffea8c;">
                                @if($values['residue'] == null || $values['residue'] == 0)
                                    -
                                @else
                                    @if(floor($values['residue']))
                                        {{ str_replace('.', ',', number_format($values['residue'], 1)) }}
                                    @else
                                        {{ str_replace('.', ',', $values['residue']) }}
                                    @endif
                                @endif
                            </td>
                            @else
                            <td style="vertical-align : middle; text-align: center;">
                                @if($values['residue'] == null || $values['residue'] == 0)
                                    -
                                @else
                                    @if(floor($values['residue']))
                                        {{ str_replace('.', ',', number_format($values['residue'], 1)) }}
                                    @else
                                        {{ str_replace('.', ',', $values['residue']) }}
                                    @endif
                                @endif
                            </td>
                            @endif
                        </tr>
                    @endif
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>