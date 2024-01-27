<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@show('title')</title>
</head>
<body>
  <div>
    <ul>
      <li><a href="{{route('home.landing')}}">Landing</a></li>
    </ul>
  </div>
  <div>
    @show('content')
  </div>
</body>
</html>
