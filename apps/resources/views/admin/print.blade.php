<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Hasil Mengajar {{ $dosen->nama_dosen }}</title>
    <link href="{{asset('assets/vendor/coreui/dist/css/coreui.min.css')}}" rel="stylesheet" />
</head>
<body>
    <div style="display: flex;justify-content:space-between;align-items:center;">
        <div><img src="{{asset('assets')}}/img/loteng.png" alt="logo" width="80px"></div>
        <div style="display: flex;justify-content:center;align-items:center;flex-direction:column">
            <p style="text-align: center;font-size:10sp">YAYASAN LOMBOK MIRAH<br>
            SEKOLAH TINGGI MANAJEMEN INFORMATIKA DAN KOMPUTER <br>
            <b>( STMIK ) LOMBOK</b> <br>
           <small> Jl. Basuki Rahmat No. 105 Praya-Lombok Tengah (83511) Telp/Fax. +62-370-654310 </small> <br>
            <small> Website : www.stmiklombok.ac.id - Email : stmikloombok@yahoo.co.id </small></p>
        </div>
        <div style="width: 80px"></div>
    </div>
    <hr>
    <h3 style="text-align: center"><u>Laporan Hasil Mengajar</u></h3>
    <table style="width: 100%">
        <tr>
            <td>NIDN</td>
            <td style="width: 10px">:</td>
            <td>{{ $dosen->nidn }}</td>
            <td>Tahun Akademik</td>
            <td style="10px">:</td>
            <td>{{$semester->nama_semester}}</td>
        </tr>
        <tr>
            <td>Nama Dosen</td>
            <td style="width: 10px">:</td>
            <td>{{ $dosen->nama_dosen }}</td>
            <td>Responden</td>
            <td style="10px">:</td>
            <td>{{$responden}}</td>
        </tr>
    </table>
    <br>
    <div class="container" style="height: 85vh">
<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>No</th>
            <th>Pertanyaan</th>
            <th>Total Point</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($nilai as $a)
            <tr>
                @foreach ($a as $b)
                    <td>{{$b}}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
<br>
<div class="row w-100">
    <div class="col-sm-12 col-md-6 col-lg-8">
        <table class="table table-bordered table-sm w-100">
            <br>
            <br>
            <tr>
                <td><b>Nilai Angka</b></td>
                <td><b>{{$nilai_grade}}</b></td>
            </tr>
            <tr>
                <td><b>Nilai Huruf</b></td>
                <td><b>{{$grade}}</b></td>
            </tr>
            <tr>
                <td><b>Predikat</b></td>
                <td><b>{{$predikat}}</b></td>
            </tr>
        </table>
    </div>
    <div class="col-sm-12 col-md-4 col-lg-2">
        <p>Keterangan</p>
        <table class="table table-bordered table-sm w-100">
            <tr>
                <td>0-19,99</td>
                <td>E</td>
                <td>Tidak Baik</td>
            </tr>
            <tr>
                <td>20-39,99</td>
                <td>D</td>
                <td>Kurang Baik</td>
            </tr>
            <tr>
                <td>40-59,99</td>
                <td>C</td>
                <td>Cukup Baik</td>
            </tr>
            <tr>
                <td>60-79,99</td>
                <td>B</td>
                <td>Baik</td>
            </tr>
            <tr>
                <td>80-100</td>
                <td>A</td>
                <td>Sangat Baik</td>
            </tr>
        </table>
    </div>
</div>
    </div>
    <div class="container">
        <div class="card border-0">
            <div class="card-body">
                <h3>Komentar Mahasiswa</h3>
                <table class="table table-bordered table-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Komentar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i=1;
                        @endphp
                        @foreach ($komentar as $a)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{$a->komentar}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="{{asset('assets/vendor/coreui/dist/js/coreui.bundle.min.js')}}"></script>
    <script>
        window.print()
    </script>
</body>
</html>
