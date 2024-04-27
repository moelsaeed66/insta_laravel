<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProfileController extends Controller
{
    public function index(User $user)
    {
//        dd($user->profile->image);
        $follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;
        $posts_count=Cache::remember('count_posts',now()->addSeconds(30),function () use ($user){
            return $user->posts->count();
        });

        $following_count=Cache::remember('count_following',now()->addSeconds(30),function () use ($user){
            return $user->following->count();
        });
        $followers_count=Cache::remember('count_followers',now()->addSeconds(30),function () use ($user){
            return $user->profile->followers->count();
        });
//        dd($followers_count);

        return view('profiles.index',compact('user','follows','posts_count','followers_count','following_count'));
    }
    public function edit(User $user)
    {
        $this->authorize('update',$user->profile);
//        $profile=Profile::find($user);
        return view('profiles.edit',compact('user'));
    }
    public function update(User $user)
    {
        $this->authorize('update',$user->profile);
        $data=\request()->validate([
            'title'=>'required',
            'description'=>'required',
            'image'=>['required','image'],
            'url'=>'url'
        ]);
        if(\request('image'))
        {
            $image_path=\request('image')->store('uploads','public');
            $image_array=['image'=>$image_path];

        }
        auth()->user()->profile->update(array_merge($data,$image_array??[]));

        return redirect("/profile/{$user->id}");


    }
}
