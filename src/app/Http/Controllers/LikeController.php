<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class LikeController extends Controller
{
    public function store(Product $product)
    {
        $product->likers()->syncWithoutDetaching(auth()->id());
        return back();
    }
    public function destroy(Product $product)
    {
        $product->likers()->detach(auth()->id());
        return back();
    }
}
