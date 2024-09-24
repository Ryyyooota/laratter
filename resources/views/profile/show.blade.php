<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('User詳細') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <!-- フォローフォロワー数表示 -->
          <p>following: {{ $user->follows->count() }}</p>
          <p>followers: {{ $user->followers->count() }}</p>

          <!-- 2カラムのレイアウト -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-6">
            <!-- 自分のツイート表示（左側） -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
              <h2 class="text-xl font-bold mb-4">MyTweet</h2>
              @if ($myTweets->count())
              <div class="mb-4">
                {{ $myTweets->appends(request()->input())->links('pagination::simple-tailwind', ['pageName' => 'myTweetsPage']) }}
              </div>

              @foreach ($myTweets as $tweet)
              <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-600 rounded-lg">
                <p class="text-gray-800 dark:text-gray-300">{{ $tweet->tweet }}</p>
                <a href="{{ route('tweets.show', $tweet) }}" class="text-blue-500 hover:text-blue-700">詳細を見る</a>
              </div>
              @endforeach

              <div class="mt-4">
                {{ $myTweets->appends(request()->input())->links('pagination::simple-tailwind', ['pageName' => 'myTweetsPage']) }}
              </div>
              @else
              <p>No tweets found.</p>
              @endif
            </div>

            <!-- フォロワーのツイート表示（右側） -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
              <h2 class="text-xl font-bold mb-4">Follower'sTweet</h2>
              @if ($followerTweets->count())
              <div class="mb-4">
                {{ $followerTweets->appends(request()->input())->links('pagination::simple-tailwind', ['pageName' => 'followerTweetsPage']) }}
              </div>

              @foreach ($followerTweets as $tweet)
              <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-600 rounded-lg">
                <p class="text-gray-800 dark:text-gray-300">{{ $tweet->tweet }}</p>
                <a href="{{ route('profile.show', $tweet->user) }}">
                  <p class="text-gray-600 dark:text-gray-400 text-sm">投稿者: {{ $tweet->user->name }}</p>
                </a>
                <a href="{{ route('tweets.show', $tweet) }}" class="text-blue-500 hover:text-blue-700">詳細を見る</a>
              </div>
              @endforeach

              <div class="mt-4">
                {{ $followerTweets->appends(request()->input())->links('pagination::simple-tailwind', ['pageName' => 'followerTweetsPage']) }}
              </div>
              @else
              <p>No tweets from followers found.</p>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

</x-app-layout>

