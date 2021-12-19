@extends('layout')

@section('title', 'Shopping Cart')

@section('extra-css')

@endsection

@section('content')

    <div class="breadcrumbs">
        <div class="container">
            <a href="#">Home</a>
            <i class="fa fa-chevron-right breadcrumb-separator"></i>
            <span>Shopping Cart</span>
        </div>
    </div> <!-- end breadcrumbs -->

    <div class="cart-section container">
        <div>
          @if (session()->has('success_message'))
              <div class="spacer"></div>
              <div class="alert alert-success">
                  {{ session()->get('success_message') }}
              </div>
          @endif

          @if(count($errors) > 0)
              <div class="spacer"></div>
              <div class="alert alert-danger">
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{!! $error !!}</li>
                      @endforeach
                  </ul>
              </div>
          @endif

          @if (Cart::count() > 0)

            <h2> {{ Cart::count() }} item(s) in Shopping Cart</h2>

            <div class="cart-table">
                @foreach (Cart::content() as $item)
                    <div class="cart-table-row">
                        <div class="cart-table-row-left">
                            <a href="{!! route('shop.show', $item->model->slug) !!}"><img src="{{ asset('storage/'.$item->model->image) }}" alt="item" class="cart-table-img"></a>
                            <div class="cart-item-details">
                                <div class="cart-table-item"><a href="{!! route('shop.show', $item->model->slug) !!}">{{ $item->model->name }}</a></div>
                                <div class="cart-table-description">{{ $item->model->details }}</div>
                            </div>
                        </div>
                        <div class="cart-table-row-right">
                            <div class="cart-table-actions">
                                <form class="form" action="{!! route('cart.destroy', $item->rowId) !!}" method="POST">
                                  @csrf
                                  <input type="hidden" name="_method" value="delete">
                                  <button type="submit" class="cart-option">Remove</button>
                                </form>
                                <br>
                                <form class="form" action="{!! route('cart.switchToSaveForLater', $item->rowId) !!}" method="POST">
                                  @csrf
                                  <button type="submit" class="cart-option">Save for Later</button>
                                </form>
                            </div>
                            <div>
                                <select class="quantity" data-id = "{{ $item->rowId }}">
                                    @for ($i=1; $i < 6; $i++)
                                      <option {{ $item->qty == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                    {{-- <option {{ $item->qty == 2 ? 'selected' : '' }}>2</option>
                                    <option {{ $item->qty == 3 ? 'selected' : '' }}>3</option>
                                    <option {{ $item->qty == 4 ? 'selected' : '' }}>4</option>
                                    <option {{ $item->qty == 5 ? 'selected' : '' }}>5</option> --}}
                                </select>
                            </div>
                            <div>$ {{ $item->subtotal }}</div>
                        </div>
                    </div> <!-- end cart-table-row -->
                @endforeach
            </div> <!-- end cart-table -->

            <div class="cart-totals">
                <div class="cart-totals-left">
                    Shipping is free because we’re awesome like that. Also because that’s additional stuff I don’t feel like figuring out :).
                </div>

                <div class="cart-totals-right">
                    <div>
                        Subtotal <br>
                        Tax(10%) <br>
                        <span class="cart-totals-total">Total</span>
                    </div>
                    <div class="cart-totals-subtotal">
                        $ {{ Cart::subtotal() }} <br>
                        $ {{ Cart::tax() }} <br>
                        <span class="cart-totals-total">$ {{ Cart::total() }}</span>
                    </div>
                </div>
            </div> <!-- end cart-totals -->

            <div class="cart-buttons">
                <a href="{!! route('shop.index') !!}" class="button">Continue Shopping</a>
                <a href="{!! route('checkout.index') !!}" class="button-primary">Proceed to Checkout</a>
            </div>

          @else

            <h3>No Items in Cart</h3>
            <br>
            <a href="{!! route('shop.index') !!}" class="button">Back to Shop</a>
            <br><br>
          @endif

          @if (Cart::instance('saveForLater')->count() > 0)

            <h2> {{ Cart::instance('saveForLater')->count() }} item(s) Saved For Later</h2>

            @foreach (Cart::instance('saveForLater')->content() as $item)
              <div class="saved-for-later cart-table">
                  <div class="cart-table-row">
                      <div class="cart-table-row-left">
                          <a href="{!! route('shop.show', $item->model->slug) !!}"><img src="{{ asset('storage/'.$item->model->image) }}" alt="item" class="cart-table-img"></a>
                          <div class="cart-item-details">
                              <div class="cart-table-item"><a href="{!! route('shop.show', $item->model->slug) !!}">{{ $item->model->name }}</a></div>
                              <div class="cart-table-description">{{ $item->model->details }}</div>
                          </div>
                      </div>
                      <div class="cart-table-row-right">
                          <div class="cart-table-actions">
                              <form class="form" action="{!! route('saveForLater.destroy', $item->rowId) !!}" method="POST">
                                @csrf
                                <input type="hidden" name="_method" value="delete">
                                <button type="submit" class="cart-option">Remove</button>
                              </form>
                              <br>
                              <form class="form" action="{!! route('saveForLater.switchToCart', $item->rowId) !!}" method="POST">
                                @csrf
                                <button type="submit" class="cart-option">Move to Cart</button>
                              </form>
                          </div>
                          <div>$ {{ $item->model->price / 100}}</div>
                      </div>
                  </div> <!-- end cart-table-row -->
              </div> <!-- end saved-for-later -->
            @endforeach


          @else

            <h3>No Items is here!</h3>
            <br>
            <a href="{!! route('shop.index') !!}" class="button">Back to Shop</a>

          @endif

        </div>

    </div> <!-- end cart-section -->

    @include('partials.might-like')


@endsection


@section('extra-js')
  <script src="{!! asset('js/app.js') !!}"></script>
  <script type="text/javascript">

      const classname = document.querySelectorAll('.quantity');

      Array.from(classname).forEach(function (element){
          element.addEventListener('change', function(){
              const id = element.getAttribute('data-id');
              axios.patch('/cart/'+id, {
                  quantity: this.value,
              })
              .then(function (response) {
                  console.log(response);
                  //location.reload(); // OR
                  window.location.href = '{!! route('cart.index') !!}'
              })
              .catch(function (error) {
                  console.log(error);
                  location.reload();
              });
          });
      });

  </script>

@endsection
