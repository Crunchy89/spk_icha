@extends('template.core')

@section('title','Data Kriteria')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-body">
            <h3>Data Kriteria</h3>
            <button class="btn btn-primary" id="tambah"><i class="cil-plus"></i> Tambah</button>
            <hr>
            <div class="table-responsive">
                <table id="table" class="table table-bordered data-table w-100" data-url="{{ $datatable }}">
                    <thead>
                        <tr>
                            <th width="50px">No</th>
                            <th>Label</th>
                            <th>Kriteria</th>
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
    <label for="label">Label</label>
    <input type="text" class="form-control" name="label" placeholder="Masukkan label" id="label" required>
</div>
<div class="form-group">
    <label for="kriteria">Kriteria</label>
    <input type="text" class="form-control" name="kriteria" placeholder="Masukkan kriteria" id="kriteria" required>
</div>
@endsection

@section('script')
<script>
    var table;
    $(document).ready(function(){
        let url = $('#table').data('url');
        table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: url,
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
                {data: 'label', name: 'label'},
                {data: 'kriteria', name: 'kriteria'},
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
          $('#label').val($(this).data('label'));
          $('#kriteria').val($(this).data('kriteria'));
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
