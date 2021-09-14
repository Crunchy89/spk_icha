<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::select('user.*', 'role.role')->join('role', 'user.role_id', '=', 'role.id')->get();
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
                ->addcolumn('reset', function ($row) {
                    $id = Crypt::encrypt($row->id);
                    $btn = "<button data-id='$id' class='btn btn-info reset'>Reset Password</button>";
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $id = Crypt::encrypt($row->id);
                    $btn = "<button data-id='$id' data-username='$row->username' data-role_id='$row->role_id' class='m-1 btn btn-warning edit'><i class='cil-pencil'></i></button> ";
                    $btn .= "<button data-id='$id' class='m-1 btn btn-danger hapus'><i class='cil-trash'></i></button>";
                    return $btn;
                })
                ->rawColumns(['aktif', 'reset', 'action'])
                ->make(true);
        }

        $data = [
            'url' => url('admin/user/aksi'),
            'role' => Role::all()
        ];
        return view('admin.user', $data);
    }

    public function aktif(Request $request)
    {
        $id = Crypt::decrypt($request->id);
        $aktif = User::select('aktif')->whereId($id)->first();
        $data = [];
        if ($aktif->aktif == '1') {
            User::whereId($id)->update([
                'aktif' => '0'
            ]);
            $data = [
                'status' => false,
                'pesan' => 'Akun di nonaktifkan'
            ];
        } else {
            User::whereId($id)->update([
                'aktif' => '1'
            ]);
            $data = [
                'status' => true,
                'pesan' => 'Akun di aktifkan'
            ];
        }
        return response()->json($data);
    }

    public function aksi(Request $request)
    {
        $aksi = $request->aksi;
        $data = [];
        if ($aksi == 'tambah') {
            $data = $this->tambah($request);
        } elseif ($aksi == 'edit') {
            $data = $this->edit($request);
        } elseif ($aksi == 'hapus') {
            $data = $this->hapus($request);
        } elseif ($aksi == 'reset') {
            $data = $this->reset($request);
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
            'username' => 'required',
            'password' => 'required',
            'role' => 'required',
        ]);
        $cek = User::whereUsername($request->username)->first();
        $data = [];
        if (!$cek) {
            $tambah = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role_id' => $request->role,
            ]);
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
        } else {
            $data = [
                'status' => false,
                'pesan' => 'Username sudah digunakan'
            ];
        }
        return $data;
    }
    private function edit(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'username' => 'required',
            'role' => 'required',
        ]);
        $id = Crypt::decrypt($request->id);
        $edit = User::whereId($id)->update([
            'username' => $request->username,
            'role_id' => $request->role,
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
        $hapus = User::whereId($id)->delete();
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
    private function reset(Request $request)
    {
        $validated = $request->validate([
            'password' => 'required',
        ]);
        $id = Crypt::decrypt($request->id);
        $save = User::whereId($id)->update([
            'password' => Hash::make($request->password),
        ]);
        $data = [];
        if ($save) {
            $data = [
                'status' => true,
                'pesan' => 'Password berhasil diubah'
            ];
        } else {
            $data = [
                'status' => false,
                'pesan' => 'Password gagal diubah'
            ];
        }
        return $data;
    }
}
