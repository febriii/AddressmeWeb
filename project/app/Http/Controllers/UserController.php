<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use App\UserModel;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $UserModel;

    public function __construct(UserModel $UserModel)
    {
        $this->UserModel = $UserModel;
        $this->middleware('isAdmin');
    }

    public function index(Request $request)
    {
        $users = $this->UserModel->getAllDataAdmin($request);
        
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
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

        $dataAdmin = new UserModel();
        $dataAdmin->name = $request->name;
        $dataAdmin->username = $request->username;
        $dataAdmin->email = $request->email;
        $dataAdmin->password = Hash::make($request->password);
        $dataAdmin->alamat = $request->alamat;
        $dataAdmin->no_telp = $request->no_telp;
        $dataAdmin->status = 2;

        $dataAdmin->save();
        
        return redirect()->route('user.index')->withStatus(__('Data berhasil ditambahkan!'));
    }

    public function edit($id)
    {
        $user = UserModel::where('id',$id)->where('status','2')->first();
        $tempID = auth()->user()->id;

        if($user == null)
        {
            return redirect()->route('user.index');
        }
        else
        {
            return view('users.edit', compact('user'));
        }
    }

    public function update(Request $request, $id)
    {
        $attributes = [
            'name' => 'name',
            'email' => 'email',
            'username' => 'username',
            'password' => 'password'
        ];
    
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($id)],
            'username' => ['required', 'string', Rule::unique('users')->ignore($id)],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [], $attributes);

        $dataAdmin = UserModel::where('id',$id)->first();
        $dataAdmin->name = $request->name;
        $dataAdmin->username = $request->username;
        $dataAdmin->email = $request->email;
        $dataAdmin->password = Hash::make($request->password);
        $dataAdmin->alamat = $request->alamat;
        $dataAdmin->no_telp = $request->no_telp;
        $dataAdmin->status = 2;

        $dataAdmin->save();

        return redirect()->route('user.index')->withStatus(__('Data berhasil diperbaharui!'));
    }

    public function destroy(User  $user)
    {
        $user->delete();

        return redirect()->route('user.index')->withStatus(__('User successfully deleted.'));
    }

    public function loginMobileApi(Request $request){
        $getStatus = UserModel::where('username',request('username'))->value('status');
        
        if (Auth::attempt(['username' => request('username'), 'password' => request('password')])) {
            $user = Auth::user();
            $result = array();
            array_push($result, $user);
            
            if($getStatus == 1){
                // Pemilik UKM
                echo json_encode(array("value" => 1, "result" => $result));
            }else if($getStatus == 2){
                // Admin
                echo json_encode(array("value" => 2));
            }else if($getStatus == 3){
                // Penyewa
                echo json_encode(array("value" => 3, "result" => $result));
            }else{
                echo json_encode(array("value" => 0));
            }
            // return response()->json([
            //     'status' => true,
            //     'data' => [
            //         $user
            //     ], 200]);
        }else{
            echo json_encode(array("value" => 0));
        }
        // return response()->json([
        //     'status' => false
        // ]);
    }

    protected function registrasiMobileApi(Request $request)
    {
        $data = new UserModel();
        $data->name = $request->name;
        $data->password = Hash::make($request->password);
        $data->email = $request->email;
        $data->username = $request->username;
        $data->alamat = $request->alamat;
        $data->no_telp = $request->no_telp;
        $data->status = 3;
        $data->save();

        $response["value"] = 1;
        $response["message"] = "Buat Akun Sukses!";
        echo json_encode($response);

        // return redirect()->back()->withStatus(__('Selamat! Pendaftaran berhasil dilakukan.'));
    }

    public function ubahKataSandiApi(PasswordRequest $request)
    {
        auth()->user()->update(['password' => Hash::make($request->get('password'))]);

        $response["value"] = 1;
        $response["message"] = "Kata Sandi Berhasil Perbaharui!";
        echo json_encode($response);
    }
}
