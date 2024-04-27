<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {

    }

    public function create()
    {
        return view('posts.create');
    }
    public function store()
    {
        $data=\request()->validate([
            'caption'=>'required',
            'image'=>['required','image']
        ]);

        $image_path=\request('image')->store('uploads','public');
//        dd(auth()->user()->posts());
        auth()->user()->posts()->create([
            'caption'=>$data['caption'],
            'image'=>$image_path
        ]);

        return redirect('/profile/'.auth()->user()->id);
    }
    public function show(Post $post)
    {
        return view('posts.show',compact('post'));
    }
}
