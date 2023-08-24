@extends('welcome')
 
@section('content')
<div class="container" style="padding: 10px;">
    <div class="row mt-5">
        <div class="col-lg-12 pb-3">
             <div class="float-left">
                <h4><b>PENJUALAN DBP</b></h4>
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
                        <form action="{{ route('akun-persediaan.store') }}"  method="POST">
                            @csrf
                            <div class="row">
                                <div class="form-group col-6">
                                    <label for="">Tanggal Awal</label>
                                    <input type="date" name="tanggal_awal" id="" class="form-control" placeholder="">
                                </div>

                                <div class="form-group col-6">
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

            <div class="card" style="padding:10px;">
                <div class="card-body">
                    <div class="col">
                        <div class="col-lg-12">
                            <table class="table table-hover table-bordered table-sm bg-light table-striped" id="example1">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Tanggal</th>
                                        <th class="text-center">No Invoice</th>
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
                                        <td class="text-left">{{ $p->crea_date }}</td>
                                        <td class="text-left">{{ $p->noinv }}</td>
                                        <td class="text-left">{{ $p->part_no }}</td>
                                        <td class="text-right">{{ number_format($p->nominal_total, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach

                                    
                                    
                                </tbody>
                            </table>
                        </div>
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