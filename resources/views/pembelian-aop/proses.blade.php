@extends('welcome')
 
@section('content')
<div class="container" style="padding: 10px;">
    <div class="row mt-5">
        <div class="col-lg-12 margin-tb p-3">
             <div class="float-left">
                <h4><b>Proses Persediaan</b></h4>
            </div>
        </div>
    </div>
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif

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
            <div class="card-body">
                        <form action="{{ route('pembelian-aop.prosesPersediaan') }}"  method="GET">
                            @csrf
                            <div class="row">
                                <div class="form-group col-4">
                                    <label for="">Pilih Area</label>
                                    <select name="area_inv" class="form-control mr-2">
                                        <option value="">-- Pilih Area --</option>
                                        <option value="KS">Kal-Sel</option>
                                        <option value="KT">Kal-Teng</option>
                                    </select>
                                </div>
                                <div class="form-group col-4">
                                    <label for="">Tanggal Awal</label>
                                    <input type="date" name="tanggal_awal" id="" class="form-control" placeholder="">
                                </div>
                                <div class="form-group col-4">
                                    <label for="">Tanggal Akhir</label>
                                    <input type="date" name="tanggal_akhir" id="" class="form-control" placeholder="">
                                </div>
                            </div>

                            <div class="float-right pt-2">
                                <button class="btn btn-warning" type="submit"><i class="fas fa-check"></i> Proses</button>
                            </div>
                        </form>
                </div>
            </div>
            <div class="card" style="padding: 10px;">
                <div class="card-body">
                    <div class="col-lg-12">  
                        <table class="table table-hover table-bordered table-sm bg-light" id="example2">
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
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $no=1;
                                @endphp

                                @foreach($persediaan as $p)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $p->area_inv }}</td>
                                     <td>{{ $p->bulan }} - {{ $p->tahun }}</td>
                                    <td>{{ $p->persediaan_awal }}</td>
                                    <td>{{ $p->pembelian }}</td>
                                    <td>{{ $p->retur_aop }}</td>
                                    <td>{{ $p->modal_terjual }}</td>
                                    <td>{{ $p->retur_modal_terjual }}</td>
                                    <td>{{ $p->persediaan_akhir }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
</div>
@endsection

@section('script')

    <script>
      $(function () {
        $("#example1")
          .DataTable({
            paging: true,
            responsive: true,
            lengthChange: false,
            autoWidth: false,
            buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
          })
          .buttons()
          .container()
          .appendTo("#example1_wrapper .col-md-6:eq(0)")
                  
        $("#example2").DataTable({
          paging: true,
          lengthChange: false,
          searching: false,
          ordering: true,
          info: true,
          autoWidth: false,
          responsive: true,
        });
      });
    </script>

@endsection