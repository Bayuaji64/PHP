<div class="list-group">
        @foreach($following as $follow)
        <a href="/post/{{$follow->userBeingTheFollowed->username}}" class="list-group-item list-group-item-action">
          <img class="avatar-tiny" src="{{$follow->userBeingTheFollowed->avatar}}" />
          {{-- <strong>{{$post->title}}</strong> on {{$post->created_at->format('n/j/Y')}} --}}

          {{$follow->userBeingTheFollowed->username}}
        </a>
        @endforeach
      </div>