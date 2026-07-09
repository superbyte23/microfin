<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-zinc-800">
        <div class="flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-6">
                <div class="flex flex-col items-center gap-3">
                    <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-600 dark:bg-emerald-700 shadow-sm">
                        <x-app-logo-icon class="size-7 fill-current text-white" />
                    </span>
                    <div class="text-center">
                        <div class="text-lg font-semibold tracking-tight text-zinc-900 dark:text-white">
                            {{ config('app.name', 'Microfin') }}
                        </div>
                        <div class="text-xs text-zinc-500 dark:text-zinc-400">
                            Community Microfinance System
                        </div>
                    </div>
                </div>
                <flux:card class="flex flex-col gap-6 p-6">
                    {{ $slot }}
                </flux:card>
                <p class="text-center text-xs text-zinc-400 dark:text-zinc-500">
                    Empowering communities through inclusive financial services.
                </p>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
