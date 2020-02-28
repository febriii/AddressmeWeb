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
        if($data == null)
        {
            // return back();
            return redirect()->route('home');
        }
        else
        {
            return view('list-p-ukm.edit', compact('data'));
        }       
    }

    public function update(Request $request)
    {
        // dd(auth()->user()->id);
        $attributes = [
            'nama_ukm' => 'nama_ukm',
            'alamat' => 'alamat',
            'no_telp' => 'no_telp',
            'website' => 'website',
            'lat' => 'lat',
            'lng' => 'lng',
        ];

        $request->validate([
            'nama_ukm' => ['required', 'string', 'alamat_validation', 'min:3', 'max:23'],
            'alamat' => ['required', 'string', 'alamat_validation', 'max:255'],
            'no_telp' => ['required', 'no_telp_validation', 'min:10', 'max:13'],
            'website' => ['required', 'string', 'alamat_validation', 'max:255'],
            'lat' => ['required', 'latlng_validation'],
            'lng' => ['required', 'latlng_validation'],
        ], [], $attributes);

        $file = $request->file('gambar_ukm');
        if($file == null){
            $dataUKM = PUKMModel::where('id_user',auth()->user()->id)->first();
            // dd(auth()->user()->id);
            $dataUKM->nama_ukm = $request->nama_ukm;
            $dataUKM->alamat = $request->alamat;
            $hp = $this->autoCorrectNumber($request->no_telp);
            $dataUKM->no_telp = $hp;
            $dataUKM->website = $request->website;
            $dataUKM->lat = $request->lat;
            $dataUKM->lng = $request->lng;
            $dataUKM->save();

            return back()->withStatus(__('Data UKM berhasil diperbaharui.'));
        }else{
            $shortnameGambarUKM = $file->getClientOriginalName();
            /* mengatur ekstensi file yg diunggah */
            $ekstensi_diperbolehkan	= array('png','jpg','jpeg');
            $x = explode('.', $shortnameGambarUKM);
            
            $ekstensi = strtolower(end($x));
            $ukuran	= $file->getSize();

            if (!file_exists("data/")) {
                mkdir("data/");
            }

            if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
                if($ukuran < 5000000){
                    /* Ambil data untuk keperluan format penamaan berkas */
                    $getIDUKM = PUKMModel::where('id_user', auth()->user()->id)->value('id_ukm');
                    $getIDUser = auth()->user()->id;
                    $inisial = 'GBR-UKM';
                    /* formatnya */
                    $formatNama = date('d-m-Y-H-i-s') . '-' . $inisial . '-' . $getIDUKM . '-' . $getIDUser . '.' . $ekstensi;

                    $sourcePathGambarUKM = $file->getRealPath();
                    $targetPathGambarUKM = "data/" . $formatNama;
                    move_uploaded_file($sourcePathGambarUKM, $targetPathGambarUKM);
                
                    /* mengambil data nama file lama */
                    $getGambarLama = PUKMModel::where('id_user', auth()->user()->id)->value('gambar_ukm');
                    $hapusGambar = "data/" . $getGambarLama;
                    if(file_exists($hapusGambar)){
                        unlink($hapusGambar);
                    }

                    $dataUKM = PUKMModel::where('id_user',auth()->user()->id)->first();
                    $dataUKM->nama_ukm = $request->nama_ukm;
                    $dataUKM->alamat = $request->alamat;
                    $hp = $this->autoCorrectNumber($request->no_telp);
                    $dataUKM->no_telp = $hp;
                    $dataUKM->website = $request->website;
                    $dataUKM->lat = $request->lat;
                    $dataUKM->lng = $request->lng;
                    $dataUKM->gambar_ukm = $formatNama;
                    $dataUKM->save();

                    return back()->withStatus(__('Data UKM berhasil diperbaharui.'));
                }else{
                    return redirect()->back()->withInput()->withErrors([
                        'gambar_ukm' => 'Ukuran Gambar/Logo UKM Terlalu Besar.'
                    ]);
                }
            }else{
                return redirect()->back()->withInput()->withErrors([
                    'gambar_ukm' => 'Ekstensi Gambar/Logo UKM Tidak Diperbolehkan.'
                ]);
            }
        }
        
    }

    public function autoCorrectNumber($nohp)
    {
        if(!preg_match('/[^+0-9]/',trim($nohp))){
            if(substr(trim($nohp), 0, 1)=='0'){
                $hp = trim($nohp);
            }
            elseif(substr(trim($nohp), 0, 2)=='62'){
                $hp = '0'.substr(trim($nohp), 2);
            }
            elseif(substr(trim($nohp), 0, 1)=='8'){
                $hp = '08'.substr(trim($nohp), 1);
            }
            elseif(substr(trim($nohp), 0, 1)=='2'){
                $hp = '02'.substr(trim($nohp), 1);
            }
            // if(substr(trim($nohp), 0, 2)=='62'){
            //     $hp = trim($nohp);
            // }
            // elseif(substr(trim($nohp), 0, 1)=='0'){
            //     $hp = '62'.substr(trim($nohp), 1);
            // }
            // elseif(substr(trim($nohp), 0, 1)=='8'){
            //     $hp = '628'.substr(trim($nohp), 1);
            // }
            // elseif(substr(trim($nohp), 0, 1)=='2'){
            //     $hp = '622'.substr(trim($nohp), 1);
            // }
            // else
            // {
            //     $hp = '0';
            // }
        }
        return $hp;
    }
}
