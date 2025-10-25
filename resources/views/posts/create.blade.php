<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
  <a href="{{  route('posts.index')}}" >back</a>
  <form method="POST" action="{{route('posts.store')}}">
    @csrf
  <label>
title
  </lable>
  <input type="text" name="title">
  @error("title")
  <p>{{ $message }}</p>
  @enderror
    <label> <br>
  body:
  </lable>
  <input type="text" name="body">
  @error("title")
  <p>{{ $message }}</p>
  @enderror <br>
  <button type="submit">submit</button>
</form>
</body>
</html