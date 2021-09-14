@extends('template.core')

@section('title','user managemen')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-body">
            <h3>User</h3>
            <button class="btn btn-primary" id="tambah"><i class="cil-plus"></i> Tambah</button>
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered data-table w-100">
                    <thead>
                        <tr>
                            <th width="50px">No</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Aktif</th>
                            <th>Reset Pasword</th>
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
    <label for="username">Username</label>
    <input type="text" class="form-control" name="username" placeholder="Username" id="username" required>
</div>
<div id="pass">
    <div class="form-group">
        <label for="icon">Password</label>
        <input type="password" class="form-control" name="password" placeholder="password" id="password" required>
    </div>
</div>
<div class="form-group">
    <label for="role">Role</label>
    <select class="form-control" name="role" id="role" required>
        <option value="">Pilih Role</option>
        @foreach ($role as $row)
            <option value="{{$row->id}}">{{$row->role}}</option>
        @endforeach
    </select>
</div>
@endsection

@section('script')
<script>
    var table;
    $(document).ready(function(){
        table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('admin/user') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
                {data: 'username', name: 'username'},
                {data: 'role', name: 'role'},
                {data: 'aktif', name: 'aktif',orderable: false, searchable: false},
                {data: 'reset', name: 'reset',orderable: false, searchable: false},
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
        $('#data').on('click','.aktif',function(){
          let id = $(this).data('id');
          let data={id:id};
          axios.post(`{{url('admin/user/aktif')}}`,data)
          .then(res=>{
              if(res.data.status){
                  toastr['success'](res.data.pesan);
                }else{
                  toastr['error'](res.data.pesan);
                }
            table.ajax.reload()
          })
          .catch(err=>{
            console.log(err)
          })
        })
        $('#data').on('click','.edit',function(){
          $('.modal-body').html(form);
          $('#modal').find('h5').html('Edit Data')
          $('#pass').html('');
          $('#id').val($(this).data('id'));
          $('#username').val($(this).data('username'));
          $('#role').val($(this).data('role_id'));
          $('#modal').find('#aksi').val('edit')
          $('#modal').find('#btn').html('Edit')
          $('#modal').modal('show');
        })
        $('#data').on('click','.reset',function(){
            $('.modal-body').html(`
            <input type="hidden" name="aksi" id="aksi" value="reset">
            <input type="hidden" id="id" name="id">
            <div class="form-group">
                <label for="password">Password Baru</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password Baru" required>
            </div>
          `);
          $('#modal').find('h5').html('Reset password')
          $('#id').val($(this).data('id'));
          $('#modal').find('#aksi').val('reset')
          $('#modal').find('#btn').html('Reset')
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
