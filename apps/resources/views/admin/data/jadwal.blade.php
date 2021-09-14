@extends('template.core')

@section('title','Data Mata Kuliah')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-body">
            <h3>Data Mata Kuliah</h3>
            <button class="btn btn-primary" id="tambah"><i class="cil-plus"></i> Tambah</button>
            <hr>
            <div class="table-responsive">
                <table id="table" class="table table-bordered data-table w-100" data-url="{{ $datatable }}">
                    <thead>
                        <tr>
                            <th width="50px">No</th>
                            <th>Nama Mahasiswa</th>
                            <th>Nama Mata Kuliah</th>
                            <th>Nama Dosen</th>
                            <th width="100px">Action</th>
                        </tr>
                    </thead>
                    <tbody id="data">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('form-modal')
<input type="hidden" name="aksi" id="aksi">
<input type="hidden" name="id" id="id">
<div class="form-group">
    <label for="nim">Nama Mahasiswa</label>
    <select name="nim" id="nim" class="form-control">
        <option value="">Pilih Mahasiswa</option>
        @foreach ($mhs as $row)
        <option value="{{ $row->nim }}">{{ $row->nama_mhs }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="kode_mk">Nama Mata Kuliah</label>
    <select name="kode_mk" id="kode_mk" class="form-control">
        <option value="">Pilih Mata Kuliah</option>
        @foreach ($mk as $row)
        <option value="{{ $row->kode_mk }}">{{ $row->nama_mk }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="nidn">Nama Dosen</label>
    <select name="nidn" id="nidn" class="form-control">
        <option value="">Pilih Dosen</option>
        @foreach ($dosen as $row)
        <option value="{{ $row->nidn }}">{{ $row->nama_dosen }}</option>
        @endforeach
    </select>
</div>

@endsection

@section('script')
<script>
    var table;
    $(document).ready(function(){
        const url=$('#table').data('url');
        table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: url,
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
                {data: 'nama_mhs', name: 'nama_mhs'},
                {data: 'nama_mk', name: 'nama_mk'},
                {data: 'nama_dosen', name: 'nama_dosen'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        const form=$('.modal-body').html();
        $('#tambah').click(function(){
          $('.modal-body').html(form);
          $('#modal').find('h5').html('Tambah Data')
          $('#modal').find('#aksi').val('tambah')
          $('#modal').find('#btn').html('Tambah')
          $('#modal').modal('show');
        })
        $('#data').on('click','.edit',function(){
          $('.modal-body').html(form);
          $('#modal').find('h5').html('Edit Data')
          $('#id').val($(this).data('id'));
          $('#kode_mk').val($(this).data('kode_mk'));
          $('#nidn').val($(this).data('nidn'));
          $('#nim').val($(this).data('nim'));
          $('#modal').find('#aksi').val('edit')
          $('#modal').find('#btn').html('Edit')
          $('#modal').modal('show');
        })
        $('#data').on('click','.hapus',function(){
          $('.modal-body').html(`
          <input type="hidden" name="aksi" id="aksi" value="hapus">
          <input type="hidden" id="id" name="id">
          <h3>Apakah Anda Yakin ?</h3>
          `);
          $('#modal').find('h5').html('Hapus Data')
          $('#id').val($(this).data('id'));
          $('#modal').find('#aksi').val('hapus')
          $('#modal').find('#btn').html('Hapus')
          $('#modal').modal('show');
        })
    })
</script>
@endsection
