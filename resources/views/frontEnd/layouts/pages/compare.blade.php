@extends('frontEnd.layouts.master')
@section('title', 'My Compare')
@section('content')
    <section class="customer-section">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="wishlist-content">
                        <h5 class="account-title">My Compare</h5>
                        <div class="vcart-inner">
                            <div class="vcart-content" id="compare">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Image</th>
                                                <th>Product</th>
                                                <th>Qty</th>
                                                <th>Price</th>
                                                <th>Cart</th>
                                                <th>Remove</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $value)
                                                <tr>
                                                    <td><img src="{{ asset($value->options->image) }}" alt=""></td>
                                                    <td><a
                                                            href="{{ route('product', $value->options->slug) }}">{{ $value->name }}</a>
                                                    </td>
                                                    <td>{{ $value->qty }}</td>
                                                    <td>{{ $value->price }} ৳</td>
                                                    <td class="compare-cart"><a href="{{ route('product', $value->options->slug) }}" class="wcart-btn addcartbutton"><i data-feather="shopping-cart"></i></a></td>
                                                    <td><button class="remove-cart compare_remove"
                                                            data-id="{{ $value->rowId }}"><i
                                                                class="fas fa-times"></i></button></td>
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
        </div>
    </section>
@endsection
