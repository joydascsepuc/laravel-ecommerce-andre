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
          });
          $categories = Category::all();
          $categoryAllField = optional(Category::where('slug', request()->category))->first();
          $categoryName = optional($categoryAllField)->name;
      }else{
          // Normal Get
          // $products = Product::inRandomOrder()->take(12)->get();
          // Paginate Get
          $products = Product::where('featured', true);
          $categories = Category::all();
          $categoryName = 'Featured';
      }

      if(request()->sort == 'low_high'){
        $products = $products->orderBy('price', 'asc')->paginate(9);
      } elseif (request()->sort == 'high_low') {
        $products = $products->orderBy('price', 'desc')->paginate(9);
      } else{
        $products = $products->paginate(9);
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
