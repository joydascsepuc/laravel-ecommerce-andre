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

    public function search(Request $request)
    {

      $request->validate([
        'query' => 'required|min:3',
      ]);

      $query = $request->input('query');

      // Normal Query Search
      // $products = Product::where('name', 'like', "%$query%")
      //                     ->orWhere('details', 'like', "%$query%")
      //                     ->orWhere('description', 'like', "%$query%")->paginate(10);

      // Using nicolaslopezj/searchable
      \DB::statement("SET SQL_MODE=''"); // Disable the strict rule is not a good idea in config/database. Just Use this for one time to remove strict.
      $products = Product::search($query)->paginate(10);

      return view('search-results')->with([
          'products' => $products,
      ]);
      
    }

    public function searchAlgolia(Request $request)
    {
      return view('search-results-algolia');
    }

}
