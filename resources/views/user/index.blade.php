@extends('welcome')
 
@section('content')
<div class="container" style="padding: 10px;">
    <div class="row mt-5">
        <div class="col-lg-12 margin-tb pb-3">
             <div class="float-left">
                <h4><b>User</b></h4>
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


@endsection