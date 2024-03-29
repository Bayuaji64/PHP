{{-- <x-profile :sharedData = "$sharedData" >



     <div class="list-group">
        @foreach ($posts as $post)

     <a href="/post/{{$post->id}}" class="list-group-item list-group-item-action">
          <img class="avatar-tiny" src="{{$post -> userData -> avatar}}" />
          <strong>{{$post->title}}</strong> on {{$post->created_at->format('n/j/Y')}}
        </a>
            
        @endforeach
       
        {{-- <a href="/post/5c3af3dcc7d0ad0004e53b3d" class="list-group-item list-group-item-action">
          <img class="avatar-tiny" src="https://gravatar.com/avatar/b9408a09298632b5151200f3449434ef?s=128" />
          <strong>Example Post #2</strong> on 0/13/2019
        </a>
        <a href="/post/5c3af3dcc7d0ad0004e53b3d" class="list-group-item list-group-item-action">
          <img class="avatar-tiny" src="https://gravatar.com/avatar/b9408a09298632b5151200f3449434ef?s=128" />
          <strong>Example Post #3</strong> on 0/13/2019
        </a> --}}
      {{-- </div> --}}

{{-- </x-profile>  --}}


<x-profile :sharedData="$sharedData" doctitle="Who {{$sharedData['username']}} Follows">
  {{-- <div class="list-group">
        @foreach($following as $follow)
        <a href="/post/{{$follow->userBeingTheFollowed->username}}" class="list-group-item list-group-item-action">
          <img class="avatar-tiny" src="{{$follow->userBeingTheFollowed->avatar}}" />
          {{-- <strong>{{$post->title}}</strong> on {{$post->created_at->format('n/j/Y')}} --}}

          {{-- {{$follow->userBeingTheFollowed->username}}
        </a>
        @endforeach --}}
      {{-- </div> --}}
      @include('profile-following-only')
</x-profile>