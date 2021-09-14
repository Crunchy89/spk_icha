@extends('template.core')

@section('title','Data Nilai Kriteria')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h3>Tabel Kriteria</h3>
            <hr>
            <div class="table-responsive">
                <table id="table" class="table table-bordered data-table w-100">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-center" width="50px">No</th>
                            <th rowspan="2" class="text-center">Kriteria</th>
                            <th class="text-center" colspan="{{ count($responden) }}">Bobot</th>
                            <th rowspan="2" class="text-center">Jumlah</th>
                            <th rowspan="2" class="text-center">Rata - rata</th>
                        </tr>
                        <tr>
                            @foreach ($responden as $row)
                                <th class="text-center">{{ $row->label }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    @php
                        $i=1;
                        $j=0;
                        $total=[];
                    @endphp
                    <tbody>
                        @foreach ($kriteria as $a)
                        @php
                            $nilai=\App\NilaiKriteria::whereId_kriteria($a->id)->get();
                        @endphp
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $a->kriteria }}</td>
                            @for ($k=0;$k<count($responden);$k++)
                                <td class="text-center">{{ $nilai[$k]->nilai ?? 0 }}</td>
                            @endfor
                            <td class="text-center">{{ round($sum[$j]) ?? 0 }}</td>
                            <td class="text-center rata">{{ round($rata[$j]) ?? 0 }}</td>
                        </tr>
                        @php
                            $j++;
                        @endphp
                        @endforeach
                        <tr>
                            <th class="text-center" colspan="{{ 3 + count($responden) }}">Total</th>
                            <td class="text-center" id="total">{{ round(array_sum($rata)) ?? 0 }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="card">
        <div class="card-body">
            <h3>Tabel Data Alternatif</h3>
            <hr>
            <div class="table-responsive">
                <table id="table" class="table table-bordered data-table w-100">
                    <thead>
                        <tr>
                            <th rowspan="2">Alternatif</th>
                            <th rowspan="2">Nama</th>
                            <th colspan="{{ count($kriteria) }}" class="text-center">Responden</th>
                            <th>Jumlah</th>
                        </tr>
                        <tr>
                            @foreach ($kriteria as $row)
                                <th>{{ $row->label }}</th>
                            @endforeach
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($nilai_kuisioner as $a)
                        @foreach ($a as $b)
                        <tr>
                            @php
                                $sum=[];
                            @endphp
                            @foreach ($b as $c=>$i)
                            <td>{{ $i }}</td>
                            {{-- @if ($c!=0 && $c!=1) --}}
                            @php
                                $sum[]=$i
                            @endphp
                            {{-- @endif --}}
                            @endforeach

                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="card">
        <div class="card-body">
            <h3>Tabel Bobot dan Kriteria</h3>
            <hr>
            <div class="table-responsive">
                <table id="table" class="table table-bordered data-table w-100">
                    <thead>
                        <tr>
                            <th>Kriteria</th>
                            <th>Keterangan</th>
                            <th>Jenis</th>
                            <th>Nilai Bobot Kriteria</th>
                            <th>% Bobot</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i=0;
                            $total=[];
                        @endphp
                        @foreach ($kriteria as $a)
                            <tr>
                                <td>{{ $a->label }}</td>
                                <td>{{ $a->kriteria }}</td>
                                <td>Binefit</td>
                                <td>{{ round($rata[$i]) }}</td>
                                <td>{{ round($rata[$i])/round(array_sum($rata)) }}</td>
                                @php
                                    $total[]=round($rata[$i])/round(array_sum($rata));
                                @endphp
                            </tr>
                            @php
                                $i++;
                            @endphp
                        @endforeach
                        <tr>
                            <td colspan="3" class="text-center">Jumlah</td>
                            <td>{{ round(array_sum($rata)) }}</td>
                            <td>{{ round(array_sum($total)) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="card">
        <div class="card-body">
            <h3>Tabel Alternatif</h3>
            <hr>
            <div class="table-responsive">
                <table id="table" class="table table-bordered data-table w-100">
                    <thead>
                        <tr>
                            <th>Alternatif</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i=1;
                        @endphp
                        @foreach ($dosen as $d)
                            <tr>
                                <td>{{'A'.$i++}}</td>
                                <td>{{$d->nama_dosen}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="card">
        <div class="card-body">
            <h3>Tabel Kriteria</h3>
            <hr>
            <div class="table-responsive">
                <table id="table" class="table table-bordered data-table w-100">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Nilai Fuzzy</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($nilai_pilihan as $n)
                            <tr>
                                <td>{{ $n->nama_pilihan }}</td>
                                <td>{{ $n->nilai }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="card">
        <div class="card-body">
            <h3>Tabel Ranting Kecocokan Pada Setiap Kriteria</h3>
            <hr>
            <div class="table-responsive">
                <table id="table" class="table table-bordered data-table w-100">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Alternatif</th>
                            <th colspan="{{count($kriteria)}}">Kriteria</th>
                        </tr>
                        <tr>
                            @foreach ($kriteria as $a)
                                <th>{{ $a->label }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i=0;
                        @endphp
                        @foreach ($kecocokan as $a)
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{'A'.$i}}</td>
                                @foreach ($a as $b)
                                    <td>{{$b}}</td>
                                @endforeach
                            </tr>
                        @php
                            $i++;
                        @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="card">
        <div class="card-body">
            <h3>Tabel Merumuskan matrik keputusan</h3>
            <hr>
            <div class="table-responsive">
                <table id="table" class="table table-bordered data-table w-100">
                    <tbody>
                        <tr>
                            <td rowspan="{{ count($kecocokan)+1 }}">Xij</td>
                        </tr>
                        @foreach ($kecocokan as $a)
                            <tr>
                                @foreach ($a as $b)
                                    <td>{{$b}}</td>
                                @endforeach
                            </tr>
                        @endforeach
                        <tr>
                            <td><h5>Jumlah</h5></td>
                            @foreach ($jumlah_kecocokan as $a)
                                <td>{{$a}}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="card">
        <div class="card-body">
            <h3>Tabel matrik keputusan</h3>
            <hr>
            <div class="table-responsive">
                <table id="table" class="table table-bordered data-table w-100">
                    <tbody>
                        <tr>
                            <td rowspan="{{ count($matrix_kecocokan)+1 }}">X*=</td>
                        </tr>
                        @foreach ($matrix_kecocokan as $a)
                            <tr>
                                @foreach ($a as $b)
                                    <td>{{round($b,3)}}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="card">
        <div class="card-body">
            <h3>Tabel hasil matrik keputusan</h3>
            <hr>
            <div class="table-responsive">
                <table id="table" class="table table-bordered data-table w-100">
                    <tbody>
                        <tr>
                            <td rowspan="{{ count($hasil_matrix_keputusan)+1 }}">D=</td>
                        </tr>
                        @foreach ($hasil_matrix_keputusan as $a)
                            <tr>
                                @foreach ($a as $b)
                                    <td>{{round($b,3)}}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="card">
        <div class="card-body">
            <h3>Tabel hasil Perkalian matrik dengan Bobot sebelumnya</h3>
            <hr>
            <div class="table-responsive">
                <table id="table" class="table table-bordered data-table w-100">
                    <thead>
                        <tr>
                            <th class="text-center" colspan="{{count($kriteria)+1}}">Tabel Hasil Perkalian matrik dengan Bobot sebelummya</th>
                            <th>Jumlah</th>
                            <th>K</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($perkalian_matrix_bobot as $a)
                            <tr>
                                @foreach ($a as $i=>$b)
                                <td>{{$b}}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="card">
        <div class="card-body">
            <h3>Tabel Nilai untuk masing - masing alternatif</h3>
            <hr>
            <div class="table-responsive">
                <table id="table" class="table table-bordered data-table w-100">
                    <thead>
                        <tr>
                            <th rowspan="2">Alternatif</th>
                            <th rowspan="2">Keterangan</th>
                            <th class="text-center" colspan="{{count($kriteria)}}">Kriteria</th>
                            <th rowspan="2">S</th>
                            <th rowspan="2">K</th>
                            <th rowspan="2">Nilai</th>
                        </tr>
                        <tr>
                            @foreach ($kriteria as $a)
                                <th>{{ $a->label }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($nilai_masing2 as $a)
                            <tr>
                                @foreach ($a as $b)
                                    <td>{{$b}}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



@endsection



