<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class PemilikModel extends Model
{
    protected $table = 'users';
    protected $fillable = ['id','name','email','alamat','no_telp','status','username'];

    public function getAllDataPemilik(Request $request)
    {
        $data = DB::table('users')->orderby('name','asc')->where('status','1');

            if($request->get('search')!=null){
                
                $data = $data->Where(function ($query) use ($request) {
                    $query
                    ->where('users.username', 'like',"%".$request->get('search')."%")
                    ->orwhere('users.name','like',"%".$request->get('search')."%");
               });
            }
        
            return $data = $data->paginate(20);

    }
}