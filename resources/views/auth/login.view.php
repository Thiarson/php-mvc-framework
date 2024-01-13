@extends('auth')

@block('title', 'Login')

@block('content')
  <h1>Login</h1>
  <?php
    use Thiarson\Framework\Templates\Form\Form;

    $form = new Form('post', '');

    $form->begin('post', '');
    $form->field($model, 'email')->emailField();
    $form->field($model, 'password')->passwordField();
    $form->end('Se connecter');
  ?>
@endblock
