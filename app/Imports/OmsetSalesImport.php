<?php

namespace App\Imports;

use App\ModelDeliveryOrders;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OmsetSalesImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    private $duplikat = 0;
    private $kosong = 0;

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
        if(!ModelDeliveryOrders::where('nosj', '=', $row[1])->where('custid', '=', $row[9])->where('itemid', '=', $row[17])->exists()) {
            if($row[5] == null || $row[5] == ''){
                $this->kosong = 1;
            }else{
                $data = DB::table('products')->select('jenis_produk', 'saldo', 'weight')->where('kode_produk', $row[17])->first();
                $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('tanggal', $this->transformDate($row[2])->format('Y-m-d'))->where('jenis_produk', $data->jenis_produk)->first();

                if($cek){
                    DB::table('inventaris_produksi')->where('jenis_produk', $data->jenis_produk)->where('tanggal', $this->transformDate($row[2])->format('Y-m-d'))->update(['pengiriman' => ($cek->pengiriman + ($row[15] / $data->weight)), 'saldo' => ($cek->saldo - ($row[15] / $data->weight))]);
                    DB::table('products')->where('jenis_produk', $data->jenis_produk)->update(['saldo' => ($data->saldo - ($row[15] / $data->weight))]);

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 0, 'action' => 'User ' . Session::get('id_user_admin') . ' Reduce Saldo Produk ' . $data->jenis_produk . ' = ' . ($row[15] / $data->weight) . ' Sak. Total Saldo = ' . ($cek->saldo - ($row[15] / $data->weight)) . ' Sak']);
                }else{
                    DB::table('inventaris_produksi')->insert(['tanggal' => $this->transformDate($row[2])->format('Y-m-d'), 'jenis_produk' => $data->jenis_produk, 'produksi' => 0, 'pengiriman' => ($row[15] / $data->weight), 'saldo' => ($data->saldo - ($row[15] / $data->weight))]);
                    DB::table('products')->where('jenis_produk', $data->jenis_produk)->update(['saldo' => ($data->saldo - ($row[15] / $data->weight))]);

                    date_default_timezone_set('Asia/Jakarta');
                    DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 0, 'action' => 'User ' . Session::get('id_user_admin') . ' Reduce Saldo Produk ' . $data->jenis_produk . ' = ' . ($row[15] / $data->weight) . ' Sak. Total Saldo = ' . ($data->saldo - ($row[15] / $data->weight)) . ' Sak']);
                }

                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_omset_sales')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'action' => 'User ' . Session::get('id_user_admin') . ' / Sales Menambahkan Data Omset No. ' . $row[1]]);

                return new ModelDeliveryOrders([
                    'nosj' => $row[1],
                    'tanggal_do' => $this->transformDate($row[2]),
                    'top' => $row[3],
                    'tanggal_jatuh_tempo' => $this->transformDate($row[4]),
                    'noinv' => $row[5],
                    'noso' => $row[6],
                    'nopo' => $row[7],
                    'sls' => $row[8],
                    'custid' => $row[9],
                    // 'custname' => $row[7],
                    'custname' => preg_replace('/_x([0-9a-fA-F]{4})_/', '', $row[10]),
                    'dpp' => $row[11],
                    'ppn' => $row[12],
                    'amount' => $row[13],
                    'qty' => $row[15],
                    'sat' => $row[16],
                    'itemid' => $row[17],
                    'itemname' => $row[18],
                    'g' => $row[19],
                    'price' => $row[20],
                    'diskon' => $row[14],
                    'sub_amount' => $row[22],
                    'hrg_pkk' => $row[23],
                    'sub_pkk' => $row[24],
                    'percent_ppn' => intval(env('PPN_VAL'));
                ]);
            }
        }else{
            $this->duplikat = 1;
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

    public function getKosong(): int
    {
        return $this->kosong;
    }
}
