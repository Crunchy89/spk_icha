<?php

namespace App\Http\Middleware;

use Closure;
use App\Akses as Permission;
use Illuminate\Support\Facades\Auth;

class Akses
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $segment = '';
        if ($request->segment(2)) {
            $segment = $request->segment(1) . '/' . $request->segment(2);
        } else {
            $segment = $request->segment(1);
        }
        $cek = Permission::select('akses.id')->join('user', 'user.role_id', '=', 'akses.role_id')->join('submenu', 'akses.menu_id', '=', 'submenu.menu_id')->where('user.role_id', Auth::user()->role_id)->where('submenu.link', $segment)->first();
        if ($cek) {
            return $next($request);
        } else {
            $cek = Permission::select('akses.id')->join('user', 'user.role_id', '=', 'akses.role_id')->join('menu', 'akses.menu_id', '=', 'menu.id')->where('user.role_id', Auth::user()->role_id)->where('menu.link', $segment)->first();
            if ($cek) {
                return $next($request);
            } else {
                return redirect('/admin');
            }
        }
    }
}
