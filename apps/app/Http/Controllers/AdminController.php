<?php

namespace App\Http\Controllers;

use App\Akses;
use App\Dosen;
use App\Helpers\Aras;
use App\Jadwal;
use App\Mahasiswa;
use App\RespondenKuisioner;
use App\Semester;
use App\Submenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (Auth::user()->role_id != 2) {
            return $this->admin();
        } else {
            return $this->mhs();
        }

        return view('admin.dashboard');
    }
    private function admin()
    {
        $aras = new Aras();
        // $juara = $aras->series_chart();
        // dd($juara);
        $data = [
            'mengisi' => Mahasiswa::select('mhs.nim')
                ->join('responden_kuisioner', 'mhs.nim', '=', 'responden_kuisioner.nim')
                ->get()
                ->count(),
            'tdk_mengisi' => Mahasiswa::select('mhs.nim')
                ->join('responden_kuisioner', 'mhs.nim', '!=', 'responden_kuisioner.nim')
                ->get()
                ->count(),
            'juara' => $aras->juara(),
            'pie' => $aras->pie_chart(),
            'series' => $aras->series_chart(),
            'semester' => Semester::first()
        ];
        return view('admin.dashboard', $data);
    }
    private function mhs()
    {
        $terisi = RespondenKuisioner::select('nim')
            ->where('nim', Auth::user()->username)
            ->get()
            ->count();
        $belum = Jadwal::where('jadwal.nim', Auth::user()->username)
            ->get()
            ->count();
        $data = [
            'mengisi' => $terisi,
            'tdk_mengisi' => $belum - $terisi
        ];
        return view('admin.mhs', $data);
    }
    public function print(Request $request)
    {
        $aras = new Aras();
        $nidn = $request->nidn;
        $nilai = $aras->print($nidn);
        $nilai_grade = $aras->grade_dosen($nidn);
        $grade = $aras->grade($nilai_grade);
        $predikat = $this->predikat($grade);
        $responden = RespondenKuisioner::select('komentar')->whereNidn($nidn)->get();
        $dosen = Dosen::whereNidn($nidn)->first();
        $semester = Semester::first();
        $data = [
            'dosen' => $dosen,
            'responden' => $responden->count(),
            'semester' => $semester,
            'komentar' => $responden,
            'nilai' => $nilai,
            'nilai_grade' => $nilai_grade,
            'grade' => $grade,
            'predikat' => $predikat,
        ];
        return view('admin.print', $data);
    }
    private function predikat($grade)
    {
        $predikat = '';
        if ($grade == 'A') {
            $predikat = "Sangat Baik";
        } else if ($grade == 'B') {
            $predikat = "Baik";
        } else if ($grade == 'C') {
            $predikat = "Cukup Baik";
        } else if ($grade == 'D') {
            $predikat = "Kurang Baik";
        } else if ($grade == 'E') {
            $predikat = "Tidak Baik";
        }
        return $predikat;
    }
}
