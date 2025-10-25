<!DOCTYPE html>
<html lang="en">
<head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <meta http-equiv="X-UA-Compatible" content="ie=edge">
                    <title>Document</title>
</head>
<body>
      <h1>create</h1>     
      <form action="{{route("car.store")}}" method="POST">
            @csrf
            <input type="text" name="ProductName" placeholder="ProductName">
            <input type="text" name="ProductType" placeholder="ProductType">
            <input type="text" name="Version" placeholder="Version">
            <button type="submit" name="submit">Save</button>
      </form> 
      <div>
    @foreach ($goods as $product)
        <h1>{{$product->ProductName}}</h1>   
        <h1>{{$product->ProductType}}</h1> 
        <h1>{{$product->Version}}</h1>  
        <a href="{{route("car.update", $product->id)}}">edit</a>  
        <form action="{{route("car.destroy", $product->id)}}" method="POST">
                    @method("DELETE")
                    @csrf
                  
                    <button name="submit" type="submit">Delete</button></form>          
    @endforeach                
</div>        
</body>
</html>