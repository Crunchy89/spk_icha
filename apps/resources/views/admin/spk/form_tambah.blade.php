@extends('template.core')

@section('title','Data Nilai Kriteria')

@section('content')

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
            <h3>Tambah Data Nilai Kriteria</h3>
            <hr>
            <div class="table-responsive">
                <form action="{{ route('nilaikriteria.simpan') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="label">Label</label>
                        <input type="text" id="label" name="label" placeholder="Masukkan Label" class="form-control" value="{{ old('label') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="responden">Responden</label>
                        <select id="responden" name="responden" class="form-control">
                            <option value="">Pilih Responden</option>
                            @foreach ($dosen as $row)
                            <option value="{{ $row->nama_dosen }}">{{ $row->nama_dosen }}</option>
                            @endforeach
                        </select>
                        {{-- <input type="text" id="responden" name="responden" placeholder="Masukkan nama Responden" value="{{ old('responden') }}" class="form-control" required> --}}
                    </div>
                    <table id="table" class="table table-bordered data-table w-100">
                        @php
                        $i=1;
                        @endphp
                    <tbody id="data">
                        @foreach ($kriteria as $row)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $row->kriteria }}
                                <input type="hidden" class="form-control" name="id_kriteria[]" value="{{ $row->id }}">
                            </td>
                            <td><input type="text" class="form-control nilai" name="nilai[]" placeholder="masukkan nilai" required></td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="2" class="text-center"><b>Total</b></td>
                            <td id="total"></td>
                        </tr>
                    </tbody>
                </table>
                <button id="simpan" class="btn ml-1 btn-success float-right" type="submit"><i class="cil-save"></i> Simpan</button>
                <a href="{{ route('nilaikriteria') }}" class="btn btn-info float-right"><i class="cil-arrow-left"></i> Kembali</a>
            </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function(){
        const max = 100;
        function Total(){
            let total_nilai=0;
            $('.nilai').each(function(){
                total_nilai += $(this).val() == "" ? 0 : parseFloat($(this).val());
                $('#total').html(total_nilai);
            })
            if(total_nilai > 100){
                toastr.error("nilai melebihi 100")
            }
        }
        Total()
        $('#data').on('keyup','.nilai',function(){
            Total()
        })
    })
</script>
@endsection
