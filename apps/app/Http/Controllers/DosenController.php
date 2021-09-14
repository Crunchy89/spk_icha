<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dosen;
use App\Helpers\Crud;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class DosenController extends Controller
{
    public function __construct()
    {
        $this->helper = new Crud('dosen', 'id');
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Dosen::orderBy('nama_dosen', 'ASC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $enId = Crypt::encrypt($row->id);
                    $btn = "<button data-id='$enId' data-nidn='$row->nidn' data-nama_dosen='$row->nama_dosen'class='m-1 btn btn-warning edit'><i class='cil-pencil'></i></button> ";
                    $btn .= "<button data-id='$enId' class='m-1 btn btn-danger hapus'><i class='cil-trash'></i></button>";
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $data = [
            'url' => url('admin/data/dosen/aksi'),
        ];
        return view('admin.data.dosen', $data);
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
            'nidn' => 'required',
            'nama_dosen' => 'required',
        ]);
        $tambah = [
            'nidn' => $request->nidn,
            'nama_dosen' => $request->nama_dosen,
        ];
        $data = $this->helper->tambah($tambah);
        return $data;
    }
    private function edit(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'nidn' => 'required',
            'nama_dosen' => 'required',
        ]);
        $id = Crypt::decrypt($request->id);
        $edit = [
            'nidn' => $request->nidn,
            'nama_dosen' => $request->nama_dosen,
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
