<?php

namespace App\Helpers;

use App\Kriteria;
use App\NilaiKriteria;
use App\Dosen;
use App\NilaiKuisioner;
use App\RespondenKuisioner;

class Aras
{
    public function __construct()
    {
        $this->kriteria = Kriteria::all();
        $this->dosen = Dosen::select('dosen.nidn', 'dosen.nama_dosen')
            ->join('responden_kuisioner', 'dosen.nidn', '=', 'responden_kuisioner.nidn')
            ->groupBy('dosen.nidn')
            ->groupBy('dosen.nama_dosen')
            ->orderBy('dosen.nama_dosen', 'ASC')
            ->get();
    }
    public function kriteria()
    {
        return $this->kriteria;
    }
    public function rata_rataKriteria()
    {
        $kriteria = $this->kriteria;
        $rata = [];
        foreach ($kriteria as $a) {
            $cek = NilaiKriteria::selectRaw('AVG(nilai) as rata')->where('id_kriteria', $a->id)->first();
            $rata[] = $cek->rata ?? 0;
        }
        return $rata;
    }
    public function sum_rataKriteria()
    {
        $kriteria = $this->kriteria;
        $sum = [];
        foreach ($kriteria as $a) {
            $cek = NilaiKriteria::selectRaw('SUM(nilai) as sum')->where('id_kriteria', $a->id)->first();
            $sum[] = $cek->sum ?? 0;
        }
        return $sum;
    }
    public function nilai_alternatif()
    {
        $dosen = $this->dosen;
        $kriteria = $this->kriteria();
        $nilai = [];
        $nil = 1;
        foreach ($dosen as $a) {
            $aa = [];
            $responden = RespondenKuisioner::whereNidn($a->nidn)->get();
            for ($j = -1; $j < count($responden); $j++) {
                $bb = [];
                if ($j == -1) {
                    $bb = ['A' . $nil];
                    $bb[] = $a->nama_dosen;
                    foreach ($kriteria as $c) {
                        $bb[] = "";
                    }
                    $bb[] = "";
                } else {
                    $bb = [""];
                    $bb[] = 'R' . ($j + 1);
                    $tes = NilaiKuisioner::select('nilai_kuisioner.nilai')
                        ->join('responden_kuisioner', 'nilai_kuisioner.id_responden_kuisioner', '=', 'responden_kuisioner.id')
                        ->where('responden_kuisioner.nidn', $a->nidn)
                        ->where('responden_kuisioner.nim', $responden[$j]->nim)
                        ->get();
                    $avg = [];
                    $sum = [];
                    for ($i = 0; $i < count($kriteria); $i++) {
                        if (count($responden) > 1) {
                            $sum[] = $tes[$i]->nilai ?? 0;
                        } else {
                            $avg[] = $tes[$i]->nilai ?? 0;
                        }
                        $bb[] = $tes[$i]->nilai ?? 0;
                    }
                    if (count($responden) > 1) {
                        $bb[] = round(array_sum($sum));
                    } else {
                        $bb[] = array_sum($avg) / count($avg);
                    }
                }
                $aa[] = $bb;
                if (count($responden) > 1 && $j == (count($responden) - 1)) {
                    $bb = [""];
                    $bb[] = "Rata-rata";
                    $avg = [];
                    foreach ($kriteria as $c) {
                        $rata = NilaiKuisioner::selectRaw('AVG(nilai_kuisioner.nilai) as rata')
                            ->join('responden_kuisioner', 'nilai_kuisioner.id_responden_kuisioner', '=', 'responden_kuisioner.id')
                            ->where('responden_kuisioner.nidn', $a->nidn)
                            ->where('nilai_kuisioner.id_kriteria', $c->id)
                            ->first();
                        $bb[] = round($rata->rata, 3) ?? 0;
                        $avg[] = $rata->rata ?? 0;
                    }
                    $bb[] = (array_sum($avg) / count($avg));
                    $aa[] = $bb;
                }
            }
            $nilai[] = $aa;
            $nil++;
        }
        return $nilai;
    }
    public function dosen()
    {
        $dosen = $this->dosen;
        return $dosen;
    }
    public function kecocokan()
    {
        $dosen = $this->dosen;
        $kriteria = $this->kriteria();
        $nilai = [];
        foreach ($dosen as $a) {
            $aa = [];
            foreach ($kriteria as $b) {
                $rata = NilaiKuisioner::selectRaw('AVG(nilai_kuisioner.nilai) as rata')
                    ->join('responden_kuisioner', 'nilai_kuisioner.id_responden_kuisioner', '=', 'responden_kuisioner.id')
                    ->where('responden_kuisioner.nidn', $a->nidn)
                    ->where('nilai_kuisioner.id_kriteria', $b->id)
                    ->first();
                $aa[] = round($rata->rata, 3) ?? 0;
            }
            $nilai[] = $aa;
        }
        $max = [];
        for ($i = 0; $i < count($nilai[0]); $i++) {
            $tes = [];
            for ($j = 0; $j < count($nilai); $j++) {
                $tes[] = $nilai[$j][$i];
            }
            $max[] = max($tes);
        }
        $cek = array_merge([$max], $nilai);
        return $cek;
    }
    public function jumlah_kecocokan()
    {
        $nilai = $this->kecocokan();
        $data = [];
        for ($i = 0; $i < count($nilai[0]); $i++) {
            $aa = [];
            for ($j = 0; $j < count($nilai); $j++) {
                $aa[] = $nilai[$j][$i];
            }
            $data[] = array_sum($aa) ?? 0;
        }
        return $data;
    }
    public function matrix_kecocokan()
    {
        $kecocokan = $this->kecocokan();
        $jumlah = $this->jumlah_kecocokan();
        $data = [];
        foreach ($kecocokan as $a) {
            $tes = [];
            for ($i = 0; $i < count($a); $i++) {
                $tes[] = round($a[$i] / $jumlah[$i], 9) ?? 0;
            }
            $data[] = $tes;
        }
        return $data;
    }
    public function bobot()
    {
        $rata = $this->rata_rataKriteria();
        $total = array_sum($rata);
        $bobot = [];
        foreach ($rata as $a) {
            $bobot[] = (round($a) / $total) ?? 0;
        }
        return $bobot;
    }
    public function hasil_matrix_keputusan()
    {
        $matrix = $this->matrix_kecocokan();
        $bobot = $this->bobot();
        $data = [];
        foreach ($matrix as $a) {
            $tes = [];
            for ($i = 0; $i < count($a); $i++) {
                $tes[] = round($a[$i] * round($bobot[$i], 2), 3) ?? 0;
            }
            $data[] = $tes;
        }
        return $data;
    }
    public function s()
    {
        $hasil = $this->hasil_matrix_keputusan();
        $data = [];
        foreach ($hasil as $a) {
            $sum = [];
            foreach ($a as $b) {
                $sum[] = $b ?? 0;
            }
            $data[] = round(array_sum($sum), 3) ?? 0;
        }
        return $data;
    }
    public function s_max()
    {
        $hasil = $this->hasil_matrix_keputusan();
        $data = [];
        foreach ($hasil as $i => $a) {
            if ($i == 0) {
                $sum = [];
                foreach ($a as $b) {
                    $sum[] = $b ?? 0;
                }
                $data = round(array_sum($sum), 3) ?? 0;
            }
        }
        return $data;
    }
    public function k()
    {
        $hasil = $this->hasil_matrix_keputusan();
        $max = $this->s_max();
        $data = [];
        foreach ($hasil as $i => $a) {
            $sum = [];
            if ($i == 0) {
                $data[] = "";
            } else {
                foreach ($a as $b) {
                    $sum[] = $b ?? 0;
                }
                $data[] = round(array_sum($sum) / $max, 4) ?? 0;
            }
        }
        return $data;
    }
    public function grade($data)
    {
        $grade = '';
        if ($data >= 80) {
            $grade = "A";
        } else if ($data >= 60) {
            $grade = "B";
        } else if ($data >= 40) {
            $grade = "C";
        } else if ($data >= 20) {
            $grade = "D";
        } else {
            $grade = "E";
        }
        return $grade;
    }
    public function nilai()
    {
        $k = $this->k();
        $data = [];
        foreach ($k as $i => $a) {
            if ($i == 0) {
                $data[] = "";
            } else {
                $cek = $a * 100;
                $data[] = $this->grade($cek);
            }
        }
        return $data;
    }
    public function perkalian_matrix_bobot()
    {
        $hasil = $this->hasil_matrix_keputusan();
        $s = $this->s();
        $k = $this->k();
        $nil = $this->nilai();
        $data = [];
        foreach ($hasil as $i => $a) {
            $nilai = ['S' . ($i) . '='];
            foreach ($a as $b) {
                $nilai[] = round($b, 3) ?? 0;
            }
            $nilai[] = $s[$i] ?? 0;
            $nilai[] = $k[$i] ?? 0;
            $nilai[] = $nil[$i] ?? "";
            $data[] = $nilai;
        }
        return $data;
    }
    public function sort_perkalian($data)
    {
        $key = count($data[0]) - 1;
        foreach ($data as $i => $a) {
            foreach ($data as $j => $b) {
                if ($a[$key] > $b[$key]) {
                    list($data[$i], $data[$j]) = array($data[$j], $data[$i]);
                }
            }
        }
        return $data;
    }
    public function nilai_masing2()
    {
        $dosen = $this->dosen();
        $hasil = $this->matrix_kecocokan();
        $s = $this->s();
        $k = $this->k();
        $nil = $this->nilai();
        $data = [];

        foreach ($hasil as $i => $a) {
            $temp = ['A' . ($i)];
            if ($i != 0) {
                $temp[] = $dosen[$i - 1]->nama_dosen ?? "";
            } else {
                $temp[] = "";
            }
            foreach ($a as $b) {
                $temp[] = round($b, 3) ?? 0;
            }
            $temp[] = $s[$i] ?? 0;
            $temp[] = $k[$i] ?? "";
            $temp[] = $nil[$i] ?? "";
            $data[] = $temp;
        }
        return $data;
    }
    public function juara()
    {
        $k = $this->k();
        $nil = $this->nilai();
        $dosen = $this->dosen;
        $data = [];
        $i = 1;
        foreach ($dosen as $a) {
            $tes = [];
            $responden = RespondenKuisioner::selectRaw("count(id) as jumlah")->whereNidn($a->nidn)->first();
            $tes[] = $a->nama_dosen;
            $tes[] = $a->nidn;
            $tes[] = $responden->jumlah;
            $tes[] = round($k[$i] * 100, 3) ?? 0;
            $tes[] = $nil[$i] ?? "";
            $data[] = $tes;
            $i++;
        }
        return $data;
    }
    public function pie_chart()
    {
        $dosen = $this->dosen;
        $data = [];
        foreach ($dosen as $a) {
            $tes = [];
            $cek = RespondenKuisioner::select('nidn')->whereNidn($a->nidn)->get()->count();
            $tes['name'] = $a->nama_dosen;
            $tes['y'] = $cek;
            $tes['drilldown'] = $a->nama_dosen;
            $data[] = $tes;
        }
        return $data;
    }
    public function series_chart()
    {
        $dosen = $this->dosen;
        $data = [];
        foreach ($dosen as $a) {
            $tes = [];
            $tes['name'] = $a->nama_dosen;
            $tes['id'] = $a->nama_dosen;
            $get = RespondenKuisioner::selectRAW('mhs.jurusan, count(mhs.jurusan) as jumlah')->join('mhs', 'responden_kuisioner.nim', '=', 'mhs.nim')->where('responden_kuisioner.nidn', $a->nidn)->groupBy('mhs.jurusan')->get();
            $tes3 = [];
            foreach ($get as $b) {
                $tes2 = [];
                $tes2[] = $b->jurusan;
                $tes2[] = $b->jumlah;
                $tes3[] = $tes2;
            }
            $tes['data'] = $tes3;
            $data[] = $tes;
        }
        return $data;
    }
    public function print($nidn)
    {
        $hasil = $this->hasil_matrix_keputusan();
        $dosen = $this->dosen;
        $s = $this->s();
        $sMax = $this->s_max();
        $aa = [];
        $i = 1;
        foreach ($dosen as $a) {
            $tes = [];
            $tes[] = $a->nidn;
            $tes2 = [];
            foreach ($hasil[$i] as $j => $b) {
                $tes2[] = $b;
            }
            $tes2[] = $s[$i];
            $tes2[] = $sMax;
            $tes2[] = round(($s[$i] / $sMax) * 100, 3);
            $tes[] = $tes2;
            $aa[] = $tes;
            $i++;
        }
        $nilai_dosen = [];
        foreach ($aa as $i => $a) {
            if ($a[0] == $nidn) {
                $nilai_dosen = $a[1];
            }
        }
        $data = [];
        $kriteria = Kriteria::all();
        foreach ($nilai_dosen as $i => $a) {
            $tes = [];
            if (count($kriteria) == $i) {
                $tes[] = "Nilai";
                $tes[] = "S";
                $tes[] = $a;
            } else if (count($kriteria) + 1 == $i) {
                $tes[] = "";
                $tes[] = "S0";
                $tes[] = $a;
            } else if (count($kriteria) + 2 == $i) {
                $tes[] = "Nilai Total";
                $tes[] = "";
                $tes[] = $a;
            } else {
                $tes[] = $i + 1;
                $tes[] = $kriteria[$i]->kriteria;
                $tes[] = $a;
            }
            $data[] = $tes;
        }
        return $data;
    }
    public function grade_dosen($nidn)
    {
        $hasil = $this->hasil_matrix_keputusan();
        $dosen = $this->dosen;
        $s = $this->s();
        $sMax = $this->s_max();
        $aa = [];
        $i = 1;
        foreach ($dosen as $a) {
            $tes = [];
            $tes[] = $a->nidn;
            $tes2 = round(($s[$i] / $sMax) * 100, 3);
            $tes[] = $tes2;
            $aa[] = $tes;
            $i++;
        }
        $nilai_dosen = [];
        foreach ($aa as $i => $a) {
            if ($a[0] == $nidn) {
                $nilai_dosen = $a[1];
            }
        }
        return $nilai_dosen;
    }
}
