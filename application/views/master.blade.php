<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }

      @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right {
          float: none;
          padding-left: 5px;
          padding-right: 5px;
        }
      }
    </style>
    <link href="/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/css/style.css">

    @yield('css')

    @yield('header_js')

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="../assets/ico/favicon.png">
  </head>

  <body>
    @yield('canvas')

    <div class="navbar navbar-fixed-top @if (isset($admin)) navbar-inverse @endif">
      <div class="navbar-inner">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="/">Craigslist (CPSC 254)</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="/">Home</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>
            </ul>
            <form action="/search" method="get" class="navbar-search pull-left">
              <input type="text" name="q" class="search-query" placeholder="Search" value="" style="margin:0 auto;" data-provide="typeahead" data-items="4" data-source="[{{ Post::city_list() }}]" autocomplete="off">
            </form>
            @if ( Auth::check() )
              <ul class="nav pull-right">
                <li class="dropdown">
                  <a class="dropdown-toggle" href="#" data-toggle="dropdown"><i class="icon-user"></i> {{ Auth::user()->username }} <strong class="caret"></strong></a>
                  <ul class="dropdown-menu">
                    <li><a href="/account/profile"><i class="icon-user"></i> Profile</a></li>
                    <li><a href="/posts"><i class="icon-edit"></i> My Posts</a></li>
                    <li class="divider"></li>
                    @if ( Auth::user()->is_admin() || Session::has('admin') )
                    <li><a href="/admin"><i class="icon-cogs"></i> Admin</li>
                    @endif
                    <li><a href="/account/logout"><i class="icon-off"></i> Logout</a></li>
                  </ul>
                </li>
              </ul>
            @else
              <ul class="nav pull-right">
                <li><a href="/account/register">Sign Up</a></li>
                <li class="divider-vertical"></li>
                <li class="dropdown">
                  <a class="dropdown-toggle" href="/account/login" data-toggle="dropdown">Sign In <strong class="caret"></strong></a>
                  <div class="dropdown-menu" style="padding: 15px; padding-bottom: 0px;">
                    <form method="post" action="/account/login" accept-charset="UTF-8">
                      {{ Form::token() }}
                      <input style="margin-bottom: 15px;" type="text" placeholder="Username" id="username" name="username">
                      <input style="margin-bottom: 15px;" type="password" placeholder="Password" id="password" name="password">
                      <input style="float: left; margin-right: 10px;" type="checkbox" name="remember-me" id="remember-me" value="1">
                      <label class="string optional" for="user_remember_me"> Remember me</label>
                      <input class="btn btn-primary btn-block" type="submit" id="sign-in" value="Sign In">
                    </form>
                  </div>
                </li>
              </ul>
            @endif
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container-fluid">

      @yield('content')

      <hr>

      <footer>
        <p>&copy; 2013 Austin White</p>
      </footer>

    </div><!--/.fluid-container-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/js/jquery-2.0.0.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/main.js"></script>

    <!-- Google Analytics -->
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-40994466-1', 'pagodabox.com');
      ga('send', 'pageview');

    </script>

    @yield('footer_js')
  </body>
</html>
