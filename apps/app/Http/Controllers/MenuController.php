<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Menu;
use App\Akses;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Crypt;

class MenuController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Menu::orderBy('urutan', 'ASC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aktif', function ($row) {
                    $btn = '';
                    $enId = Crypt::encrypt($row->id);
                    if ($row->aktif) {
                        $btn = "<button data-id='$enId' class='btn btn-success aktif'>Aktif</button>";
                    } else {
                        $btn = "<button data-id='$enId' class='btn btn-danger aktif'>Non Aktif</button>";
                    }
                    return $btn;
                })
                ->addcolumn('urutan', function ($row) {
                    $enId = Crypt::encrypt($row->id);
                    $btn = "<button data-id='$enId' class='m-1 btn btn-success up'><i class='cil-arrow-thick-top'></i></button>";
                    $btn .= "<button data-id='$enId' class='m-1 btn btn-danger down'><i class='cil-arrow-thick-bottom'></i></button>";
                    return $btn;
                })
                ->addcolumn('submenu', function ($row) {
                    $enId = Crypt::encrypt($row->id);
                    $btn = "<a href='" . url('/admin/menu') . "/submenu/$enId' class='btn btn-info down'>Submenu</a>";
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $enId = Crypt::encrypt($row->id);
                    $btn = "<button data-id='$enId' data-title='$row->title' data-icon='$row->icon' data-link='$row->link' class='m-1 btn btn-warning edit'><i class='cil-pencil'></i></button> ";
                    $btn .= "<button data-id='$enId' class='m-1 btn btn-danger hapus'><i class='cil-trash'></i></button>";
                    return $btn;
                })
                ->rawColumns(['aktif', 'urutan', 'submenu', 'action'])
                ->make(true);
        }

        $data = [
            'url' => url('admin/menu/aksi')
        ];
        return view('admin.menu', $data);
    }

    public function aktif(Request $request)
    {
        $id = $request->post('id');
        $id = Crypt::decrypt($id);
        $aktif = Menu::select('aktif')->whereId($id)->first();
        $data = [];
        if ($aktif->aktif == '1') {
            Menu::whereId($id)->update([
                'aktif' => '0'
            ]);
            $data = [
                'status' => false,
                'pesan' => 'Menu dinonaktifkan'
            ];
        } else {
            Menu::whereId($id)->update([
                'aktif' => '1'
            ]);
            $data = [
                'status' => true,
                'pesan' => 'Menu diaktifkan'
            ];
        }
        return response()->json($data);
    }

    public function up(Request $request)
    {
        $id = $request->post('id');
        $id = Crypt::decrypt($id);
        $up = Menu::select('id', 'urutan')->whereId($id)->first();
        $data = [];
        if (($up->urutan - 1) > 0) {
            $down = Menu::select('id', 'urutan')->whereUrutan(($up->urutan - 1))->first();
            if ($down) {
                $up = Menu::whereId($up->id)->update([
                    'urutan' => ($up->urutan - 1)
                ]);
                $down = Menu::whereId($down->id)->update([
                    'urutan' => ($down->urutan + 1)
                ]);
            } else {
                if (($up->urutan - 1) > 0) {
                    $up = Menu::whereId($up->id)->update([
                        'urutan' => ($up->urutan - 1)
                    ]);
                }
            }
            $data = [
                'status' => true,
                'pesan' => 'data berhasil diubah'
            ];
        } else {
            $data = [
                'status' => false,
                'pesan' => 'Menu di urutan teratas data gagal diubah'
            ];
        }
        return response()->json($data);
    }

    public function down(Request $request)
    {
        $id = $request->post('id');
        $id = Crypt::decrypt($id);
        $all = Menu::get()->count();
        $down = Menu::select('id', 'urutan')->whereId($id)->first();
        $up = Menu::select('id', 'urutan')->whereUrutan(($down->urutan + 1))->first();
        $data = [];
        if ($down->urutan < $all) {
            if ($up) {
                $down = Menu::whereId($down->id)->update([
                    'urutan' => ($down->urutan + 1)
                ]);
                $up = Menu::whereId($up->id)->update([
                    'urutan' => ($up->urutan - 1)
                ]);
            } else {
                if (($down->urutan + 1) < $all) {
                    $down = Menu::whereId($down->id)->update([
                        'urutan' => ($down->urutan + 1)
                    ]);
                }
            }
            $data = [
                'status' => true,
                'pesan' => 'data berhasil diubah'
            ];
        } else {
            $data = [
                'status' => false,
                'pesan' => 'Menu di urutan terbawah data gagal diubah'
            ];
        }
        return response()->json($data);
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
            'title' => 'required',
            'icon' => 'required',
            'link' => 'required',
        ]);
        $check = false;
        try {
            DB::transaction(function () use ($request) {
                $tambah = Menu::create([
                    'title' => $request->title,
                    'icon' => $request->icon,
                    'link' => $request->link,
                    'urutan' => ((Menu::get()->count()) + 1)
                ]);
                Akses::create([
                    'menu_id' => $tambah->id,
                    'role_id' => 1
                ]);
            });
            $check = true;
        } catch (Exception $e) {
            $check = false;
        }
        $data = [];
        if ($check) {
            $data = [
                'status' => true,
                'pesan' => 'Data berhasil ditambah'
            ];
        } else {
            $data = [
                'status' => false,
                'pesan' => 'Data gagal ditambah'
            ];
        }
        return $data;
    }
    private function edit(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'title' => 'required',
            'icon' => 'required',
            'link' => 'required',
        ]);
        $id = Crypt::decrypt($request->id);
        $edit = Menu::whereId($id)->update([
            'title' => $request->title,
            'icon' => $request->icon,
            'link' => $request->link,
        ]);
        $data = [];
        if ($edit) {
            $data = [
                'status' => true,
                'pesan' => 'Data berhasil diubah'
            ];
        } else {
            $data = [
                'status' => false,
                'pesan' => 'Data gagal diubah'
            ];
        }
        return $data;
    }
    private function hapus(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
        ]);
        $id = Crypt::decrypt($request->id);
        $hapus = Menu::whereId($id)->delete();
        $data = [];
        if ($hapus) {
            $data = [
                'status' => true,
                'pesan' => 'Data berhasil dihapus'
            ];
        } else {
            $data = [
                'status' => false,
                'pesan' => 'Data gagal dihapus'
            ];
        }
        return $data;
    }
}
