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
        $idKatalog = DB::table('list_katalog')->select('id')->where('status','1')->get();
        $idKatalogCount = $idKatalog->count();
        $getLastNumberKatalog = $idKatalogCount+1;
        $newId = $this->generateLastNumber($getLastNumberKatalog);
        // dd($idUKM);

        return view('list-katalog.create', compact('idUKM','newId'));
    }
    
    public function store(Request $request)
    {
        $userUbah = DB::table('users')->where('name',auth()->user()->name)->value('id');
        // $attributes = [
        //     'name' => 'name',
        //     'email' => 'email',
        //     'username' => 'username',
        //     'password' => 'password'
        // ];
    
        // $request->validate([
        //     'name' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        //     'username' => ['required', 'string', 'unique:users'],
        //     'password' => ['required', 'string', 'min:8', 'confirmed'],
        // ], [], $attributes);


        $dataKatalog = new KatalogModel();
        $dataKatalog->id_katalog = $request->idKatalog;
        $dataKatalog->id_ukm = $request->IDUKM;
        $dataKatalog->judul_katalog = $request->judul_katalog;
        $dataKatalog->usia = $request->usia;
        $dataKatalog->ukuran = $request->ukuran;
        $dataKatalog->stok_katalog = $request->stok_katalog;
        $dataKatalog->stok_produk = $request->stok_katalog;
        $dataKatalog->harga_katalog = $request->harga_katalog;
        $dataKatalog->gambar_katalog = $request->gambar_katalog;
        $dataKatalog->user_ubah = $userUbah;
        $dataKatalog->status = 1;

        $dataKatalog->save();
        
        return redirect()->route('katalog.index')->withStatus(__('Data berhasil ditambahkan!'));
    }

    public function edit($id)
    {
        // dd($id);
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
        
    //     $attributes = [
    //         'name' => 'name',
    //         'email' => 'email',
    //         'username' => 'username',
    //         'password' => 'password'
    //     ];
    
    //     $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($id)],
    //         'username' => ['required', 'string', Rule::unique('users')->ignore($id)],
    //         'password' => ['required', 'string', 'min:8', 'confirmed'],
    //     ], [], $attributes);

        $dataKatalog = KatalogModel::where('id_katalog',$id)->first();
        
        $dataKatalog->judul_katalog = $request->judul_katalog;
        $dataKatalog->usia = $request->usia;
        $dataKatalog->ukuran = $request->ukuran;
        $dataKatalog->stok_katalog = $request->stok_katalog;
        $dataKatalog->stok_produk = $request->stok_katalog;
        $dataKatalog->harga_katalog = $request->harga_katalog;
        $dataKatalog->gambar_katalog = $request->gambar_katalog;
        $dataKatalog->user_ubah = $userUbah;
        $dataKatalog->status = 1;

        $dataKatalog->save();

        return redirect()->route('katalog.index')->withStatus(__('Data berhasil diperbaharui!'));
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
