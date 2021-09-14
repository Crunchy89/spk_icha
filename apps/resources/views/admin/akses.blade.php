@extends('template.core')

@section('title','akses managemen')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-body">
            <h3>Akses</h3>
            <div class="table-responsive">
                <table class="table table-bordered data-table w-100">
                    <thead>
                        <tr>
                            <th></th>
                            @foreach ($role as $row)
                            <th>{{ $row->role }}</th>
                            @endforeach
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

@section('script')
<script>
$(document).ready(function(){
    get()
    function get(){
        axios.get(`{{ url('admin/akses/getAkses') }}`)
        .then(res=>{
            console.log(res.data)
            let data='';
            res.data.map(menu=>{
                let input='';
                menu.data.map(row=>{
                    if(row.value == 1 ){
                        input +=`
                        <td>
                            <input type="checkbox" class='check' checked data-menu_id='${row.menu_id}' data-role_id='${row.role_id}' >
                        </td>
                        `;
                    }else{
                        input +=`
                        <td>
                            <input type="checkbox" class='check' data-menu_id='${row.menu_id}' data-role_id='${row.role_id}' >
                        </td>
                        `;
                    }
                })
                data+=`
                <tr>
                    <td>${menu.title}</td>
                    ${input}
                </tr>
                `;
            })
            $('#data').html(data)
        })
        .catch(err=>{
            toastr['error']('terjadi kesalahan saat mengambil data')
        })

        $('#data').on('change','input',function(){
            let menu_id=$(this).data('menu_id')
            let role_id=$(this).data('role_id')
            let data={menu_id:menu_id,role_id:role_id}
            axios.post(`{{ url('admin/akses/check') }}`,data)
            .then(res=>{
                if(res.data.status){
                    toastr["success"](res.data.pesan);
                }else{
                    toastr["error"](res.data.pesan);
                }
            })
            .catch(err=>{
                toastr["error"]('Terjadi kesalahan pada server');
            })
        })
    }
});
</script>
@endsection
