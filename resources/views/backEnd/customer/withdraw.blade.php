@extends('backEnd.layouts.master')
@section('title','Withdraw')
@section('content')
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Withdraws</h4>
            </div>
        </div>
    </div>       
    <!-- end page title --> 
   <div class="row order_page">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-8">
                        <div class="export-print ">
                           
                        <ul class="action2-btn">
                            <li><a data-bs-toggle="modal" data-bs-target="#changeStatus" class="btn rounded-pill btn-primary"><i class="fe-plus"></i> Change Status</a></li>
                            <li><a href="{{route('customer.multiple_destroy')}}" class="btn rounded-pill btn-danger order_delete"><i class="fe-plus"></i> Delete All</a></li>
                            <li><a href="" onclick="printFunction()" class="btn rounded-pill btn-info"><i class="fe-printer"></i> Print</a></li>
                            <li><a id="export-excel-button" class="btn rounded-pill btn-success"><i class="fas fa-file-export"></i> Excel</a></li>
                            
                        </ul>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <form class="custom_form">
                            <div class="form-group">
                                <input type="text" name="keyword" placeholder="Search">
                                <button class="btn  rounded-pill btn-info">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive " id="content-to-export">
                <table id="datatable-buttons" class="table table-striped   w-100">
                    <thead>
                        <tr>
                            <th style="width:2%"><div class="form-check"><label class="form-check-label"><input type="checkbox" class="form-check-input checkall" value=""></label>
                            <th >SL</th>
                            <th >Date</th>
                            <th >Name</th>
                            <th >Phone</th>
                            <th >Bkash</th>
                            <th >Amount</th>
                            <th >Status</th>
                            <th >Action</th>
                        </tr>
                    </thead>
                
                
                    <tbody>
                        @foreach($data as $key=>$value)
                        <tr>
                            <td><input type="checkbox" class="checkbox" value="{{$value->id}}"></td>
                            <td>{{$loop->iteration}}</td>
                            <td>{{date('d-m-Y', strtotime($value->created_at))}}<br> {{date('h:i:s a', strtotime($value->created_at))}}</td>
                            <td>{{$value->customer?$value->customer->name:''}}</td>
                            <td>{{$value->customer?$value->customer->phone:''}}</td>
                            <td>{{$value->customer?$value->customer->bkash_no:''}}</td>
                            <td>৳{{$value->amount}}</td>
                            <td>{{$value->status}}</td>
                            <td>
                                <div class="button-list custom-btn-list">  
                                    <a href="{{route('customer.withdraw_process',['id'=>$value->id])}}" title="Process"><i class="fe-edit"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                <div class="custom-paginate">
                    {{$data->links('pagination::bootstrap-4')}}
                </div>
            </div> <!-- end card body-->
           
        </div> <!-- end card -->
    </div><!-- end col-->
   </div>
</div>
<!-- Assign User End -->
<div class="modal fade" id="changeStatus" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Order Status Change</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{route('customer.withdraw_change_multiple')}}" id="order_status_form">
      <div class="modal-body">
        <div class="form-group">
            <select name="status" id="order_status" class="form-control">
                <option value="">Select..</option>
                <option value="paid">Paid</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success">Submit</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- Assign User End-->
@endsection
@section('script')
<script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<script>
$(document).ready(function(){
    $(".checkall").on('change',function(){
      $(".checkbox").prop('checked',$(this).is(":checked"));
    });

    // order status change 
    $(document).on('submit', 'form#order_status_form', function(e){
        e.preventDefault();
        var url = $(this).attr('action');
        let status=$(document).find('select#order_status').val();
    
        var order = $('input.checkbox:checked').map(function(){
          return $(this).val();
        });
        var order_ids=order.get();
        
        if(order_ids.length ==0){
            toastr.error('Please Select An Order First !');
            return ;
        }
        $.ajax({
           type:'GET',
           url:url,
           data:{status,order_ids},
           success:function(res){
               if(res.status=='success'){
                toastr.success(res.message);
                window.location.reload();
                
            }else{
                toastr.error('Failed something wrong');
            }
           }
        });
    
    });
    
    // order delete
    $(document).on('click', '.order_delete', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var order = $('input.checkbox:checked').map(function(){
          return $(this).val();
        });
        var order_ids=order.get();
        
        if(order_ids.length ==0){
            toastr.error('Please Select An Order First !');
            return ;
        }
        
        $.ajax({
           type:'GET',
           url:url,
           data:{order_ids},
           success:function(res){
               if(res.status=='success'){
                toastr.success(res.message);
                window.location.reload();
                
            }else{
                toastr.error('Failed something wrong');
            }
           }
        });
    
    });
   
   
})
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