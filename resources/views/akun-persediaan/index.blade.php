@extends('welcome')
 
@section('content')
<div class="container" style="padding: 10px;">
    <div class="row mt-5">
        <div class="col-lg-12 pb-3">
             <div class="float-left">
                <h4><b>Akun Persediaan DBP</b></h4>
            </div>
        </div>
    </div>
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                </button>
            @endif

            {{-- <div class="card" style="padding: 10px;">
                <div class="card-body">
                    <strong>DBP Penjualan</strong><br>
                    <ul>
                        <li>Dilakukan pada tanggal 31 sebanyak <strong>1 x</strong> dalam 1 bulan</li>
                        <li>Apabila sudah diproses untuk dua area, dapat dilihat hasilnya pada tombol Lihat Nilai Persediaan</li>
                    </ul>
                </div>
            </div> --}}

        <div class="card" style="padding: 10px;">
            <div class="card-body">
                        <form action="{{ route('akun-persediaan.store') }}"  method="POST">
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
        </div>

        <div class="card" style="padding:10px;">
            <div class="card-body">
                <div class="col">
                    <div class="col-lg-12">
                        <table class="table table-hover table-bordered table-sm bg-light table-striped" id="example1">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Part No</th>
                                    <th class="text-center">DBP</th>
                                </tr>
                            </thead>
                            <tbody>

                                @php
                                $no=1;
                                @endphp

                                @foreach($persediaan_dbp as $p)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td class="text-left">{{ $p->part_no }}</td>
                                    <td class="text-right">{{ number_format($p->nominal_amount, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach

                                
                                
                            </tbody>
                        </table>
                    </div>
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
    
    <script>
    $(document).ready(function() {
        $('#tanggal_awal').change(function() {
            var selectedDate = $(this).val();
            
            if (selectedDate) {
                // Get the year and month from the selected date
                var year = selectedDate.split('-')[0];
                var month = selectedDate.split('-')[1];
                
                // Set the date input to the first day of the selected month
                var firstDayOfMonth = year + '-' + month + '-01';
                $(this).val(firstDayOfMonth);
            }
        });
    });
    </script>

@endsection