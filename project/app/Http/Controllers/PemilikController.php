<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use App\PemilikModel;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PemilikController extends Controller
{
    private $PemilikModel;

    public function __construct(PemilikModel $PemilikModel)
    {
        $this->PemilikModel = $PemilikModel;
        $this->middleware('isAdmin');
    }

    public function index(Request $request)
    {
        // $password = 'anjenggg';
        // $hash = Hash::make($password);
        // dd($hash);
        $users = $this->PemilikModel->getAllDataPemilik($request);
        
        return view('list-pemilik.index', compact('users'));
    }

    public function create()
    {
        return view('list-pemilik.create');
    }
    
    public function store(Request $request)
    {
        $attributes = [
            'name' => 'name',
            'email' => 'email',
            'username' => 'username',
            'password' => 'password',
            'alamat' => 'alamat',
            'no_telp' => 'no_telp'
        ];

        $request->validate([
            'name' => ['required', 'string', 'alpha_spaces', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'alpha_dash', 'min:6', 'max:15', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'alamat' => ['required', 'string', 'alamat_validation', 'max:255'],
            'no_telp' => ['required', 'no_telp_validation', 'min:10', 'max:13'],
        ], [], $attributes);

        $dataPemilik = new PemilikModel();
        $dataPemilik->name = $request->name;
        $dataPemilik->username = $request->username;
        $dataPemilik->email = $request->email;
        $dataPemilik->password = Hash::make($request->password);
        $dataPemilik->alamat = $request->alamat;
        $hp = $this->autoCorrectNumber($request->no_telp);
        $dataPemilik->no_telp = $hp;
        $dataPemilik->status = 1;

        $dataPemilik->save();
        
        return redirect()->route('pemilik.index')->withStatus(__('Data berhasil ditambahkan!'));
    }

    public function edit($id)
    {
        $user = PemilikModel::where('id',$id)->where('status','1')->first();
        $tempID = auth()->user()->id;

        if($user == null)
        {
            return redirect()->route('pemilik.index');
        }
        else
        {
            return view('list-pemilik.edit', compact('user'));
        }
    }

    public function update(Request $request, $id)
    {
        $dataUsername = PemilikModel::where('id',$id)->value('username');
        $dataEmail = PemilikModel::where('id',$id)->value('email');
        
        if($dataUsername == $request->username){
            if($dataEmail == $request->email){
                //username dan email sama
                $attributes = [
                    'name' => 'name',
                    'alamat' => 'alamat',
                    'no_telp' => 'no_telp'
                ];
                $request->validate([
                    'name' => ['required', 'string', 'alpha_spaces', 'max:255'],
                    'alamat' => ['required', 'string', 'alamat_validation', 'max:255'],
                    'no_telp' => ['required', 'no_telp_validation', 'min:10', 'max:13'],
                ], [], $attributes);
            }else{
                //username sama, email beda
                $attributes = [
                    'name' => 'name',
                    'email' => 'email',
                    'alamat' => 'alamat',
                    'no_telp' => 'no_telp'
                ];
                $request->validate([
                    'name' => ['required', 'string', 'alpha_spaces', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'alamat' => ['required', 'string', 'alamat_validation', 'max:255'],
                    'no_telp' => ['required', 'no_telp_validation', 'min:10', 'max:13'],
                ], [], $attributes);
            }
        }else{
            if($dataEmail == $request->email){
                //username beda, email sama
                $attributes = [
                    'name' => 'name',
                    'username' => 'username',
                    'alamat' => 'alamat',
                    'no_telp' => 'no_telp'
                ];
                $request->validate([
                    'name' => ['required', 'string', 'alpha_spaces', 'max:255'],
                    'username' => ['required', 'string', 'alpha_dash', 'min:6', 'max:15', 'unique:users'],
                    'alamat' => ['required', 'string', 'alamat_validation', 'max:255'],
                    'no_telp' => ['required', 'no_telp_validation', 'min:10', 'max:13'],
                ], [], $attributes);
            }else{
                //username dan email beda
                $attributes = [
                    'name' => 'name',
                    'email' => 'email',
                    'username' => 'username',
                    'alamat' => 'alamat',
                    'no_telp' => 'no_telp'
                ];
                $request->validate([
                    'name' => ['required', 'string', 'alpha_spaces', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'username' => ['required', 'string', 'alpha_dash', 'min:6', 'max:15', 'unique:users'],
                    'alamat' => ['required', 'string', 'alamat_validation', 'max:255'],
                    'no_telp' => ['required', 'no_telp_validation', 'min:10', 'max:13'],
                ], [], $attributes);
            }
        }

        $dataPemilik = PemilikModel::where('id',$id)->first();
        $dataPemilik->name = $request->name;
        $dataPemilik->username = $request->username;
        $dataPemilik->email = $request->email;
        $dataPemilik->alamat = $request->alamat;
        $hp = $this->autoCorrectNumber($request->no_telp);
        $dataPemilik->no_telp = $hp;
        $dataPemilik->status = 1;

        $dataPemilik->save();

        return redirect()->route('pemilik.index')->withStatus(__('Data berhasil diperbaharui!'));
    }

    public function destroy($id)
    {
        $dataPemilik = PemilikModel::where('id',$id)->first();

        $dataPemilik->status = 0;
        
        $dataPemilik->save();

        return redirect()->route('pemilik.index')->withStatus(__('Data berhasil dihapus!'));
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
