<?php

namespace App\Http\Controllers;

use App\Jadwal;
use App\Kriteria;
use App\Matkul;
use App\NilaiKuisioner;
use App\NilaiPilihan;
use App\RespondenKuisioner;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class KuisionerController extends Controller
{
    //
    public function index()
    {
        $jadwal = [];
        if (Auth::user()->role_id == 2) {
            $jadwal = Jadwal::select('dosen.nama_dosen', 'mata_kuliah.nama_mk', 'jadwal.*', 'mhs.jurusan')
                ->join('dosen', 'jadwal.nidn', '=', 'dosen.nidn')
                ->join('mata_kuliah', 'jadwal.kode_mk', '=', 'mata_kuliah.kode_mk')
                ->join('mhs', 'jadwal.nim', '=', 'mhs.nim')
                ->where('jadwal.nim', Auth::user()->username)
                ->groupBy('jadwal.id')
                ->groupBy('dosen.nama_dosen')
                ->groupBy('mhs.jurusan')
                ->get();
        } else {
            $jadwal = Jadwal::select('dosen.nama_dosen', 'mata_kuliah.nama_mk', 'jadwal.*', 'mhs.jurusan')
                ->join('dosen', 'jadwal.nidn', '=', 'dosen.nidn')
                ->join('mata_kuliah', 'jadwal.kode_mk', '=', 'mata_kuliah.kode_mk')
                ->join('mhs', 'jadwal.nim', '=', 'mhs.nim')
                ->groupBy('jadwal.id')
                ->groupBy('dosen.nama_dosen')
                ->groupBy('mhs.jurusan')
                ->get();
        }
        $data = [
            'jadwal' => $jadwal
        ];
        return view('admin.kuisioner.index', $data);
    }
    public function isi(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->id);
            $jadwal = Jadwal::whereId($id)->first();

            $data = [
                'mk' => Matkul::whereKode_mk($jadwal->kode_mk)->first(),
                'id' => $request->id,
                'kriteria' => Kriteria::all(),
                'nilai' => NilaiPilihan::all()
            ];
            return view('admin.kuisioner.isi', $data);
        } catch (DecryptException $e) {
            return redirect(route('kuisioner'));
        }
    }
    public function simpan(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = Crypt::decrypt($request->id);
            $jadwal = Jadwal::whereId($id)->first();
            $nilai = $request->nilai;
            $id_kriteria = $request->id_kriteria;
            $responden = [
                'nim' => $jadwal->nim,
                'kode_mk' => $jadwal->kode_mk,
                'nidn' => $jadwal->nidn,
                'komentar' => $request->komentar
            ];
            $id_responden = RespondenKuisioner::create($responden);
            $insert_batch = [];
            for ($i = 0; $i < count($nilai); $i++) {
                $insert_batch[] = [
                    'id_responden_kuisioner' => $id_responden->id,
                    'nilai' => $nilai[$i],
                    'id_kriteria' => $id_kriteria[$i]
                ];
            }
            NilaiKuisioner::insert($insert_batch);
            DB::commit();
            return redirect(route('kuisioner'))->with(['success' => 'Kuisioner Berhasil Disimpan']);
        } catch (DecryptException $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'Kuisioner Gagal Disimpan']);
        }
    }
}
