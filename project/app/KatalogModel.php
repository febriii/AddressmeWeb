<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class KatalogModel extends Model
{
    protected $table = 'list_katalog';
    protected $fillable = ['id_katalog','id_ukm','judul_katalog','usia','ukuran','stok_katalog','stok_produk','harga_katalog','gambar_katalog','user_ubah','status'];

    public function getAllDataKatalog(Request $request)
    {
        $data = DB::table('list_katalog')->orderby('judul_katalog','asc')->where('status','1');

            if($request->get('search')!=null){
                
                $data = $data->Where(function ($query) use ($request) {
                    $query
                    ->where('list_katalog.id_katalog', 'like',"%".$request->get('search')."%")
                    ->orwhere('list_katalog.judul_katalog','like',"%".$request->get('search')."%");
               });
            }
        
            return $data = $data->paginate(10);

    }
}