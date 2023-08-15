@extends('welcome')
 
@section('content')
<div class="container" style="padding: 10px;">
    <div class="row mt-5">
        <div class="col-lg-12 margin-tb pb-3">
             <div class="float-left">
                <h4><b>DBP</b></h4>
            </div>

            <div class="float-right">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-upload"></i> Upload Excel DBP</button>

                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Upload Excel DBP</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            
                                <div class="modal-body">
                                        <form action="{{ route('dbp.upload')}}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" name="excel_file">  
                                            <button type="submit">Upload</button>
                                        </form>
                                </div>
                                <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                </div>
                        </div>

                        
                    </div>
                </div>
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
                    <li>Masukkan File dengan Format Excel yang sudah disediakan, lalu klik tombol Upload</li>
                </ul>
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

                                @foreach($dbp as $p)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td class="text-left">{{ $p->part_no }}</td>
                                    <td class="text-right">{{ number_format($p->dbp, 0, ',', '.') }}</td>
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


@endsection