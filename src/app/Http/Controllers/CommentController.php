<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'body' => ['required','string','max:255'],
        ],[
            'body.required' => 'コメントを入力してください。',
            'body.max' => 'コメントは255文字以内で入力してください。'
        ]);

        Comment::create([
            'product_id' => $product->id,
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        return back()->with('status','コメントを投稿しました。');
    }
}
