@extends('layouts.web')

@section('page_title', 'Dashboard - CRM')

@section('page_html_class', 'ls-html-nobg')

@section('main')

<div class="container-fluid">
  <h1 class="ls-title-intro ls-ico-dashboard">Dashboard</h1>

  @include('dashboard._opportunities_info', [ 'opportunities' => $opportunities ])
  @include('dashboard._opportunities_chart', [ 'chart' => $chart ])

</div>

@endsection

@section('page_custom_assets')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.js"></script>

  <script>
    $(document).ready(function(){
      start_opportunities_chart();
    });
  </script>
@endsection
