<?php

namespace App\Imports;

use App\ModelPurchaseBatu;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PurchaseBatuImport implements ToModel, WithStartRow
{
    private $duplikat = 0;
    private $batu_afkir = 0;
    private $total_batu = 0;
    private $nomor_gr = null;
    private $potongan_batu;

    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return Carbon::instance(Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return Carbon::createFromFormat($format, date('Y-m-d', strtotime($value)));
        }
    }

    public function model(array $row)
    {
    	$data_vendor = DB::table('vendor_batu')->select('vendorid')->where('nama_vendor', $row[6])->first();
    	$data_item = DB::table('item_batu')->select('kode_batu')->where('nama_batu', $row[15])->first();
        if(!ModelPurchaseBatu::where('grno', '=', $row[1])->where('vendorid', '=', $data_vendor->vendorid)->where('kode_batu', '=', $data_item->kode_batu)->exists()) {
            $this->duplikat = 1;
            if($this->nomor_gr == null){
            	$this->nomor_gr = $row[1];
            	$this->batu_afkir = $row[11];
            	$this->total_batu += $row[11];
            }else if($this->nomor_gr == $row[1]){
            	if($this->batu_afkir > $row[11]){
            		$this->batu_afkir = $row[11];
            		$this->total_batu += $row[11];
            	}else{
            		$this->total_batu += $row[11];
            	}

            	$this->potongan_batu = ($this->batu_afkir / $this->total_batu) * 100;
            	$this->potongan_batu = round($this->potongan_batu);

            	DB::table('potongan_batu')->insert(["grno" => $row[1], "tanggal" => $this->transformDate($row[2])->format('Y-m-d'), "vendorid" => $data_vendor->vendorid, "nama_vendor" => $row[6], "total_batu_afkir" => $this->batu_afkir, "total_batu" => $this->total_batu, "potongan_batu" => $this->potongan_batu]);
            }else{
            	$this->total_batu = 0;
            	$this->nomor_gr = $row[1];
            	$this->batu_afkir = $row[11];
            	$this->total_batu += $row[11];
            }

            $data = DB::table('item_batu')->select('stok')->where('kode_batu', $data_item->kode_batu)->first();
            $cek = DB::table('inventaris_batu')->select('tanggal', 'kode_batu', 'masuk', 'keluar', 'stok')->where('tanggal', $this->transformDate($row[2])->format('Y-m-d'))->where('kode_batu', $data_item->kode_batu)->first();

            if($cek){
                DB::table('inventaris_batu')->where('kode_batu', $data_item->kode_batu)->where('tanggal', $this->transformDate($row[2])->format('Y-m-d'))->update(['masuk' => ($cek->masuk + $row[11]), 'stok' => ($cek->stok + $row[11])]);
                DB::table('item_batu')->where('kode_batu', $data_item->kode_batu)->update(['stok' => ($data->stok + $row[11])]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_inventaris_batu')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Stok Batu ' . $data_item->kode_batu . ' = ' . $row[11] . ' Kg. Total Stok = ' . ($cek->stok + $row[11]) . ' Kg']);
            }else{
                DB::table('inventaris_batu')->insert(['tanggal' => $this->transformDate($row[2])->format('Y-m-d'), 'kode_batu' => $data_item->kode_batu, 'masuk' => $row[11], 'keluar' => 0, 'stok' => ($data->stok + $row[11])]);
                DB::table('item_batu')->where('kode_batu', $data_item->kode_batu)->update(['stok' => ($data->stok + $row[11])]);

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_inventaris_batu')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Stok Batu ' . $data_item->kode_batu . ' = ' . $row[11] . ' Kg. Total Stok = ' . ($data->stok + $row[11]) . ' Kg']);
            }

            date_default_timezone_set('Asia/Jakarta');
            DB::table('logbook_purchase_batu')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' / Divisi Batu Menambahkan Data Purchase Batu No. ' . $row[1]]);

            return new ModelPurchaseBatu([
                'grno' => $row[1],
                'tanggal' => $this->transformDate($row[2]),
                'nopo' => $row[3],
                'sls' => $row[4],
                'purtype' => $row[5],
                'vendorid' => $data_vendor->vendorid,
                'nama_vendor' => $row[6],
                'basegst' => $row[7],
                'gstvalue' => $row[8],
                'amount' => $row[9],
                'user' => $row[10],
                'qpur' => $row[11],
                'sat' => $row[12],
                'qtystock' => $row[13],
                'produk' => $row[14],
                'kode_batu' => $data_item->kode_batu,
                'nama_batu' => $row[15],
                'wrh' => $row[16],
                'subitem' => $row[17],
                'location' => $row[18],
                'from_batu' => $row[19],
                'price' => $row[20],
                'disc' => $row[21],
                'subamount' => $row[22],
            ]);
        }else{
            $this->duplikat = 0;
        }
    }

    public function startRow(): int
    {
        return 2;
    }

    public function getDuplikat(): int
    {
        return $this->duplikat;
    }
}
