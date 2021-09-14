@extends('template.core')

@section('title','Kuisioner Mahasiswa')

@section('content')

@if(Session::has('error'))
    <script>
        $(document).ready(function(){
            toastr.error(`{{ Session()->get('error') }}`)
        })
    </script>
@endif

<form action="{{ route('kuisioner.simpan',['id'=>$id]) }}" method="post">
    @csrf
<div class="container-fluid">
<div class="card">
    <div class="card-header">
        <h3>Kuisioner {{ $mk->nama_mk }}</h3>
    </div>
    <div class="card-body">
        <a href="{{ route('kuisioner') }}" class="btn btn-info"><i class="cil-arrow-left"></i> Kembali</a>
        <hr>
        @php
            $i=0;
        @endphp
        <ol type="1">
        @foreach ($kriteria as $row)
            <div class="form-group">
                <h3><li>{{ $row->kriteria }}</li></h3>
                <ul type="none">
                    <input type="hidden" name="id_kriteria[{{$i}}]" value="{{$row->id}}">
                @php
                 $j=0;
                @endphp
                    @foreach ($nilai as $n)
                    <li><input type="radio" value="{{ $n->nilai }}" id="{{$i.$j}}" name="nilai[{{$i}}]" required> <label for="{{$i.$j}}">{{ $n->nama_pilihan }}</label></li>
                   @php
                       $j++;
                   @endphp
                    @endforeach
                </ul>
            </div>
            @php
                $i++;
            @endphp
        @endforeach
    </ol>
    </div>
</div>
</div>
<div class="container-fluid">
<div class="card">
    <div class="card-header">
        <h3>Komentar</h3>
    </div>
    <div class="card-body">
       <div class="form-group">
           <textarea class="form-control" name="komentar" id="komentar" cols="30" rows="10" required></textarea>
       </div>
       <div class="form-group">
           <button type="submit" class="btn btn-success float-right"><i class="cil-save"></i> Simpan</button>
       </div>
    </div>
</div>
</div>


</form>
@endsection


