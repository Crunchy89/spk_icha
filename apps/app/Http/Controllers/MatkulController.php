<?php

namespace App\Http\Controllers;

use App\Helpers\Crud;
use Illuminate\Http\Request;
use App\Matkul;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class MatkulController extends Controller
{
    //
    public function __construct()
    {
        $this->helper = new Crud('mata_kuliah', 'id');
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Matkul::orderBy('kode_mk', 'ASC')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $enId = Crypt::encrypt($row->id);
                    $btn = "<button data-id='$enId' data-nama_mk='$row->nama_mk' data-kode_mk='$row->kode_mk' class='m-1 btn btn-warning edit'><i class='cil-pencil'></i></button> ";
                    $btn .= "<button data-id='$enId' class='m-1 btn btn-danger hapus'><i class='cil-trash'></i></button>";
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $data = [
            'url' => route('matkul.aksi'),
            'datatable' => route('matkul'),
        ];
        return view('admin.data.matkul', $data);
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
            'kode_mk' => 'required',
            'nama_mk' => 'required',
        ]);
        $tambah = [
            'kode_mk' => $request->kode_mk,
            'nama_mk' => $request->nama_mk,
        ];
        $data = $this->helper->tambah($tambah);
        return $data;
    }
    private function edit(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'kode_mk' => 'required',
            'nama_mk' => 'required',
        ]);
        $id = Crypt::decrypt($request->id);
        $edit = [
            'kode_mk' => $request->kode_mk,
            'nama_mk' => $request->nama_mk,
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
