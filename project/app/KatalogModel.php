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
        $getIdUkm = DB::table('list_ukm')->select('id_ukm')->where('id_user',auth()->user()->id)->value('id_ukm');

        $data = DB::table('list_katalog')->join('users','list_katalog.user_ubah','users.id')->where('id_ukm',$getIdUkm)
        ->where('list_katalog.status','1')->orderby('list_katalog.updated_at','desc');
        // $data = DB::table('list_katalog')->where('id_ukm',$getIdUkm)->orwhere('status','1')->orderby('updated_at','desc');
        
            if($request->get('search')!=null){
                
                $data = $data->Where(function ($query) use ($request) {
                    $query
                    ->where('list_katalog.id_katalog', 'like',"%".$request->get('search')."%")
                    ->orwhere('list_katalog.judul_katalog','like',"%".$request->get('search')."%");
               });
            }else{
                // ngeluarin pesan bahwa data yang dicari tidak ditemukan
            }
        
            return $data = $data->paginate(10);

    }
}