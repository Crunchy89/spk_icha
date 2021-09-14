@extends('template.core')

@section('title','Data Mata Kuliah')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-body">
            <h3>Setting</h3>
            <hr>
            @if(Session::has('berhasil'))
                            <script>
                                $(document).ready(function(){
                                    toastr.success(`{{ Session()->get('berhasil') }}`)
                                })
                            </script>
                            @endif
            @if(Session::has('gagal'))
                            <script>
                                $(document).ready(function(){
                                    toastr.error(`{{ Session()->get('gagal') }}`)
                                })
                            </script>
                            @endif
            <form action="{{ route('setting.simpan') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="nama_aplikasi">Nama_Aplikasi</label>
                    <input type="text" id="nama_aplikasi" name="nama_aplikasi" class="form-control" value="{{ $setting->nama_aplikasi ?? "" }}">
                </div>
                <div class="form-group">
                    <label for="link_api">Link API</label>
                    <input type="text" id="link_api" name="link_api" class="form-control" value="{{ $setting->link_api ?? "" }}">
                </div>
                <button id="simpan" class="btn btn-success" type="submit"><i class="cil-save"></i> Simpan</button>
                <a href="{{ route('sinkronisasi') }}" id="sinkron" type="button" class="btn btn-primary">Sinkronisasi</a>
            </form>
        </div>
    </div>
</div>
@endsection



