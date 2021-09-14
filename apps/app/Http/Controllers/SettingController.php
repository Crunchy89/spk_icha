<?php

namespace App\Http\Controllers;

use App\Dosen;
use App\Jadwal;
use App\Mahasiswa;
use App\Matkul;
use App\Semester;
use App\Setting;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class SettingController extends Controller
{
    //
    public function index(Request $request)
    {
        $data = [
            'setting' => Setting::first()
        ];
        return view('admin.setting', $data);
    }
    public function simpan(Request $request)
    {
        $validated = $request->validate([
            'nama_aplikasi' => 'required',
            'link_api' => 'required',
        ]);
        Setting::whereId(1)->update([
            'nama_aplikasi' => $request->nama_aplikasi,
            'link_api' => $request->link_api,
        ]);
        return redirect()->back();
    }
    public function sinkronisasi()
    {
        $mhs = $this->mhs();
        $dosen = $this->dosen();
        $mk = $this->mk();
        $jadwal = $this->jadwal();
        $semester = $this->semester();

        if ($mhs && $dosen && $mk && $jadwal && $semester) {
            return redirect()->back()->with(['berhasil' => "Sinkronisasi Berhasil"]);
        } else {
            return redirect()->back()->with(['gagal' => "Sinkronisasi Gagal"]);
        }
    }
    private function mhs()
    {
        $setting = Setting::first();
        $url = $setting->link_api . "mhs";
        $response = Http::get($url);
        DB::beginTransaction();
        try {
            if ($response->successful()) {
                $response = json_decode($response->body());
                $mhs = [];
                $user = [];
                foreach ($response->data as $row) {
                    $mhs[] = [
                        'nim' => $row->nipd,
                        'nama_mhs' => $row->nm_pd,
                        'jurusan' => $row->nama_jurusan,
                    ];
                    $user[] = [
                        'username' => $row->nipd,
                        'password' => Hash::make($row->nipd),
                        'role_id' => '2'
                    ];
                }
                DB::table('user')->whereRole_id(2)->delete();
                DB::table('mhs')->delete();
                User::insert($user);
                Mahasiswa::insert($mhs);
            }
            DB::commit();
            return 1;
        } catch (Exception $e) {
            DB::rollback();
            return 0;
        }
    }
    private function dosen()
    {
        $setting = Setting::first();
        $url = $setting->link_api . "dosen";
        $response = Http::get($url);
        DB::beginTransaction();
        try {
            if ($response->successful()) {
                $response = json_decode($response->body());
                $dosen = [];
                foreach ($response->data as $row) {
                    $dosen[] = [
                        'nidn' => $row->nidn,
                        'nama_dosen' => $row->nama_dosen,
                    ];
                }
                DB::table('dosen')->delete();
                Dosen::insert($dosen);
            }
            DB::commit();
            return 1;
        } catch (Exception $e) {
            DB::rollback();
            return 0;
        }
    }
    private function mk()
    {
        $setting = Setting::first();
        $url = $setting->link_api . "mk";
        $response = Http::get($url);
        DB::beginTransaction();
        try {
            if ($response->successful()) {
                $response = json_decode($response->body());
                $mk = [];
                foreach ($response->data as $row) {
                    $mk[] = [
                        'kode_mk' => $row->kode_mk,
                        'nama_mk' => $row->nama_mk,
                    ];
                }
                DB::table('mata_kuliah')->delete();
                Matkul::insert($mk);
            }
            DB::commit();
            return 1;
        } catch (Exception $e) {
            DB::rollback();
            return 0;
        }
    }
    private function jadwal()
    {
        $setting = Setting::first();
        $url = $setting->link_api . "jadwal";
        $response = Http::get($url);
        DB::beginTransaction();
        try {
            if ($response->successful()) {
                $response = json_decode($response->body());
                $jadwal = [];
                foreach ($response->data as $row) {
                    $jadwal[] = [
                        'kode_mk' => $row->kode_mk,
                        'nidn' => $row->nidn,
                        'nim' => $row->nim,
                    ];
                }
                DB::table('jadwal')->delete();
                Jadwal::insert($jadwal);
            }
            DB::commit();
            return 1;
        } catch (Exception $e) {
            DB::rollback();
            return 0;
        }
    }
    private function semester()
    {
        $setting = Setting::first();
        $url = $setting->link_api . "semester";
        $response = Http::get($url);
        DB::beginTransaction();
        $data = [];
        try {
            if ($response->successful()) {
                $response = json_decode($response->body());
                $data = [
                    'semester' => $response->data->semester,
                    'nama_semester' => $response->data->nama_semester
                ];
            }
            Semester::find(1)->update($data);
            DB::commit();
            return 1;
        } catch (Exception $e) {
            DB::rollback();
            return 0;
        }
    }
}
