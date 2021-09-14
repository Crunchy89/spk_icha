<?php

namespace App\Http\Controllers;

use App\Helpers\Aras;
use App\NilaiPilihan;
use App\RespondenKriteria;

class ArasController extends Controller
{
    //
    public function index()
    {
        $aras = new Aras();
        // $tes = $aras->nilai();
        // dd($tes);
        $data = [
            'kriteria' => $aras->kriteria(),
            'responden' => RespondenKriteria::all(),
            'sum' => $aras->sum_rataKriteria(),
            'rata' => $aras->rata_rataKriteria(),
            'nilai_kuisioner' => $aras->nilai_alternatif(),
            'dosen' => $aras->dosen(),
            'nilai_pilihan' => NilaiPilihan::orderBy('nilai', 'desc')->get(),
            'kecocokan' => $aras->kecocokan(),
            'jumlah_kecocokan' => $aras->jumlah_kecocokan(),
            'matrix_kecocokan' => $aras->matrix_kecocokan(),
            'hasil_matrix_keputusan' => $aras->hasil_matrix_keputusan(),
            'perkalian_matrix_bobot' => $aras->perkalian_matrix_bobot(),
            'nilai_masing2' => $aras->nilai_masing2(),
        ];
        return view('admin.aras.index', $data);
    }
}
