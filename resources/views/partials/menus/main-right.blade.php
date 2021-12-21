<ul>
    @guest
      <li><a href="{{ route('register') }}">Signup</a></li>
      <li><a href="{{ route('login') }}">Login</a></li>
    @else
      <li>
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
            {{ __('Logout') }}
        </a>
      </li>

      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
          @csrf
      </form>
    @endguest
    
    <li><a href="{{ route('cart.index') }}">Cart
      <span class="cart-count">
        @if(Cart::instance('default')->count() > 0)
          <span>{{ Cart::instance('default')->count() }}</span>
        @endif
      </span>
    </a></li>
</ul>