<!DOCTYPE html>
<html class="ls-theme-turquoise">
  <head>
    <title>
      @yield('page_title')
    </title>

    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="description" content="@yield('page_descriptio')">

    @include('layouts.web.head-assets')

  </head>
  <body>

    @yield('topbar')
    @yield('sidebar')

    @yield('main-start')

      @yield('main')

    @yield('main-end')

    @include('layouts.web.footer-assets')
  </body>
</html>
