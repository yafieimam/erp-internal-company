<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Surat Pesanan</title>
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
    </style>
</head>
<body>
    <table style="width: 100%;">
        <tr>
            <td>
                <tr>
                    <td style="font-size: 16px; font-family: 'Times New Roman'; font-weight: 900; width: 70%">PT. DWI SELO GIRI MAS</td>
                    <td style="font-size: 12px; font-weight: 0; width: 5%; text-align: right;">No. :</td>
                    <td style="font-size: 12px; font-weight: 0; width: 25%; border-bottom: 1px solid #000;">{{ $data->nomor_order_receipt }}</td>
                </tr>
                <tr>
                    <td style="font-size: 11.5px; font-family: 'Arial'; font-weight: 900; width: 70%">JL. RAYA TEBEL NO. 50</td>
                    <td style="font-size: 12px; font-weight: 0; width: 10%; text-align: right;">Tgl. :</td>
                    <td style="font-size: 12px; font-weight: 0; width: 20%; border-bottom: 1px solid #000;">{{ $data->tanggal_order }}</td>
                </tr>
                <tr>
                    <td style="font-size: 11.5px; font-family: 'Arial'; font-weight: 900; width: 70%">TELP. (031) 8913030</td>
                </tr>
                <tr>
                    <td style="font-size: 11.5px; font-family: 'Arial'; font-weight: 900; width: 70%">GEDANGAN - SIDOARJO</td>
                </tr>
            </td>
        </tr>
    </table>
    <br>
    <table style="width: 100%;">
        <tr>
            <td style="font-size: 20px; font-family: 'Times New Roman'; font-weight: 900; text-align: center; text-decoration: underline;">SURAT PESANAN</td>
        </tr>
    </table>
    <br><br>
    <table style="width: 100%;">
        <tr>
            <td style="font-size: 12px; font-weight: 0; width: 15%;">Nama Persh :</td>
            <td style="font-size: 12px; font-weight: 0; border-bottom: 1px solid #000; width: 50%;">{{ $data->custname_order }}</td>
            <td style="font-size: 12px; font-weight: 0; width: 12%; text-align: right;">Kd Cust :</td>
            <td style="font-size: 12px; font-weight: 0; border-bottom: 1px solid #000;">{{ $data->custid }}</td>
        </tr>
        <tr>
            <td style="font-size: 12px; font-weight: 0; width: 15%;">Alamat :</td>
            <td style="font-size: 12px; font-weight: 0; border-bottom: 1px solid #000; width: 50%;">{{ $data->address_order }}</td>
            <td style="font-size: 12px; font-weight: 0; width: 12%; text-align: right;">Pemesan :</td>
            <td style="font-size: 12px; font-weight: 0; border-bottom: 1px solid #000;"></td>
        </tr>
        <tr>
            <td style="font-size: 12px; font-weight: 0; width: 15%;">No. PO :</td>
            <td style="font-size: 12px; font-weight: 0; border-bottom: 1px solid #000; width: 50%;">{{ $data->nomor_po }}</td>
        </tr>
    </table>
    <br>
    <table style="font-size: 12px; font-weight: 0; width: 100%; text-align: center; border-collapse: collapse;">
        <tr style="">
            <td style="width: 28%; border: 1px solid #000; border-left: none;">Jenis Barang</td>
            <td style="width: 16%; border: 1px solid #000;">Tgl Kirim</td>
            <td style="width: 12%; border: 1px solid #000;">Kuantitas (KG)</td>
            <td style="width: 10%; border: 1px solid #000;">Harga (KG)</td>
            <td style="width: 15%; border: 1px solid #000; border-right: none;">Total Harga (Rp)</td>
        </tr>
        {{ $sum = 0 }}
        {{ $sum_noppn = 0 }}
        @foreach($products as $prod)
        {{ $sum+= $prod->total_price }}
        {{ $sum_noppn += $prod->qty * $prod->harga_satuan }}
        <tr style="">
            <td style="width: 20%; border-right: 1px solid #000; padding: 5px;">{{ $prod->nama_produk }}</td>
            <td style="width: 15%; border-right: 1px solid #000; padding: 5px;">{{ date('j F Y', strtotime($prod->tanggal_kirim)) }}</td>
            <td style="border-right: 1px solid #000; padding: 5px;">{{ number_format($prod->qty, 0, ".", ".") }}</td>
            <td style="border-right: 1px solid #000; padding: 5px;">{{ number_format($prod->harga_satuan, 0, ".", ".") }}</td>
            <td style="width: 15%; padding: 5px;">{{ number_format($prod->qty * $prod->harga_satuan, 0, ".", ".") }}</td>
        </tr>
        @endforeach
        <tr style="">
            <td style="width: 20%; border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 5px;"></td>
            <td style="width: 15%; border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 5px;"></td>
            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 5px;"></td>
            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 5px;"></td>
            <td style="width: 15%; border-bottom: 1px solid #000; padding: 5px;"></td>
        </tr>
        <tr style="">
            <td style="width: 20%;border-bottom: 1px solid #000; padding: 3px; text-align: left;">Subtotal</td>
            <td colspan="3" style="border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 3px;"></td>
            <td style="width: 15%; border-bottom: 1px solid #000; padding: 3px;">{{ number_format($sum_noppn, 0, ".", ".") }}</td>
        </tr>
        <tr style="">
            <td style="width: 20%;border-bottom: 1px solid #000; padding: 3px; height: 12px; text-align: left;">PPN (10%)</td>
            <td colspan="3" style="border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 3px;"></td>
            <td style="width: 15%; border-bottom: 1px solid #000; padding: 3px;">{{ number_format($sum_noppn * 10 / 100, 0, ".", ".") }}</td>
        </tr>
        <tr style="">
            <td style="width: 20%;border-bottom: 1px solid #000; padding: 3px; height: 12px; text-align: left;">Total</td>
            <td colspan="3" style="border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 3px;"></td>
            <td style="width: 15%; border-bottom: 1px solid #000; padding: 3px;">{{ number_format($sum, 0, ".", ".") }}</td>
        </tr>
    </table>
    <table style="width: 100%;">
        @if($data->crd == 1)
        <tr>
            <td style="font-size: 12px; font-weight: 0; width: 16%;">Syarat Pembayaran :</td>
            <td style="font-size: 12px; font-weight: 0; border-bottom: 1px solid #000; width: 50%;">{{ $data->crd }} Hari Tunai / Transfer</td>
        </tr>
        @else
        <tr>
            <td style="font-size: 12px; font-weight: 0; width: 16%;">Syarat Pembayaran :</td>
            <td style="font-size: 12px; font-weight: 0; border-bottom: 1px solid #000; width: 50%;">{{ $data->crd }} Hari</td>
        </tr>
        @endif
        <tr>
            <td style="font-size: 12px; font-weight: 0; width: 15%;">Barang dikirim ke :</td>
            <td style="font-size: 12px; font-weight: 0; border-bottom: 1px solid #000; width: 50%;">
            <?php $num = 0; ?>
            @foreach($products as $prod)
                <?php $num++; ?>
                {{ $num }}. {{ $prod->custalamat_receive . ', ' . ucwords(strtolower($prod->kota_receive)) }} <br>
            @endforeach
            </td>
        </tr>
        <tr>
            <td style="font-size: 12px; font-weight: 0; width: 15%;">Keterangan Lain-lain :</td>
            <td style="font-size: 12px; font-weight: 0; border-bottom: 1px solid #000; width: 50%;">{{ $data->keterangan_order . ' ' . $data->keterangan_quotation }}</td>
        </tr>
    </table>
    <br>
    <table style="width: 100%; text-align: center;">
        <tr>
            <td style="font-size: 12px; font-weight: 0; width: 40%;">Pemesan :</td>
            <td style="font-size: 12px; font-weight: 0; width: 20%;"></td>
            <td style="font-size: 12px; font-weight: 0; width: 40%;">Salesman :</td>
        </tr>
        <tr>
            <td style="font-size: 12px; font-weight: 0; width: 40%; height: 50px;"></td>
            <td style="font-size: 12px; font-weight: 0; width: 20%; height: 50px;"></td>
            <td style="font-size: 12px; font-weight: 0; width: 40%; height: 50px;"></td>
        </tr>
        <tr>
            <td style="font-size: 12px; font-weight: 0; width: 40%;">(......................................)</td>
            <td style="font-size: 12px; font-weight: 0; width: 20%;"></td>
            <td style="font-size: 12px; font-weight: 0; width: 40%;">(......................................)</td>
        </tr>
    </table>
    <!-- <div class="header" style="width:100%!important">
        <div class="" style="width: 50%; float: left;">
            <p class="header-comp" style="font-size: 16px; font-family: 'Times New Roman';">PT. DWI SELO GIRI MAS</p>
            <p class="header-comp" style="font-size: 11.5px; font-family: 'Calibri';">JL. RAYA TEBEL NO. 50</p>
            <p class="header-comp" style="font-size: 11.5px;">TELP. (031) 8913030</p>
            <p class="header-comp" style="font-size: 11.5px;">GEDANGAN - SIDOARJO</p>
        </div>
        <div class="" style="width: 50%; float: left; align-content: right;">
            <table style="margin-top: 5px; float: right; font-size: 12px; font-weight: 0;">
                <tr>
                    <td width="10%">No. </td>
                    <td width="2%"> : </td>
                    <td>123456718</td>
                </tr>
                <tr>
                    <td>Tanggal. </td>
                    <td> : </td>
                    <td>2020-01-03</td>
                </tr>
            </table>
            <div style="float: right;">
                <div style="margin-top: 10px; font-size: 12px; font-weight: 0; float: left;">No. : 124571239123</div>
                <div style="margin-top: 30px; font-size: 12px; font-weight: 0; float: left;">Tgl. : 2020-01-03</div>
            </div>
        </div>
    </div> -->
</body>
</html>