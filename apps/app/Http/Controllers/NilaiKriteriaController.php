<?php

namespace App\Http\Controllers;

use App\Dosen;
use App\Kriteria;
use App\NilaiKriteria;
use App\RespondenKriteria;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NilaiKriteriaController extends Controller
{
    //
    public function index()
    {

        $kriteria = Kriteria::all();
        $responden = RespondenKriteria::all();
        $nilai = [];
        foreach ($responden as $a) {
            $tes = [];
            foreach ($kriteria as $b) {
                $cek_nilai = NilaiKriteria::whereId_responden_kriteria($a->id)->whereId_kriteria($b->id)->first();
                $tes[] = $cek_nilai->nilai ?? 0;
            }
            $nilai[] = $tes;
        }
        $data = [
            'kriteria' => Kriteria::all(),
            'responden' => RespondenKriteria::all(),
            'nilai' => $nilai
        ];
        return view("admin.spk.nilaikriteria", $data);
    }
    public function form_tambah()
    {
        $data = [
            'kriteria' => Kriteria::all(),
            'dosen' => Dosen::all(),
        ];
        return view('admin.spk.form_tambah', $data);
    }
    public function simpan(Request $request)
    {
        $label = $request->label;
        $responden = $request->responden;
        $nilai = $request->nilai;
        $id_kriteria = $request->id_kriteria;
        if (array_sum($nilai) > 100) {
            return redirect()->back()
                ->with(['error' => "Total nilai lebih besar dari 100"])
                ->withInput(['label' => $request->label, 'responden' => $responden]);
        } else if (array_sum($nilai) < 100) {
            return redirect()->back()
                ->with(['error' => "Total nilai lebih kecil dari 100"])
                ->withInput(['label' => $request->label, 'responden' => $responden]);;
        } else if (array_sum($nilai) == 100) {
            DB::beginTransaction();
            try {
                $id_responden = RespondenKriteria::create([
                    'label' => $label,
                    'nama_responden' => $responden
                ]);
                $data = [];
                for ($i = 0; $i < count($nilai); $i++) {
                    $data[] = [
                        'id_responden_kriteria' => $id_responden->id,
                        'id_kriteria' => $id_kriteria[$i],
                        'nilai' => $nilai[$i]
                    ];
                }
                NilaiKriteria::insert($data);
                DB::commit();
                return redirect(route('nilaikriteria'))->with(['success' => 'Data Berhasil ditambah']);
            } catch (Exception $e) {
                return redirect()->back()->with(['error' => 'Data Gagal ditambah']);
            }
        }
    }
    public function hapus(Request $request)
    {
        $id = $request->id;
        try {
            RespondenKriteria::where('id', $id)->delete();
            return redirect()->back()->with(['success' => 'Data Berhasil Dihapus']);
        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->with(['error' => 'Data Gagal Dihapus']);
        }
    }
}
