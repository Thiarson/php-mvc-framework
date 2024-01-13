@extends('default')

@block('title', 'Home')

@block('content')
  <h1>Home</h1>
  <p>Bienvenue {{ session('user') }}</p>
@endblock
