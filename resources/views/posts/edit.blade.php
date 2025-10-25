<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel CRUD</title>
    </head>
    <body>
        <div class="container">
        <h1>edit/update form</h1>


<form method="POST" action="{{route("posts.update", $post->id)}}">
@csrf
@method('PUT')
<input type="text" name="title" placeholder="title" value="{{$post->title}}">
@error('title')
<p>{{$message}} </p>
@enderror
<input type="text" name="body" placeholder="enter the body" value="{{$post->body}}">
@error('body')
  <p>{{$message}} </p>
@enderror
<button>submit</button>
     </form>
        </div>
    </body>
</html>
