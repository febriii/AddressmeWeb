@extends('layouts.app', ['title' => __('Manajemen Pemilik')])

@section('content')
@include('users.partials.header', ['title' => __('Ubah Data')])

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">{{ __('Ubah Data Pemilik UKM') }}</h3>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('pemilik.index') }}"
                                class="btn btn-sm btn-primary">{{ __('Kembali') }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="post" action="{{route('pemilik.update', $user->id)}}" autocomplete="off">
                        @csrf
                        @method('put')
                        <div class="pl-lg-4">
                            <div class="form-group">
                                <label class="form-control-label">{{ __('Nama') }}</label>
                                <input type="text" name="name" class="form-control" placeholder="{{ __('Nama Pemilik UKM') }}"
                                    value="{{ $user->name }}" required autofocus>
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">{{ __('Username') }}</label>
                                <input type="text" name="username" class="form-control"
                                    placeholder="{{ __('Username') }}" value="{{ $user->username }}" required>
                                @if ($errors->has('username'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">{{ __('Alamat') }}</label>
                                <input type="text" name="alamat" class="form-control"
                                    placeholder="{{ __('Alamat Pemilik UKM') }}" value="{{ $user->alamat }}" required>
                                @if ($errors->has('alamat'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('alamat') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">{{ __('No Telepon') }}</label>
                                <input type="text" name="no_telp" class="form-control" placeholder="{{ __('No Telepon/HP Pemilik UKM') }}"
                                    value="{{ $user->no_telp }}" required>
                                @if ($errors->has('no_telp'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('no_telp') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">{{ __('Email') }}</label>
                                <input type="text" name="email" class="form-control" placeholder="{{ __('Email') }}"
                                    value="{{ $user->email }}" required>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
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
@endsection
