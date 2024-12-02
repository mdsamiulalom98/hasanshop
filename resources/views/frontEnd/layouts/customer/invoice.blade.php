@extends('frontEnd.layouts.master')
@section('title', 'Customer Invoice')
@section('content')
    <style>
        .customer-invoice {
            margin: 25px 0;
        }

        .invoice_btn {
            margin-bottom: 15px;
        }

        td {
            font-size: 16px;
        }

        @page {
            size: a4;
            margin: 0mm;
            background: #F9F9F9
        }

        @media print {
            td {
                font-size: 18px;
            }

            header,
            footer,
            .no-print {
                display: none !important;
            }
        }
    </style>
    <section class="customer-invoice ">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <a href="{{ route('customer.orders') }}"><strong><i class="fa-solid fa-arrow-left"></i> Back To
                            Order</strong></a>
                </div>


                <div class="col-sm-6">
                    <button onclick="printFunction()" class="no-print invoice_btn"><i class="fa fa-print"></i></button>
                </div>

                <div class="col-sm-12">
                    <div class="invoice-innter"
                        style="width: 900px;margin: 0 auto;background: #f9f9f9;overflow: hidden;padding: 30px;padding-top: 0;">
                        <table style="width:100%">
                            <tr>
                                <td style="width: 40%; float: left; padding-top: 15px;">
                                    <img src="{{ asset($generalsetting->white_logo) }}"
                                        style="margin-top:25px !important;width:150px" alt="">
                                    <p style="font-size: 14px; color: #222; margin: 20px 0;"><strong>Payment
                                            Method:</strong> <span
                                            style="text-transform: uppercase;">{{ $order->payment ? $order->payment->payment_method : '' }}</span>
                                    </p>
                                    <div class="invoice_form">
                                        <p style="font-size:16px;line-height:1.8;color:#222"><strong>Invoice From:</strong>
                                        </p>
                                        <p style="font-size:16px;line-height:1.8;color:#222">{{ $generalsetting->name }}</p>
                                        <p style="font-size:16px;line-height:1.8;color:#222">{{ $contact->phone }}</p>
                                        <p style="font-size:16px;line-height:1.8;color:#222">{{ $contact->email }}</p>
                                        <p style="font-size:16px;line-height:1.8;color:#222">{{ $contact->address }}</p>
                                    </div>
                                </td>
                                <td style="width:60%;float: left;">
                                    <div class="invoice-bar"
                                        style=" background: #00aef0; transform: skew(38deg); width: 100%; margin-left: 65px; padding: 20px 60px; ">
                                        <p
                                            style="font-size: 30px; color: #fff; transform: skew(-38deg); text-transform: uppercase; text-align: right; font-weight: bold;">
                                            Invoice</p>
                                    </div>
                                    <div class="invoice-bar"
                                        style="background:#fff; transform: skew(36deg); width: 80%; margin-left: 182px; padding: 12px 32px; margin-top: 6px;text-align:right">
                                        <p style="transform: skew(-36deg);display:inline-block">Invoice Date:
                                            <strong>{{ $order->created_at->format('d-m-y') }}</strong></p>
                                        <p style="transform: skew(-36deg);display:inline-block">Invoice No:
                                            <strong>{{ $order->invoice_id }}</p>
                                        </p>
                                    </div>
                                    <div class="invoice_to" style="padding-top: 20px;">
                                        <p style="font-size:16px;line-height:1.8;color:#222;text-align: right;">
                                            <strong>Invoice To:</strong></p>
                                        <p
                                            style="font-size:16px;line-height:1.8;color:#222;text-align: right;font-weight:normal">
                                            {{ $order->shipping ? $order->shipping->name : '' }}</p>
                                        <p
                                            style="font-size:16px;line-height:1.8;color:#222;text-align: right;font-weight:normal">
                                            {{ $order->shipping ? $order->shipping->phone : '' }}</p>
                                        <p
                                            style="font-size:16px;line-height:1.8;color:#222;text-align: right;font-weight:normal">
                                            {{ $order->shipping ? $order->shipping->address : '' }}</p>
                                        <p
                                            style="font-size:16px;line-height:1.8;color:#222;text-align: right;font-weight:normal">
                                            {{ $order->shipping ? $order->shipping->area : '' }}</p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <table class="table" style="margin-top: 30px">
                            <thead style="background: #00aef0; color: #fff;">
                                <tr>
                                    <th>SL</th>
                                    <th style="width: 50px">Image</th>
                                    <th>Product</th>
                                    <th style="white-space: nowrap;">Product ID</th>
                                    <th>IMEI</th>
                                    <th>Warranty</th>
                                    <th style="white-space: nowrap;">Regular Price</th>
                                    <th style="white-space: nowrap;">Discount Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderdetails as $key => $value)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><img src="{{ asset($value->image ? $value->image->image : '') }}" height="50" width="50" alt=""></td>
                                        <td>{{ $value->product_name }} </td>
                                        <td>{{ $value->product->pro_barcode ?? '' }}</td>
                                        <td>{{ $value->imei }}</td>
                                        <td>{{$value->warranty->warranty}}</td>
                                            @if ($value->product_size)
                                                <small>Size: {{ $value->product_size }}</small>
                                                @endif @if ($value->product_color)
                                                    <small>Color: {{ $value->product_color }}</small>
                                                @endif
                                        </td>
                                        <td>৳{{ $value->product->old_price ?? 0 * $value->qty }}</td>
                                        <td>৳{{ $value->sale_price * $value->qty }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="invoice-bottom">
                            <table class="table" style="width: 300px; float: right;    margin-bottom: 30px;">
                                <tbody style="background:#00aef0">
                                    <tr style="color:#fff">
                                        <td><strong>SubTotal</strong></td>
                                        <td><strong>৳{{ $order->orderdetails->sum('sale_price') }}</strong></td>
                                    </tr>
                                    <tr style="color:#fff">
                                        <td><strong>Shipping(+)</strong></td>
                                        <td><strong>৳{{ $order->shipping_charge }}</strong></td>
                                    </tr>
                                    <tr style="color:#fff">
                                        <td><strong>Discount(-)</strong></td>
                                        <td><strong>৳{{ $order->discount }}</strong></td>
                                    </tr>
                                    <tr style="background:#00aef0;color:#fff">
                                        <td><strong>Final Total</strong></td>
                                        <td><strong>৳{{ $order->amount }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="terms-condition"
                                style="overflow: hidden; width: 100%; text-align: center; padding: 20px 0; border-top: 1px solid #ddd;">
                                <h5 style="font-style: italic;"><a
                                        href="{{ route('page', ['slug' => 'terms-condition']) }}">Terms & Conditions</a></h5>
                                <p style="text-align: center; font-style: italic; font-size: 15px; margin-top: 10px;">* This
                                    is a computer generated invoice, does not require any signature.</p>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Disable right-click on the entire page
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault();
            });

            // Disable certain key combinations (e.g., Ctrl+S, Ctrl+P, Ctrl+Shift+I for Developer Tools)
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey && (e.key === 's' || e.key === 'p' || e.key === 'u' || (e.shiftKey && e
                        .key === 'I'))) {
                    e.preventDefault();
                }
            });

            var pdfFrame = document.getElementById('pdfIframe');

            // Load the PDF inside an iframe
            pdfFrame.addEventListener('load', function() {
                var iframeDoc = pdfFrame.contentDocument || pdfFrame.contentWindow.document;

                // Hide the download button inside the iframe (this might not work in all browsers)
                var downloadButton = iframeDoc.querySelector('[data-tooltip="Download"]');
                if (downloadButton) {
                    downloadButton.style.display = 'none';
                }

                // Hide the print button inside the iframe (this might not work in all browsers)
                var printButton = iframeDoc.querySelector('[data-tooltip="Print"]');
                if (printButton) {
                    printButton.style.display = 'none';
                }
            });
        });
    </script>

@endsection
