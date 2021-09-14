@extends('template.core')

@section('title','Kuisioner Mahasiswa')

@section('content')

@if(Session::has('success'))
                            <script>
                                $(document).ready(function(){
                                    toastr.success(`{{ Session()->get('success') }}`)
                                })
                            </script>
                            @endif
@if(Session::has('error'))
                            <script>
                                $(document).ready(function(){
                                    toastr.error(`{{ Session()->get('error') }}`)
                                })
                            </script>
                            @endif

<div class="container-fluid">
    <div class="row">
        @foreach ($jadwal as $row)
        @php
            $cek=\App\RespondenKuisioner::whereNim($row->nim)->whereNidn($row->nidn)->whereKode_mk($row->kode_mk)->first();
        @endphp
        @if (!$cek)
        <div class="col-sm-12 col-md-6 col-lg-4">
            <a href="{{ route('kuisioner.isi',['id'=>Crypt::encrypt($row->id)]) }}" class="card text-dark" style="text-decoration:none">
                <div class="card-header bg-white content-center p-5 d-flex justify-content-center align-items-center" style="height: 50px">
                    <h5>{{ $row->nama_mk }}</h5>
                </div>
                <div class="card-body row text-center" style="height: 100px">
                    <div class="col">
                        <div class="text-value-md">{{$row->nama_dosen}}</div>
                        <div class="text-uppercase text-muted small">Dosen</div>
                    </div>
                    <div class="c-vr"></div>
                    <div class="col">
                        <div class="text-value-ms">{{ $row->jurusan }}</div>
                        <div class="text-uppercase text-muted small">Program Studi</div>
                    </div>
                </div>
            </a>
        </div>
        @endif

        @endforeach
    </div>
</div>

@endsection


