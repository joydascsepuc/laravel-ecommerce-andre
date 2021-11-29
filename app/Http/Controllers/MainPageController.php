<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;

class MainPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::inRandomOrder()->take(8)->get();

        return view('main')->with('products', $products);
    }
}
