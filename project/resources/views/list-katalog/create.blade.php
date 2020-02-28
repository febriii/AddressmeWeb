@extends('layouts.app', ['title' => __('Manajemen Katalog')])

@section('content')
    @include('users.partials.header', ['title' => __('Tambah Katalog')])   
    @php
    $isAdmin = auth()->user()->status;
    @endphp
    <?php if($isAdmin != 3){?>
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Manajemen Katalog') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('katalog.index') }}" class="btn btn-sm btn-primary">{{ __('Kembali') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('katalog.store') }}" autocomplete="off" enctype="multipart/form-data">
                            @csrf
                            @method('post')
                            
                            <h6 class="heading-small text-muted mb-4">{{ __('Informasi Katalog') }}</h6>
                                <!-- <div class="form-group{{ $errors->has('id_katalog') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-id-katalog">{{ __('Id Katalog') }}</label>
                                    <input type="text" name="id_katalog" id="input-id-katalog" class="form-control form-control-alternative{{ $errors->has('id_katalog') ? ' is-invalid' : '' }}" placeholder="{{ __('Id Katalog') }}" value="{{ old('id_katalog') }}" required autofocus>

                                    @if ($errors->has('id_katalog'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('id_katalog') }}</strong>
                                        </span>
                                    @endif
                                </div> -->
                                <div class="form-group">
                                    <input type="hidden" value="{{$newId}}" name="idKatalog"> {{-- TAMPUNGAN ID KATALOG --}}
                                    <input type="hidden" value="{{$idUKM}}" name="IDUKM"> {{-- TAMPUNGAN ID UKM --}}
                                    
                                </div>
                                <div class="form-group{{ $errors->has('judul_katalog') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-judul-katalog">{{ __('Judul Katalog') }}</label>
                                    <input type="text" name="judul_katalog" id="input-judul-katalog" class="form-control form-control-alternative{{ $errors->has('judul_katalog') ? ' is-invalid' : '' }}" placeholder="{{ __('Judul Katalog') }}" value="{{ old('judul_katalog') }}" required>

                                    @if ($errors->has('judul_katalog'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('judul_katalog') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('usia') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-usia">{{ __('Usia') }}</label>
                                    <input type="text" name="usia" id="input-usia" class="form-control form-control-alternative{{ $errors->has('usia') ? ' is-invalid' : '' }}" placeholder="{{ __('Usia') }}" value="{{ old('usia') }}" required>
                                    @if ($errors->has('usia'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('usia') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('ukuran') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-ukuran">{{ __('Ukuran') }}</label>
                                    <input type="text" name="ukuran" id="input-ukuran" class="form-control form-control-alternative{{ $errors->has('ukuran') ? ' is-invalid' : '' }}" placeholder="{{ __('Ukuran') }}" value="{{ old('ukuran') }}" required>
                                    @if ($errors->has('ukuran'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('ukuran') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('stok_katalog') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-stok-katalog">{{ __('Stok Katalog') }}</label>
                                    <input type="text" name="stok_katalog" id="input-stok-katalog" class="form-control form-control-alternative{{ $errors->has('stok_katalog') ? ' is-invalid' : '' }}" placeholder="{{ __('Stok Katalog') }}" value="{{ old('stok_katalog') }}" required>
                                    @if ($errors->has('stok_katalog'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('stok_katalog') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('harga_katalog') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-harga-katalog">{{ __('Harga Katalog') }}</label>
                                    <input type="text" name="harga_katalog" id="input-harga-katalog" class="form-control form-control-alternative{{ $errors->has('harga_katalog') ? ' is-invalid' : '' }}" placeholder="{{ __('Harga Katalog') }}" value="{{ old('harga_katalog') }}" required>
                                    @if ($errors->has('harga_katalog'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('harga_katalog') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('gambar_katalog') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-gambar-katalog">{{ __('Gambar Katalog') }}</label>
                                    <input type="file" name="gambar_katalog" id="input-gambar-katalog" class="form-control form-control-alternative{{ $errors->has('gambar_katalog') ? ' is-invalid' : '' }}" placeholder="{{ __('Gambar Katalog') }}" value="{{ old('gambar_katalog') }}" required>
                                    @if ($errors->has('gambar_katalog'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('gambar_katalog') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <!-- <div class="form-group{{ $errors->has('user_ubah') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-user-ubah">{{ __('User Ubah') }}</label>
                                    <input type="text" name="user_ubah" id="input-user-ubah" class="form-control form-control-alternative{{ $errors->has('user_ubah') ? ' is-invalid' : '' }}" placeholder="{{ __('User Ubah') }}" value="{{ old('user_ubah') }}">

                                    @if ($errors->has('user_ubah'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('user_ubah') }}</strong>
                                        </span>
                                    @endif
                                </div> -->
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Tambah Data') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        @include('layouts.footers.auth')
    </div>
    <?php }?>
@endsection