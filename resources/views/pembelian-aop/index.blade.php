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
                <strong>Tata Cara Proses Upload Surat Tagihan dan Rekap Tagihan</strong><br>
                <ul>
                    <li>Apabila sudah diproses untuk dua area, dapat dilihat hasilnya pada tombol Lihat Nilai Persediaan</li>
                </ul>
            </div>
        </div>
        <div class="card" style="padding: 10px;">
            <div class="card-body">
                <div class="float-left p-1">
                    <a class="btn btn-success" href="{{ route('pembelian-aop.formUploadRekap')}}"><i class="fas fa-upload"></i> Upload Rekap Tagihan</a>
                </div>
                <div class="float-left p-1">
                    <a class="btn btn-warning" href="{{ route('pembelian-aop.formUploadSurat')}}"><i class="fas fa-file"></i> Upload Surat Tagihan</a>
                </div>
            </div>
        </div>
        <div class="card" style="padding: 20px;">
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
                            {{--  --}}
                        </tbody>
                    </table>
                   
                    </div>
                </div>

                </div>

</div>
@endsection

@section('script')


@endsection