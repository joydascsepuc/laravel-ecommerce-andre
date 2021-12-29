<?php

use Carbon\Carbon;


function setActiveCategory($category, $output = 'font-weight-bold'){
    return request()->category == $category ? $output : '';
}

function productImage($path){
    return $path && file_exists('storage/'.$path) ? asset('storage/'.$path) : asset('img/not-found.jpg');
}

function getNumbers()
{
    $tax = config('cart.tax') / 100;
    $discount = session()->get('coupon')['discount'] ?? 0;
    $code = session()->get('coupon')['name'] ?? null;
    $newSubtotal = ((float)Cart::subtotal() - $discount);
    if ($newSubtotal < 0) {
        $newSubtotal = 0;
    }
    $newTax = $newSubtotal * $tax;
    $newTotal = $newSubtotal * (1 + $tax);

    return collect([
        'tax' => $tax,
        'discount' => $discount,
        'code' => $code,
        'newSubtotal' => $newSubtotal,
        'newTax' => $newTax,
        'newTotal' => $newTotal,
    ]);
}

function getStockLevel($quantity)
{
    if ($quantity > 7) {
        $stockLevel = '<div class="badge badge-success">In Stock</div>';
      }else if($quantity < 5){
        $stockLevel = '<div class="badge badge-warning">Low Stock</div>';
      } else if($quantity == 0){
        $stockLevel = '<div class="badge badge-danger">Not available</div>';
      }

    return $stockLevel;
}

function presentDate($date)
{
    return Carbon::parse($date)->format('M d, Y');
}

function presentPrice($price)
{
    // return money_format('$%i', $price / 100);
    if ($price<0) return "-".asDollars(-$price);
    return '$' . number_format($price, 2);
}