<x-layout doctitle="Editing: {{$postData->title}}">
<div class="container py-md-5 container--narrow">
      <form action="/post/{{$postData->id}}" method="POST">
        <p><small><strong><a href="/post/{{$postData->id}}">&laquo; Back to post permalink</a></strong></small></p>
        @csrf
        @method('PUT')
        <div class="form-group">
          <label for="post-title" class="text-muted mb-1"><small>Title</small></label>
          <input value="{{old('title',$postData->title)}}"name="title" id="post-title" class="form-control form-control-lg form-control-title" type="text" placeholder="" autocomplete="off" />
          
          @error('title')

          <p class="m-0 small alert alert-danger shadow-sm">{{$message}}</p>
              
          @enderror
        </div>

        <div class="form-group">
          <label for="post-body" class="text-muted mb-1"><small>Body Content</small></label>
          <textarea  name="body" id="post-body" class="body-content tall-textarea form-control" type="text">  {{old('body',$postData->title) }}</textarea>
           @error('body')

          <p class="m-0 small alert alert-danger shadow-sm">{{$message}}</p>
              
          @enderror
        </div>

        <button class="btn btn-primary">Save Changes</button>
      </form>
    </div>
</x-layout>