<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Category;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if(request()->category){
          $products = Product::with('categories')->whereHas('categories', function ($query) {
              $query->where('slug', request()->category);
          })->get();
          $categories = Category::all();
          $categoryAllField = Category::where('slug', request()->category)->first();
          $categoryName = $categoryAllField->name;
      }else{
          $products = Product::inRandomOrder()->take(12)->get();
          $categories = Category::all();
          $categoryName = 'Featured';
      }

      return view('shop')->with([
        'products' => $products,
        'categories' => $categories,
        'categoryName' => $categoryName
      ]);
    }

    public function show($slug='')
    {
      $product = Product::where('slug', $slug)->firstOrFail();
      $mightLike = Product::where('slug', '!=' , $slug)->MightAlsoLike()->get();
      return view('product')->with([
        'product' => $product,
        'mightLike' => $mightLike
      ]);
    }

}
