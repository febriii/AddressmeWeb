@extends('layouts.app', ['title' => __('Manajemen Katalog')])

@section('content')
    @include('users.partials.header', ['title' => __('Ubah Katalog')])
    {{-- CEK STATUS LOGIN --}}
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
                                <h3 class="mb-0">{{ __('Ubah Katalog') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('katalog.index') }}"
                                    class="btn btn-sm btn-primary">{{ __('Kembali') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{route('katalog.update', $katalog->id_katalog)}}" autocomplete="off" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="pl-lg-4">
                                <div class="form-group">
                                {{--  <input type="hidden" value="{{$userUbah}}" name="user_ubah"> username yang mengubah katalog ini --}}
                                    <label class="form-control-label">{{ __('Judul Katalog') }}</label>
                                    <input type="text" name="judul_katalog" class="form-control" placeholder="{{ __('Judul Katalog') }}"
                                        value="{{ $katalog->judul_katalog }}" required autofocus>
                                    @if ($errors->has('judul_katalog'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('judul_katalog') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">{{ __('Usia') }}</label>
                                    <input type="text" name="usia" class="form-control"
                                        placeholder="{{ __('Usia') }}" value="{{ $katalog->usia }}" required>
                                    @if ($errors->has('usia'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('usia') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">{{ __('Ukuran') }}</label>
                                    <input type="text" name="ukuran" class="form-control"
                                        placeholder="{{ __('Ukuran') }}" value="{{ $katalog->ukuran }}" required>
                                    @if ($errors->has('ukuran'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('ukuran') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">{{ __('Stok Katalog') }}</label>
                                    <input type="text" name="stok_katalog" class="form-control" placeholder="{{ __('Stok Katalog') }}"
                                        value="{{ $katalog->stok_katalog }}" required>
                                    @if ($errors->has('stok_katalog'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('stok_katalog') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">{{ __('Harga Katalog') }}</label>
                                    <input type="text" name="harga_katalog" class="form-control" placeholder="{{ __('Harga Katalog') }}"
                                        value="{{ $katalog->harga_katalog }}" required>
                                    @if ($errors->has('harga_katalog'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('harga_katalog') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">{{ __('Gambar Katalog') }}</label>
                                    <input type="file" name="gambar_katalog" class="form-control form-control-alternative{{ $errors->has('gambar_katalog') ? ' is-invalid' : '' }}" placeholder="{{ __('Gambar Katalog') }}"
                                        value="{{ $katalog->gambar_katalog }}">
                                    @if ($errors->has('gambar_katalog'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('gambar_katalog') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Ubah Data') }}</button>
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
