@extends('layouts.app', ['title' => __('UKM Management')])

@section('content')
@include('users.partials.header', ['title' => __('Edit UKM')])

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">{{ __('Edit UKM') }}</h3>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('list-ukm.index') }}"
                                class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="post" action="{{route('list-ukm.update', $data->id)}}" autocomplete="off">
                        @csrf
                        @method('put')
                        <div class="pl-lg-4">
                            <div class="form-group">
                                <label class="form-control-label">{{ __('ID UKM') }}</label>
                                <input type="text" name="id_ukm" class="form-control" placeholder="{{ __('ID UKM') }}"
                                    value="{{ $data->id_ukm }}" required autofocus>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">{{ __('Nama UKM') }}</label>
                                <input type="text" name="nama_ukm" class="form-control"
                                    placeholder="{{ __('Nama UKM') }}" value="{{ $data->nama_ukm }}" required autofocus>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">{{ __('Alamat') }}</label>
                                <input type="text" name="alamat" class="form-control" placeholder="{{ __('Alamat') }}"
                                    value="{{ $data->alamat }}" required autofocus>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">{{ __('Nomor Telepon') }}</label>
                                <input type="text" name="no_telp" class="form-control"
                                    placeholder="{{ __('Nomor Telepon') }}" value="{{ $data->no_telp }}" required
                                    autofocus>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">{{ __('Website') }}</label>
                                <input type="text" name="website" class="form-control" placeholder="{{ __('Website') }}"
                                    value="{{ $data->website }}" required autofocus>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">{{ __('Latitude') }}</label>
                                <input type="text" name="lat" class="form-control" placeholder="{{ __('Latitude') }}"
                                    value="{{ $data->lat }}" required autofocus>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">{{ __('Longitude') }}</label>
                                <input type="text" name="lng" class="form-control" placeholder="{{ __('Longitude') }}"
                                    value="{{ $data->lng }}" required autofocus>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">{{ __('Gambar') }}</label>
                                <input type="text" name="gambar_ukm" class="form-control"
                                    placeholder="{{ __('Gambar UKM') }}" value="{{ $data->gambar_ukm }}" required
                                    autofocus>
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
