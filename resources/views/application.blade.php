<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <link rel="icon" href="{{ asset('ig.ico') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Instgram</title>
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('loader.css') }}" /> --}}
    @vite(['resources/js/main.js'])
</head>

<body>
    <div id="app">
        <div id="loading-bg">
            <div class="loading-logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a5/Instagram_icon.png" alt="Instagram Logo"
                    style="width: 80px; height: 80px;" />
            </div>

            <div class="loading">
                <div class="effect-1 effects"></div>
                <div class="effect-2 effects"></div>
                <div class="effect-3 effects"></div>
            </div>
        </div>
    </div>

    <script>
        const loaderColor = localStorage.getItem('sneat-initial-loader-bg') || '#FFFFFF'
        const primaryColor = localStorage.getItem('sneat-initial-loader-color') || '#696CFF'

        if (loaderColor)
            document.documentElement.style.setProperty('--initial-loader-bg', loaderColor)

        if (primaryColor)
            document.documentElement.style.setProperty('--initial-loader-color', primaryColor)
    </script>
</body>

</html>
