@extends('template.core')
@section('title','Dashboard')
@section('content')

<div class="container-lg">

    <div class="row">

        <div class="col-sm-6 col-lg-4">
            <div class="card mb-4">
                <div class="card-header position-relative d-flex justify-content-center align-items-center">
                    <i class="icon text-dark text-center my-4 " style="height: 3rem; width: 100%">
                        <h3>Kuisioner</h3>
                    </i>
                    <div class="chart-wrapper position-absolute top-0 start-0 w-100 h-100">
                        <canvas id="social-box-chart-1" height="90"></canvas>
                    </div>
                </div>
                <div class="card-body row text-center">
                    <div class="col">
                        <div class="fs-5 fw-semibold">{{ $mengisi }}</div>
                        <div class="text-uppercase text-medium-emphasis small">Sudah Terisi</div>
                    </div>
                    <div class="vr"></div>
                    <div class="col">
                        <div class="fs-5 fw-semibold">{{ $tdk_mengisi }}</div>
                        <div class="text-uppercase text-medium-emphasis small">Belum Diisi</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<div class="container-lg">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-6">

<figure class="highcharts-figure">
    <div id="container"></div>
    <script>
        $(document).ready(function(){
            Highcharts.chart('container', {
      chart: {
        type: 'pie'
      },
      title: {
        text: 'Responden Kuisioner {{$semester->nama_semester}}'
      },
      subtitle: {
        text: 'Klik chart untuk melihat detail responden'
      },

      accessibility: {
        announceNewData: {
          enabled: true
        },
        point: {
          valueSuffix: '%'
        }
      },

      plotOptions: {
        series: {
          dataLabels: {
            enabled: true,
            format: '{point.name}: {point.y}'
          }
        }
      },

      tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> of total<br/>'
      },

      series: [{
        name: "Dosen",
        colorByPoint: true,
        data: {!! json_encode($pie) !!}
      }],
      drilldown: {
        series: {!! json_encode($series) !!}
      }
    });
        })
    </script>
</figure>
        </div>
    <div class="col-sm-12 col-md-12 col-lg-6">
        <figure class="highcharts-figure">
            <div id="bar"></div>
        </figure>
        <script>
            $(document).ready(function(){
                Highcharts.chart('bar', {
      chart: {
        type: 'column'
      },
      title: {
        text: 'Responden Kuisioner {{$semester->nama_semester}}'
      },
      subtitle: {
        text: 'Klik chart untuk melihat detail responden'
      },
      accessibility: {
        announceNewData: {
          enabled: true
        }
      },
      xAxis: {
        type: 'category'
      },
      yAxis: {
        title: {
          text: 'Banyak Responden'
        }

      },
      legend: {
        enabled: false
      },
      plotOptions: {
        series: {
          borderWidth: 0,
          dataLabels: {
            enabled: true,
            format: '{point.y}'
          }
        }
      },

      tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> of total<br/>'
      },

      series: [{
        name: "Dosen",
        colorByPoint: true,
        data: {!! json_encode($pie) !!}
      }],
      drilldown: {
        series: {!! json_encode($series) !!}
      }
    });
            })
        </script>
    </div>

    </div>
</div>

<div class="container">
    <div class="card">
        <div class="card-body">
            <h3>Ranking</h3>
            <hr>
            <table class="table table-striped">
                <thead>
                    <th>Nama Dosen</th>
                    <th>NIDN</th>
                    <th>Responden</th>
                    <th>Point</th>
                    <th>Nilai</th>
                    <th>Aksi</th>
                </thead>
                <tbody>
                    @foreach ($juara as $i=>$a)
                        <tr>
                            @foreach ($a as $b)
                                <td>{{$b}}</td>
                            @endforeach
                            <td>
                                <a href="{{route('print',['nidn'=>$a[1]])}}" target="_BLANK" class="btn btn-info"><i class="cil-print"></i> Print Laporan Hasil Mengajar</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@section('script')
@if(Session::has('message'))
<script>
    $(document).ready(function() {
        toastr.success(`{{ Session()->get('message') }}`)
    })
</script>
@endif
@endsection
