@extends('welcome')
 
@section('content')
<div class="container" style="padding: 10px;">
    <div class="row mt-5">
        <div class="col-lg-12 margin-tb pb-3">
             <div class="float-left">
                <h4><b>DBP</b></h4>
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

        <div class="card" style="padding:10px;">
            <div class="card-body">
                <table class="table table-hover table-bordered table-sm bg-light table-striped" id="example1">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">HET</th>
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

                                @foreach($data as $p)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $p->kode->nama_gudang }}</td>
                                     <td>{{ $p->bulan }} - {{ $p->tahun }}</td>
                                    <td class="text-right">{{ number_format($p->persediaan_awal, 0, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($p->pembelian, 0, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($p->retur_aop, 0, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($p->modal_terjual, 0, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($p->retur_modal_terjual, 0, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($p->persediaan_akhir, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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