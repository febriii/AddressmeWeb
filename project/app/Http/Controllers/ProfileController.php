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
        // dd($dataPemilik);
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
        $dataPemilik = PemilikModel::where('id',auth()->user()->id)->first();
        // dd($dataPemilik);
        $dataPemilik->username = $request->username;
        $dataPemilik->alamat = $request->alamat;
        $dataPemilik->no_telp = $request->no_telp;
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
        auth()->user()->update(['password' => Hash::make($request->get('password'))]);

        return back()->withPasswordStatus(__('Kata Sandi berhasil diperbaharui.'));
    }
}
