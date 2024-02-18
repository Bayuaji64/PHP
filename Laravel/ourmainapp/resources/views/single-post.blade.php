{{-- @include('header') --}}

<x-layout>
 <div class="container py-md-5 container--narrow">
      <div class="d-flex justify-content-between">
        <h2>{{$postData->title}}</h2>

      @can('update', $postData)
          

        <span class="pt-2">
          <a href="/post/{{$postData->id}}/edit" class="text-primary mr-2" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>
          <form class="delete-post-form d-inline" action="/post/{{$postData->id}}" method="POST">
            @csrf
            @method('DELETE')
            <button class="delete-post-button text-danger" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash"></i></button>
          </form>
        </span>

           @endcan
      </div>

      <p class="text-muted small mb-4">
        <a href="#"><img class="avatar-tiny" src="{{$postData->userData->avatar}}" /></a>
        Posted by <a href="#">{{$postData -> userData-> username}}</a> on {{$postData -> created_at-> format('n/j/Y')}}
      </p>

      <div class="body-content">


        {!!$postData -> body!!}
        {{-- <p>My roommate yells at me when I destroy things, but I do what I want.</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Numquam praesentium laboriosam unde fuga accusamus reiciendis laudantium quis consequatur, beatae temporibus nemo, tempora voluptatum, perspiciatis accusantium ullam molestiae cupiditate incidunt architecto.</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Numquam praesentium laboriosam unde fuga accusamus reiciendis laudantium quis consequatur, beatae temporibus nemo, tempora voluptatum, perspiciatis accusantium ullam molestiae cupiditate incidunt architecto.</p> --}}
      </div>
    </div>
</x-layout>

   
