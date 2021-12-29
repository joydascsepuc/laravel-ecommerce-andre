<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CheckoutRequest;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlaced;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (! Cart::count()) {
            return redirect()->route('shop.index');
        }

        if (auth()->user() && request()->is('guestcheckout')) {
            return redirect()->route('checkout.index');
        }

        $tax = config('cart.tax') / 100;
        $discount = session()->get('coupon')['discount'] ?? 0;
        $newSubtotal = (float)Cart::subtotal() - (float)$discount;
        $newTax = $newSubtotal * (float)$tax;
        $newTotal = $newSubtotal * (1 + (float)$tax);

        return view('checkout')->with([
          'discount' => $discount,
          'newSubtotal' => $newSubtotal,
          'newTax' => $newTax,
          'newTotal' => $newTotal,
        ]);

        return view('checkout');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CheckoutRequest $request)
    {

        // Check the racing conditiion
        if ($this->productsAreNoLongerAvaibale()) {
            return back()->withErrors('Sorry! One item in your cart are no longer available.!');
        }

        $contents = Cart::content()->map(function ($item){
            return $item->model->slug.', '.$item->qty;
        })->values()->toJson();
        try {

          $charge = Stripe::charges()->create([
                'amount' => Cart::total(),
                'currency' => 'USD',
                'source' => $request->stripeToken,
                'description' => 'Order',
                'receipt_email' => $request->email,
                'metadata' => [
                    'contents' => $contents,
                    'quantity' => Cart::instance('default')->count()
                ],
            ]);

            // Success Message
            // return back()->with('success_message', 'Your payment is accepted!');
            // dd($charge);

            
            // ON SUCCESS ACTIONS
            $order = $this->addToOrdersTable($request, null);
            Mail::send(new OrderPlaced($order));

            // Foreach products deduct the quantity in the stock
            $this->decreaseQuantites();

            // Destroy Cart Instance 
            Cart::instance('default')->destroy();

            return redirect()->route('confirmation.index')->with('success_message', 'Your payment is accepted!');

        } catch (\Exception $e) {
            $this->addToOrdersTable($request, $e->getMessage());
            return back()->withErrors('Error! '. $e->getMessage());
        }

    }

    protected function addToOrdersTable($request, $errors){

        // Insert Into the order table
        $order = Order::create([
            'user_id' => auth()->user() ? auth()->user()->id : null,
            'billing_email' => $request->email,
            'billing_name' => $request->name,
            'billing_address' => $request->address,
            'billing_city' => $request->city,
            'billing_province' => $request->province,
            'billing_postalcode' => $request->postalcode,
            'billing_phone' => $request->phone,
            'billing_name_on_card' => $request->name_on_card,
            'billing_discount' => getNumbers()->get('discount'),
            'billing_discount_code' => getNumbers()->get('code'),
            'billing_subtotal' => getNumbers()->get('newSubtotal'),
            'billing_tax' => getNumbers()->get('newTax'),
            'billing_total' => getNumbers()->get('newTotal'),
            'error' => $errors,
        ]);

        // Insert Into order_product table
        foreach (Cart::content() as $item) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $item->model->id,
                'quantity' => $item->qty,
            ]);
        }

        return $order;
    }

    protected function decreaseQuantites()
    {
        foreach (Cart::content() as $item) {
            $product = Product::find($item->model->id);
            $product->update(['quantity' => $product->quantity - $item->qty]);
        }
    }

    protected function productsAreNoLongerAvaibale(){
        foreach (Cart::content() as $item) {
            $product = Product::find($item->model->id);
            
            if ($product->quantity < $item->qty) {
                return true;
            }
        }

        return false;
    }

}
