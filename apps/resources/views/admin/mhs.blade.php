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
                        <div class="fs-5 fw-semibold">{{$mengisi}}</div>
                        <div class="text-uppercase text-medium-emphasis small">Kuisioner terisi</div>
                    </div>
                    <div class="vr"></div>
                    <div class="col">
                        <div class="fs-5 fw-semibold">{{ $tdk_mengisi }}</div>
                        <div class="text-uppercase text-medium-emphasis small">Belum terisi</div>
                    </div>
                </div>
            </div>
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
