@extends('default')

@block('title', 'Framework')

@block('content')
  <h1>Home</h1>
  <p>Bienvenue {{ session('user') }}</p>
@endblock
