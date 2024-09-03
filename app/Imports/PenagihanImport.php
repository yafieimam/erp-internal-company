<?php

namespace App\Imports;

use App\ModelPenagihan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PenagihanImport implements ToModel, WithStartRow
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
        if(!ModelPenagihan::where('nosj', '=', $row[1])->where('custid', '=', $row[9])->where('itemid', '=', $row[17])->exists()) {
            if($row[5] == null || $row[5] == ''){
                $this->kosong = 1;
            }else{
                date_default_timezone_set('Asia/Jakarta');
                DB::table('logbook_penagihan')->insert(['tanggal' => date("Y-m-d H:i:s"), 'nosj' => $row[1], 'id_user' => Session::get('id_user_admin'), 'status_jadwal' => 0, 'action' => 'Upload Excel dan Insert Data ke Tabel Penagihan']);

                return new ModelPenagihan([
                    'nosj' => $row[1],
                    'tanggal_do' => $this->transformDate($row[2]),
                    'top' => $row[3],
                    'noinv' => $row[5],
                    'noso' => $row[6],
                    'nopo' => $row[7],
                    'sls' => $row[8],
                    'custid' => $row[9],
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
                    'percent_ppn' => intval(env('PPN_VAL'));,
                    'tanggal_jatuh_tempo' => $this->transformDate($row[4]),
                    'status_jadwal' => 0,
                    'status_hadir' => 0,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                    'created_by' => Session::get('id_user_admin'),
                    'updated_by' => Session::get('id_user_admin'),
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
