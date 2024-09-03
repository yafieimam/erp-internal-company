<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Form Lunas</title>
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
    </style>
</head>
<body>
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td rowspan="2" style="width: 45%; border: 1px solid;"><div style="font-size: 15px; text-align: center; font-weight: 900; margin: 20px 0 20px 0;">PT. DWI SELO GIRI MAS</div></td>
            <td rowspan="2" style="width: 45%; border: 1px solid; text-align: center; font-size: 15px; font-weight: 900;">BUKTI KAS/BANK MASUK</td>
            <td style="width: 15%; border-left: 1px solid; border-top: 1px solid; font-size: 12px; margin-top: 10px;">No. Voucher</td>
            <td style="width: 5%; border-top: 1px solid; font-size: 12px; margin-top: 10px;">:</td>
            <td style="width: 35%; border-right: 1px solid; border-top: 1px solid; font-size: 12px; font-size: 16px; margin-top: 10px; font-weight: 900;">{{ $data->noar }}</td>
        </tr>
        <tr>
            <td style="width: 15%; border-left: 1px solid; border-bottom: 1px solid; font-size: 12px;">Tanggal</td>
            <td style="width: 5%; border-bottom: 1px solid; font-size: 12px; margin-top: 10px;">:</td>
            <td style="width: 35%; border-right: 1px solid; border-bottom: 1px solid; font-size: 12px;">{{ date('j M Y', strtotime($data->tanggal)) }}</td>
        </tr>
    </table>
    @if($data->user_type == 17)
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 25%; border-left: 1px solid; border-bottom: 1px solid; font-size: 12px;"><div style="margin: 5px 0 5px 0;">Kas/Bank-Acc : 110201</div></td>
            <td style="width: 65%; border-bottom: 1px solid; font-size: 12px;">BCA A/C 9008</td>
            <td style="width: 10%; border-right: 1px solid; border-bottom: 1px solid; font-size: 12px;">Page : 1</td>
        </tr>
    </table>
    @elseif($data->user_type == 15)
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 25%; border-left: 1px solid; border-bottom: 1px solid; font-size: 12px;"><div style="margin: 5px 0 5px 0;">Kas/Bank-Acc : 110101</div></td>
            <td style="width: 65%; border-bottom: 1px solid; font-size: 12px;">KAS</td>
            <td style="width: 10%; border-right: 1px solid; border-bottom: 1px solid; font-size: 12px;">Page : 1</td>
        </tr>
    </table>
    @endif
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td colspan="2" style="border-left: 1px solid; border-right: 1px solid;">
                <table style="width: 100%; border-collapse: collapse; margin: 10px 0 10px 0; font-size: 12px;">
                    <tr>
                        <td style="width: 5%; border-right: 1px solid; text-align: center">No.</td>
                        <td style="width: 15%; border-right: 1px solid; text-align: center">Account ID</td>
                        <td style="width: 50%; border-right: 1px solid; text-align: center">Uraian</td>
                        <td style="width: 15%; border-right: 1px solid; text-align: center">Jumlah</td>
                        <td style="width: 15%; text-align: center">Other</td>
                    </tr>
                    <tr>
                        <td style="width: 5%; border-top: 1px solid;">1.</td>
                        <td style="width: 15%; border-top: 1px solid;">110401</td>
                        <td style="width: 50%; border-top: 1px solid;">{{ $data->keterangan }}</td>
                        <td style="width: 15%; border-top: 1px solid; text-align: right">{{ number_format($data->total_nominal, 0, ".", ",") }}.00</td>
                        <td style="width: 15%; border-top: 1px solid;"></td>
                    </tr>
                    <tr>
                        <td colspan="5" style="padding: 70px;"></td>
                    </tr>
                    <tr>
                        <td style="width: 5%; border-top: 1px solid;"></td>
                        <td style="width: 15%; border-top: 1px solid;"></td>
                        <td style="width: 50%; border-top: 1px solid; text-align: right;">Total Amount :</td>
                        <td style="width: 15%; border-top: 1px solid; text-align: right">{{ number_format($data->total_nominal, 0, ".", ",") }}.00</td>
                        <td style="width: 15%; border-top: 1px solid;"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border-left: 1px solid; border-right: 1px solid; padding-left: 5px;">
                <table style="width: 80%; border-collapse: collapse; font-size: 12px;">
                    <tr>
                        <td style="width: 25%; border: 1px solid; text-align: center">Dibuat Oleh :</td>
                        <td style="width: 25%; border: 1px solid; text-align: center">Diperiksa Oleh :</td>
                        <td style="width: 25%; border: 1px solid; text-align: center">Disetujui Oleh :</td>
                        <td style="width: 25%; border: 1px solid; text-align: center">Penyetor</td>
                    </tr>
                    <tr>
                        <td style="width: 25%; border: 1px solid; text-align: center; padding: 30px;"></td>
                        <td style="width: 25%; border: 1px solid; text-align: center"></td>
                        <td style="width: 25%; border: 1px solid; text-align: center"></td>
                        <td style="width: 25%; border: 1px solid; text-align: center"></td>
                    </tr>
                </table>
            </td>
        </tr>
        @if($data->user_type == 17)
        <tr>
            <td style="width: 82%; border-left: 1px solid; border-bottom: 1px solid; font-size: 12px; padding-left: 5px;">
                {{ date('Y-m-d H:i:s') }}, piutang
            </td>
            <td style="border-right: 1px solid; border-bottom: 1px solid; font-size: 12px; font-weight: 900;">
                ORIGINAL
            </td>
        </tr>
        @elseif($data->user_type == 15)
        <tr>
            <td style="width: 82%; border-left: 1px solid; border-bottom: 1px solid; font-size: 12px; padding-left: 5px;">
                {{ date('Y-m-d H:i:s') }}, maria
            </td>
            <td style="border-right: 1px solid; border-bottom: 1px solid; font-size: 12px; font-weight: 900;">
                ORIGINAL
            </td>
        </tr>
        @endif
    </table>
</body>
</html>