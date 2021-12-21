@extends('layout')

@section('title', 'Checkout')

@section('extra-css')

<script src="https://js.stripe.com/v3/"></script>

@endsection

@section('content')

    <div class="container">

        <h1 class="checkout-heading stylish-heading">Checkout</h1>

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

        <div class="checkout-section">

            <div>
                <form action="{!! route('checkout.store') !!}" method="post" id="payment-form">
                  @csrf
                    <h2>Billing Details</h2>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        @if (auth()->user())
                            <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" required readonly>
                        @else
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        @endif
                        
                        
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        @if (auth()->user())
                            <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}" required readonly>
                        @else
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        @endif
                        
                        
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" required>
                    </div>

                    <div class="half-form">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="province">Province</label>
                            <input type="text" class="form-control" id="province" name="province" value="{{ old('province') }}" required>
                        </div>
                    </div> <!-- end half-form -->

                    <div class="half-form">
                        <div class="form-group">
                            <label for="postalcode">Postal Code</label>
                            <input type="text" class="form-control" id="postalcode" name="postalcode" value="{{ old('postalcode') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                        </div>
                    </div> <!-- end half-form -->

                    <div class="spacer"></div>

                    <h2>Payment Details</h2>

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name_on_card" name="name_on_card" value="{{ old('name_on_card') }}" required>
                        </div>

                        <div class="form-group">
                          <label for="card-element">
                            Credit or debit card
                          </label>
                          <div id="card-element">
                            <!-- A Stripe Element will be inserted here. -->
                          </div>

                          <!-- Used to display Element errors. -->
                          <div id="card-errors" role="alert"></div>
                        </div>

                        {{-- <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="">
                        </div>

                        <div class="half-form">
                            <div class="form-group">
                                <label for="expiry">Expiry</label>
                                <input type="text" class="form-control" id="expiry" name="expiry" placeholder="MM/DD">
                            </div>
                            <div class="form-group">
                                <label for="cvc">CVC Code</label>
                                <input type="text" class="form-control" id="cvc" name="cvc" value="">
                            </div>
                        </div> <!-- end half-form --> --}}

                        <div class="spacer"></div>

                        <input type="submit" class="button-primary full-width" id="complete-order" value="Complete Order">
                    </form>

            </div>



            <div class="checkout-table-container">
                <h2>Your Order</h2>

                <div class="checkout-table">

                    @foreach (Cart::content() as $item)
                      <div class="checkout-table-row">
                          <div class="checkout-table-row-left">
                              <img src="{{ productImage($item->model->image) }}" alt="item" class="checkout-table-img">
                              <div class="checkout-item-details">
                                  <div class="checkout-table-item">{{ $item->model->name }}</div>
                                  <div class="checkout-table-description">{{ $item->model->details }}</div>
                                  <div class="checkout-table-price">{{ $item->model->price / 100 }}</div>
                              </div>
                          </div> <!-- end checkout-table -->

                          <div class="checkout-table-row-right">
                              <div class="checkout-table-quantity"> {{ $item->qty }}</div>
                          </div>
                      </div> <!-- end checkout-table-row -->
                    @endforeach
                </div> <!-- end checkout-table -->

                <div class="checkout-totals">
                    <div class="checkout-totals-left">
                        Subtotal <br>
                        @if (session()->has('coupon'))
                          Discount ({{ session()->get('coupon')['name'] }}) :
                          <form action="{!! route('coupon.destroy') !!}" method="post" style="display:inline;">
                            @csrf
                            <input type="hidden" name="_method" value="delete">
                            <button class="btn" type="submit">Remove</button>
                          </form>
                          <br>
                          <hr>
                          New Subtotal <br>
                        @endif
                        Tax <br>
                        <span class="checkout-totals-total">Total</span>

                    </div>

                    <div class="checkout-totals-right">
                        $ {{ Cart::subtotal() }} <br>
                        @if (session()->has('coupon'))
                          -${{ session()->get('coupon')['discount'] }} <br>
                          <hr>
                          stuff <br>
                        @endif
                        $ {{ Cart::tax() }} <br>
                        <span class="checkout-totals-total">$ {{ Cart::total() }}</span>

                    </div>

                    {{-- <div class="checkout-totals-right">
                        $ {{ Cart::subtotal() }} <br>
                        @if (session()->has('coupon'))
                          -${{ $discount; }} <br>
                          <hr>
                          $ {{ $newSubtotal }} <br>
                        @endif
                        $ {{ $newTax }} <br>
                        <span class="checkout-totals-total">$ {{ $newTotal }}</span>

                    </div> --}}
                </div> <!-- end checkout-totals -->

                @if (! session()->has('coupon'))
                  <a href="#" class="have-code">Have a Code?</a>
                  <div class="have-code-container">
                      <form action="{!! route('coupon.store') !!}" method="post">
                          @csrf
                          <input type="text" name="coupon_code" id="coupon_code">
                          <input type="submit" class="button" value="Apply">
                      </form>
                  </div>
                @endif

            </div>

        </div> <!-- end checkout-section -->
    </div>

@endsection

@section('extra-js')
  <script type="text/javascript">

      // Set your publishable key: remember to change this to your live publishable key in production
      // See your keys here: https://dashboard.stripe.com/apikeys
      var stripe = Stripe('pk_test_51K1HnjIniS9fPN84H5hIQDfcaNQFsHaKuiAWAKwswRYI5VgSsJSrzMwfy8jLcb4C6QXiEjEXxPfobFQ02QHla9nk00Tm06bkk2');
      var elements = stripe.elements();
      // Custom styling can be passed to options when creating an Element.
      var style = {
        base: {
        // Add your base input styles here. For example:
        fontSize: '16px',
        color: '#32325d',
        },
      };

      // Create an instance of the card Element.
      var card = elements.create('card', {
        style: style,
        hidePostalCode: true
      });

      // Add an instance of the card Element into the `card-element` <div>.
      card.mount('#card-element');


      // Create a token or display an error when the form is submitted.
      var form = document.getElementById('payment-form');
      form.addEventListener('submit', function(event) {
        event.preventDefault();

        // Disable submit button to prevent repeated clicks
        document.getElementById('complete-order').disabled = true;

        var options = {
          name: document.getElementById('name_on_card').value,
          address_line1: document.getElementById('address').value,
          address_city: document.getElementById('city').value,
          address_state: document.getElementById('province').value,
          address_zip: document.getElementById('postalcode').value
        }

        stripe.createToken(card, options).then(function(result) {
          if (result.error) {
            // Inform the customer that there was an error.
            var errorElement = document.getElementById('card-errors');
            errorElement.textContent = result.error.message;

            // Enable submit button if there's an error
            document.getElementById('complete-order').disabled = false;
          } else {
            // Send the token to your server.
            stripeTokenHandler(result.token);
          }
        });
      });


      function stripeTokenHandler(token) {
      // Insert the token ID into the form so it gets submitted to the server
      var form = document.getElementById('payment-form');
      var hiddenInput = document.createElement('input');
      hiddenInput.setAttribute('type', 'hidden');
      hiddenInput.setAttribute('name', 'stripeToken');
      hiddenInput.setAttribute('value', token.id);
      form.appendChild(hiddenInput);

      // Submit the form
      form.submit();
    }


  </script>
@endsection
