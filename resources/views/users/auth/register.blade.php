@extends('users.layouts.auth')

@section('title', 'Register')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">

    <!-- Template CSS -->
@endpush

@section('main')
    <div class="card card-primary">
        <div class="card-header">
            <h4 class="text-center">Isi Data Pelapor Users</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="form-group col-6">
                        <label for="no_HP">No HP</label>
                        <input id="no_hp" type="number" class="form-control" name="no_hp" autofocus>
                        <label for="age">Umur</label>
                        <input id="age" type="number" class="form-control" name="age" autofocus>
                    </div>
                    @if ($errors->has('age'))
                        <span class="help-block">{{ $errors->first('age') }}</span>
                    @endif
                    <div class="form-group col-6">
                        <label for="gender">Gender</label>
                        <select name="gender" id="gender" class="form-control">
                            <option value="" selected>Pilih Gender</option>
                            <option value="laki-laki">Laki-Laki</option>
                            <option value="perempuan">Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-6">
                        <label for="province" class="d-block">Provinsi</label>
                        <input id="province" type="text" class="form-control pwstrength" data-indicator="pwindicator"
                            name="province">
                    </div>
                    <div class="form-group col-6">
                        <label for="city" class="d-block">Kota</label>
                        <input id="city" type="text" class="form-control" name="city">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label>Kelurahan</label>
                        <input id="kelurahan" type="text" class="form-control" name="kelurahan">
                    </div>
                    <div class="form-group col-6">
                        <label>Kecamatan</label>
                        <input id="kecamatan" type="text" class="form-control" name="kecamatan">
                    </div>
                </div>

                {{-- <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="agree" class="custom-control-input" id="agree">
                        <label class="custom-control-label" for="agree">I agree with the terms and conditions</label>
                    </div>
                </div> --}}

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('library/jquery.pwstrength/jquery.pwstrength.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/auth-register.js') }}"></script>
@endpush
