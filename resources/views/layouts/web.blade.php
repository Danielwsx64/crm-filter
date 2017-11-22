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

    <link href="css/locastyle.css" rel="stylesheet" type="text/css">

    <link rel="icon" sizes="192x192" href="">
    <link rel="apple-touch-icon" href="">
  </head>
  <body>

    @include('layouts.web.topbar')
    @include('layouts.web.sidebar')

    <main class="ls-main ">

      @yield('main')

    </main>

    <!-- We recommended use jQuery 1.10 or up -->
    <script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
    <script src="js/locastyle.js" type="text/javascript"></script>
  </body>
</html>
