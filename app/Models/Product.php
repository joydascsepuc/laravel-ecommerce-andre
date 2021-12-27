<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// This one for is OLD
use Nicolaslopezj\Searchable\SearchableTrait;

// This one is Laravel Scout with algolia
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory;

    // For Nicolaslopezj\Searchable
    use SearchableTrait;

    // For Scount and Algolia
    use Searchable;

     /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'products.name' => 10,
            'products.details' => 5,
            'products.description' => 2,
        ],
    ];

    // public function PresentPrice()
    // {
    //   return money_format('$%i', $this->price / 100);
    // }

    public function scopeMightAlsoLike($query)
    {
      return $query->inRandomOrder()->take(4);
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category');
    }

}
