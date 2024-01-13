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
      @if isLogged()
        <li><a href="/logout">({{ session('user') }}) Logout</a></li>
      @else
        <li><a href="/login">Login</a></li>
      @endif
    </ul>
  </div>
  <div>
    @show('content')
  </div>
</body>
</html>
