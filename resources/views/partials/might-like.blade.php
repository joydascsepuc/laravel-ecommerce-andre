<div class="might-like-section">
    <div class="container">
        <h2>You might also like...</h2>
        <div class="might-like-grid">
            @foreach ($mightLike as $key => $product)
              <a href="{!! route('shop.show', $product->slug) !!}" class="might-like-product">
                  <img src="{{ asset('storage/'.$product->image) }}" alt="product">
                  <div class="might-like-product-name">{{ $product->name }}</div>
                  <div class="might-like-product-price">${{ $product->price / 100 }}</div>
              </a>
            @endforeach
        </div>
    </div>
</div>
