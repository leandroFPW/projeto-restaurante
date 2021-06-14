<!DOCTYPE html><?php

use App\Models\Layout; ?>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{Layout::myTitle()}}</title>
        @include ('_html.head-links')
    </head>
    <body class="login">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-5 content">
                    <div class="a-center">
                        <img src="{{ asset('img/latorre-logo.png')}}" alt="latorrelogo" style="width: 100%;" />
                    </div>
                    @yield('content')
                </div>
            </div>
        </div>
    </body>
</html>