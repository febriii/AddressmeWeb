<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use App\KatalogModel;
use App\UKMModel;
use App\PemilikModel;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KatalogController extends Controller
{
    private $KatalogModel;

    public function __construct(KatalogModel $KatalogModel)
    {
        $this->KatalogModel = $KatalogModel;
        $this->middleware('isPemilik');
    }

    public function index(Request $request)
    {
       
        $dataKatalog = $this->KatalogModel->getAllDataKatalog($request);
        // dd(auth()->user()->id);

        return view('list-katalog.index', compact('dataKatalog'));
    }

    public function create()
    {
        $idUKM = UKMModel::select('id_ukm')->where('id_user',auth()->user()->id)->value('id_ukm');
        $idKatalog = DB::table('list_katalog')->select('id')->get();
        $idKatalogCount = $idKatalog->count();
        $getLastNumberKatalog = $idKatalogCount+1;
        $newId = $this->generateLastNumber($getLastNumberKatalog);
        // dd($newId);

        return view('list-katalog.create', compact('idUKM','newId'));
    }
    
    public function store(Request $request)
    {
        $userUbah = DB::table('users')->where('name',auth()->user()->name)->value('id');
        $cekIDKat = KatalogModel::where('id_katalog',$request->idKatalog)->value('id');
        
        if($cekIDKat == null){
            $attributes = [
                'judul_katalog' => 'judul_katalog',
                'usia' => 'usia',
                'ukuran' => 'ukuran',
                'stok_katalog' => 'stok_katalog',
                'harga_katalog' => 'harga_katalog',
                'gambar_katalog' => 'gambar_katalog'
            ];
        
            $request->validate([
                'judul_katalog' => ['required', 'string', 'alphanumeric_space', 'min:12', 'max:37'],
                'usia' => ['required', 'string', 'min:7', 'max:21'],
                'ukuran' => ['required', 'string', 'max:5'],
                'stok_katalog' => ['required', 'string', 'no_telp_validation'],
                'harga_katalog' => ['required', 'string', 'no_telp_validation', 'max:9'],
                'gambar_katalog' => ['required'],
            ], [], $attributes);

            $file = $request->file('gambar_katalog');
            $shortnameGambarKatalog = $file->getClientOriginalName();
            /* mengatur ekstensi file yg diunggah */
            $ekstensi_diperbolehkan	= array('png','jpg','jpeg');
            $x = explode('.', $shortnameGambarKatalog);
            $ekstensi = strtolower(end($x));
            $ukuran	= $file->getSize();

            if (!file_exists("data/")) {
                mkdir("data/");
            }

            if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
                if($ukuran < 5000000){
                    /* Ambil data untuk keperluan format penamaan berkas */
                    $getIDKat = $request->idKatalog;
                    $getIDUKM = $request->IDUKM;
                    $inisial = 'GBR-KAT';
                    /* formatnya */
                    $formatNama = date('d-m-Y-H-i-s') . '-' . $inisial . '-' . $getIDKat . '-' . $getIDUKM . '.' . $ekstensi;

                    $sourcePathGambarKatalog = $file->getRealPath();
                    $targetPathGambarKatalog = "data/" . $formatNama;
                    move_uploaded_file($sourcePathGambarKatalog, $targetPathGambarKatalog);

                    $dataKatalog = new KatalogModel();
                    $dataKatalog->id_katalog = $request->idKatalog;
                    $dataKatalog->id_ukm = $request->IDUKM;
                    $dataKatalog->judul_katalog = $request->judul_katalog;
                    $dataKatalog->usia = $request->usia;
                    $dataKatalog->ukuran = $request->ukuran;
                    $dataKatalog->stok_katalog = $request->stok_katalog;
                    $dataKatalog->stok_produk = $request->stok_katalog;
                    $dataKatalog->harga_katalog = $request->harga_katalog;
                    $dataKatalog->gambar_katalog = $formatNama;
                    $dataKatalog->user_ubah = $userUbah;
                    $dataKatalog->status = 1;
                    $dataKatalog->save();
                
                    return redirect()->route('katalog.index')->withStatus(__('Data berhasil ditambahkan!'));
                }else{
                    return redirect()->back()->withInput()->withErrors([
                        'gambar_katalog' => 'Ukuran Gambar Katalog Terlalu Besar.'
                    ]);
                }
            }else{
                return redirect()->back()->withInput()->withErrors([
                    'gambar_katalog' => 'Ekstensi Gambar Katalog Tidak Diperbolehkan.'
                ]);
            }
        }else{
            return redirect()->route('katalog.index')->withStatus(__('Maaf, Penambahan Data Gagal, Silakan Coba Tambahkan Kembali.'));
        }
    }

    public function edit($id)
    {
        // $getIDUKM = UKMModel::where('id_user',auth()->user()->id)->value('id_ukm');
        // dd($getIDUKM);
        $katalog = KatalogModel::where('id_katalog',$id)->where('status','1')->first();
        $tempID = auth()->user()->id;
        
        if($katalog == null)
        {
            return redirect()->route('katalog.index');
        }
        else
        {
            return view('list-katalog.edit', compact('katalog'));
        }
    }

    public function update(Request $request, $id)
    {
        $userUbah = DB::table('users')->where('name',auth()->user()->name)->value('id');
        
        $attributes = [
            'judul_katalog' => 'judul_katalog',
            'usia' => 'usia',
            'ukuran' => 'ukuran',
            'stok_katalog' => 'stok_katalog',
            'harga_katalog' => 'harga_katalog',
        ];
    
        $request->validate([
            'judul_katalog' => ['required', 'string', 'alphanumeric_space', 'min:12', 'max:37'],
            'usia' => ['required', 'string', 'min:7', 'max:21'],
            'ukuran' => ['required', 'string', 'max:5'],
            'stok_katalog' => ['required', 'string', 'no_telp_validation'],
            'harga_katalog' => ['required', 'string', 'no_telp_validation', 'max:9'],
        ], [], $attributes);

        $file = $request->file('gambar_katalog');
        if($file == null){
            $dataKatalog = KatalogModel::where('id_katalog',$id)->first();
        
            $dataKatalog->judul_katalog = $request->judul_katalog;
            $dataKatalog->usia = $request->usia;
            $dataKatalog->ukuran = $request->ukuran;
            $dataKatalog->stok_katalog = $request->stok_katalog;
            $dataKatalog->stok_produk = $request->stok_katalog;
            $dataKatalog->harga_katalog = $request->harga_katalog;
            $dataKatalog->user_ubah = $userUbah;
            $dataKatalog->status = 1;

            $dataKatalog->save();

            return redirect()->route('katalog.index')->withStatus(__('Data berhasil diperbaharui!'));
        }else{
            $shortnameGambarKatalog = $file->getClientOriginalName();
            /* mengatur ekstensi file yg diunggah */
            $ekstensi_diperbolehkan	= array('png','jpg','jpeg');
            $x = explode('.', $shortnameGambarKatalog);
            $ekstensi = strtolower(end($x));
            $ukuran	= $file->getSize();
            // dd($ekstensi);
            // dd(in_array($ekstensi, $ekstensi_diperbolehkan));

            if (!file_exists("data/")) {
                mkdir("data/");
            }

            if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
                if($ukuran < 5000000){
                    /* Ambil data untuk keperluan format penamaan berkas */
                    $getIDKat = $id;
                    $getIDUKM = UKMModel::where('id_user',auth()->user()->id)->value('id_ukm');
                    $inisial = 'GBR-KAT';
                    /* formatnya */
                    $formatNama = date('d-m-Y-H-i-s') . '-' . $inisial . '-' . $getIDKat . '-' . $getIDUKM . '.' . $ekstensi;

                    $sourcePathGambarKatalog = $file->getRealPath();
                    $targetPathGambarKatalog = "data/" . $formatNama;
                    move_uploaded_file($sourcePathGambarKatalog, $targetPathGambarKatalog);
    
                    /* mengambil data nama file lama */
                    $getGambarLama = KatalogModel::where('id_katalog',$id)->value('gambar_katalog');
                    $hapusGambar = "data/" . $getGambarLama;
                    if(file_exists($hapusGambar)){
                        unlink($hapusGambar);
                    }

                    $dataKatalog = KatalogModel::where('id_katalog',$id)->first();
                    $dataKatalog->judul_katalog = $request->judul_katalog;
                    $dataKatalog->usia = $request->usia;
                    $dataKatalog->ukuran = $request->ukuran;
                    $dataKatalog->stok_katalog = $request->stok_katalog;
                    $dataKatalog->stok_produk = $request->stok_katalog;
                    $dataKatalog->harga_katalog = $request->harga_katalog;
                    $dataKatalog->gambar_katalog = $formatNama;
                    $dataKatalog->user_ubah = $userUbah;
                    $dataKatalog->status = 1;
                    $dataKatalog->save();

                    return redirect()->route('katalog.index')->withStatus(__('Data berhasil diperbaharui!'));
                }else{
                    return redirect()->back()->withInput()->withErrors([
                        'gambar_katalog' => 'Ukuran Gambar Katalog Terlalu Besar.'
                    ]);
                }
            }else{
                return redirect()->back()->withInput()->withErrors([
                    'gambar_katalog' => 'Ekstensi Gambar Katalog Tidak Diperbolehkan.'
                ]);
            }
        }
    }

    public function destroy($id)
    {
        $dataKatalog = KatalogModel::where('id_katalog',$id)->first();
        
        $dataKatalog->status = 0;

        $dataKatalog->save();

        return redirect()->route('katalog.index')->withStatus(__('Data berhasil dihapus!'));
    }


    public function generateLastNumber($lastNumber)
    {
        if($lastNumber < 10)
        {
            $templateKatalog = "KAT_00".$lastNumber;
        }
        else if($lastNumber < 100)
        {
            $templateKatalog = "KAT_0".$lastNumber;
        }
        else if($lastNumber >= 100)
        {
            $templateKatalog = "KAT_".$lastNumber;
        }

        return $templateKatalog;
    }
}
