<?php

namespace App\Http\Controllers;

use App\Submenu;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Crypt;

class SubmenuController extends Controller
{
    //
    public function index(Request $request, $menu_id)
    {
        if ($request->ajax()) {
            $menu_id = Crypt::decrypt($menu_id);
            $data = Submenu::whereMenu_id($menu_id)->orderBy('urutan', 'ASC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aktif', function ($row) {
                    $btn = '';
                    $id = Crypt::encrypt($row->id);
                    if ($row->aktif) {
                        $btn = "<button data-id='$id' class='btn btn-success aktif'>Aktif</button>";
                    } else {
                        $btn = "<button data-id='$id' class='btn btn-danger aktif'>Non Aktif</button>";
                    }
                    return $btn;
                })
                ->addcolumn('urutan', function ($row) {
                    $id = Crypt::encrypt($row->id);
                    $btn = "<button data-id='$id' class='m-1 btn btn-success up'><i class='cil-arrow-thick-top'></i></button>";
                    $btn .= "<button data-id='$id' class='m-1 btn btn-danger down'><i class='cil-arrow-thick-bottom'></i></button>";
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $id = Crypt::encrypt($row->id);
                    $btn = "<button data-id='$id' data-title='$row->title' data-icon='$row->icon' data-link='$row->link' class='m-1 btn btn-warning edit'><i class='cil-pencil'></i></button> ";
                    $btn .= "<button data-id='$id' class='m-1 btn btn-danger hapus'><i class='cil-trash'></i></button>";
                    return $btn;
                })
                ->rawColumns(['aktif', 'urutan', 'action'])
                ->make(true);
        }

        $data = [
            'url' => url('admin/menu/submenu/aksi') . '/' . $menu_id,
            'menu_id' => $menu_id
        ];
        return view('admin.submenu', $data);
    }

    public function aktif(Request $request, $menu_id)
    {
        $id = $request->post('id');
        $id = Crypt::decrypt($id);
        $aktif = Submenu::select('aktif')->whereId($id)->first();
        $data = [];
        if ($aktif->aktif == '1') {
            Submenu::whereId($id)->update([
                'aktif' => '0'
            ]);
            $data = [
                'status' => false,
                'pesan' => 'Submenu dinonaktifkan'
            ];
        } else {
            Submenu::whereId($id)->update([
                'aktif' => '1'
            ]);
            $data = [
                'status' => true,
                'pesan' => 'Submenu diaktifkan'
            ];
        }
        return response()->json($data);
    }

    public function up(Request $request, $menu_id)
    {
        $id = $request->post('id');
        $menu_id = Crypt::decrypt($menu_id);
        $id = Crypt::decrypt($id);
        $up = Submenu::select('id', 'urutan')->whereMenu_id($menu_id)->whereId($id)->first();
        $data = [];
        if (($up->urutan - 1) > 0) {
            $down = Submenu::select('id', 'urutan')->whereMenu_id($menu_id)->whereUrutan(($up->urutan - 1))->first();
            if ($down) {
                $up = Submenu::whereId($up->id)->update([
                    'urutan' => ($up->urutan - 1)
                ]);
                $down = Submenu::whereId($down->id)->update([
                    'urutan' => ($down->urutan + 1)
                ]);
            } else {
                if (($up->urutan - 1) > 0) {
                    $up = Submenu::whereId($up->id)->update([
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
                'pesan' => 'Submenu di urutan teratas data gagal diubah'
            ];
        }
        return response()->json($data);
    }

    public function down(Request $request, $menu_id)
    {
        $id = $request->post('id');
        $menu_id = Crypt::decrypt($menu_id);
        $id = Crypt::decrypt($id);
        $all = Submenu::get()->count();
        $down = Submenu::select('id', 'urutan')->whereId($id)->whereMenu_id($menu_id)->first();
        $up = Submenu::select('id', 'urutan')->whereMenu_id($menu_id)->whereUrutan(($down->urutan + 1))->first();
        $data = [];
        if ($down->urutan < $all) {
            if ($up) {
                $down = Submenu::whereId($down->id)->update([
                    'urutan' => ($down->urutan + 1)
                ]);
                $up = Submenu::whereId($up->id)->update([
                    'urutan' => ($up->urutan - 1)
                ]);
            } else {
                if (($down->urutan + 1) < $all) {
                    $down = Submenu::whereId($down->id)->update([
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
                'pesan' => 'Submenu di urutan terbawah data gagal diubah'
            ];
        }
        return response()->json($data);
    }

    public function aksi(Request $request, $menu_id)
    {
        $aksi = $request->post('aksi');
        $data = [];
        if ($aksi == 'tambah') {
            $data = $this->tambah($request, $menu_id);
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

    private function tambah(Request $request, $menu_id)
    {
        $validated = $request->validate([
            'title' => 'required',
            'link' => 'required',
        ]);
        $menu_id = Crypt::decrypt($menu_id);
        $tambah = Submenu::create([
            'title' => $request->title,
            'link' => $request->link,
            'urutan' => ((Submenu::whereMenu_id($menu_id)->get()->count()) + 1),
            'menu_id' => $menu_id
        ]);
        $data = [];
        if ($tambah) {
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
            'link' => 'required',
        ]);
        $id = Crypt::decrypt($request->id);
        $edit = Submenu::whereId($id)->update([
            'title' => $request->title,
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
        $hapus = Submenu::whereId($id)->delete();
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
