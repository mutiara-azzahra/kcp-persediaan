@extends('welcome')
 
@section('content')
<div class="container" style="padding: 10px;">
    <div class="row mt-5">
        <div class="col-lg-12 margin-tb p-3">
             <div class="float-left">
                <h4><b>Pembelian AOP</b></h4>
            </div>
        </div>
    </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Maaf!</strong> Ada yang salah
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card" style="padding: 10px;">
            <div class="card-body">
                <strong>Tata Cara Proses Upload DBP</strong><br>
                <ul>
                    <li>Masukkan File dengan Format Excel yang sudah disediakan, lalu klik tombol Up</li>
                </ul>
            </div>
        </div>
        <div class="card" style="padding: 10px;">
            <div class="card-body">
                <div class="float-left p-1">
                    <a class="btn btn-success" href="{{ route('pembelian-aop.formUploadRekap')}}"><i class="fas fa-upload"></i> Upload Excel DBP</a>
                </div>
            </div>
        </div>

        <div class="card" style="padding: 10px;">
            <div class="card-body">
                <div class="col">
                    <div class="col-lg-12">  
                        <table class="table table-hover table-bordered table-sm bg-light table-striped" id="example1">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Area</th>
                                <th class="text-center">Bulan</th>
                                <th class="text-center">Persediaan Awal</th>
                                <th class="text-center">Pembelian AOP</th>
                                <th class="text-center">Pejualan</th>
                                <th class="text-center">Retur Terjual</th>
                                <th class="text-center">Persediaan Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $no=1;

                            @endphp
                        </tbody>
                    </table>
                   
                    </div>
                </div>
            </div>
        </div>
                

</div>
@endsection

@section('script')


@endsection