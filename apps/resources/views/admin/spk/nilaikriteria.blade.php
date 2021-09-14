@extends('template.core')

@section('title','Data Nilai Kriteria')

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

<div class="container">
    <div class="card">
        <div class="card-body">
            <h3>Data Nilai Kriteria</h3>
            <a href="{{ route('nilaikriteria.tambah') }}" class="btn btn-primary" id="tambah"><i class="cil-plus"></i> Tambah</a>
            <hr>
            <div class="table-responsive">
                <table id="table" class="table table-bordered data-table w-100">
                    <thead>
                        <tr>
                            <th width="50px">No</th>
                            <th>Responden</th>
                            @foreach ($kriteria as $row)
                            <th>{{ $row->label }}</th>
                            @endforeach
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="data">
                        @php
                            $i=1;
                        @endphp
                        @for ($j=0;$j<count($responden);$j++)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{ $responden[$j]->label }}</td>
                            @for ($k=0;$k<count($nilai[$j]);$k++)
                                <td>{{ $nilai[$j][$k] ?? 0 }}</td>
                            @endfor
                            <td>
                                <button id="hapus" data-id="{{ $responden[$j]->id }}" class="btn btn-danger hapus"><i class="cil-trash"></i></button>
                            </td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="modalHapus" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Hapus Data</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('nilaikriteria.hapus') }}" method="POST">
            @csrf
            <div class="modal-body">
                <input type="hidden" id="id" name="id">
                <h3>
                    Apakah anda yakin ?
                </h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-danger">Hapus</button>
            </div>
        </form>
      </div>
    </div>
  </div>

@endsection

@section('script')
<script>
    $(document).ready(function(){
        $('#data').on('click','.hapus',function(){
            let id=$(this).data('id')
            $('#id').val(id)
            $('#modalHapus').modal('show')
        })
    })
</script>
@endsection
