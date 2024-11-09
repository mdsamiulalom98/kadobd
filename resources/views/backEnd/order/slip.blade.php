@extends('backEnd.layouts.master')
@section('title', 'Order Slip')
@section('content')
    <style>
        .lable_print {
            margin: 25px 0;
        }

        .invoice_btn {
            margin-bottom: 15px;
        }

        p {
            margin: 0;
        }

        td {
            font-size: 16px;
        }

        .pos__prints {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 95px;
        }

        @page {
            margin: 0px;
        }

        @media print {
            .label_print {
                margin-left: -380px !important;
                margin-top: -50px !important;
            }

            td {
                font-size: 18px;
            }

            p {
                margin: 0;
            }

            header,
            footer,
            .no-print,
            .user-header,
            .user-sidebar {
                display: none !important;
            }
        }
    </style>

    <section class="lable_print_inner">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-6">
                    <div class="print-btn text-center">
                        <button onclick="printFunction()"class="no-print btn btn-xs btn-success waves-effect waves-light"><i
                                class="fa fa-print"></i></button>
                    </div>
                    <div class="label_print" style="width: 385px;margin: 0 auto;background: #ffff;padding: 20px;">
                        <div class="label_merchant" style="text-align:center;">
                            <div class="merchant-logo">
                                <img src="{{ asset($generalsetting->dark_logo) }}" style="width: 80px;">
                            </div>

                            <div class="parcel-info"
                                style="margin: 15px 0; padding: 10px 28px; display: grid; grid-template-columns: 90px auto; align-items: center; border: 1px solid #ddd; border-radius: 5px;">
                                <div class="qr">

                                </div>
                                <div class="parcel" style="text-align:left">
                                    <p>ID: {{ $order->invoice_id }}</p>
                                </div>
                            </div>
                            <div class="customer-info">
                                <p>Name: {{ $order->shipping->name ?? '' }}</p>
                                <p>Phone: {{ $order->shipping->phone ?? '' }}</p>
                                <p>Address: {{ $order->shipping->address ?? '' }}</p>
                                <p>Address: {{ $order->shipping->address ?? '' }}</p>
                            </div>
                            <div class="cod-amount"
                                style="display: grid; grid-template-columns: 1fr 1fr; border: 1px solid #ddd; border-radius: 5px; margin: 15px 0;">
                                <h6 class="cod_border"
                                    style="border-right: 1px solid #ddd;font-weight: 600;padding: 10px 0;">Amount</h6>
                                <h6 style="font-weight: 600;padding: 10px 0;">৳ {{ $order->amount }}</h6>
                            </div>
                            <div class="site-info" style="text-align:center;">
                                <img src="{{ asset($generalsetting->dark_logo) }}" style="width: 130px;" alt="">
                                <p style="margin-top: 5px;">www.websolutionit.com</p>
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
