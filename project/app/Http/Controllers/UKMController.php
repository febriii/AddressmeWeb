<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash; // manggil fungsi HASH
use App\UKMModel; // panggil nama model dari controller ini
use App\PemilikModel;
use DB; // manggil fungsi explicit DB

class UKMController extends Controller
{

    private $UKMModel;

    public function __construct(UKMModel $UKMModel)
    {
        $this->UKMModel = $UKMModel;
        $this->middleware('isAdmin');
    }

    public function index(Request $request)
    {
        $dataUKM = $this->UKMModel->getAllDataUKM($request);

        return view('list-ukm.index', compact('dataUKM'));
    }

    public function create()
    {
        $namaPemilik = PemilikModel::select('id','name')->where('status','1')->get();

        return view('list-ukm.create', compact('namaPemilik'));
    }

    public function store(Request $request)
    {
        $dataUKM = new UKMModel();
        $dataUKM->id_ukm = $request->id_ukm;
        $dataUKM->nama_ukm = $request->nama_ukm;
        $dataUKM->alamat = $request->alamat;
        $hp = $this->autoCorrectNumber($request->no_telp);
        $dataUKM->no_telp = $hp;
        $dataUKM->website = $request->website;
        $dataUKM->lat = $request->lat;
        $dataUKM->lng = $request->lng;
        $dataUKM->gambar_ukm = $request->gambar_ukm;
        $dataUKM->id_user = $request->id_pemilik;
        $dataUKM->save();
        
        return redirect()->route('list-ukm.index')->withStatus(__('Data berhasil ditambahkan!'));
    }

    public function edit($id)
    {
        $data = UKMModel::where('id',$id)->first(); // pengecekan data dan mengambil data

        if($data == null)
        {
            return redirect()->route('list-ukm.index');
        }
        else
        {
            return view('list-ukm.edit', compact('data'));
        }
        
    }

    public function update(Request $request, $id)
    {
        $dataUKM = UKMModel::where('id',$id)->first();
        $dataUKM->id_ukm = $request->id_ukm;
        $dataUKM->nama_ukm = $request->nama_ukm;
        $dataUKM->alamat = $request->alamat;
        $dataUKM->no_telp = $request->no_telp;
        $dataUKM->website = $request->website;
        $dataUKM->lat = $request->lat;
        $dataUKM->lng = $request->lng;
        $dataUKM->gambar_ukm = $request->gambar_ukm;
        $dataUKM->id_user = $request->id_pemilik;
        $dataUKM->save();

        return redirect()->route('list-ukm.index')->withStatus(__('Data berhasil diedit!'));
        
    }

    public function destroy(User  $user)
    {

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
