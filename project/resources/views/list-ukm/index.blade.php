@extends('layouts.app', ['title' => __('UKM Management')])

@section('content')
    @include('layouts.headers.cards')

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h3 class="mb-0">{{ __('Daftar UKM') }}</h3>
                            </div>
                            <div class="col-6 text-right">
                                <a href="{{ route('list-ukm.create') }}" class="btn btn-sm btn-primary">{{ __('Tambah Data') }}</a>
                            </div>
                        </div>
                    </div>

                    {{-- SEARCH FORM --}}
                    <div class="col-12" style="margin-bottom:10px;">
                        <form action="{{route('list-ukm.index')}}" method="GET" autocomplete="off">
                            <input type="text" minlength="3" name="search" class="form-control" placeholder="Masukkan kata kunci..">
                        </form>
                    </div>

                    <div class="col-12">
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">{{ __('No.') }}</th>
                                        <th scope="col">{{ __('ID UKM') }}</th>
                                        <th scope="col">{{ __('Nama UKM') }}</th>
                                        <th scope="col">{{ __('Nama Pemilik') }}</th>
                                        <th scope="col">{{ __('Alamat') }}</th>
                                        <th scope="col">{{ __('No. Telp') }}</th>
                                        <th scope="col">{{ __('Website') }}</th>
                                        <th scope="col">{{ __('Latitude') }}</th>
                                        <th scope="col">{{ __('Longitude') }}</th>
                                        <th scope="col">{{ __('Gambar') }}</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $i = 0;
                                    @endphp
                                    @foreach ($dataUKM as $data)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $data->id_ukm }}</td>
                                            <td>{{ $data->nama_ukm }}</td>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->alamat }}</td>
                                            <td>{{ $data->no_telp }}</td>
                                            <td>{{ $data->website }}</td>
                                            <td>{{ $data->lat }}</td>
                                            <td>{{ $data->lng }}</td>
                                            <td>{{ $data->gambar_ukm }}</td>

                                            <td class="text-right">
                                                <div class="dropdown">
                                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                            <form action="{{ route('list-ukm.destroy', $data->id) }}" method="post">
                                                                @csrf
                                                                @method('delete')
                                                                
                                                                <!-- <a class="dropdown-item" href="{{ route('list-ukm.edit', $data->id) }}">{{ __('Edit') }}</a> -->
                                                                <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this user?") }}') ? this.parentElement.submit() : ''">
                                                                    {{ __('Delete') }}
                                                                </button>
                                                            </form>    
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer py-4">
                        <nav class="d-flex justify-content-end" aria-label="...">
                             {{ $dataUKM->links() }} 
                        </nav>
                    </div>
                </div>
            </div>
        </div>
            
        @include('layouts.footers.auth')
    </div>
@endsection