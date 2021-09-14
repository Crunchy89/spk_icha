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
                            <th>Kode MK</th>
                            <th>Nama MK</th>
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
    <label for="kode_mk">Kode Mata Kuliah</label>
    <input type="text" class="form-control" name="kode_mk" placeholder="Masukkan Kode Mata Kuliah" id="kode_mk" required>
</div>
<div class="form-group">
    <label for="nama_mk">Nama Mata Kuliah</label>
    <input type="text" class="form-control" name="nama_mk" placeholder="Masukkan Nama Mata Kuliah" id="nama_mk" required>
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
                {data: 'kode_mk', name: 'kode_mk'},
                {data: 'nama_mk', name: 'nama_mk'},
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
          $('#nama_mk').val($(this).data('nama_mk'));
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
