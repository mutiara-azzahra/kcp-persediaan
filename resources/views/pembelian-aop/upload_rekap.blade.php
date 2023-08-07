@extends('welcome')
 
@section('content')
<div class="container" style="padding: 10px;">
    <div class="row mt-5">
        <div class="col-lg-12 margin-tb p-3">
             <div class="float-left">
                <h4><b>Upload Rekap Tagihan</b></h4>
            </div>
            <div class="float-right">
                <a class="btn btn-success" href="{{ route('pembelian-aop.index')}}"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </div>
        <div class="card" style="padding: 10px;">
            <div class="card-body">
                <form href="{{ route('pembelian-aop.uploadRekap')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="col-lg-12">
                        <div class="col">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlFile1">Masukkan File</label>
                                    <input type="file" class="form-control-file" name="rekap_tagihan" accept=".txt">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <a class="btn btn-warning"><i class="fas fa-upload"></i> Upload</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
</div>
@endsection

@section('script')


@endsection