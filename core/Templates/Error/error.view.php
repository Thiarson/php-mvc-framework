@extends('error')

@block('title', 'Error')

@block('content')
  <h1><?= $exception->getCode() ?> - <?= $exception->getMessage() ?></h1>
@endblock
