<?php

namespace App\Imports;

use App\ModelLaporanHasilProduksi;
use App\ModelLaporanHasilLab;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LaporanHasilLabImport implements ToModel, WithHeadingRow, WithStartRow
{

    private $baris = 0;
    private $tanggal = NULL;
    private $duplikat = 0;
    private $nomor_laporan_produksi1 = NULL;
    private $nomor_laporan_produksi2 = NULL;
    private $no_mesin = 0;

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
    	++$this->baris;
    	$data_mesin = ['SA' => 1, 'SB' => 2, 'Mixer' => 3, 'RA' => 4, 'RB' => 5, 'RC' => 6, 'RD' => 7, 'RE' => 8, 'RF' => 9, 'RG' => 10];

    	if($this->baris == 2){
    		$this->tanggal = $this->transformDate($row['MESH'])->format('Y-m-d');
	    	$tanggal_no = $this->transformDate($row['MESH'])->format('ym');

    		if(!ModelLaporanHasilProduksi::where('tanggal_laporan_produksi', '=', $this->tanggal)->exists()) {
	            $this->duplikat = 1;

	    		$cek_data_laporan = ModelLaporanHasilProduksi::select('nomor_laporan_produksi')->where('nomor_laporan_produksi', 'like', 'LHP' . $tanggal_no . '%')->orderBy('nomor_laporan_produksi', 'asc')->distinct()->get();

	    		if($cek_data_laporan){
	    			$data_laporan_count = $cek_data_laporan->count();
	    			if($data_laporan_count > 0){
	    				$num = (int) substr($cek_data_laporan[$cek_data_laporan->count() - 1]->nomor_laporan_produksi, 8);
	    				if($data_laporan_count != $num){
	    					$this->nomor_laporan_produksi1 = ++$cek_data_laporan[$cek_data_laporan->count() - 1]->nomor_laporan_produksi;
	    				}else{
	    					if($data_laporan_count < 9){
	    						$this->nomor_laporan_produksi1 = "LHP" . $tanggal_no . "-000" . ($data_laporan_count + 1);
	    					}else if($data_laporan_count >= 9 && $data_laporan_count < 99){
	    						$this->nomor_laporan_produksi1 = "LHP" . $tanggal_no . "-00" . ($data_laporan_count + 1);
	    					}else if($data_laporan_count >= 99 && $data_laporan_count < 999){
	    						$this->nomor_laporan_produksi1 = "LHP" . $tanggal_no . "-0" . ($data_laporan_count + 1);
	    					}else{
	    						$this->nomor_laporan_produksi1 = "LHP" . $tanggal_no . "-" . ($data_laporan_count + 1);
	    					}
	    				}
	    			}else{
	    				$this->nomor_laporan_produksi1 = "LHP" . $tanggal_no . "-0001";
	    			}
	    		}else{
	    			$this->nomor_laporan_produksi1 = "LHP" . $tanggal_no . "-0001";
	    		}

	    		date_default_timezone_set('Asia/Jakarta');
	        	$data = ModelLaporanHasilProduksi::insert(["nomor_laporan_produksi" => $this->nomor_laporan_produksi1, "tanggal_laporan_produksi" => $this->tanggal, "tanggal_input" => date('Y-m-d'), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s"), "created_by" => Session::get('id_user_admin'), "updated_by" => Session::get('id_user_admin')]);

	        	$tmp_nomor = $this->nomor_laporan_produksi1;

	        	$this->nomor_laporan_produksi2 = ++$tmp_nomor;

	        	date_default_timezone_set('Asia/Jakarta');
	        	$data = ModelLaporanHasilProduksi::insert(["nomor_laporan_produksi" => $this->nomor_laporan_produksi2, "tanggal_laporan_produksi" => $this->tanggal, "tanggal_input" => date('Y-m-d'), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s"), "created_by" => Session::get('id_user_admin'), "updated_by" => Session::get('id_user_admin')]);
	        }else{
	        	$this->duplikat = 0;
	        }
    	}else if($this->baris > 11 && $this->baris < 33){
    		$tanggal_no = $this->transformDate($this->tanggal)->format('ym');

    		if($this->duplikat == 1){
	    		if(isset($row['Moist']) || !empty($row['Moist'])){
	    			$cek_data_lab = ModelLaporanHasilLab::select('nomor_laporan_produksi_lab')->where('nomor_laporan_produksi_lab', 'like', 'LHPL' . $tanggal_no . '%')->orderBy('nomor_laporan_produksi_lab', 'asc')->distinct()->get();

	    			if(!isset($row['d-50']) || empty($row['d-50'])){
	                    $row['d-50'] = 0;
	                }

	    			if(!isset($row[3]) || empty($row[3])){
	                    $row[3] = 0;
	                }

	                if(!isset($row['d-98']) || empty($row['d-98'])){
	                    $row['d-98'] = 0;
	                }

	    			if(!isset($row[4]) || empty($row[4])){
	                    $row[4] = 0;
	                }

	                if(!isset($row['Whiteness']) || empty($row['Whiteness'])){
	                    $row['Whiteness'] = 0;
	                }

	                if(!isset($row[5]) || empty($row[5])){
	                    $row[5] = 0;
	                }

	                if(!isset($row['Moist']) || empty($row['Moist'])){
	                    $row['Moist'] = 0;
	                }

	                if(!isset($row[6]) || empty($row[6])){
	                    $row[6] = 0;
	                }

	                if($cek_data_lab){
	                    $data_lab_count = $cek_data_lab->count();
	                    if($data_lab_count > 0){
	                        $num = (int) substr($cek_data_lab[$cek_data_lab->count() - 1]->nomor_laporan_produksi_lab, 9);
	                        if($data_lab_count != $num){
	                            $nomor_laporan_produksi_lab = ++$cek_data_lab[$cek_data_lab->count() - 1]->nomor_laporan_produksi_lab;
	                        }else{
	                            if($data_lab_count < 9){
	                                $nomor_laporan_produksi_lab = "LHPL" . $tanggal_no . "-00000" . ($data_lab_count + 1);
	                            }else if($data_lab_count >= 9 && $data_lab_count < 99){
	                                $nomor_laporan_produksi_lab = "LHPL" . $tanggal_no . "-0000" . ($data_lab_count + 1);
	                            }else if($data_lab_count >= 99 && $data_lab_count < 999){
	                                $nomor_laporan_produksi_lab = "LHPL" . $tanggal_no . "-000" . ($data_lab_count + 1);
	                            }else if($data_lab_count >= 999 && $data_lab_count < 9999){
	                                $nomor_laporan_produksi_lab = "LHPL" . $tanggal_no . "-00" . ($data_lab_count + 1);
	                            }else if($data_lab_count >= 9999 && $data_lab_count < 99999){
	                                $nomor_laporan_produksi_lab = "LHPL" . $tanggal_no . "-0" . ($data_lab_count + 1);
	                            }else{
	                                $nomor_laporan_produksi_lab = "LHPL" . $tanggal_no . "-" . ($data_lab_count + 1);
	                            }
	                        }
	                    }else{
	                        $nomor_laporan_produksi_lab = "LHPL" . $tanggal_no . "-000001";
	                    }
	                }else{
	                    $nomor_laporan_produksi_lab = "LHPL" . $tanggal_no . "-000001";
	                }

	                if(isset($row['MESIN']) || !empty($row['MESIN'])){
	                    $this->no_mesin = $row['MESIN'];

	                    $data_lab = ModelLaporanHasilLab::insert(["nomor_laporan_produksi_lab" => $nomor_laporan_produksi_lab, "nomor_laporan_produksi" => $this->nomor_laporan_produksi1, "jam_waktu" => $row[0], "mesin" => $data_mesin[$this->no_mesin], "mesh" => $row['MESH'], "std_ssa" => $row['SSA'], "ssa" => $row[2], "std_d50" => str_replace(',', '.', $row['d-50']), "d50" => str_replace(',', '.', $row[3]), "std_d98" => str_replace(',', '.', $row['d-98']), "d98" => str_replace(',', '.', $row[4]), "cie86" => str_replace(',', '.', $row['Whiteness']), "iso2470" => str_replace(',', '.', $row[5]), "moisture" => str_replace(',', '.', $row['Moist']), "residue" => str_replace(',', '.', $row[6])]);
	                }else{
	                	$data_lab = ModelLaporanHasilLab::insert(["nomor_laporan_produksi_lab" => $nomor_laporan_produksi_lab, "nomor_laporan_produksi" => $this->nomor_laporan_produksi2, "jam_waktu" => $row[0], "mesin" => $data_mesin[$this->no_mesin], "mesh" => $row['MESH'], "std_ssa" => $row['SSA'], "ssa" => $row[2], "std_d50" => str_replace(',', '.', $row['d-50']), "d50" => str_replace(',', '.', $row[3]), "std_d98" => str_replace(',', '.', $row['d-98']), "d98" => str_replace(',', '.', $row[4]), "cie86" => str_replace(',', '.', $row['Whiteness']), "iso2470" => str_replace(',', '.', $row[5]), "moisture" => str_replace(',', '.', $row['Moist']), "residue" => str_replace(',', '.', $row[6])]);
	                }
	    		}
	    	}
    	}
    }

    public function headingRow(): int
    {
    	return 10;
    }

    public function startRow(): int
    {
        return 1;
    }

    public function getDuplikat(): int
    {
        return $this->duplikat;
    }

    public function getNomorLaporan1(): String
    {
        return $this->nomor_laporan_produksi1;
    }

    public function getNomorLaporan2(): String
    {
        return $this->nomor_laporan_produksi2;
    }
}
