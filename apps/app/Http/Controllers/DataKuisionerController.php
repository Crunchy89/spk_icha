<?php

namespace App\Http\Controllers;

use App\Helpers\Crud;
use App\RespondenKriteria;
use App\RespondenKuisioner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class DataKuisionerController extends Controller
{
    //
    public function __construct()
    {
        $this->helper = new Crud('responden_kuisioner', 'id');
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = RespondenKuisioner::select('responden_kuisioner.*', 'mhs.nama_mhs', 'mhs.nim', 'dosen.nama_dosen', 'mata_kuliah.nama_mk')
                ->join('mhs', 'responden_kuisioner.nim', '=', 'mhs.nim')
                ->join('mata_kuliah', 'responden_kuisioner.kode_mk', '=', 'mata_kuliah.kode_mk')
                ->join('dosen', 'responden_kuisioner.nidn', '=', 'dosen.nidn')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $enId = Crypt::encrypt($row->id);
                    $btn = "<a href='" . route('data_kuisioner.detail', ['id' => $enId]) . "' class='m-1 btn btn-info'><i class='cil-list'></i> Nilai Kuisioner</a>";
                    $btn .= "<button data-id='$enId' class='m-1 btn btn-danger hapus'><i class='cil-trash'></i></button>";
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $data = [
            'url' => route('data_kuisioner.aksi'),
            'datatable' => route('data_kuisioner')
        ];
        return view('admin.data.kuisioner', $data);
    }

    public function detail(Request $request)
    {
        $id = Crypt::decrypt($request->id);
        $data = RespondenKuisioner::select('kriteria.kriteria', 'nilai_kuisioner.nilai')
            ->join('nilai_kuisioner', 'responden_kuisioner.id', '=', 'nilai_kuisioner.id_responden_kuisioner')
            ->join('kriteria', 'nilai_kuisioner.id_kriteria', '=', 'kriteria.id')
            ->where('responden_kuisioner.id', $id)
            ->get();
        $view = [
            'data' => $data
        ];
        return view('admin.data.detail_kuisioner', $view);
    }

    public function aksi(Request $request)
    {
        $aksi = $request->post('aksi');
        $data = [];
        if ($aksi == 'hapus') {
            $data = $this->hapus($request);
        } else {
            $data = [
                'status' => false,
                'pesan' => 'Tidak ada pilihan aksi'
            ];
        }
        return response()->json($data);
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
