@extends('template.core')

@section('title','Master Menu')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-body">
            <h3>Menu</h3>
            <button class="btn btn-primary" id="tambah"><i class="cil-plus"></i> Tambah</button>
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered data-table w-100">
                    <thead>
                        <tr>
                            <th width="50px">No</th>
                            <th>Title</th>
                            <th>Icon</th>
                            <th>Link</th>
                            <th>Aktif</th>
                            <th>Urutan</th>
                            <th>Submenu</th>
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
    <label for="title">Title</label>
    <input type="text" class="form-control" name="title" placeholder="Title" id="title" required>
</div>
<div class="form-group">
    <label for="icon">Icon</label>
    <input type="text" class="form-control" name="icon" placeholder="Icon" id="icon" required>
</div>
<div class="form-group">
    <label for="link">Link</label>
    <input type="text" class="form-control" name="link" placeholder="link" id="link" required>
</div>
@endsection

@section('script')
<script>
    var table;
    $(document).ready(function(){
        table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('admin/menu') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
                {data: 'title', name: 'title',orderable: false, searchable: false},
                {data: 'icon', name: 'icon',orderable: false, searchable: false},
                {data: 'link', name: 'link',orderable: false, searchable: false},
                {data: 'aktif', name: 'aktif',orderable: false, searchable: false},
                {data: 'urutan', name: 'urutan',orderable: false, searchable: false},
                {data: 'submenu', name: 'submenu',orderable: false, searchable: false},
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
          axios.post(`{{url('admin/menu/aktif')}}`,data)
          .then(res=>{
            toastr['success'](res.data.pesan);
            table.ajax.reload()
          })
          .catch(err=>{
            console.log(err)
          })
        })
        $('#data').on('click','.up',function(){
          let id = $(this).data('id');
          let data={id:id};
          axios.post(`{{url('admin/menu/up')}}`,data)
          .then(res=>{
            toastr['success'](res.data.pesan);
            table.ajax.reload()
          })
          .catch(err=>{
            console.log(err)
          })
        })
        $('#data').on('click','.down',function(){
          let id = $(this).data('id');
          let data={id:id};
          axios.post(`{{url('admin/menu/down')}}`,data)
          .then(res=>{
            toastr['success'](res.data.pesan);
            table.ajax.reload()
          })
          .catch(err=>{
            console.log(err)
          })
        })
        $('#data').on('click','.edit',function(){
          $('.modal-body').html(form);
          $('#modal').find('h5').html('Edit Data')
          $('#id').val($(this).data('id'));
          $('#title').val($(this).data('title'));
          $('#icon').val($(this).data('icon'));
          $('#link').val($(this).data('link'));
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
