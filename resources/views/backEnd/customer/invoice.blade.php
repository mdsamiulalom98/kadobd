@extends('backEnd.layouts.master')
@section('title','Order Invoice')
@section('content')
<style>
    .customer-invoice {
        margin: 25px 0;
    }
    .invoice_btn{
        margin-bottom: 15px;
    }
    p{
        margin:0;
    }
    td{
        font-size: 16px;
    }
   @page { 
    margin:0px;
    }
   @media print {
    .invoice-innter{
        margin-left: -120px !important;
    }
    .invoice_btn{
        margin-bottom: 0 !important;
    }
    td{
        font-size: 18px;
    }
    p{
        margin:0;
    }
    header,footer,.no-print,.left-side-menu,.navbar-custom {
      display: none !important;
    }
  }
</style>
<section class="customer-invoice ">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <button onclick="printFunction()"class="no-print btn btn-xs btn-success waves-effect waves-light"><i class="fa fa-print"></i></button>
            </div>
            <div class="col-sm-12 mt-3">
                <div class="invoice-innter" style="width:760px;margin: 0 auto;background: #fff;overflow: hidden;padding: 30px;padding-top: 0;">
                    <table style="width:100%">
                        <tr>
                            <td style="width: 40%; float: left; padding-top: 15px;">
                                <img src="{{asset($generalsetting->white_logo)}}" alt="">
                                <div class="invoice_form">
                                    <p style="font-size:16px;line-height:1.8;color:#222"><strong>Bill From:</strong></p>
                                    <p style="font-size:16px;line-height:1.8;color:#222">{{$generalsetting->name}}</p>
                                    <p style="font-size:16px;line-height:1.8;color:#222">{{$contact->phone}}</p>
                                    <p style="font-size:16px;line-height:1.8;color:#222">{{$contact->email}}</p>
                                    <p style="font-size:16px;line-height:1.8;color:#222">{{$contact->address}}</p>
                                </div>
                            </td>
                            <td  style="width:60%;float: left;">
                                <div class="invoice-bar" style=" background: #5EC26E; transform: skew(38deg); width: 100%; margin-left: 65px; padding: 20px 60px; ">
                                    <p style="font-size: 30px; color: #fff; transform: skew(-38deg); text-transform: uppercase; text-align: right; font-weight: bold;">Invoice</p>
                                </div>
                                <div class="invoice-bar" style="background: #5EC26E; transform: skew(36deg); width: 72%; margin-left: 182px; padding: 12px 32px; margin-top: 6px;">
                                    <p style="font-size: 15px; color: #fff; transform: skew(-36deg); text-align: right; padding-right: 18px">Invoice ID : <strong>#{{$data->id}}</strong></p>
                                    <p style="font-size: 15px; color: #fff; transform: skew(-36deg); text-align: right; padding-right: 32px"> Date: <strong>{{date('d-m-Y', strtotime($data->created_at))}} ,{{date('h:i a', strtotime($data->created_at))}}</strong></span></p>
                                </div>
                                <div class="invoice_to" style="padding-top: 20px;">
                                    <p style="font-size:16px;line-height:1.8;color:#222;text-align: right;"><strong>Bill To:</strong></p>
                                    <p style="font-size:16px;line-height:1.8;color:#222;text-align: right;">{{$data->customer?$data->customer->name:''}}</p>
                                    <p style="font-size:16px;line-height:1.8;color:#222;text-align: right;">{{$data->customer?$data->customer->phone:''}}</p>
                                    <p style="font-size:16px;line-height:1.8;color:#222;text-align: right;">{{$data->customer?$data->customer->address:''}}</p>
                                    <p style="font-size:16px;line-height:1.8;color:#222;text-align: right;">{{$data->customer?$data->customer->email:''}}</p>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <table class="table" style="margin-top: 30px;margin-bottom: 0;">
                        <thead style="background: #5EC26E; color: #fff;">
                            <tr>
                                <th>SL</th>
                                <th>Customer</th>
                                <th>Date & Time</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($data)
                            <tr>
                                <td>{{$data->id}}</td>
                                <td>{{$data->customer?$data->customer->name:''}}</td>
                                <td>{{$data->created_at->format('d-m-y h:m a')}}</td>
                                <td>৳{{$data->amount}}</td>
                                <td>@if($data->type_id=='1') Main Balance @else Savings @endif</td>
                                <td>{{$data->note}}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="invoice-bottom">
                        <table class="table" style="width: 300px; float: right;    margin-bottom: 30px;">
                            <tbody style="background:#f1f9f8">
                                <tr style="background:#5EC26E;color:#fff">
                                    <td><strong>Final Total</strong></td>
                                    <td><strong>৳{{$data->amount}}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="terms-condition" style="overflow: hidden; width: 100%; text-align: center; padding: 20px 0; border-top: 1px solid #ddd;">
                            <h5 style="font-style: italic;">Terms & Conditions</h5>
                            <p style="text-align: center; font-style: italic; font-size: 15px; margin-top: 10px;">* This is a computer generated invoice, does not require any signature.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function printFunction() {
        window.print();
    }
</script>
@endsection
