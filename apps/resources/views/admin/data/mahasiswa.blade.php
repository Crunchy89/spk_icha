@extends('template.core')

@section('title','Data Mahasiswa')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-body">
            <h3>Data Mahasiswa</h3>
            <button class="btn btn-primary" id="tambah"><i class="cil-plus"></i> Tambah</button>
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered data-table w-100">
                    <thead>
                        <tr>
                            <th width="50px">No</th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Jurusan</th>
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
    <label for="nim">NIM</label>
    <input type="text" class="form-control" name="nim" placeholder="Masukkan NIM Mahasiswa" id="nim" required>
</div>
<div class="form-group">
    <label for="nama_mhs">Nama Mahasiswa</label>
    <input type="text" class="form-control" name="nama_mhs" placeholder="Masukkan Nama Mahasiswa" id="nama_mhs" required>
</div>
<div class="form-group">
    <label for="jurusan">Jurusan</label>
    <input type="text" class="form-control" name="jurusan" placeholder="Masukkan Jurusan" id="jurusan" required>
</div>

@endsection

@section('script')
<script>
    var table;
    $(document).ready(function(){
        table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('admin/data/mahasiswa') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
                {data: 'nim', name: 'nim'},
                {data: 'nama_mhs', name: 'nama_mhs'},
                {data: 'jurusan', name: 'jurusan'},
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
          $('#nim').val($(this).data('nim'));
          $('#nama_mhs').val($(this).data('nama_mhs'));
          $('#jurusan').val($(this).data('jurusan'));
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
