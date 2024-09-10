<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;

class TweetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //ツイート一覧を押された時の処理
        $tweets = Tweet::with('user')->latest()->get();
        return view('tweets.index', compact('tweets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //ツイート作成が押された時の処理
        return view('tweets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //データの保存
         $request->validate([
         'tweet' => 'required|max:255',
         ]);

         $request->user()->tweets()->create($request->only('tweet'));

         return redirect()->route('tweets.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tweet $tweet)
    {
        //詳細画面の表示処理
        //dd($tweet);
        return view('tweets.show', compact('tweet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tweet $tweet)
    {
        //編集ボタンを押された時の処理
        return view('tweets.edit', compact('tweet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tweet $tweet)
    {
        //編集→更新する際の処理
        //dd($request->all(), $tweet);
        $request->validate([
         'tweet' => 'required|max:255',
        ]);

        $tweet->update($request->only('tweet'));

        return redirect()->route('tweets.show', $tweet);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tweet $tweet)
    {
        //ツイートの削除
        {
           $tweet->delete();

           return redirect()->route('tweets.index');
        }
    }
}
