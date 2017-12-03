@extends('layouts.web')

@section('page_title', 'Filtro de Oportunidades - CRM')

@section('page_html_class', '')

@section('page_custom_metas')
  <meta name="csrf-token" content="{{ csrf_token()  }}">
@endsection

@section('main')

  <div class="container-fluid">
    <h1 class="ls-title-intro ls-ico-search">Filtro de Oportunidades</h1>
    @include('opportunity._form')
    <hr>
    <div id="filter_result">
      <!-- include('opportunity._filter_result', [ 'opportunities' => $opportunities ]) -->
    </div>

  </div>

@endsection

@section('page_custom_assets')
  <script type="text/javascript" src="{{ asset('js/opportunity.js') }}"></script>

  <script>
Application.opportunity.init({
  filter_route: '{{ route('opportuninies_filter') }}',
  filter_content: '#filter_result',
  star_onInit: 'true',
});
  </script>

@endsection
