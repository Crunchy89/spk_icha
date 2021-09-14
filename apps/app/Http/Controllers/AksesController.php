<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Akses;
use App\Role;
use App\Menu;
use Illuminate\Support\Facades\Crypt;


class AksesController extends Controller
{
    //
    public function index(Request $request)
    {
        $data = [
            'role' => Role::all()
        ];
        return view('admin.akses', $data);
    }
    public function getAkses()
    {
        $roles = Role::select('id')->get();
        $menus = Menu::select('id', 'title')->get();
        $data = [];
        foreach ($menus as $menu) {
            $tes = [];
            foreach ($roles as $role) {
                $cek = Akses::whereRole_id($role->id)->whereMenu_id($menu->id)->first();
                $menu_id = Crypt::encrypt($menu->id);
                $role_id = Crypt::encrypt($role->id);
                if ($cek) {
                    $tes[] = [
                        'value' => 1,
                        'menu_id' => $menu_id,
                        'role_id' => $role_id
                    ];
                } else {
                    $tes[] = [
                        'value' => 0,
                        'menu_id' => $menu_id,
                        'role_id' => $role_id
                    ];
                }
            }
            $data[] = [
                'title' => $menu->title,
                'data' => $tes
            ];
        }
        return response()->json($data);
    }
    public function check(Request $request)
    {
        $role_id = Crypt::decrypt($request->role_id);
        $menu_id = Crypt::decrypt($request->menu_id);
        $cek = Akses::whereMenu_id($menu_id)->whereRole_id($role_id)->first();
        $data = [];
        if ($cek) {
            Akses::whereId($cek->id)->delete();
            $data = [
                'status' => false,
                'pesan' => 'Akses dihapus'
            ];
        } else {
            Akses::create([
                'menu_id' => $menu_id,
                'role_id' => $role_id
            ]);
            $data = [
                'status' => true,
                'pesan' => 'Akses diberikan'
            ];
        }
        return response()->json($data);
    }
}
