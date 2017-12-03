@extends('layouts.web.base')

@section('topbar')
    @include('layouts.web.topbar')
@endsection

@section('sidebar')
    @include('layouts.web.sidebar')
@endsection

@section('main-start')
  {!! '<main class="ls-main">' !!}
@endsection

@section('main-end')
  @include('layouts.web.footer')
  {!! '</main>' !!}
@endsection
