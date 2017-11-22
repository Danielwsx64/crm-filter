@extends('layouts.web')

@section('page_title', 'Page title')

@section('main')

<div class="container-fluid">
  <h1 class="ls-title-intro ls-ico-home">Página inicial</h1>
  <p>the name is {{ $user->user_name }}</p>


</div>

@endsection
