<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use App\KatalogModel;
use App\UKMModel;
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
        
        return view('list-katalog.index', compact('dataKatalog'));
    }

    public function create()
    {
        $idUKM = UKMModel::select('id','id_ukm')->get();

        return view('list-katalog.create', compact('idUKM'));
    }
    
    public function store(Request $request)
    {

        $attributes = [
            'name' => 'name',
            'email' => 'email',
            'username' => 'username',
            'password' => 'password'
        ];
    
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [], $attributes);

        $dataAdmin = new KatalogModel();
        $dataAdmin->name = $request->name;
        $dataAdmin->username = $request->username;
        $dataAdmin->email = $request->email;
        $dataAdmin->password = Hash::make($request->password);
        $dataAdmin->alamat = $request->alamat;
        $dataAdmin->no_telp = $request->no_telp;
        $dataAdmin->status = 1;

        $dataAdmin->save();
        
        return redirect()->route('katalog.index')->withStatus(__('Data berhasil ditambahkan!'));
    }

    // public function edit($id)
    // {
    //     $user = KatalogModel::where('id',$id)->where('status','1')->first();
    //     $tempID = auth()->user()->id;

    //     if($user == null)
    //     {
    //         return redirect()->route('katalog.index');
    //     }
    //     else
    //     {
    //         return view('list-katalog.edit', compact('user'));
    //     }
    // }

    // public function update(Request $request, $id)
    // {
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

    //     $dataAdmin = KatalogModel::where('id',$id)->first();
    //     $dataAdmin->name = $request->name;
    //     $dataAdmin->username = $request->username;
    //     $dataAdmin->email = $request->email;
    //     $dataAdmin->password = Hash::make($request->password);
    //     $dataAdmin->alamat = $request->alamat;
    //     $dataAdmin->no_telp = $request->no_telp;
    //     $dataAdmin->status = 1;

    //     $dataAdmin->save();

    //     return redirect()->route('katalog.index')->withStatus(__('Data berhasil diperbaharui!'));
    // }

    // public function destroy(User  $user)
    // {
    //     $user->delete();

    //     return redirect()->route('katalog.index')->withStatus(__('User successfully deleted.'));
    // }
}
