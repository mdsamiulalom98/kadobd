@extends('backEnd.layouts.master')
@section('title','Customer List')
@section('content')
@section('css')
<link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('public/backEnd/')}}/assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet" type="text/css" />
<style>
    .info-box {
        padding: 25px;
        color: #fff;
        border-radius: 5px;
    }
    span.info-box-text {
        font-size: 16px;
    }
    .progress-description {
        font-size: 16px;
    }
    .info-box .progress {
        height: 3px;
        margin: 6px 0;
    }
    span.info-box-icon {
        font-size: 22px;
    }
    .info-box-content span {
        font-size: 16px;
        font-weight: 600;
    }
    p{
        margin:0;
    }
   @page { 
        margin: 50px 0px 0px 0px;
    }
   @media print {
    td{
        font-size: 18px;
    }
    p{
        margin:0;
    }
    title {
        font-size: 25px;
    }
    header,footer,.no-print,.left-side-menu,.navbar-custom {
      display: none !important;
    }
    
  }
</style>
@endsection 
<div class="container-fluid">
    
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Customer List</h4>
            </div>
        </div>
    </div>       
    <!-- end page title --> 
   <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form class="no-print">
                    <div class="row no-print">  
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="start_date" class="form-label">Telegram Number</label>
                                <input type="text" class="form-control" value="{{request()->get('keyword')}}" name="keyword" placeholder="Telegram Number">
                            </div>
                        </div> 
                        <div class="col-sm-2">
                            <div class="form-group">
                               <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" value="{{request()->get('start_date')}}"  class="form-control flatdate" name="start_date">
                            </div>
                        </div>
                        <!--col-sm-3-->
                        <div class="col-sm-2">
                            <div class="form-group">
                               <label for="end_date" class="form-label">End Date</label>
                                <input type="date" value="{{request()->get('end_date')}}" class="form-control flatdate" name="end_date">
                            </div>
                        </div>
                        <!--col-sm-3-->
                        <div class="col-sm-2">
                            <label class="form-label" style="opacity:0">Submit</label>
                            <div class="form-group mb-3">
                                <button class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                        <!-- col end -->
                        <div class="col-sm-4 mt-3">
                            <div class="export-print text-end">
                                <button onclick="printFunction()"class="no-print btn btn-success"><i class="fa fa-print"></i> Print</button>
                                <button id="export-excel-button" class="no-print btn btn-info"><i class="fas fa-file-export"></i> Export</button>
                            </div>
                        </div>
                        <!-- col end -->
                    </div>  
                </form>
                <div id="content-to-export" class="table-responsive">
                  <div class="card-body">
                      <div class="table-responsive ">
                            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Telegram</th>
                                    <th>Refer ID</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        
                        
                            <tbody>
                                @foreach($show_data as $key=>$value)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$value->name}}</td>
                                    <td>{{$value->phone}}</td>
                                    <td>{{$value->refferal_id}}</td>
                                    <td>@if($value->status=='active')<span class="badge bg-soft-success text-success">Active</span> @else <span class="badge bg-soft-danger text-danger">{{$value->status}}</span> @endif</td>
                                    <td>
                                        <div class="button-list">
                                            @if($value->status == 'active')
                                            <form method="post" action="{{route('customers.inactive')}}" class="d-inline"> 
                                            @csrf
                                            <input type="hidden" value="{{$value->id}}" name="hidden_id">       
                                            <button type="button" class="btn btn-xs  btn-secondary waves-effect waves-light change-confirm"><i class="fe-thumbs-down"></i></button></form>
                                            @else
                                            <form method="post" action="{{route('customers.active')}}" class="d-inline">
                                                @csrf
                                            <input type="hidden" value="{{$value->id}}" name="hidden_id">        
                                            <button type="button" class="btn btn-xs  btn-success waves-effect waves-light change-confirm"><i class="fe-thumbs-up"></i></button></form>
                                            @endif

                                            <a href="{{route('customers.edit',$value->id)}}" class="btn btn-xs btn-primary waves-effect waves-light"><i class="fe-edit-1"></i></a>

                                            <a href="{{route('customers.profile',['id'=>$value->id])}}" class="btn btn-xs btn-blue waves-effect waves-light"><i class="fe-eye"></i></a>
                                            <form method="post" action="{{route('customers.delete')}}" class="d-inline">
                                                @csrf
                                            <input type="hidden" value="{{$value->id}}" name="hidden_id">  
                                            <button type="button" class="btn btn-xs btn-pink waves-effect waves-light delete-confirm" title="Login as customer"><i class="fe-trash"></i></button></form>

                                            <form method="post" action="{{route('customers.adminlog')}}" class="d-inline" target="_blank">
                                                @csrf
                                            <input type="hidden" value="{{$value->id}}" name="hidden_id">  
                                            <button type="button" class="btn btn-xs btn-success waves-effect waves-light change-confirm" title="Login as customer"><i class="fe-log-in"></i></button></form>

                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                  </div>
                  {{$show_data->links('pagination::bootstrap-4')}}
                </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
   </div>
</div>
@endsection
@section('script')
<script src="{{asset('public/backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-advanced.init.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/libs/flatpickr/flatpickr.min.js"></script>
<script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.select2').select2();
        flatpickr(".flatdate", {});
    });
</script>
<script>
    function printFunction() {
        window.print();
    }
</script>
<script>
    $(document).ready(function() {
        $('#export-excel-button').on('click', function() {
            var contentToExport = $('#content-to-export').html();
            var tempElement = $('<div>');
            tempElement.html(contentToExport);
            tempElement.find('.table').table2excel({
                exclude: ".no-export",
                name: "Order Report" 
            });
        });
    });
</script>
@endsection
