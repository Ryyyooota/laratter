<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Tweet;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }


    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
 

    
    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // 認証ユーザーが自分自身の場合
         if (auth()->user()->is($user)) {
            // 自分のツイート
            $myTweets = Tweet::where('user_id', $user->id)
                ->latest()
                ->paginate(10, ['*'], 'myTweetsPage');

        // フォローしているユーザーのツイート
            $followerTweets = Tweet::whereIn('user_id', $user->follows->pluck('id'))
                ->latest()
                ->paginate(10, ['*'], 'followerTweetsPage');
        } else {
        // 他のユーザーの場合、そのユーザーのツイートのみを取得
            $myTweets = $user->tweets()->latest()->paginate(10, ['*'], 'myTweetsPage');
            $followerTweets = collect(); // フォロワーのツイートは取得しない
        }

        // ユーザーのフォロワーとフォローしているユーザーを取得
        $user->load(['follows', 'followers']);

        return view('profile.show', compact('user', 'myTweets', 'followerTweets'));
    }


    public function showFollowers(User $user)
    {
        // 指定されたユーザーのフォロワーを取得
        $followers = $user->followers()->get();  // フォロワーのリストを取得

        // ビューにユーザーとフォロワー一覧を渡す
        return view('profile.followers', compact('user', 'followers'));
    }

    public function showFollowing(User $user)
    {
        // 指定されたユーザーのフォロワーを取得
        $following = $user->follows()->get();  // フォロワーのリストを取得

        // ビューにユーザーとフォロワー一覧を渡す
        return view('profile.following', compact('user', 'following'));
    }
    
}
