<?php use Thiarson\Framework\Application; ?>

<h1>Home</h1>
<p>Bienvenue <?= Application::$session->get('user') ?></p>
