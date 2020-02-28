@extends('layouts.app', ['title' => __('Manajemen UKM')])

@section('content')
@include('users.partials.header', ['title' => __('Ubah UKM')])

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">{{ __('Ubah Data UKM') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="post" action="{{route('pemilik-ukm.update')}}" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        @method('put')

						<h6 class="heading-small text-muted mb-4">{{ __('Informasi Lokasi UKM') }}</h6>

						@if (session('status'))
							<div class="alert alert-success alert-dismissible fade show" role="alert">
								{{ session('status') }}
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
						@endif
                        <div class="pl-lg-4">
                            <!-- <div class="form-group">
                                <label class="form-control-label">{{ __('ID UKM') }}</label>
                                <input type="text" name="id_ukm" class="form-control" placeholder="{{ __('ID UKM') }}"
                                    value="{{ $data->id_ukm }}" required autofocus>
                            </div> -->
                            <div class="form-group">
                                <label class="form-control-label">{{ __('Nama UKM') }}</label>
                                <input type="text" name="nama_ukm" class="form-control"
                                    placeholder="{{ __('Nama UKM') }}" value="{{ $data->nama_ukm }}" required autofocus>
								@if ($errors->has('nama_ukm'))
								<span class="invalid-feedback" style="display: block;" role="alert">
									<strong>{{ $errors->first('nama_ukm') }}</strong>
								</span>
                                @endif
							</div>
                            <div class="form-group">
                                <label class="form-control-label">{{ __('Nomor Telepon') }}</label>
                                <input type="text" name="no_telp" class="form-control"
                                    placeholder="{{ __('Nomor Telepon') }}" value="{{ $data->no_telp }}" required
                                    autofocus>
								@if ($errors->has('no_telp'))
								<span class="invalid-feedback" style="display: block;" role="alert">
									<strong>{{ $errors->first('no_telp') }}</strong>
								</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">{{ __('Website') }}</label>
                                <input type="text" name="website" class="form-control" placeholder="{{ __('Website') }}"
                                    value="{{ $data->website }}" required autofocus>
								@if ($errors->has('website'))
								<span class="invalid-feedback" style="display: block;" role="alert">
									<strong>{{ $errors->first('website') }}</strong>
								</span>
                                @endif
                            </div>
                            <div class="form-group input-group">
                                <input type="text" id="search_location" class="form-control" placeholder="Cari Lokasi">
                                <div class="input-group-btn">
                                    <button class="btn btn-default get_map" type="submit">
                                        Get
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <div id="geomap"></div>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">{{ __('Alamat') }}</label>
                                <input type="text" name="alamat" class="form-control search_addr" placeholder="{{ __('Alamat') }}"
                                    value="{{ $data->alamat }}" required autofocus>
								@if ($errors->has('alamat'))
								<span class="invalid-feedback" style="display: block;" role="alert">
									<strong>{{ $errors->first('alamat') }}</strong>
								</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">{{ __('Latitude') }}</label>
                                <input type="text" name="lat" class="form-control search_latitude" placeholder="{{ __('Latitude') }}"
                                    value="{{ $data->lat }}" required autofocus>
									@if ($errors->has('lat'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('lat') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">{{ __('Longitude') }}</label>
                                <input type="text" name="lng" class="form-control search_longitude" placeholder="{{ __('Longitude') }}"
                                    value="{{ $data->lng }}" required autofocus>
								@if ($errors->has('lng'))
								<span class="invalid-feedback" style="display: block;" role="alert">
									<strong>{{ $errors->first('lng') }}</strong>
								</span>
                                @endif
                            </div>
							<?php if($data->gambar_ukm != null){?>
								<img src="/data/{{ $data->gambar_ukm }}" alt="Anda Belum Memiliki Gambar/Logo UKM" width="150">
								<div class="form-group">
                                <label class="form-control-label">{{ __('Gambar') }}</label>
                                <input type="file" name="gambar_ukm" class="form-control"
                                    placeholder="{{ __('Gambar UKM') }}" value="{{ $data->gambar_ukm }}">
								@if ($errors->has('gambar_ukm'))
								<span class="invalid-feedback" style="display: block;" role="alert">
									<strong>{{ $errors->first('gambar_ukm') }}</strong>
								</span>
                                @endif
                            </div>
							<?php }else{?>
								<div class="form-group">
                                <label class="form-control-label">{{ __('Gambar') }}</label>
                                <input type="file" name="gambar_ukm" class="form-control"
                                    placeholder="{{ __('Gambar UKM') }}" value="{{ $data->gambar_ukm }}">
								@if ($errors->has('gambar_ukm'))
								<span class="invalid-feedback" style="display: block;" role="alert">
									<strong>{{ $errors->first('gambar_ukm') }}</strong>
								</span>
                                @endif
                            </div>
							<?php }?>
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

<script type="text/javascript">
			var geocoder;
			var map;
			var marker;

		/*
		 * Google Map with marker
		 */
		function initialize() {
			var initialLat = $('.search_latitude').val();
			var initialLong = $('.search_longitude').val();
			initialLat = initialLat?initialLat: -7.761881;
			initialLong = initialLong?initialLong: 110.403333;

			var latlng = new google.maps.LatLng(initialLat, initialLong);
			var options = {
				zoom: 16,
				center: latlng,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};

			map = new google.maps.Map(document.getElementById("geomap"), options);

			geocoder = new google.maps.Geocoder();

			marker = new google.maps.Marker({
				map: map,
				draggable: true,
				position: latlng
			});

			google.maps.event.addListener(marker, "dragend", function () {
				var point = marker.getPosition();
				map.panTo(point);
				geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						map.setCenter(results[0].geometry.location);
						marker.setPosition(results[0].geometry.location);
						$('.search_addr').val(results[0].formatted_address);
						$('.search_latitude').val(marker.getPosition().lat());
						$('.search_longitude').val(marker.getPosition().lng());
					}
				});
			});

		}

		$(document).ready(function () {
			//load google map
			initialize();
			
			/*
			 * autocomplete location search
			*/
			var PostCodeid = '#search_location';
			$(function () {
				$('#search_location').autocomplete({
					source: function (request, response) {
						geocoder.geocode({
							'address': request.term
						}, function (results, status) {
							response($.map(results, function (item) {
								return {
									label: item.formatted_address,
									value: item.formatted_address,
									lat: item.geometry.location.lat(),
									lon: item.geometry.location.lng()
								};
							}));
						});
					},
					select: function (event, ui) {
						$('.search_addr').val(ui.item.value);
						$('.search_latitude').val(ui.item.lat);
						$('.search_longitude').val(ui.item.lon);
						var latlng = new google.maps.LatLng(ui.item.lat, ui.item.lon);
						marker.setPosition(latlng);
						initialize();
					}
				});
			});

			
			/*
			 * Point location on google map
			 */
			$('.get_map').click(function (e) {
				var address = $(PostCodeid).val();
				geocoder.geocode({'address': address}, function (results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						map.setCenter(results[0].geometry.location);
						marker.setPosition(results[0].geometry.location);
						$('.search_addr').val(results[0].formatted_address);
						$('.search_latitude').val(marker.getPosition().lat());
						$('.search_longitude').val(marker.getPosition().lng());
					} else {
						alert("Geocode was not successful for the following reason: " + status);
					}
				});
				e.preventDefault();
			});

			//Add listener to marker for reverse geocoding
			google.maps.event.addListener(marker, 'drag', function () {
				geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						if (results[0]) {
							$('.search_addr').val(results[0].formatted_address);
							$('.search_latitude').val(marker.getPosition().lat());
							$('.search_longitude').val(marker.getPosition().lng());
						}
					}
				});
			});
		});
		
	</script>

    <style>
        #geomap{
            width: 100%;
            height: 400px;
        }
    </style>

@endsection
