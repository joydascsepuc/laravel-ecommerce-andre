{{-- <ul>
    <li><a href="{!! route('shop.index') !!}">Shop</a></li>
    <li><a href="#">About</a></li>
    <li><a href="#">Blog</a></li>
    <li>
      <a href="{!! route('cart.index') !!}">
        Cart
        <span class="cart-count">
          @if(Cart::instance('default')->count() > 0)
            <span>{{ Cart::instance('default')->count() }}</span>
          @endif
        </span>
      </a>
    </li>
</ul> --}}


<ul>
    @foreach($items as $menu_item)
        <li>
          <a href="{{ $menu_item->link() }}">
            {{ $menu_item->title }}
            @if ( $menu_item->title === 'Cart' )
              <span class="cart-count">
                @if(Cart::instance('default')->count() > 0)
                  <span>{{ Cart::instance('default')->count() }}</span>
                @endif
              </span>
            @endif
          </a>
        </li>
    @endforeach
</ul>
