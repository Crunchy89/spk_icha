@extends('template.core')

@section('title','Master role')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h3>Role</h3>
            <button class="btn btn-primary" id="tambah"><i class="cil-plus"></i> Tambah</button>
            <hr>
            <div class="table-responsive">
                <table id="table" class="table table-bordered data-table" style="width: 100%" data-datatable="{{ $datatable }}">
                    <thead>
                        <tr>
                            <th width="50px">No</th>
                            <th>Role</th>
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
    <label for="role">Role</label>
    <input type="text" class="form-control" name="role" placeholder="Role" id="role" required>
</div>
@endsection

@section('script')
      <script>
          var table;
          $(document).ready(function(){
                  let datatable=$('#table').data('datatable')
                  table = $('.data-table').DataTable({
                      processing: true,
                      serverSide: true,
                      ajax: datatable,
                      columns: [
                          {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
                          {data: 'role', name: 'role'},
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
                    $('#role').val($(this).data('role'));
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

