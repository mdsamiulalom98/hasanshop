@php
    $data = Cart::instance('wishlist')->content();
@endphp
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
                    <td><a href="{{ route('product', $value->options->slug) }}" class="wcart-btn addcartbutton"><i data-feather="shopping-cart"></i></a></td>
                    <td><button class="remove-cart wishlist_remove"
                            data-id="{{ $value->rowId }}"><i
                                class="fas fa-times"></i></button></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>