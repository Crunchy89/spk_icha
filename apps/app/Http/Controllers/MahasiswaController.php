<?php

namespace App\Http\Controllers;

use App\Helpers\Crud;
use Illuminate\Http\Request;
use App\Mahasiswa;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class MahasiswaController extends Controller
{
    public function __construct()
    {
        $this->helper = new Crud('mahasiswa', 'id');
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Mahasiswa::orderBy('nim', 'ASC')
                ->orderBy('nama_mhs', 'ASC')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $enId = Crypt::encrypt($row->id);
                    $btn = "<button data-id='$enId' data-nim='$row->nim' data-nama_mhs='$row->nama_mhs'  data-jurusan='$row->jurusan' class='m-1 btn btn-warning edit'><i class='cil-pencil'></i></button> ";
                    $btn .= "<button data-id='$enId' class='m-1 btn btn-danger hapus'><i class='cil-trash'></i></button>";
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $data = [
            'url' => url('admin/data/mahasiswa/aksi'),
        ];
        return view('admin.data.mahasiswa', $data);
    }

    public function aksi(Request $request)
    {
        $aksi = $request->post('aksi');
        $data = [];
        if ($aksi == 'tambah') {
            $data = $this->tambah($request);
        } elseif ($aksi == 'edit') {
            $data = $this->edit($request);
        } elseif ($aksi == 'hapus') {
            $data = $this->hapus($request);
        } else {
            $data = [
                'status' => false,
                'pesan' => 'Tidak ada pilihan aksi'
            ];
        }
        return response()->json($data);
    }

    private function tambah(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required',
            'nama_mhs' => 'required',
            'jurusan' => 'required',
        ]);
        $tambah = [
            'nim' => $request->nim,
            'nama_mhs' => $request->nama_mhs,
            'jurusan' => $request->jurusan,
        ];
        $data = $this->helper->tambah($tambah);
        return $data;
    }
    private function edit(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'nim' => 'required',
            'nama_mhs' => 'required',
            'jurusan' => 'required',
        ]);
        $id = Crypt::decrypt($request->id);
        $edit = [
            'nim' => $request->nim,
            'nama_mhs' => $request->nama_mhs,
            'Jurusan' => $request->jurusan,
        ];
        $data = $this->helper->edit($id, $edit);
        return $data;
    }
    private function hapus(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
        ]);
        $id = Crypt::decrypt($request->id);
        $data = $this->helper->hapus($id);
        return $data;
    }
}
