<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script>window.Laravel = { csrfToken: '{{ csrf_token() }}' }</script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <div class="container-fluid">
            <app :app_name="'{{ config('app.name', 'Laravel') }}'"></app>

            <div class="modal" id="info-modal" tabindex="-1" role="dialog" aria-labelledby="info-modal-label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div id="info-modal-body" class="modal-body"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info" data-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal" id="confirm-modal" tabindex="-1" role="dialog" aria-labelledby="info-modal-label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal" role="document">
                    <div class="modal-content">
                        <div id="confirm-modal-body" class="modal-body"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">NIE</button>
                            <button id="confirm-modal-yes-btn" type="button" class="btn btn-success" data-dismiss="modal">TAK</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
