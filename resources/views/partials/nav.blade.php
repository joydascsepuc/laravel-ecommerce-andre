<header>
    <div class="top-nav container">
        <div class="top-menu-left">
            <div class="logo"><a href="{{ route('mainPage') }}">Laravel Ecommerce</a></div>
            @if (! request()->is('checkout'))
                {{ menu('main','partials.menus.main') }}
            @endif
        </div>
        <div class="top-menu-right">
            @include('partials.menus.main-right')
        </div>
        
    </div> <!-- end top-nav -->
</header>
