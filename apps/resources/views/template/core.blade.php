@extends('template.admin')
@section('body')
<div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
    <div class="c-sidebar-brand d-lg-down-none">
        <div class="c-sidebar-brand-full p-1" alt="CoreUI Logo">
            <img src="{{ asset('assets/img/loteng.png') }}" height="50px" alt="logo">
        </div>
        <div class="c-sidebar-brand-minimized" alt="CoreUI Logo">
            <img src="{{ asset('assets/img/loteng.png') }}" height="50px" alt="logo">
        </div>
    </div>
    @include('template.sidebar')
    <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>
</div>
<div class="c-wrapper c-fixed-components">
    <header class="c-header c-header-light c-header-fixed c-header-with-subheader">
        <button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button" data-target="#sidebar" data-class="c-sidebar-show">
            <div class="c-icon c-icon-lg">
                <i class="cil-menu"></i>
            </div>
        </button><a class="c-header-brand d-lg-none" href="#">
            <div width="118" height="46" alt="CoreUI Logo">
                <i class="assets/brand/coreui.div#full"></i>
            </div>
        </a>
        <button class="c-header-toggler c-class-toggler mfs-3 d-md-down-none" type="button" data-target="#sidebar" data-class="c-sidebar-lg-show" responsive="true">
            <div class="c-icon c-icon-lg">
                <i class="cil-menu"></i>
            </div>
        </button>
        <div class="c-header-nav ml-auto mr-4">
                <h3>
                @php
                    $semester=\App\Semester::first();
                    echo "Tahun Akademik ". $semester->nama_semester;
                @endphp
                </h3>
            </div>

        <ul class="c-header-nav ml-auto mr-5">
            <li class="c-header-nav-item dropdown mr-4"><a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <div class="c-avatar">
                        <div class="c-icon mr-2">
                            <i class="cil-user"></i>
                        </div>
                        {{ ucfirst(Auth::user()->username) }}
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right pt-0">
                    <a class="dropdown-item" href="{{ route('logout') }}">
                        <div class="c-icon mr-2">
                            <i class="cil-account-logout"></i>
                        </div> Logout
                    </a>
                </div>
            </li>
        </ul>
    </header>
    <div class="c-body">
        <main class="c-main">
            @yield('content')
        </main>
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form id="form" data-url="{{ $url ?? ''}}">
                    <div class="modal-body">
                       @yield('form-modal')
                    </div>
                    <div class="modal-footer">
                        <div id="spin">
                            <div class="text-center btn btn-primary px-4 d-none">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button class="btn btn-primary px-4" type="submit" id="btn">
                                Login
                            </button>
                        </div>
                    </div>
                </form>
              </div>
            </div>
          </div>
        <footer class="c-footer">
            <div><a href="https://coreui.io">CoreUI</a> © 2020 creativeLabs.</div>
            <div class="ml-auto">Powered by&nbsp;<a href="https://coreui.io/">CoreUI</a></div>
        </footer>
    </div>
</div>
@endsection
