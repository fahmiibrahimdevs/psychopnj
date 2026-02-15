<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>PR PNJ - Psychorobotic - Login</title>

        <!-- Icon -->
        <link rel="icon" type="image/png" href="{{ asset("icons/logo-psychorobotic.png") }}" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(["resources/css/app.css", "resources/js/app.js"])

        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
        </style>
    </head>
    <body class="tw-antialiased tw-bg-gray-50">
        <div class="tw-min-h-screen tw-flex tw-items-center tw-justify-center tw-py-8 sm:tw-py-12 tw-px-4 sm:tw-px-6 lg:tw-px-8">
            <div class="tw-w-full tw-max-w-md">
                <div class="sm:tw-bg-white sm:tw-rounded-2xl sm:tw-shadow-xl tw-px-4 sm:tw-px-8 tw-py-6 sm:tw-py-10">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
