<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

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
