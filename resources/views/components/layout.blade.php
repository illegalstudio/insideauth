<html lang="en">
<head>
    <title>Laravel InsideAuth</title>


    @vite('resources/js/app.js', 'vendor/illegal/insideauth')
</head>
<body>

<div class="min-h-screen flex flex-col sm:justify-center pt-6 sm:pt-0 bg-gray-100">
    <div class="max-w-xl mx-auto w-full">
        {{ $slot }}
    </div>
</div>

</body>
</html>
