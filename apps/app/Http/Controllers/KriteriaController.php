<?php

namespace App\Http\Controllers;

use App\Helpers\Crud;
use App\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class KriteriaController extends Controller
{
    //
    public function __construct()
    {
        $this->helper = new Crud('kriteria', 'id');
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Kriteria::orderBy('id', 'ASC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $enId = Crypt::encrypt($row->id);
                    $btn = "<button data-id='$enId' data-label='$row->label' data-kriteria='$row->kriteria'class='m-1 btn btn-warning edit'><i class='cil-pencil'></i></button> ";
                    $btn .= "<button data-id='$enId' class='m-1 btn btn-danger hapus'><i class='cil-trash'></i></button>";
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $data = [
            'url' => route('kriteria.aksi'),
            'datatable' => route('kriteria')
        ];
        return view('admin.spk.kriteria', $data);
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
            'label' => 'required',
            'kriteria' => 'required',
        ]);
        $tambah = [
            'label' => $request->label,
            'kriteria' => $request->kriteria,
        ];
        $data = $this->helper->tambah($tambah);
        return $data;
    }
    private function edit(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'label' => 'required',
            'kriteria' => 'required',
        ]);
        $id = Crypt::decrypt($request->id);
        $edit = [
            'label' => $request->label,
            'kriteria' => $request->kriteria,
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
