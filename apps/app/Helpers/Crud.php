<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class Crud
{
    public function __construct($table, $id)
    {
        $this->table = $table;
        $this->id = $id;
    }
    private function res($cek, $pesan)
    {
        $data = [];
        if ($cek) {
            $data = [
                'status' => true,
                'pesan' => "Data berhasil " . $pesan
            ];
        } else {
            $data = [
                'status' => false,
                'pesan' => "Data gagal " . $pesan
            ];
        }
        return $data;
    }
    public function tambah($data)
    {
        $cek = DB::table($this->table)->insert($data);
        return $this->res($cek, "ditambah");
    }
    public function edit($id, $data)
    {
        $cek = DB::table($this->table)->where($this->id, $id)->update($data);
        return $this->res($cek, "diubah");
    }
    public function hapus($id)
    {
        $cek = DB::table($this->table)->where($this->id, $id)->delete();
        return $this->res($cek, "dihapus");
    }
}
