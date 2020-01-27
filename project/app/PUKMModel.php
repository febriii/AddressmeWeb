<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class PUKMModel extends Model
{
    protected $table = 'list_ukm';
    protected $fillable = ['id_ukm','nama_ukm','alamat','no_telp','long','lat','gambar_ukm'];

    public function getDataUKM(Request $request)
    {
        $data = DB::table('list_ukm')->join('users','list_ukm.id_user','users.id')
        ->where('id_user',auth()->user()->id)->orderby('nama_ukm','asc');
        
        return $data = $data->paginate(1);

    }
}