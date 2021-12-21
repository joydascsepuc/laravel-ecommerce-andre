<ul>
    <li><a href="{{ route('register') }}">Signup</a></li>
    <li><a href="{{ route('login') }}">Login</a></li>
    <li><a href="{{ route('cart.index') }}">Cart
      <span class="cart-count">
        @if(Cart::instance('default')->count() > 0)
          <span>{{ Cart::instance('default')->count() }}</span>
        @endif
      </span>
    </a></li>
</ul>