<?php use Thiarson\Framework\Templates\Form\Form; ?>

<h1>Login</h1>

<?php

  $form = Form::begin('post', '');

  $form->field($model, 'email')->emailField();
  $form->field($model, 'password')->passwordField();

  Form::end('Se connecter');

?>
