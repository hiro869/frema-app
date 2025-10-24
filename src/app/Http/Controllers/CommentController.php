<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;


class CommentController extends Controller
{
    public function store(CommentRequest $request, Product $product)
    {
        $validated = $request->validated();

        Comment::create([
            'product_id' => $product->id,
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        return back()->with('status','コメントを投稿しました。');
    }
}
