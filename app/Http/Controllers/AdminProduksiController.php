<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminProduksiController extends Controller
{
    public function index(Request $request){
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $omset_produksi = DB::table('omset_produksi as produksi')->select("products.nama_produk as produk", "produksi.tanggal_produksi as tanggal", DB::raw('sum(produksi.jumlah_customer) as jumlah_customer'), DB::raw('sum(produksi.jumlah_tonase_per_mesin) as jumlah_tonase_per_mesin'), DB::raw('sum(produksi.jumlah_tonase) as jumlah_tonase'), DB::raw('sum(produksi.total_omset) as total_omset'), "produksi.whiteness as whiteness", "produksi.residue as residue", "produksi.mean_particle_diameter as mean_particle_diameter", "produksi.moisture as moisture", "sp.name as standard_packaging", "produksi.weight as weight", "produksi.mesh as mesh")->join("products", "products.kode_produk_komputer", "=", "produksi.kode_produk")->join("standard_packaging as sp", "sp.id_standard_packaging", "=", "produksi.standard_packaging")->whereBetween('produksi.tanggal_produksi', array($request->from_date, $request->to_date))->groupBy('produk', 'tanggal', 'whiteness', 'residue', 'mean_particle_diameter', 'moisture', 'standard_packaging', 'weight', 'mesh')->get();
            }else{
                $omset_produksi = DB::table('omset_produksi as produksi')->select("products.nama_produk as produk", "produksi.tanggal_produksi as tanggal", DB::raw('sum(produksi.jumlah_customer) as jumlah_customer'), DB::raw('sum(produksi.jumlah_tonase_per_mesin) as jumlah_tonase_per_mesin'), DB::raw('sum(produksi.jumlah_tonase) as jumlah_tonase'), DB::raw('sum(produksi.total_omset) as total_omset'), "produksi.whiteness as whiteness", "produksi.residue as residue", "produksi.mean_particle_diameter as mean_particle_diameter", "produksi.moisture as moisture", "sp.name as standard_packaging", "produksi.weight as weight", "produksi.mesh as mesh")->join("products", "products.kode_produk_komputer", "=", "produksi.kode_produk")->join("standard_packaging as sp", "sp.id_standard_packaging", "=", "produksi.standard_packaging")->whereDate('produksi.tanggal_produksi', Carbon::now()->toDateString())->groupBy('produk', 'tanggal', 'whiteness', 'residue', 'mean_particle_diameter', 'moisture', 'standard_packaging', 'weight', 'mesh')->get();
            }

            return datatables()->of($omset_produksi)->make(true);
        }
        return view('produksi_admin');
    }
}
