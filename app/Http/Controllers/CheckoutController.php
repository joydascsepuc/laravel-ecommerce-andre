<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CheckoutRequest;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Cartalyst\Stripe\Laravel\Facades\Stripe;

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

        // return view('checkout')->with([
        //   'discount' => $discount,
        //   'newSubtotal' => $newSubtotal,
        //   'newTax' => $newTax,
        //   'newTotal' => $newTotal,
        // ]);

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

            return redirect()->route('confirmation.index')->with('success_message', 'Your payment is accepted!');

        } catch (\Exception $e) {

            return back()->withErrors('Error! '. $e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
