<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Form Customers Visit</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page { margin: 15px; }
        * { box-sizing: border-box; }
        body{
            margin-left: 30px;
            margin-right: 30px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
    <style>
        .td-utama{
            font-size: 11px;
        }
        .td-solusi{
            width: 92px;
            font-size: 11px;
        }
        .div-approved{
            vertical-align: all; 
            font-size: 16px; 
            font-weight: 900; 
            font-style: oblique; 
            transform: rotate(-10deg); 
            margin-left: 10px; 
        }
        .div-approved-solusi{
            vertical-align: all; 
            font-size: 16px; 
            font-weight: 900; 
            font-style: oblique; 
            transform: rotate(-10deg); 
            margin-left: 10px; 
        }
    </style>
</head>
<body>
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td rowspan="2" style="width: 25%; border: 1px solid;"><img src="{{ public_path('app-assets/asset/images/logo-footer.png') }}" style="width: 50px; margin-left: 35%;"><br><div style="font-size: 14px; text-align: center; margin-top: 10px; font-weight: 900;">PT. DWI SELO GIRI MAS</div></td>
            <td rowspan="2" style="width: 50%; border: 1px solid; text-align: center; font-size: 20px; font-family: 'Times New Roman'; font-weight: 900;">FORM CUSTOMERS VISIT</td>
            <td style="width: 25%; border: 1px solid; font-size: 12px;">No. Customers Visit :<br><div style="font-size: 16px; margin-top: 10px; text-align: center;">{{ $data->id_schedule }}</div></td>
        </tr>
        <tr>
            <td style="width: 25%; border: 1px solid; font-size: 12px;">Tanggal Schedule :<br><div style="font-size: 16px; margin-top: 10px; text-align: center;">{{ $data->tanggal_schedule }} 
            </div>
            </td>
        </tr>
    </table>
    <table style="width: 100%; border-left: 1px solid; border-right: 1px solid; border-collapse: collapse; font-size: 14px;">
        <tr style="">
            <td colspan="5" style="height: 20px;"></td>
        </tr>
        <tr>
            <td style="width: 17%; padding: 5px; padding-bottom: 0;">Visit By Sales :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ Session::get('nama_admin') }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 10%; padding: 5px; padding-bottom: 0;">Customers :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->customers }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 10%; padding: 5px; padding-bottom: 0;">Perihal :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->perihal }} ({{ $data->offline }})</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 10%; padding: 5px; padding-bottom: 0;">Keterangan :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->keterangan }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 10%; padding: 5px; padding-bottom: 0;">Penawaran :</td>
            <td style="width: 10%; border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">
                @if($data->penawaran_yes == 'yes')
                    Ya
                @else
                    Tidak
                @endif
            </td>
            <td style="width: 14%; padding: 5px; padding-bottom: 0;">Catatan :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->catatan_penawaran }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 10%; padding: 5px; padding-bottom: 0;">Sample :</td>
            <td style="width: 10%; border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">
                @if($data->sample_yes == 'yes')
                    Ya
                @else
                    Tidak
                @endif
            </td>
            <td style="width: 10%; padding: 5px; padding-bottom: 0;">Catatan :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->catatan_penawaran }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 10%; padding: 5px; padding-bottom: 0;">Order :</td>
            <td style="width: 10%; border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">
                @if($data->order_yes == 'yes')
                    Ya
                @else
                    Tidak
                @endif
            </td>
            <td style="width: 10%; padding: 5px; padding-bottom: 0;">Catatan :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->catatan_order }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 10%; padding: 5px; padding-bottom: 0;">Route Length :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->route_length }} Km</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 10%; padding: 5px; padding-bottom: 0;">BBM :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->bbm }} Ltr</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 10%; padding: 5px; padding-bottom: 0;">Biaya Perjalanan :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">Rp {{ $data->biaya_perjalanan }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 10%; padding: 5px; padding-bottom: 0;">Alasan Suspend :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->alasan_suspend }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 10%; padding: 5px; padding-bottom: 0;">Hasil Visit :</td>
            <td colspan="3" style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->alasan_done }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 10%; padding: 5px; padding-bottom: 0;">Kegiatan :</td>
            <td style="width: 10%; border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">
                @if($data->offline == 1)
                    Visit
                @else
                    Telepon
                @endif
            </td>
            <td style="width: 10%; padding: 5px; padding-bottom: 0;">Tanggal Done :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $data->tanggal_done }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        <tr>
            <td style="width: 10%; border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td colspan="3" style="border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td style="width: 2%; border-bottom: 1px solid; padding-bottom: 0;"></td>
        </tr>
    </table>
    @if(count($detail) > 0)
    <table style="width: 100%; border-left: 1px solid; border-right: 1px solid; border-collapse: collapse; font-size: 14px;">
        <tr style="">
            <td colspan="5" style="height: 20px; padding: 5px; padding-bottom: 0;">Catatan Perusahaan</td>
        </tr>
        @foreach($detail as $det)
        <tr>
            <td style="width: 15%; padding: 5px; padding-bottom: 0;">Perusahaan :</td>
            <td style="width: 25%; border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $det->company }}</td>
            <td style="width: 20%; padding: 5px; padding-bottom: 0;">Catatan Perusahaan :</td>
            <td style="border-bottom: 1px dotted; padding: 5px; padding-bottom: 0;">{{ $det->catatan_perusahaan }}</td>
            <td style="width: 2%; padding-bottom: 0;"></td>
        </tr>
        @endforeach
        <tr>
            <td style="width: 16%; border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td colspan="3" style="border-bottom: 1px solid; padding: 5px; padding-bottom: 0;"></td>
            <td style="width: 2%; border-bottom: 1px solid; padding-bottom: 0;"></td>
        </tr>
    </table>
    @endif
    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
        <tr style="">
            <td style="width:5px; padding: 5px; border: 1px solid #000;"></td>
            <td style="padding: 5px; border: 1px solid #000; text-align: center;">Dibuat</td>
            <td style="padding: 5px; border: 1px solid #000; text-align: center;">Disetujui</td>
            <td style="padding: 5px; border: 1px solid #000; text-align: center;">Disetujui</td>
        </tr>
        <tr style="">
            <td style="width:5px; padding: 5px; border: 1px solid #000; height: 70px; font-size: 12px;">Approved By</td>
            <td class="td-solusi" style="padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved-solusi">APPROVED</div>( {{ $data->created_by }} )<br>Sales Marketing</td>
            <td class="td-utama" style="width: 80px; padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved">APPROVED</div>(Cien Cien)<br>Manager Marketing</td>
            <td class="td-utama" style="width: 90px; padding: 5px; border: 1px solid #000; height: 20px; text-align: center; vertical-align: bottom;"><div class="div-approved">APPROVED</div>(Inggrid Chandranata)<br>Direktur</td>
        </tr>
        <tr style="">
            <td style="width:5px; padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">Tanggal</td>
            <td style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;">{{ date('Y-m-d', strtotime($data->created_at)) }}</td>
            <td style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;"></td>
            <td style="padding: 5px; border: 1px solid #000; height: 5px; font-size: 14px; text-align: center;"></td>
        </tr>
    </table>
</body>
</html>