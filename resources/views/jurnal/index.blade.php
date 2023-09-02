@extends('welcome')
 
@section('content')
<div class="container" style="padding: 10px;">
    <div class="row mt-5">
        <div class="col-lg-12 pb-3">
             <div class="float-left">
                <h4><b>Jurnal</b></h4>
            </div>
        </div>
    </div>
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div> 
            @endif

        <div class="card" style="padding: 10px;">
            <div class="card" style="padding: 10px;">
                <div class="card-body">
                    <div class="col-lg-12">  
                        <table class="table table-hover table-bordered table-sm bg-light table-striped" id="example1">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Trx Date</th>
                                    <th class="text-center">Trx From </th>
                                    <th class="text-center">Keterangan</th>
                                    <th class="text-center">Debet</th>
                                    <th class="text-center">Kredit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $no=1;
                                @endphp

                                <tr>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                </tr>
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