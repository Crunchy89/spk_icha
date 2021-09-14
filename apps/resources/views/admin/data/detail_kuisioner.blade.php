@extends('template.core')

@section('title','Data Nilai Kuisioner')

@section('content')

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h3>Detail Nilai</h3>
            <a href="{{ route('data_kuisioner') }}" class="btn btn-info"><i class="cil-arrow-left"></i> Kembali</a>
            <hr>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Kriteria</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $row)
                            <tr>
                                <td>{{ $row->kriteria }}</td>
                                <td>{{ $row->nilai }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection


