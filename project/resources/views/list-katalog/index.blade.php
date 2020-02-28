@extends('layouts.app', ['title' => __('User Management')])

@section('content')
@include('layouts.headers.cards')
{{-- CEK STATUS LOGIN --}}
@php
$isAdmin = auth()->user()->status;
@endphp
<?php if($isAdmin != 3){?>
<div class="container-fluid mt--7">
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h3 class="mb-0">{{ __('Daftar Katalog') }}</h3>
                        </div>
                        <div class="col-6 text-right">
                            <a href="{{ route('katalog.create') }}"
                                class="btn btn-sm btn-primary">{{ __('Tambah Data') }}</a>
                        </div>
                    </div>
                </div>

                {{-- SEARCH FORM --}}
                <div class="col-12" style="margin-bottom:10px;">
                    <form action="{{route('katalog.index')}}" method="GET" autocomplete="off">
                        <input type="text" minlength="3" name="search" class="form-control"
                            placeholder="Masukkan kata kunci..">
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
                                    <th scope="col">{{ __('Id Katalog') }}</th>
                                    <!-- <th scope="col">{{ __('Id UKM') }}</th> -->
                                    <th scope="col">{{ __('Judul Katalog') }}</th>
                                    <th scope="col">{{ __('Usia') }}</th>
                                    <th scope="col">{{ __('Ukuran') }}</th>
                                    <th scope="col">{{ __('Stok Katalog') }}</th>
                                    <th scope="col">{{ __('Harga Katalog') }}</th>
                                    <th scope="col">{{ __('Gambar Katalog') }}</th>
                                    <th scope="col">{{ __('Pengubah') }}</th>
                                    <th scope="col">{{ __('Tanggal Buat') }}</th>
                                    <th scope="col">{{ __('Tanggal Ubah') }}</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataKatalog as $data)
                                <tr>
                                    <td>{{ $data->id_katalog }}</td>
                                    <!-- <td>{{ $data->id_ukm }}</td> -->
                                    <td>{{ $data->judul_katalog }}</td>
                                    <td>{{ $data->usia }}</td>
                                    <td>{{ $data->ukuran }}</td>
                                    <td>{{ $data->stok_katalog }}</td>
                                    <td>{{ $data->harga_katalog }}</td>
                                    <td>{{ $data->gambar_katalog }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td><?php echo date("d-M-Y",strtotime($data->created_at)); ?></td>
                                    <td><?php echo date("d-M-Y",strtotime($data->updated_at)); ?></td>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <?php if($data->gambar_katalog != null){?>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <form action="{{ route('katalog.destroy', $data->id_katalog) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('delete')

                                                    <a class="dropdown-item"
                                                        href="{{ route('katalog.edit', $data->id_katalog) }}">{{ __('Ubah') }}</a>
                                                    <a class="dropdown-item"
                                                        href="/data/{{ $data->gambar_katalog }}">{{ __('Lihat Gambar') }}</a>
                                                    <button type="button" class="dropdown-item"
                                                        onclick="confirm('{{ __("Apakah Anda yakin akan menghapus data ini?") }}') ? this.parentElement.submit() : ''">
                                                        {{ __('Hapus') }}
                                                    </button>
                                                </form>
                                            </div>
                                            <?php }else{?>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <form action="{{ route('katalog.destroy', $data->id_katalog) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('delete')

                                                    <a class="dropdown-item"
                                                        href="{{ route('katalog.edit', $data->id_katalog) }}">{{ __('Ubah') }}</a>
                                                    <button type="button" class="dropdown-item"
                                                        onclick="confirm('{{ __("Apakah Anda yakin akan menghapus data ini?") }}') ? this.parentElement.submit() : ''">
                                                        {{ __('Hapus') }}
                                                    </button>
                                                </form>
                                            </div>
                                            <?php }?>
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
                        {{ $dataKatalog->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footers.auth')
</div>
<?php }?>
@endsection
