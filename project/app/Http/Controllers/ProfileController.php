<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use Illuminate\Support\Facades\Hash;
use App\PemilikModel;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $dataPemilik = PemilikModel::where('id',auth()->user()->id)->first();
        
        return view('profile.edit', compact('dataPemilik'));
    }

    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileRequest $request)
    {
        $dataUsername = PemilikModel::where('id',auth()->user()->id)->value('username');
        
        if($dataUsername == $request->username){
            //username sama
            $attributes = [
                'alamat' => 'alamat',
                'no_telp' => 'no_telp'
            ];

            $request->validate([
                'alamat' => ['required', 'string', 'alamat_validation', 'max:255'],
                'no_telp' => ['required', 'no_telp_validation', 'min:10', 'max:13'],
            ], [], $attributes);
        }else{
            //username beda
            $attributes = [
                'username' => 'username',
                'alamat' => 'alamat',
                'no_telp' => 'no_telp'
            ];

            $request->validate([
                'username' => ['required', 'string', 'alpha_dash', 'min:6', 'max:15', 'unique:users'],
                'alamat' => ['required', 'string', 'alamat_validation', 'max:255'],
                'no_telp' => ['required', 'no_telp_validation', 'min:10', 'max:13'],
            ], [], $attributes);
        }

        $dataPemilik = PemilikModel::where('id',auth()->user()->id)->first();
        $dataPemilik->username = $request->username;
        $dataPemilik->alamat = $request->alamat;
        $hp = $this->autoCorrectNumber($request->no_telp);
        $dataPemilik->no_telp = $hp;
        $dataPemilik->save();

        auth()->user()->update($request->all());

        return back()->withStatus(__('Profil berhasil diperbaharui.'));
    }

    /**
     * Change the password
     *
     * @param  \App\Http\Requests\PasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(PasswordRequest $request)
    {
        // $attributes = [
        //     'password' => 'password'
        // ];
    
        // $request->validate([
        //     'password' => ['required', 'string', 'min:8', 'confirmed'],
        // ], [], $attributes);

        auth()->user()->update(['password' => Hash::make($request->get('password'))]);

        return back()->withPasswordStatus(__('Kata Sandi berhasil diperbaharui.'));
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
