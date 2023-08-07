@extends('welcome')
 
@section('content')
<div class="container" style="padding: 10px;">
    <div class="row mt-5">
        <div class="col-lg-12 margin-tb p-3">
             <div class="float-left">
                <h4><b>Proses Persediaan</b></h4>
            </div>
            <div class="float-right">
                <a class="btn btn-success" href="{{ route('pembelian-aop.index')}}"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </div>

            <div class="card" style="padding: 10px;">
                <div class="card-body">
                    <strong>Tata Cara Proses Nilai Persediaan</strong><br>
                    <ul>
                        <li>Dilakukan pada tanggal 31 sebanyak <strong>1 x</strong> dalam 1 bulan</li>
                        <li>Apabila sudah diproses untuk dua area, dapat dilihat hasilnya pada tombol Lihat Nilai Persediaan</li>
                    </ul>
                </div>
            </div>

        <div class="card" style="padding: 10px;">
            <div class="card" style="padding: 10px;">
                <div class="card-body">
                    <div class="col-lg-12">  
                        <table class="table table-hover table-bordered table-sm bg-light" id="dataTable">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Kode Daerah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $no=1;
                                @endphp
                                <div class="card" style="padding: 10px;">
                <div class="card-body">
                    <div class="col-lg-12">  
                        <table class="table table-hover table-bordered table-sm bg-light" id="dataTable">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Kode Daerah</th>
                                    <th class="text-center">Bulan - Tahun</th>
                                    <th class="text-center">Persediaan Awal</th>
                                    <th class="text-center">Pembelian AOP</th>
                                    <th class="text-center">Retur AOP</th>
                                    <th class="text-center">Penjualan</th>
                                    <th class="text-center">Retur Penjualan</th>
                                    <th class="text-center">Persediaan Akhir</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $no=1;
                                @endphp
                                @foreach ($get_pembelian_aop as $g)
                                <tr>
                                    <td class="text-center">{{$g}}</td>
                                </tr>

                                @endforeach
                            </tbody>
                        </table>
                    
                </div>
            </div>
                            </tbody>
                        </table>
                    
                </div>
            </div>
        </div>
        
</div>
@endsection

@section('script')


@endsection