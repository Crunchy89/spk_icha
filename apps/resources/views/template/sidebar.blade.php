@php
    $akses = \App\Akses::select('menu.*')->join('menu', 'akses.menu_id', '=', 'menu.id')->where('akses.role_id', Auth::user()->role_id)->where('menu.aktif', 1)->orderBy('menu.urutan', 'ASC')->get();
    $data = [];
        foreach ($akses as $a) {
            $data[] = [
                'title' => $a->title,
                'icon' => $a->icon,
                'link' => $a->link,
                'submenu' => \App\Submenu::whereMenu_id($a->id)->whereAktif(1)->orderBy('urutan', 'ASC')->get()
            ];
        }
    $active=Route::current()->uri;
@endphp
<ul class="c-sidebar-nav">
    @foreach ($data as $menu)
        @if (count($menu['submenu']) > 0)
        <li class="c-sidebar-nav-item c-sidebar-nav-dropdown">
            <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
                <div class="c-sidebar-nav-icon">
                    <i class="{{$menu['icon']}}"></i>
                </div> {{$menu['title']}}
            </a>
            <ul class="c-sidebar-nav-dropdown-items">
                @foreach ($menu['submenu'] as $submenu)
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link @if($active == $menu['link']) c-active @endif" href="{{url('/'.$submenu->link) }}">
                    <span class="c-sidebar-nav-icon"></span> {{$submenu->title}}</a>
                </li>
                @endforeach
            </ul>
        </li>
        @else
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link @if($active == $menu['link']) c-active @endif" href="{{ url('/'.$menu['link']) }}">
                <div class="c-sidebar-nav-icon">
                    <i class="{{$menu['icon']}}"></i>
                </div>
                {{$menu['title']}}
            </a>
        </li>
        @endif
    @endforeach
    <li class="c-sidebar-nav-item">
        <a class="c-sidebar-nav-link" href="{{ route('logout') }}">
            <div class="c-sidebar-nav-icon">
                <i class="cil-account-logout"></i>
            </div> Logout
        </a>
    </li>
</ul>
