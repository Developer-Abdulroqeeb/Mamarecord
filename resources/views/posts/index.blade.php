<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel CRUD</title>
    </head>
    <body>
        <div class="container">
        <a href="{{ route('posts.create')}}">creat post</a>
            <h1>see Posting</h1>
            <table>
                <tr>
                    <th>id</th>
                    <th>title</th>
                    <th>body</th>
                    <th>edit</th>
                </tr>
                @foreach ($posts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->body }}</td>
                        <td>
                            <a href="{{route('posts.show', $post->id)}}">view</a>
                            <a href="{{route('posts.edit', $post->id)}}">Edit</a>
                        </td>
                       <td>
                        <form  method="post" action="{{ route("posts.destroy", $post->id) }}">
                            @csrf
                            @method("DELETE")
                        <button type="submit">Delete</button>
                        </form>
                    </td>
                    </tr>
                @endforeach
            </table>

            <!-- Pagination links -->
            {{ $posts->links() }}
        </div>
    </body>
</html>
