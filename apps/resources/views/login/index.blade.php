@extends('template.admin')

@section('title','Login Page')

@section('body')

<div class="container-fluid" id="root">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-8">
            <div class="card-group">
                <div class="card text-white bg-warning py-5 d-md-down-none" style="width:44%">
                    <div class="card-body d-flex justify-content-center align-items-center flex-column">
                        <img src="{{asset('assets')}}/img/loteng.png" alt="logo" height="150px">
                        <h4 class="text-center">{{ $setting->nama_aplikasi }}</h4>
                    </div>
                </div>
                <div class="card p-4">
                    <form action="{{ url('/ceklogin') }}" method="post">
                        {{ csrf_field() }}
                        <div class="card-body">
                            @if(Session::has('message'))
                            {{-- <div class="alert alert-danger text-center">
                            </div> --}}
                            <script>
                                $(document).ready(function(){
                                    toastr.error(`{{ Session()->get('message') }}`)
                                })
                            </script>
                            @endif
                            <h1>Selamat Datang</h1>
                            <p class="text-muted">Masuk ke aplikasi</p>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="cil-user"></i>
                                    </span>
                                </div>
                                <input class="form-control @error('username') is-invalid @enderror" type="text" placeholder="Username" name="username" id="username" value="{{ old('username') }}">
                                @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                  </div>
                                @enderror
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="cil-lock-locked"></i>
                                    </span>
                                </div>
                                <input class="form-control @error('password') is-invalid @enderror" type="password" placeholder="Password" name="password" id="password">
                                @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                  </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button class="btn btn-warning" type="submit">Masuk</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

