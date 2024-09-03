<?php

namespace App\Http\Middleware;

use App\ModelUjiSample;

use Illuminate\Support\Facades\Session;
use Closure;

class RequestUjiSample
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Session::get('tipe_user') == 3){
            $data_prd = ModelUjiSample::select('nomor_uji_sample', 'tanggal', 'merk', 'tipe', 'status', 'tanggal_pengujian_sample')->where(function($query) { $query->where('status', 2)->orWhere('status', 3); })->whereNull('analisa')->whereRaw("tanggal_pengujian_sample <= NOW() - INTERVAL 3 DAY")->get();

            if($data_prd->isEmpty()){
                return $next($request);
            }else{
                return redirect('produksi/uji_sample');
            }
        }else if(Session::get('tipe_user') == 9){
            $data_lab = ModelUjiSample::select('nomor_uji_sample', 'tanggal', 'merk', 'tipe', 'status', 'tanggal_pengujian_sample')->whereRaw("tanggal <= NOW() - INTERVAL 8 DAY")->where('status', 1)->get();

            if($data_lab->isEmpty()){
                return $next($request);
            }else{
                return redirect('lab/uji_sample');
            }
        }else{
            return $next($request);
        }
    }
}
