<x-profile :sharedData="$sharedData" doctitle="{{$sharedData['username']}}'s Followers">
     {{-- <div class="list-group">
     @foreach ($followers as $follow)
     <a href="/profile/{{$follow->userDoingTheFollowing->username}}" class="list-group-item list-group-item-action">
          <img class="avatar-tiny" src="{{$follow->userDoingTheFollowing->avatar}}" />
          {{-- <strong>{{$post->title}}</strong> on {{$post->created_at->format('n/j/Y')}} --}}

          {{-- {{$follow->userDoingTheFollowing->username}}
        </a> --}}
    {{-- @endforeach
     </div> --}}
     @include('profile-followers-only')

</x-profile>


{{-- <x-profile :sharedData="$sharedData">
  <div class="list-group">
        @foreach($posts as $post)
        <a href="/post/{{$post->id}}" class="list-group-item list-group-item-action">
          <img class="avatar-tiny" src="{{$post->userData->avatar}}" />
          <strong>{{$post->title}}</strong> on {{$post->created_at->format('n/j/Y')}}
        </a>
        @endforeach
      </div>
</x-profile> --}}