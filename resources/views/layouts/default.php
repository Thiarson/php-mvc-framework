<?php use Database\Models\LoginModel; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Framework</title>
</head>
<body>
  <div>
    <ul>
      <?php
        if (LoginModel::isLogged())
          echo '<li><a href="/logout">Logout</a></li>';
        else
          echo '<li><a href="/login">Login</a></li>';
      ?>
    </ul>
  </div>
  <div>{{content}}</div>
</body>
</html>
