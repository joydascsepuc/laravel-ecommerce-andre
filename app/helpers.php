<?php

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
        $stockLevel = 'In Stock';
      }else if($quantity < 5){
        $stockLevel = 'Low Stock';
      } else if($quantity == 0){
        $stockLevel = 'Out Of Stock';
      }

    return $stockLevel;
}