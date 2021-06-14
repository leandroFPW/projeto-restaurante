<!DOCTYPE html><?php use App\Models\Layout;?>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{Layout::myTitle()}}</title>
        @include ('_html.head-links')
    </head>
    <body class="{{Layout::$CSS_CLASS}}">
        @include ('_html.nav')
        <div class="container" id="main-content">
            @yield('content')
        </div>
        @include ('_html.footer')
        @include ('_html.js')
    </body>
</html>