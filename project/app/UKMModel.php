<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UKMModel extends Model
{
    protected $table = 'list_ukm';
    protected $fillable = ['id_ukm','nama_ukm','alamat','no_telp','long','lat','gambar_ukm'];

    public function getAllDataUKM(Request $request)
    {
        $data = DB::table('list_ukm')->orderby('nama_ukm','asc');

            if($request->get('search')!=null){
                
                $data = $data->Where(function ($query) use ($request) {
                    $query
                    ->where('list_ukm.id_ukm', 'like',"%".$request->get('search')."%")
                    ->orwhere('list_ukm.nama_ukm','like',"%".$request->get('search')."%");
               });
            }
        
        return $data = $data->paginate(1);

    }
}