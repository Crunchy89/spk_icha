<?php

namespace App\Http\Controllers;

use App\Helpers\Crud;
use Illuminate\Http\Request;
use App\NilaiPilihan;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class NilaiPilihanController extends Controller
{
    //
    public function __construct()
    {
        $this->helper = new Crud('nilai_pilihan', 'id');
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = NilaiPilihan::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $enId = Crypt::encrypt($row->id);
                    $btn = "<button data-id='$enId' data-nama_pilihan='$row->nama_pilihan' data-nilai='$row->nilai' class='m-1 btn btn-warning edit'><i class='cil-pencil'></i></button> ";
                    $btn .= "<button data-id='$enId' class='m-1 btn btn-danger hapus'><i class='cil-trash'></i></button>";
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $data = [
            'url' => route('nilai.aksi'),
            'datatable' => route('nilai'),
        ];
        return view('admin.spk.nilai', $data);
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
            'nama_pilihan' => 'required',
            'nilai' => 'required',
        ]);
        $tambah = [
            'nama_pilihan' => $request->nama_pilihan,
            'nilai' => $request->nilai,
        ];
        $data = $this->helper->tambah($tambah);
        return $data;
    }
    private function edit(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'nama_pilihan' => 'required',
            'nilai' => 'required',
        ]);
        $id = Crypt::decrypt($request->id);
        $edit = [
            'nama_pilihan' => $request->nama_pilihan,
            'nilai' => $request->nilai,
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
