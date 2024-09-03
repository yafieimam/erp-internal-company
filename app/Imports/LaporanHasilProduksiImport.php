<?php

namespace App\Imports;

use App\ModelLaporanHasilProduksi;
use App\ModelLaporanHasilProduksiDetail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LaporanHasilProduksiImport implements ToModel, WithStartRow, WithCalculatedFormulas
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    private $duplikat = 0;

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
      $value_mesin = 0;
      $nomor_laporan_produksi = "";
    	$tanggal = $this->transformDate($row[0])->format('ym');
      $data_mesin = ['SA' => 1, 'SB' => 2, 'MIXER' => 3, 'RA' => 4, 'RB' => 5, 'RC' => 6, 'RD' => 7, 'RE' => 8, 'RF' => 9, 'RG' => 10];

      if(array_key_exists($row[1], $data_mesin)){
          $value_mesin = $data_mesin[$row[1]];
      }

      if(!ModelLaporanHasilProduksi::where('tanggal_laporan_produksi', $this->transformDate($row[0])->format('Y-m-d'))->exists()) {
        $cek_data_laporan = ModelLaporanHasilProduksi::select('nomor_laporan_produksi')->where('nomor_laporan_produksi', 'like', 'LHP' . $tanggal . '%')->orderBy('nomor_laporan_produksi', 'asc')->distinct()->get();

        if($cek_data_laporan){
          $data_laporan_count = $cek_data_laporan->count();
          if($data_laporan_count > 0){
            $num = (int) substr($cek_data_laporan[$cek_data_laporan->count() - 1]->nomor_laporan_produksi, 8);
            if($data_laporan_count != $num){
              $nomor_laporan_produksi = ++$cek_data_laporan[$cek_data_laporan->count() - 1]->nomor_laporan_produksi;
            }else{
              if($data_laporan_count < 9){
                $nomor_laporan_produksi = "LHP" . $tanggal . "-000" . ($data_laporan_count + 1);
              }else if($data_laporan_count >= 9 && $data_laporan_count < 99){
                $nomor_laporan_produksi = "LHP" . $tanggal . "-00" . ($data_laporan_count + 1);
              }else if($data_laporan_count >= 99 && $data_laporan_count < 999){
                $nomor_laporan_produksi = "LHP" . $tanggal . "-0" . ($data_laporan_count + 1);
              }else{
                $nomor_laporan_produksi = "LHP" . $tanggal . "-" . ($data_laporan_count + 1);
              }
            }
          }else{
            $nomor_laporan_produksi = "LHP" . $tanggal . "-0001";
          }
        }else{
          $nomor_laporan_produksi = "LHP" . $tanggal . "-0001";
        }

        date_default_timezone_set('Asia/Jakarta');
        $data = ModelLaporanHasilProduksi::insert(["nomor_laporan_produksi" => $nomor_laporan_produksi, "tanggal_laporan_produksi" =>  $this->transformDate($row[0])->format('Y-m-d'), "tanggal_input" => date('Y-m-d'), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s"), "created_by" => Session::get('id_user_admin'), "updated_by" => Session::get('id_user_admin')]);
      }else{
        $data_nomor = ModelLaporanHasilProduksi::select('nomor_laporan_produksi')->where('tanggal_laporan_produksi', $this->transformDate($row[0])->format('Y-m-d'))->first();
        $nomor_laporan_produksi = $data_nomor->nomor_laporan_produksi;
      }

      if($row[2] != NULL || $row[2] != ''){
        $cek_data_detail = ModelLaporanHasilProduksiDetail::select('nomor_laporan_produksi_detail')->where('nomor_laporan_produksi_detail', 'like', 'LHPD' . $tanggal . '%')->orderBy('nomor_laporan_produksi_detail', 'asc')->distinct()->get();

        if($cek_data_detail){
          $data_detail_count = $cek_data_detail->count();
          if($data_detail_count > 0){
            $num = (int) substr($cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail, 9);
            if($data_detail_count != $num){
              $nomor_laporan_produksi_detail = ++$cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail;
            }else{
              if($data_detail_count < 9){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9 && $data_detail_count < 99){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 99 && $data_detail_count < 999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 999 && $data_detail_count < 9999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9999 && $data_detail_count < 99999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0" . ($data_detail_count + 1);
              }else{
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-" . ($data_detail_count + 1);
              }
            }
          }else{
            $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
          }
        }else{
          $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
        }

        $data_cek = DB::table('products')->select('weight', 'saldo')->where('jenis_produk', "AA40")->first();
        $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', "AA40")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->first();

        ModelLaporanHasilProduksiDetail::insert(["nomor_laporan_produksi_detail" => $nomor_laporan_produksi_detail, "nomor_laporan_produksi" => $nomor_laporan_produksi, "mesin" => $value_mesin, "jenis_produk" => "AA40", "jumlah_sak" => $row[2], "jumlah_tonase" => $row[2] * $data_cek->weight]);

        if($cek){
          DB::table('inventaris_produksi')->where('jenis_produk', "AA40")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->update(['produksi' => ($cek->produksi + $row[2]), 'saldo' => ($cek->saldo + $row[2])]);
          DB::table('products')->where('jenis_produk', "AA40")->update(['saldo' => ($cek->saldo + $row[2])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk AA40 = ' . $row[2] . ' Sak. Total Saldo = ' . ($cek->saldo + $row[2]) . ' Sak']);
        }else{
          DB::table('inventaris_produksi')->insert(['tanggal' => $this->transformDate($row[0])->format('Y-m-d'), 'jenis_produk' => "AA40", 'produksi' => $row[2], 'pengiriman' => 0, 'saldo' => ($data_cek->saldo + $row[2])]);
          DB::table('products')->where('jenis_produk', "AA40")->update(['saldo' => ($data_cek->saldo + $row[2])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk AA40 = ' . $row[2] . ' Sak. Total Saldo = ' . ($data_cek->saldo + $row[2]) . ' Sak']);
        }
      }

      if($row[3] != NULL || $row[3] != ''){
        $cek_data_detail = ModelLaporanHasilProduksiDetail::select('nomor_laporan_produksi_detail')->where('nomor_laporan_produksi_detail', 'like', 'LHPD' . $tanggal . '%')->orderBy('nomor_laporan_produksi_detail', 'asc')->distinct()->get();

        if($cek_data_detail){
          $data_detail_count = $cek_data_detail->count();
          if($data_detail_count > 0){
            $num = (int) substr($cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail, 9);
            if($data_detail_count != $num){
              $nomor_laporan_produksi_detail = ++$cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail;
            }else{
              if($data_detail_count < 9){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9 && $data_detail_count < 99){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 99 && $data_detail_count < 999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 999 && $data_detail_count < 9999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9999 && $data_detail_count < 99999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0" . ($data_detail_count + 1);
              }else{
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-" . ($data_detail_count + 1);
              }
            }
          }else{
            $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
          }
        }else{
          $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
        }

        $data_cek = DB::table('products')->select('weight', 'saldo')->where('jenis_produk', "AA25")->first();
        $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', "AA25")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->first();

        ModelLaporanHasilProduksiDetail::insert(["nomor_laporan_produksi_detail" => $nomor_laporan_produksi_detail, "nomor_laporan_produksi" => $nomor_laporan_produksi, "mesin" => $value_mesin, "jenis_produk" => "AA25", "jumlah_sak" => $row[3], "jumlah_tonase" => $row[3] * $data_cek->weight]);

        if($cek){
          DB::table('inventaris_produksi')->where('jenis_produk', "AA25")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->update(['produksi' => ($cek->produksi + $row[3]), 'saldo' => ($cek->saldo + $row[3])]);
          DB::table('products')->where('jenis_produk', "AA25")->update(['saldo' => ($cek->saldo + $row[3])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk AA25 = ' . $row[3] . ' Sak. Total Saldo = ' . ($cek->saldo + $row[3]) . ' Sak']);
        }else{
          DB::table('inventaris_produksi')->insert(['tanggal' => $this->transformDate($row[0])->format('Y-m-d'), 'jenis_produk' => "AA25", 'produksi' => $row[3], 'pengiriman' => 0, 'saldo' => ($data_cek->saldo + $row[3])]);
          DB::table('products')->where('jenis_produk', "AA25")->update(['saldo' => ($data_cek->saldo + $row[3])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk AA25 = ' . $row[3] . ' Sak. Total Saldo = ' . ($data_cek->saldo + $row[3]) . ' Sak']);
        }
      }

      if($row[4] != NULL || $row[4] != ''){
        $cek_data_detail = ModelLaporanHasilProduksiDetail::select('nomor_laporan_produksi_detail')->where('nomor_laporan_produksi_detail', 'like', 'LHPD' . $tanggal . '%')->orderBy('nomor_laporan_produksi_detail', 'asc')->distinct()->get();

        if($cek_data_detail){
          $data_detail_count = $cek_data_detail->count();
          if($data_detail_count > 0){
            $num = (int) substr($cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail, 9);
            if($data_detail_count != $num){
              $nomor_laporan_produksi_detail = ++$cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail;
            }else{
              if($data_detail_count < 9){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9 && $data_detail_count < 99){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 99 && $data_detail_count < 999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 999 && $data_detail_count < 9999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9999 && $data_detail_count < 99999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0" . ($data_detail_count + 1);
              }else{
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-" . ($data_detail_count + 1);
              }
            }
          }else{
            $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
          }
        }else{
          $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
        }

        $data_cek = DB::table('products')->select('weight', 'saldo')->where('jenis_produk', "AA20")->first();
        $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', "AA20")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->first();

        ModelLaporanHasilProduksiDetail::insert(["nomor_laporan_produksi_detail" => $nomor_laporan_produksi_detail, "nomor_laporan_produksi" => $nomor_laporan_produksi, "mesin" => $value_mesin, "jenis_produk" => "AA20", "jumlah_sak" => $row[4], "jumlah_tonase" => $row[4] * $data_cek->weight]);

        if($cek){
          DB::table('inventaris_produksi')->where('jenis_produk', "AA20")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->update(['produksi' => ($cek->produksi + $row[4]), 'saldo' => ($cek->saldo + $row[4])]);
          DB::table('products')->where('jenis_produk', "AA20")->update(['saldo' => ($cek->saldo + $row[4])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk AA20 = ' . $row[4] . ' Sak. Total Saldo = ' . ($cek->saldo + $row[4]) . ' Sak']);
        }else{
          DB::table('inventaris_produksi')->insert(['tanggal' => $this->transformDate($row[0])->format('Y-m-d'), 'jenis_produk' => "AA20", 'produksi' => $row[4], 'pengiriman' => 0, 'saldo' => ($data_cek->saldo + $row[4])]);
          DB::table('products')->where('jenis_produk', "AA20")->update(['saldo' => ($data_cek->saldo + $row[4])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk AA20 = ' . $row[4] . ' Sak. Total Saldo = ' . ($data_cek->saldo + $row[4]) . ' Sak']);
        }
      }

      if($row[5] != NULL || $row[5] != ''){
        $cek_data_detail = ModelLaporanHasilProduksiDetail::select('nomor_laporan_produksi_detail')->where('nomor_laporan_produksi_detail', 'like', 'LHPD' . $tanggal . '%')->orderBy('nomor_laporan_produksi_detail', 'asc')->distinct()->get();

        if($cek_data_detail){
          $data_detail_count = $cek_data_detail->count();
          if($data_detail_count > 0){
            $num = (int) substr($cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail, 9);
            if($data_detail_count != $num){
              $nomor_laporan_produksi_detail = ++$cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail;
            }else{
              if($data_detail_count < 9){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9 && $data_detail_count < 99){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 99 && $data_detail_count < 999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 999 && $data_detail_count < 9999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9999 && $data_detail_count < 99999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0" . ($data_detail_count + 1);
              }else{
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-" . ($data_detail_count + 1);
              }
            }
          }else{
            $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
          }
        }else{
          $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
        }

        $data_cek = DB::table('products')->select('weight', 'saldo')->where('jenis_produk', "BB40")->first();
        $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', "BB40")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->first();

        ModelLaporanHasilProduksiDetail::insert(["nomor_laporan_produksi_detail" => $nomor_laporan_produksi_detail, "nomor_laporan_produksi" => $nomor_laporan_produksi, "mesin" => $value_mesin, "jenis_produk" => "BB40", "jumlah_sak" => $row[5], "jumlah_tonase" => $row[5] * $data_cek->weight]);

        if($cek){
          DB::table('inventaris_produksi')->where('jenis_produk', "BB40")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->update(['produksi' => ($cek->produksi + $row[5]), 'saldo' => ($cek->saldo + $row[5])]);
          DB::table('products')->where('jenis_produk', "BB40")->update(['saldo' => ($cek->saldo + $row[5])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk BB40 = ' . $row[5] . ' Sak. Total Saldo = ' . ($cek->saldo + $row[5]) . ' Sak']);
        }else{
          DB::table('inventaris_produksi')->insert(['tanggal' => $this->transformDate($row[0])->format('Y-m-d'), 'jenis_produk' => "BB40", 'produksi' => $row[5], 'pengiriman' => 0, 'saldo' => ($data_cek->saldo + $row[5])]);
          DB::table('products')->where('jenis_produk', "BB40")->update(['saldo' => ($data_cek->saldo + $row[5])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk BB40 = ' . $row[5] . ' Sak. Total Saldo = ' . ($data_cek->saldo + $row[5]) . ' Sak']);
        }
      }

      if($row[6] != NULL || $row[6] != ''){
        $cek_data_detail = ModelLaporanHasilProduksiDetail::select('nomor_laporan_produksi_detail')->where('nomor_laporan_produksi_detail', 'like', 'LHPD' . $tanggal . '%')->orderBy('nomor_laporan_produksi_detail', 'asc')->distinct()->get();

        if($cek_data_detail){
          $data_detail_count = $cek_data_detail->count();
          if($data_detail_count > 0){
            $num = (int) substr($cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail, 9);
            if($data_detail_count != $num){
              $nomor_laporan_produksi_detail = ++$cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail;
            }else{
              if($data_detail_count < 9){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9 && $data_detail_count < 99){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 99 && $data_detail_count < 999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 999 && $data_detail_count < 9999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9999 && $data_detail_count < 99999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0" . ($data_detail_count + 1);
              }else{
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-" . ($data_detail_count + 1);
              }
            }
          }else{
            $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
          }
        }else{
          $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
        }

        $data_cek = DB::table('products')->select('weight', 'saldo')->where('jenis_produk', "CC50")->first();
        $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', "CC50")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->first();

        ModelLaporanHasilProduksiDetail::insert(["nomor_laporan_produksi_detail" => $nomor_laporan_produksi_detail, "nomor_laporan_produksi" => $nomor_laporan_produksi, "mesin" => $value_mesin, "jenis_produk" => "CC50", "jumlah_sak" => $row[6], "jumlah_tonase" => $row[6] * $data_cek->weight]);

        if($cek){
          DB::table('inventaris_produksi')->where('jenis_produk', "CC50")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->update(['produksi' => ($cek->produksi + $row[6]), 'saldo' => ($cek->saldo + $row[6])]);
          DB::table('products')->where('jenis_produk', "CC50")->update(['saldo' => ($cek->saldo + $row[6])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk CC50 = ' . $row[6] . ' Sak. Total Saldo = ' . ($cek->saldo + $row[6]) . ' Sak']);
        }else{
          DB::table('inventaris_produksi')->insert(['tanggal' => $this->transformDate($row[0])->format('Y-m-d'), 'jenis_produk' => "CC50", 'produksi' => $row[6], 'pengiriman' => 0, 'saldo' => ($data_cek->saldo + $row[6])]);
          DB::table('products')->where('jenis_produk', "CC50")->update(['saldo' => ($data_cek->saldo + $row[6])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk CC50 = ' . $row[6] . ' Sak. Total Saldo = ' . ($data_cek->saldo + $row[6]) . ' Sak']);
        }
      }

      if($row[7] != NULL || $row[7] != ''){
        $cek_data_detail = ModelLaporanHasilProduksiDetail::select('nomor_laporan_produksi_detail')->where('nomor_laporan_produksi_detail', 'like', 'LHPD' . $tanggal . '%')->orderBy('nomor_laporan_produksi_detail', 'asc')->distinct()->get();

        if($cek_data_detail){
          $data_detail_count = $cek_data_detail->count();
          if($data_detail_count > 0){
            $num = (int) substr($cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail, 9);
            if($data_detail_count != $num){
              $nomor_laporan_produksi_detail = ++$cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail;
            }else{
              if($data_detail_count < 9){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9 && $data_detail_count < 99){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 99 && $data_detail_count < 999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 999 && $data_detail_count < 9999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9999 && $data_detail_count < 99999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0" . ($data_detail_count + 1);
              }else{
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-" . ($data_detail_count + 1);
              }
            }
          }else{
            $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
          }
        }else{
          $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
        }

        $data_cek = DB::table('products')->select('weight', 'saldo')->where('jenis_produk', "DD50")->first();
        $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', "DD50")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->first();

        ModelLaporanHasilProduksiDetail::insert(["nomor_laporan_produksi_detail" => $nomor_laporan_produksi_detail, "nomor_laporan_produksi" => $nomor_laporan_produksi, "mesin" => $value_mesin, "jenis_produk" => "DD50", "jumlah_sak" => $row[7], "jumlah_tonase" => $row[7] * $data_cek->weight]);

        if($cek){
          DB::table('inventaris_produksi')->where('jenis_produk', "DD50")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->update(['produksi' => ($cek->produksi + $row[7]), 'saldo' => ($cek->saldo + $row[7])]);
          DB::table('products')->where('jenis_produk', "DD50")->update(['saldo' => ($cek->saldo + $row[7])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk DD50 = ' . $row[7] . ' Sak. Total Saldo = ' . ($cek->saldo + $row[7]) . ' Sak']);
        }else{
          DB::table('inventaris_produksi')->insert(['tanggal' => $this->transformDate($row[0])->format('Y-m-d'), 'jenis_produk' => "DD50", 'produksi' => $row[7], 'pengiriman' => 0, 'saldo' => ($data_cek->saldo + $row[7])]);
          DB::table('products')->where('jenis_produk', "DD50")->update(['saldo' => ($data_cek->saldo + $row[7])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk DD50 = ' . $row[7] . ' Sak. Total Saldo = ' . ($data_cek->saldo + $row[7]) . ' Sak']);
        }
      }

      if($row[8] != NULL || $row[8] != ''){
        $cek_data_detail = ModelLaporanHasilProduksiDetail::select('nomor_laporan_produksi_detail')->where('nomor_laporan_produksi_detail', 'like', 'LHPD' . $tanggal . '%')->orderBy('nomor_laporan_produksi_detail', 'asc')->distinct()->get();

        if($cek_data_detail){
          $data_detail_count = $cek_data_detail->count();
          if($data_detail_count > 0){
            $num = (int) substr($cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail, 9);
            if($data_detail_count != $num){
              $nomor_laporan_produksi_detail = ++$cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail;
            }else{
              if($data_detail_count < 9){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9 && $data_detail_count < 99){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 99 && $data_detail_count < 999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 999 && $data_detail_count < 9999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9999 && $data_detail_count < 99999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0" . ($data_detail_count + 1);
              }else{
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-" . ($data_detail_count + 1);
              }
            }
          }else{
            $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
          }
        }else{
          $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
        }

        $data_cek = DB::table('products')->select('weight', 'saldo')->where('jenis_produk', "SSF25")->first();
        $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', "SSF25")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->first();

        ModelLaporanHasilProduksiDetail::insert(["nomor_laporan_produksi_detail" => $nomor_laporan_produksi_detail, "nomor_laporan_produksi" => $nomor_laporan_produksi, "mesin" => $value_mesin, "jenis_produk" => "SSF25", "jumlah_sak" => $row[8], "jumlah_tonase" => $row[8] * $data_cek->weight]);

        if($cek){
          DB::table('inventaris_produksi')->where('jenis_produk', "SSF25")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->update(['produksi' => ($cek->produksi + $row[8]), 'saldo' => ($cek->saldo + $row[8])]);
          DB::table('products')->where('jenis_produk', "SSF25")->update(['saldo' => ($cek->saldo + $row[8])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk SSF25 = ' . $row[8] . ' Sak. Total Saldo = ' . ($cek->saldo + $row[8]) . ' Sak']);
        }else{
          DB::table('inventaris_produksi')->insert(['tanggal' => $this->transformDate($row[0])->format('Y-m-d'), 'jenis_produk' => "SSF25", 'produksi' => $row[8], 'pengiriman' => 0, 'saldo' => ($data_cek->saldo + $row[8])]);
          DB::table('products')->where('jenis_produk', "SSF25")->update(['saldo' => ($data_cek->saldo + $row[8])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk SSF25 = ' . $row[8] . ' Sak. Total Saldo = ' . ($data_cek->saldo + $row[8]) . ' Sak']);
        }
      }

      if($row[9] != NULL || $row[9] != ''){
        $cek_data_detail = ModelLaporanHasilProduksiDetail::select('nomor_laporan_produksi_detail')->where('nomor_laporan_produksi_detail', 'like', 'LHPD' . $tanggal . '%')->orderBy('nomor_laporan_produksi_detail', 'asc')->distinct()->get();

        if($cek_data_detail){
          $data_detail_count = $cek_data_detail->count();
          if($data_detail_count > 0){
            $num = (int) substr($cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail, 9);
            if($data_detail_count != $num){
              $nomor_laporan_produksi_detail = ++$cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail;
            }else{
              if($data_detail_count < 9){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9 && $data_detail_count < 99){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 99 && $data_detail_count < 999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 999 && $data_detail_count < 9999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9999 && $data_detail_count < 99999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0" . ($data_detail_count + 1);
              }else{
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-" . ($data_detail_count + 1);
              }
            }
          }else{
            $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
          }
        }else{
          $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
        }

        $data_cek = DB::table('products')->select('weight', 'saldo')->where('jenis_produk', "SW30")->first();
        $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', "SW30")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->first();

        ModelLaporanHasilProduksiDetail::insert(["nomor_laporan_produksi_detail" => $nomor_laporan_produksi_detail, "nomor_laporan_produksi" => $nomor_laporan_produksi, "mesin" => $value_mesin, "jenis_produk" => "SW30", "jumlah_sak" => $row[9], "jumlah_tonase" => $row[9] * $data_cek->weight]);

        if($cek){
          DB::table('inventaris_produksi')->where('jenis_produk', "SW30")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->update(['produksi' => ($cek->produksi + $row[9]), 'saldo' => ($cek->saldo + $row[9])]);
          DB::table('products')->where('jenis_produk', "SW30")->update(['saldo' => ($cek->saldo + $row[9])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk SW30 = ' . $row[9] . ' Sak. Total Saldo = ' . ($cek->saldo + $row[9]) . ' Sak']);
        }else{
          DB::table('inventaris_produksi')->insert(['tanggal' => $this->transformDate($row[0])->format('Y-m-d'), 'jenis_produk' => "SW30", 'produksi' => $row[9], 'pengiriman' => 0, 'saldo' => ($data_cek->saldo + $row[9])]);
          DB::table('products')->where('jenis_produk', "SW30")->update(['saldo' => ($data_cek->saldo + $row[9])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk SW30 = ' . $row[9] . ' Sak. Total Saldo = ' . ($data_cek->saldo + $row[9]) . ' Sak']);
        }
      }

      if($row[10] != NULL || $row[10] != ''){
        $cek_data_detail = ModelLaporanHasilProduksiDetail::select('nomor_laporan_produksi_detail')->where('nomor_laporan_produksi_detail', 'like', 'LHPD' . $tanggal . '%')->orderBy('nomor_laporan_produksi_detail', 'asc')->distinct()->get();

        if($cek_data_detail){
          $data_detail_count = $cek_data_detail->count();
          if($data_detail_count > 0){
            $num = (int) substr($cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail, 9);
            if($data_detail_count != $num){
              $nomor_laporan_produksi_detail = ++$cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail;
            }else{
              if($data_detail_count < 9){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9 && $data_detail_count < 99){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 99 && $data_detail_count < 999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 999 && $data_detail_count < 9999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9999 && $data_detail_count < 99999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0" . ($data_detail_count + 1);
              }else{
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-" . ($data_detail_count + 1);
              }
            }
          }else{
            $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
          }
        }else{
          $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
        }

        $data_cek = DB::table('products')->select('weight', 'saldo')->where('jenis_produk', "SW40")->first();
        $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', "SW40")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->first();

        ModelLaporanHasilProduksiDetail::insert(["nomor_laporan_produksi_detail" => $nomor_laporan_produksi_detail, "nomor_laporan_produksi" => $nomor_laporan_produksi, "mesin" => $value_mesin, "jenis_produk" => "SW40", "jumlah_sak" => $row[10], "jumlah_tonase" => $row[10] * $data_cek->weight]);

        if($cek){
          DB::table('inventaris_produksi')->where('jenis_produk', "SW40")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->update(['produksi' => ($cek->produksi + $row[10]), 'saldo' => ($cek->saldo + $row[10])]);
          DB::table('products')->where('jenis_produk', "SW40")->update(['saldo' => ($cek->saldo + $row[10])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk SW40 = ' . $row[10] . ' Sak. Total Saldo = ' . ($cek->saldo + $row[10]) . ' Sak']);
        }else{
          DB::table('inventaris_produksi')->insert(['tanggal' => $this->transformDate($row[0])->format('Y-m-d'), 'jenis_produk' => "SW40", 'produksi' => $row[10], 'pengiriman' => 0, 'saldo' => ($data_cek->saldo + $row[10])]);
          DB::table('products')->where('jenis_produk', "SW40")->update(['saldo' => ($data_cek->saldo + $row[10])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk SW40 = ' . $row[10] . ' Sak. Total Saldo = ' . ($data_cek->saldo + $row[10]) . ' Sak']);
        }
      }

      if($row[11] != NULL || $row[11] != ''){
        $cek_data_detail = ModelLaporanHasilProduksiDetail::select('nomor_laporan_produksi_detail')->where('nomor_laporan_produksi_detail', 'like', 'LHPD' . $tanggal . '%')->orderBy('nomor_laporan_produksi_detail', 'asc')->distinct()->get();

        if($cek_data_detail){
          $data_detail_count = $cek_data_detail->count();
          if($data_detail_count > 0){
            $num = (int) substr($cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail, 9);
            if($data_detail_count != $num){
              $nomor_laporan_produksi_detail = ++$cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail;
            }else{
              if($data_detail_count < 9){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9 && $data_detail_count < 99){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 99 && $data_detail_count < 999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 999 && $data_detail_count < 9999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9999 && $data_detail_count < 99999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0" . ($data_detail_count + 1);
              }else{
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-" . ($data_detail_count + 1);
              }
            }
          }else{
            $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
          }
        }else{
          $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
        }

        $data_cek = DB::table('products')->select('weight', 'saldo')->where('jenis_produk', "SF30")->first();
        $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', "SF30")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->first();

        ModelLaporanHasilProduksiDetail::insert(["nomor_laporan_produksi_detail" => $nomor_laporan_produksi_detail, "nomor_laporan_produksi" => $nomor_laporan_produksi, "mesin" => $value_mesin, "jenis_produk" => "SF30", "jumlah_sak" => $row[11], "jumlah_tonase" => $row[11] * $data_cek->weight]);

        if($cek){
          DB::table('inventaris_produksi')->where('jenis_produk', "SF30")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->update(['produksi' => ($cek->produksi + $row[11]), 'saldo' => ($cek->saldo + $row[11])]);
          DB::table('products')->where('jenis_produk', "SF30")->update(['saldo' => ($cek->saldo + $row[11])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk SF30 = ' . $row[11] . ' Sak. Total Saldo = ' . ($cek->saldo + $row[11]) . ' Sak']);
        }else{
          DB::table('inventaris_produksi')->insert(['tanggal' => $this->transformDate($row[0])->format('Y-m-d'), 'jenis_produk' => "SF30", 'produksi' => $row[11], 'pengiriman' => 0, 'saldo' => ($data_cek->saldo + $row[11])]);
          DB::table('products')->where('jenis_produk', "SF30")->update(['saldo' => ($data_cek->saldo + $row[11])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk SF30 = ' . $row[11] . ' Sak. Total Saldo = ' . ($data_cek->saldo + $row[11]) . ' Sak']);
        }
      }

      if($row[12] != NULL || $row[12] != ''){
        $cek_data_detail = ModelLaporanHasilProduksiDetail::select('nomor_laporan_produksi_detail')->where('nomor_laporan_produksi_detail', 'like', 'LHPD' . $tanggal . '%')->orderBy('nomor_laporan_produksi_detail', 'asc')->distinct()->get();

        if($cek_data_detail){
          $data_detail_count = $cek_data_detail->count();
          if($data_detail_count > 0){
            $num = (int) substr($cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail, 9);
            if($data_detail_count != $num){
              $nomor_laporan_produksi_detail = ++$cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail;
            }else{
              if($data_detail_count < 9){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9 && $data_detail_count < 99){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 99 && $data_detail_count < 999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 999 && $data_detail_count < 9999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9999 && $data_detail_count < 99999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0" . ($data_detail_count + 1);
              }else{
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-" . ($data_detail_count + 1);
              }
            }
          }else{
            $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
          }
        }else{
          $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
        }

        $data_cek = DB::table('products')->select('weight', 'saldo')->where('jenis_produk', "SS30")->first();
        $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', "SS30")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->first();

        ModelLaporanHasilProduksiDetail::insert(["nomor_laporan_produksi_detail" => $nomor_laporan_produksi_detail, "nomor_laporan_produksi" => $nomor_laporan_produksi, "mesin" => $value_mesin, "jenis_produk" => "SS30", "jumlah_sak" => $row[12], "jumlah_tonase" => $row[12] * $data_cek->weight]);

        if($cek){
          DB::table('inventaris_produksi')->where('jenis_produk', "SS30")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->update(['produksi' => ($cek->produksi + $row[12]), 'saldo' => ($cek->saldo + $row[12])]);
          DB::table('products')->where('jenis_produk', "SS30")->update(['saldo' => ($cek->saldo + $row[12])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk SS30 = ' . $row[12] . ' Sak. Total Saldo = ' . ($cek->saldo + $row[12]) . ' Sak']);
        }else{
          DB::table('inventaris_produksi')->insert(['tanggal' => $this->transformDate($row[0])->format('Y-m-d'), 'jenis_produk' => "SS30", 'produksi' => $row[12], 'pengiriman' => 0, 'saldo' => ($data_cek->saldo + $row[12])]);
          DB::table('products')->where('jenis_produk', "SS30")->update(['saldo' => ($data_cek->saldo + $row[12])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk SS30 = ' . $row[12] . ' Sak. Total Saldo = ' . ($data_cek->saldo + $row[12]) . ' Sak']);
        }
      }

      if($row[13] != NULL || $row[13] != ''){
        $cek_data_detail = ModelLaporanHasilProduksiDetail::select('nomor_laporan_produksi_detail')->where('nomor_laporan_produksi_detail', 'like', 'LHPD' . $tanggal . '%')->orderBy('nomor_laporan_produksi_detail', 'asc')->distinct()->get();

        if($cek_data_detail){
          $data_detail_count = $cek_data_detail->count();
          if($data_detail_count > 0){
            $num = (int) substr($cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail, 9);
            if($data_detail_count != $num){
              $nomor_laporan_produksi_detail = ++$cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail;
            }else{
              if($data_detail_count < 9){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9 && $data_detail_count < 99){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 99 && $data_detail_count < 999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 999 && $data_detail_count < 9999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9999 && $data_detail_count < 99999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0" . ($data_detail_count + 1);
              }else{
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-" . ($data_detail_count + 1);
              }
            }
          }else{
            $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
          }
        }else{
          $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
        }

        $data_cek = DB::table('products')->select('weight', 'saldo')->where('jenis_produk', "SSS30")->first();
        $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', "SSS30")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->first();

        ModelLaporanHasilProduksiDetail::insert(["nomor_laporan_produksi_detail" => $nomor_laporan_produksi_detail, "nomor_laporan_produksi" => $nomor_laporan_produksi, "mesin" => $value_mesin, "jenis_produk" => "SSS30", "jumlah_sak" => $row[13], "jumlah_tonase" => $row[13] * $data_cek->weight]);

        if($cek){
          DB::table('inventaris_produksi')->where('jenis_produk', "SSS30")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->update(['produksi' => ($cek->produksi + $row[13]), 'saldo' => ($cek->saldo + $row[13])]);
          DB::table('products')->where('jenis_produk', "SSS30")->update(['saldo' => ($cek->saldo + $row[13])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk SSS30 = ' . $row[13] . ' Sak. Total Saldo = ' . ($cek->saldo + $row[13]) . ' Sak']);
        }else{
          DB::table('inventaris_produksi')->insert(['tanggal' => $this->transformDate($row[0])->format('Y-m-d'), 'jenis_produk' => "SSS30", 'produksi' => $row[13], 'pengiriman' => 0, 'saldo' => ($data_cek->saldo + $row[13])]);
          DB::table('products')->where('jenis_produk', "SSS30")->update(['saldo' => ($data_cek->saldo + $row[13])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk SSS30 = ' . $row[13] . ' Sak. Total Saldo = ' . ($data_cek->saldo + $row[13]) . ' Sak']);
        }
      }

      if($row[14] != NULL || $row[14] != ''){
        $cek_data_detail = ModelLaporanHasilProduksiDetail::select('nomor_laporan_produksi_detail')->where('nomor_laporan_produksi_detail', 'like', 'LHPD' . $tanggal . '%')->orderBy('nomor_laporan_produksi_detail', 'asc')->distinct()->get();

        if($cek_data_detail){
          $data_detail_count = $cek_data_detail->count();
          if($data_detail_count > 0){
            $num = (int) substr($cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail, 9);
            if($data_detail_count != $num){
              $nomor_laporan_produksi_detail = ++$cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail;
            }else{
              if($data_detail_count < 9){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9 && $data_detail_count < 99){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 99 && $data_detail_count < 999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 999 && $data_detail_count < 9999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9999 && $data_detail_count < 99999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0" . ($data_detail_count + 1);
              }else{
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-" . ($data_detail_count + 1);
              }
            }
          }else{
            $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
          }
        }else{
          $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
        }

        $data_cek = DB::table('products')->select('weight', 'saldo')->where('jenis_produk', "AC30")->first();
        $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', "AC30")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->first();

        ModelLaporanHasilProduksiDetail::insert(["nomor_laporan_produksi_detail" => $nomor_laporan_produksi_detail, "nomor_laporan_produksi" => $nomor_laporan_produksi, "mesin" => $value_mesin, "jenis_produk" => "AC30", "jumlah_sak" => $row[14], "jumlah_tonase" => $row[14] * $data_cek->weight]);

        if($cek){
          DB::table('inventaris_produksi')->where('jenis_produk', "AC30")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->update(['produksi' => ($cek->produksi + $row[14]), 'saldo' => ($cek->saldo + $row[14])]);
          DB::table('products')->where('jenis_produk', "AC30")->update(['saldo' => ($cek->saldo + $row[14])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk AC30 = ' . $row[14] . ' Sak. Total Saldo = ' . ($cek->saldo + $row[14]) . ' Sak']);
        }else{
          DB::table('inventaris_produksi')->insert(['tanggal' => $this->transformDate($row[0])->format('Y-m-d'), 'jenis_produk' => "AC30", 'produksi' => $row[14], 'pengiriman' => 0, 'saldo' => ($data_cek->saldo + $row[14])]);
          DB::table('products')->where('jenis_produk', "AC30")->update(['saldo' => ($data_cek->saldo + $row[14])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk AC30 = ' . $row[14] . ' Sak. Total Saldo = ' . ($data_cek->saldo + $row[14]) . ' Sak']);
        }
      }

      if($row[15] != NULL || $row[15] != ''){
        $cek_data_detail = ModelLaporanHasilProduksiDetail::select('nomor_laporan_produksi_detail')->where('nomor_laporan_produksi_detail', 'like', 'LHPD' . $tanggal . '%')->orderBy('nomor_laporan_produksi_detail', 'asc')->distinct()->get();

        if($cek_data_detail){
          $data_detail_count = $cek_data_detail->count();
          if($data_detail_count > 0){
            $num = (int) substr($cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail, 9);
            if($data_detail_count != $num){
              $nomor_laporan_produksi_detail = ++$cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail;
            }else{
              if($data_detail_count < 9){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9 && $data_detail_count < 99){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 99 && $data_detail_count < 999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 999 && $data_detail_count < 9999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9999 && $data_detail_count < 99999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0" . ($data_detail_count + 1);
              }else{
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-" . ($data_detail_count + 1);
              }
            }
          }else{
            $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
          }
        }else{
          $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
        }

        $data_cek = DB::table('products')->select('weight', 'saldo')->where('jenis_produk', "NL25")->first();
        $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', "NL25")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->first();

        ModelLaporanHasilProduksiDetail::insert(["nomor_laporan_produksi_detail" => $nomor_laporan_produksi_detail, "nomor_laporan_produksi" => $nomor_laporan_produksi, "mesin" => $value_mesin, "jenis_produk" => "NL25", "jumlah_sak" => $row[15], "jumlah_tonase" => $row[15] * $data_cek->weight]);

        if($cek){
          DB::table('inventaris_produksi')->where('jenis_produk', "NL25")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->update(['produksi' => ($cek->produksi + $row[15]), 'saldo' => ($cek->saldo + $row[15])]);
          DB::table('products')->where('jenis_produk', "NL25")->update(['saldo' => ($cek->saldo + $row[15])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk NL25 = ' . $row[15] . ' Sak. Total Saldo = ' . ($cek->saldo + $row[15]) . ' Sak']);
        }else{
          DB::table('inventaris_produksi')->insert(['tanggal' => $this->transformDate($row[0])->format('Y-m-d'), 'jenis_produk' => "NL25", 'produksi' => $row[15], 'pengiriman' => 0, 'saldo' => ($data_cek->saldo + $row[15])]);
          DB::table('products')->where('jenis_produk', "NL25")->update(['saldo' => ($data_cek->saldo + $row[15])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk NL25 = ' . $row[15] . ' Sak. Total Saldo = ' . ($data_cek->saldo + $row[15]) . ' Sak']);
        }
      }

      if($row[16] != NULL || $row[16] != ''){
        $cek_data_detail = ModelLaporanHasilProduksiDetail::select('nomor_laporan_produksi_detail')->where('nomor_laporan_produksi_detail', 'like', 'LHPD' . $tanggal . '%')->orderBy('nomor_laporan_produksi_detail', 'asc')->distinct()->get();

        if($cek_data_detail){
          $data_detail_count = $cek_data_detail->count();
          if($data_detail_count > 0){
            $num = (int) substr($cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail, 9);
            if($data_detail_count != $num){
              $nomor_laporan_produksi_detail = ++$cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail;
            }else{
              if($data_detail_count < 9){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9 && $data_detail_count < 99){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 99 && $data_detail_count < 999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 999 && $data_detail_count < 9999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9999 && $data_detail_count < 99999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0" . ($data_detail_count + 1);
              }else{
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-" . ($data_detail_count + 1);
              }
            }
          }else{
            $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
          }
        }else{
          $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
        }

        $data_cek = DB::table('products')->select('weight', 'saldo')->where('jenis_produk', "JPAC")->first();
        $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', "JPAC")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->first();

        ModelLaporanHasilProduksiDetail::insert(["nomor_laporan_produksi_detail" => $nomor_laporan_produksi_detail, "nomor_laporan_produksi" => $nomor_laporan_produksi, "mesin" => $value_mesin, "jenis_produk" => "JPAC", "jumlah_sak" => $row[16], "jumlah_tonase" => $row[16] * $data_cek->weight]);

        if($cek){
          DB::table('inventaris_produksi')->where('jenis_produk', "JPAC")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->update(['produksi' => ($cek->produksi + $row[16]), 'saldo' => ($cek->saldo + $row[16])]);
          DB::table('products')->where('jenis_produk', "JPAC")->update(['saldo' => ($cek->saldo + $row[16])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk JPAC = ' . $row[16] . ' Sak. Total Saldo = ' . ($cek->saldo + $row[16]) . ' Sak']);
        }else{
          DB::table('inventaris_produksi')->insert(['tanggal' => $this->transformDate($row[0])->format('Y-m-d'), 'jenis_produk' => "JPAC", 'produksi' => $row[16], 'pengiriman' => 0, 'saldo' => ($data_cek->saldo + $row[16])]);
          DB::table('products')->where('jenis_produk', "JPAC")->update(['saldo' => ($data_cek->saldo + $row[16])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk JPAC = ' . $row[16] . ' Sak. Total Saldo = ' . ($data_cek->saldo + $row[16]) . ' Sak']);
        }
      }

      if($row[17] != NULL || $row[17] != ''){
        $cek_data_detail = ModelLaporanHasilProduksiDetail::select('nomor_laporan_produksi_detail')->where('nomor_laporan_produksi_detail', 'like', 'LHPD' . $tanggal . '%')->orderBy('nomor_laporan_produksi_detail', 'asc')->distinct()->get();

        if($cek_data_detail){
          $data_detail_count = $cek_data_detail->count();
          if($data_detail_count > 0){
            $num = (int) substr($cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail, 9);
            if($data_detail_count != $num){
              $nomor_laporan_produksi_detail = ++$cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail;
            }else{
              if($data_detail_count < 9){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9 && $data_detail_count < 99){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 99 && $data_detail_count < 999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 999 && $data_detail_count < 9999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9999 && $data_detail_count < 99999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0" . ($data_detail_count + 1);
              }else{
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-" . ($data_detail_count + 1);
              }
            }
          }else{
            $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
          }
        }else{
          $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
        }

        $data_cek = DB::table('products')->select('weight', 'saldo')->where('jenis_produk', "JSW")->first();
        $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', "JSW")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->first();

        ModelLaporanHasilProduksiDetail::insert(["nomor_laporan_produksi_detail" => $nomor_laporan_produksi_detail, "nomor_laporan_produksi" => $nomor_laporan_produksi, "mesin" => $value_mesin, "jenis_produk" => "JSW", "jumlah_sak" => $row[17], "jumlah_tonase" => $row[17] * $data_cek->weight]);

        if($cek){
          DB::table('inventaris_produksi')->where('jenis_produk', "JSW")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->update(['produksi' => ($cek->produksi + $row[17]), 'saldo' => ($cek->saldo + $row[17])]);
          DB::table('products')->where('jenis_produk', "JSW")->update(['saldo' => ($cek->saldo + $row[17])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk JSW = ' . $row[17] . ' Sak. Total Saldo = ' . ($cek->saldo + $row[17]) . ' Sak']);
        }else{
          DB::table('inventaris_produksi')->insert(['tanggal' => $this->transformDate($row[0])->format('Y-m-d'), 'jenis_produk' => "JSW", 'produksi' => $row[17], 'pengiriman' => 0, 'saldo' => ($data_cek->saldo + $row[17])]);
          DB::table('products')->where('jenis_produk', "JSW")->update(['saldo' => ($data_cek->saldo + $row[17])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk JSW = ' . $row[17] . ' Sak. Total Saldo = ' . ($data_cek->saldo + $row[17]) . ' Sak']);
        }
      }

      if($row[18] != NULL || $row[18] != ''){
        $cek_data_detail = ModelLaporanHasilProduksiDetail::select('nomor_laporan_produksi_detail')->where('nomor_laporan_produksi_detail', 'like', 'LHPD' . $tanggal . '%')->orderBy('nomor_laporan_produksi_detail', 'asc')->distinct()->get();

        if($cek_data_detail){
          $data_detail_count = $cek_data_detail->count();
          if($data_detail_count > 0){
            $num = (int) substr($cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail, 9);
            if($data_detail_count != $num){
              $nomor_laporan_produksi_detail = ++$cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail;
            }else{
              if($data_detail_count < 9){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9 && $data_detail_count < 99){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 99 && $data_detail_count < 999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 999 && $data_detail_count < 9999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9999 && $data_detail_count < 99999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0" . ($data_detail_count + 1);
              }else{
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-" . ($data_detail_count + 1);
              }
            }
          }else{
            $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
          }
        }else{
          $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
        }

        $data_cek = DB::table('products')->select('weight', 'saldo')->where('jenis_produk', "JAA")->first();
        $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', "JAA")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->first();

        ModelLaporanHasilProduksiDetail::insert(["nomor_laporan_produksi_detail" => $nomor_laporan_produksi_detail, "nomor_laporan_produksi" => $nomor_laporan_produksi, "mesin" => $value_mesin, "jenis_produk" => "JAA", "jumlah_sak" => $row[18], "jumlah_tonase" => $row[18] * $data_cek->weight]);

        if($cek){
          DB::table('inventaris_produksi')->where('jenis_produk', "JAA")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->update(['produksi' => ($cek->produksi + $row[18]), 'saldo' => ($cek->saldo + $row[18])]);
          DB::table('products')->where('jenis_produk', "JSW")->update(['saldo' => ($cek->saldo + $row[18])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk JSW = ' . $row[18] . ' Sak. Total Saldo = ' . ($cek->saldo + $row[18]) . ' Sak']);
        }else{
          DB::table('inventaris_produksi')->insert(['tanggal' => $this->transformDate($row[0])->format('Y-m-d'), 'jenis_produk' => "JSW", 'produksi' => $row[18], 'pengiriman' => 0, 'saldo' => ($data_cek->saldo + $row[18])]);
          DB::table('products')->where('jenis_produk', "JSW")->update(['saldo' => ($data_cek->saldo + $row[18])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk JSW = ' . $row[18] . ' Sak. Total Saldo = ' . ($data_cek->saldo + $row[18]) . ' Sak']);
        }
      }

      if($row[19] != NULL || $row[19] != ''){
        $cek_data_detail = ModelLaporanHasilProduksiDetail::select('nomor_laporan_produksi_detail')->where('nomor_laporan_produksi_detail', 'like', 'LHPD' . $tanggal . '%')->orderBy('nomor_laporan_produksi_detail', 'asc')->distinct()->get();

        if($cek_data_detail){
          $data_detail_count = $cek_data_detail->count();
          if($data_detail_count > 0){
            $num = (int) substr($cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail, 9);
            if($data_detail_count != $num){
              $nomor_laporan_produksi_detail = ++$cek_data_detail[$cek_data_detail->count() - 1]->nomor_laporan_produksi_detail;
            }else{
              if($data_detail_count < 9){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9 && $data_detail_count < 99){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 99 && $data_detail_count < 999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000" . ($data_detail_count + 1);
              }else if($data_detail_count >= 999 && $data_detail_count < 9999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-00" . ($data_detail_count + 1);
              }else if($data_detail_count >= 9999 && $data_detail_count < 99999){
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-0" . ($data_detail_count + 1);
              }else{
                $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-" . ($data_detail_count + 1);
              }
            }
          }else{
            $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
          }
        }else{
          $nomor_laporan_produksi_detail = "LHPD" . $tanggal . "-000001";
        }

        $data_cek = DB::table('products')->select('weight', 'saldo')->where('jenis_produk', "Polos40")->first();
        $cek = DB::table('inventaris_produksi')->select('tanggal', 'jenis_produk', 'produksi', 'pengiriman', 'saldo')->where('jenis_produk', "Polos40")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->first();

        ModelLaporanHasilProduksiDetail::insert(["nomor_laporan_produksi_detail" => $nomor_laporan_produksi_detail, "nomor_laporan_produksi" => $nomor_laporan_produksi, "mesin" => $value_mesin, "jenis_produk" => "Polos40", "jumlah_sak" => $row[19], "jumlah_tonase" => $row[19] * $data_cek->weight]);

        if($cek){
          DB::table('inventaris_produksi')->where('jenis_produk', "Polos40")->where('tanggal', $this->transformDate($row[0])->format('Y-m-d'))->update(['produksi' => ($cek->produksi + $row[19]), 'saldo' => ($cek->saldo + $row[19])]);
          DB::table('products')->where('jenis_produk', "Polos40")->update(['saldo' => ($cek->saldo + $row[19])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk Polos40 = ' . $row[19] . ' Sak. Total Saldo = ' . ($cek->saldo + $row[19]) . ' Sak']);
        }else{
          DB::table('inventaris_produksi')->insert(['tanggal' => $this->transformDate($row[0])->format('Y-m-d'), 'jenis_produk' => "Polos40", 'produksi' => $row[19], 'pengiriman' => 0, 'saldo' => ($data_cek->saldo + $row[19])]);
          DB::table('products')->where('jenis_produk', "Polos40")->update(['saldo' => ($data_cek->saldo + $row[19])]);

          date_default_timezone_set('Asia/Jakarta');
          DB::table('logbook_inventaris')->insert(['tanggal' => date("Y-m-d H:i:s"), 'id_user' => Session::get('id_user_admin'), 'status' => 1, 'action' => 'User ' . Session::get('id_user_admin') . ' Add Saldo Produk Polos40 = ' . $row[19] . ' Sak. Total Saldo = ' . ($data_cek->saldo + $row[19]) . ' Sak']);
        }
      }
    }

    public function startRow(): int
    {
        return 2;
    }
}
