<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jadwal;
use App\Dosen;
use App\Mahasiswa;
use App\Matkul;
use App\Helpers\Crud;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Database\Eloquent\Model;

class JadwalController extends Controller
{
    //
    public function __construct()
    {
        $this->helper = new Crud('jadwal', 'id');
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Jadwal::select('jadwal.*', 'mhs.nama_mhs', 'dosen.nama_dosen', 'mata_kuliah.nama_mk')
                ->join('mhs', 'jadwal.nim', '=', 'mhs.nim')
                ->join('dosen', 'jadwal.nidn', '=', 'dosen.nidn')
                ->join('mata_kuliah', 'jadwal.kode_mk', '=', 'mata_kuliah.kode_mk')
                ->orderBy('nim', 'ASC')
                ->orderBy('nidn', 'ASC')
                ->orderBy('kode_mk', 'ASC')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $enId = Crypt::encrypt($row->id);
                    $btn = "<button data-id='$enId' data-nidn='$row->nidn' data-nim='$row->nim' data-kode_mk='$row->kode_mk' class='m-1 btn btn-warning edit'><i class='cil-pencil'></i></button> ";
                    $btn .= "<button data-id='$enId' class='m-1 btn btn-danger hapus'><i class='cil-trash'></i></button>";
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $data = [
            'url' => route('jadwal.aksi'),
            'datatable' => route('jadwal'),
            'dosen' => Dosen::all(),
            'mhs' => Mahasiswa::all(),
            'mk' => Matkul::all(),
        ];
        return view('admin.data.jadwal', $data);
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
            'nim' => 'required',
            'kode_mk' => 'required',
        ]);
        $tambah = [
            'nidn' => $request->nidn,
            'kode_mk' => $request->kode_mk,
            'nim' => $request->nim,
        ];
        $data = $this->helper->tambah($tambah);
        return $data;
    }
    private function edit(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'nidn' => 'required',
            'nim' => 'required',
            'kode_mk' => 'required',
        ]);
        $id = Crypt::decrypt($request->id);
        $edit = [
            'nidn' => $request->nidn,
            'nim' => $request->nim,
            'kode_mk' => $request->kode_mk,
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
