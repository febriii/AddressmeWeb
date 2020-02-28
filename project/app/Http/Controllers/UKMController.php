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
        // dd($dataUKM);

        return view('list-ukm.index', compact('dataUKM'));
    }

    public function create()
    {
        $namaPemilik = PemilikModel::select('id','name')->where('status','1')->get();

        return view('list-ukm.create', compact('namaPemilik'));
    }

    public function store(Request $request)
    {
        $attributes = [
            'id_ukm' => 'id_ukm',
            'nama_ukm' => 'nama_ukm',
            'alamat' => 'alamat',
            'no_telp' => 'no_telp',
            'website' => 'website',
            'lat' => 'lat',
            'lng' => 'lng',
        ];

        $request->validate([
            'id_ukm' => ['required', 'string', 'alpha_underscore', 'min:7', 'max:8', 'unique:list_ukm'],
            'nama_ukm' => ['required', 'string', 'alamat_validation', 'min:3', 'max:23'],
            'alamat' => ['required', 'string', 'alamat_validation', 'max:255'],
            'no_telp' => ['required', 'no_telp_validation', 'min:10', 'max:13'],
            'website' => ['required', 'string', 'alamat_validation', 'max:255'],
            'lat' => ['required', 'latlng_validation'],
            'lng' => ['required', 'latlng_validation'],
        ], [], $attributes);

        $file = $request->file('gambar_ukm');
        if($file == null){
            $dataUKM = new UKMModel();
            $dataUKM->id_ukm = $request->id_ukm;
            $dataUKM->nama_ukm = $request->nama_ukm;
            $dataUKM->alamat = $request->alamat;
            $hp = $this->autoCorrectNumber($request->no_telp);
            $dataUKM->no_telp = $hp;
            $dataUKM->website = $request->website;
            $dataUKM->lat = $request->lat;
            $dataUKM->lng = $request->lng;
            $dataUKM->id_user = $request->id_pemilik;
            $dataUKM->save();
            
            return redirect()->route('list-ukm.index')->withStatus(__('Data berhasil ditambahkan!'));
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
                    $getIDUKM = $request->id_ukm;
                    $getIDUser = $request->id_pemilik;
                    $inisial = 'GBR-UKM';
                    /* formatnya */
                    $formatNama = date('d-m-Y-H-i-s') . '-' . $inisial . '-' . $getIDUKM . '-' . $getIDUser . '.' . $ekstensi;

                    $sourcePathGambarUKM = $file->getRealPath();
                    $targetPathGambarUKM = "data/" . $formatNama;
                    move_uploaded_file($sourcePathGambarUKM, $targetPathGambarUKM);

                    $dataUKM = new UKMModel();
                    $dataUKM->id_ukm = $request->id_ukm;
                    $dataUKM->nama_ukm = $request->nama_ukm;
                    $dataUKM->alamat = $request->alamat;
                    $hp = $this->autoCorrectNumber($request->no_telp);
                    $dataUKM->no_telp = $hp;
                    $dataUKM->website = $request->website;
                    $dataUKM->lat = $request->lat;
                    $dataUKM->lng = $request->lng;
                    $dataUKM->gambar_ukm = $formatNama;
                    $dataUKM->id_user = $request->id_pemilik;
                    $dataUKM->save();
                    
                    return redirect()->route('list-ukm.index')->withStatus(__('Data berhasil ditambahkan!'));
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

    public function edit($id)
    {
        // $pathheader = UKMModel::find($id)->gambar_ukm;
        // dd($pathheader);
        $data = UKMModel::where('id_ukm',$id)->first(); // pengecekan data dan mengambil data
        // dd($data);
        $namaPemilik = PemilikModel::select('id','name')->where('status','1')->get();
        $getIdPemilik = UKMModel::where('id_ukm',$id)->value('id_user');

        if($data == null)
        {
            return redirect()->route('list-ukm.index');
        }
        else
        {
            return view('list-ukm.edit', compact('data', 'namaPemilik', 'getIdPemilik'));
        }
        
    }

    public function update(Request $request, $id)
    {
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
            $dataUKM = UKMModel::where('id',$id)->first();
            // dd($dataUKM);
            $dataUKM->nama_ukm = $request->nama_ukm;
            $dataUKM->alamat = $request->alamat;
            $hp = $this->autoCorrectNumber($request->no_telp);
            $dataUKM->no_telp = $hp;
            $dataUKM->website = $request->website;
            $dataUKM->lat = $request->lat;
            $dataUKM->lng = $request->lng;
            $dataUKM->id_user = $request->id_pemilik;
            $dataUKM->save();

            return redirect()->route('list-ukm.index')->withStatus(__('Data berhasil diubah!'));
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
                    $getIDUKM = UKMModel::where('id',$id)->value('id_ukm');
                    $getIDUser = $request->id_pemilik;
                    $inisial = 'GBR-UKM';
                    /* formatnya */
                    $formatNama = date('d-m-Y-H-i-s') . '-' . $inisial . '-' . $getIDUKM . '-' . $getIDUser . '.' . $ekstensi;
    
                    $sourcePathGambarUKM = $file->getRealPath();
                    $targetPathGambarUKM = "data/" . $formatNama;
                    move_uploaded_file($sourcePathGambarUKM, $targetPathGambarUKM);
    
                    /* mengambil data nama file lama */
                    $getGambarLama = UKMModel::where('id',$id)->value('gambar_ukm');
                    $hapusGambar = "data/" . $getGambarLama;
                    if(file_exists($hapusGambar)){
                        unlink($hapusGambar);
                    }

                    $dataUKM = UKMModel::where('id',$id)->first();
                    $dataUKM->nama_ukm = $request->nama_ukm;
                    $dataUKM->alamat = $request->alamat;
                    $hp = $this->autoCorrectNumber($request->no_telp);
                    $dataUKM->no_telp = $hp;
                    $dataUKM->website = $request->website;
                    $dataUKM->lat = $request->lat;
                    $dataUKM->lng = $request->lng;
                    $dataUKM->gambar_ukm = $formatNama;
                    $dataUKM->id_user = $request->id_pemilik;
                    $dataUKM->save();
                    
                    return redirect()->route('list-ukm.index')->withStatus(__('Data berhasil ditambahkan!'));
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

    public function destroy($data)
    {
        $getDataID = UKMModel::where('id_ukm',$data)->value('id');
        $getDatas = UKMModel::where('id',$getDataID)->first();
        // dd($getDatas);
        $getDatas->delete();

        return redirect()->route('list-ukm.index')->withStatus(__('Data berhasil dihapus!'));
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
