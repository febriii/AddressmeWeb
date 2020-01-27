<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash; // manggil fungsi HASH
use App\PUKMModel; // panggil nama model dari controller ini
use App\PemilikModel;
use DB; // manggil fungsi explicit DB

class PUKMController extends Controller
{
    public function edit()
    {
        $data = PUKMModel::where('id_user',auth()->user()->id)->first(); // pengecekan data dan mengambil data
        
        return view('list-p-ukm.edit', compact('data'));       
    }

    public function update(Request $request)
    {
        $dataUKM = PUKMModel::where('id_user',auth()->user()->id)->first();
        // dd(auth()->user()->id);
        $dataUKM->nama_ukm = $request->nama_ukm;
        $dataUKM->alamat = $request->alamat;
        $dataUKM->no_telp = $request->no_telp;
        $dataUKM->website = $request->website;
        $dataUKM->lat = $request->lat;
        $dataUKM->lng = $request->lng;
        $dataUKM->gambar_ukm = $request->gambar_ukm;
        $dataUKM->save();

        return back()->withStatus(__('Data UKM berhasil diperbaharui.'));
        
    }

    public function autoCorrectNumber($nohp)
    {
        if(!preg_match('/[^+0-9]/',trim($nohp))){
            if(substr(trim($nohp), 0, 2)=='62'){
                $hp = trim($nohp);
            }
            elseif(substr(trim($nohp), 0, 1)=='0'){
                $hp = '62'.substr(trim($nohp), 1);
            }
            elseif(substr(trim($nohp), 0, 1)=='8'){
                $hp = '628'.substr(trim($nohp), 1);
            }
            else
            {
                $hp = '0';
            }
        }
        return $hp;
    }
}
