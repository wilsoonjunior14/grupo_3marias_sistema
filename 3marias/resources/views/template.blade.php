<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  </head>

    <body>
        <style>
            #title {
                position: absolute;
                top: 55px;
                right: 0;
                margin-right: 12px;
                font-family: 'Times New Roman', Times, serif;
                font-size: 25px;
                font-weight: bold;
            }
            #logo {
                position: absolute;
                top: 12px;
                left: 12px;
            }
            #container {
                position: absolute;
                top: 120px;
                margin: 12px;
                width: 100%;
            }
            .row {
                margin-right: 20px;
            }
            .row-colored {
                border-left: 10px solid rgb(12, 52, 114);
                border-bottom: 1px solid gray;
            }
            .row span {
                font-size: 11px;
            }
            .row b {
                font-size: 12px;
            }
        </style>

        <h1 id="title">{{ $title }}</h1>
        <img id="logo" height="80" src="/img/logo_document.png" />
        
        <div id="container" class="container-fluid">
            @yield('content')
        </div>

        <script type="text/javascript">
            window.onload = function() {
                setTimeout(() => {
                    window.print();
                }, 1000);
            }
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>