<?php use Thiarson\Framework\Templates\Form\Form; ?>

<h1>Login</h1>

<?php

  $form = Form::begin('post', '');

  echo $form->field($model, 'email')->emailField();
  echo $form->field($model, 'password')->passwordField();

  Form::end('Se connecter');

?>
