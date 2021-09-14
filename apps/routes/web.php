<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', 'AuthController@index')->name('login');
Route::get('/logout', 'AuthController@logout')->name('logout');
Route::post('/ceklogin', 'AuthController@login');
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/', 'AdminController@index')->name('dashboard');
    Route::get('/print', 'AdminController@print')->name('print');
    Route::get('/pie', 'AdminController@pie')->name('pie');
    Route::middleware(['akses'])->group(function () {
        Route::get('/role', 'RoleController@index');
        Route::post('/role/aksi', 'RoleController@aksi');

        Route::get('/menu', 'MenuController@index');
        Route::post('/menu/aktif', 'MenuController@aktif');
        Route::post('/menu/up', 'MenuController@up');
        Route::post('/menu/down', 'MenuController@down');
        Route::post('/menu/aksi', 'MenuController@aksi');

        Route::get('/menu/submenu/{menu_id}', 'SubmenuController@index');
        Route::post('/menu/submenu/aksi/{menu_id}', 'SubmenuController@aksi');
        Route::post('/menu/submenu/aktif/{menu_id}', 'SubmenuController@aktif');
        Route::post('/menu/submenu/up/{menu_id}', 'SubmenuController@up');
        Route::post('/menu/submenu/down/{menu_id}', 'SubmenuController@down');

        Route::get('/akses', 'AksesController@index');
        Route::get('/akses/getAkses', 'AksesController@getAkses');
        Route::post('/akses/check', 'AksesController@check');

        Route::get('/user', 'UserController@index');
        Route::post('/user/aksi', 'UserController@aksi');
        Route::post('/user/aktif', 'UserController@aktif');

        Route::get('/data/dosen', 'DosenController@index')->name('dosen');
        Route::post('/data/dosen/aksi', 'DosenController@aksi')->name('dosen.aksi');

        Route::get('/data/mahasiswa', 'MahasiswaController@index')->name('mahasiswa');
        Route::post('/data/mahasiswa/aksi', 'MahasiswaController@aksi')->name('mahasiswa.aksi');

        Route::get('/data/matkul', 'MatkulController@index')->name('matkul');
        Route::post('/data/matkul/aksi', 'MatkulController@aksi')->name('matkul.aksi');

        Route::get('/data/perkuliahan', 'JadwalController@index')->name('jadwal');
        Route::post('/data/perkuliahan/aksi', 'JadwalController@aksi')->name('jadwal.aksi');

        Route::get('/spk/kriteria', 'KriteriaController@index')->name('kriteria');
        Route::post('/spk/kriteria/aksi', 'KriteriaController@aksi')->name('kriteria.aksi');

        Route::get('/spk/nilai', 'NilaiPilihanController@index')->name('nilai');
        Route::post('/spk/nilai/aksi', 'NilaiPilihanController@aksi')->name('nilai.aksi');

        Route::get('/spk/nilaikriteria', 'NilaiKriteriaController@index')->name('nilaikriteria');
        Route::get('/spk/nilaikriteria/form-tambah', 'NilaiKriteriaController@form_tambah')->name('nilaikriteria.tambah');
        Route::post('/spk/nilaikriteria/simpan', 'NilaiKriteriaController@simpan')->name('nilaikriteria.simpan');
        Route::post('/spk/nilaikriteria/hapus', 'NilaiKriteriaController@hapus')->name('nilaikriteria.hapus');

        Route::get('/setting', 'SettingController@index')->name('setting');
        Route::post('/setting/simpan', 'SettingController@simpan')->name('setting.simpan');
        Route::get('/setting/sinkronisasi', 'SettingController@sinkronisasi')->name('sinkronisasi');

        Route::get('/spk/aras', 'ArasController@index')->name('aras');

        Route::get('/spk/kuisioner', 'DataKuisionerController@index')->name('data_kuisioner');
        Route::get('/spk/kuisioner/detail', 'DataKuisionerController@detail')->name('data_kuisioner.detail');
        Route::post('/spk/kuisioner/aksi', 'DataKuisionerController@aksi')->name('data_kuisioner.aksi');

        Route::get('/kuisioner', 'KuisionerController@index')->name('kuisioner');
        Route::get('/kuisioner/isi', 'KuisionerController@isi')->name('kuisioner.isi');
        Route::post('/kuisioner/simpan', 'KuisionerController@simpan')->name('kuisioner.simpan');
    });
});
