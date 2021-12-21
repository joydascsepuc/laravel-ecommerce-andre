<header>
    <div class="top-nav container">
        <div class="top-menu-left">
            <div class="logo"><a href="{{ route('mainPage') }}">Ecommerce</a></div>
            @if (! (request()->is('checkout') || request()->is('guestcheckout')))
                {{ menu('main','partials.menus.main') }}
            @endif
        </div>
        <div class="top-menu-right">
            @if (! (request()->is('checkout') || request()->is('guestcheckout')))
                @include('partials.menus.main-right')
            @endif
        </div>
        
    </div> <!-- end top-nav -->
</header>
