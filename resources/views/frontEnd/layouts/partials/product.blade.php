<div class="product_item_inner">
    <div class="wish-compare">
        <div class="wish-compare-inner">
            <button data-id="{{ $value->id }}" class="hover-zoom compare_store"
                title="Compare"><i class="fa-solid fa-sliders"></i></button>
            <button data-id="{{ $value->id }}" class="hover-zoom wishlist_store"
                title="Wishlist"><i class="fa-regular fa-heart"></i></button>
        </div>
    </div>
    @if ($value->old_price)
        <div class="discount">
            <p>@php $discount=(((($value->old_price)-($value->new_price))*100) / ($value->old_price)) @endphp {{ number_format($discount, 0) }}% Discount</p>
        </div>
    @endif
    <div class="pro_img">
        <a href="{{ route('product', $value->slug) }}">
            <img src="{{ asset($value->image ? $value->image->image : '') }}"
                alt="{{ $value->name }}" />
        </a>
    </div>
    <div class="pro_des">
        <div class="pro_name">
            <a href="{{ route('product', $value->slug) }}">{{ Str::limit($value->name, 80) }}</a>
        </div>
        <div class="pro_price">
            @if ($value->variable_count > 0 && $value->type == 0)
                <p>
                    @if ($value->variable->old_price)
                        <del>৳ {{ $value->variable->old_price }}</del>
                    @endif
                    ৳ {{ $value->variable->new_price }}
                </p>
            @else
                <p>
                    @if ($value->old_price)
                        <del>৳ {{ $value->old_price }}</del>
                    @endif
                    ৳ {{ $value->new_price }}
                </p>
            @endif
        </div>
        <div class="pro_btn">
            @if ($value->variable_count > 0 && $value->type == 0)
            <div class="cart_btn">
               <a href="{{ route('product', $value->slug) }}" data-id="{{ $value->id }}" class="addcartbutton"><i class="fa-solid fa-shopping-cart"></i> Order Now</a>
            </div>
            @else
            <div class="cart_btn">
                <form action="{{ route('cart.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $value->id }}" />
                    <input type="hidden" name="qty" value="1" />
                    <button type="submit"><i class="fa-solid fa-shopping-cart"></i> Order Now</button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>