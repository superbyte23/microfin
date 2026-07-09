<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Microfin') }} — Community Microfinance System</title>
        @include('partials.head')
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="bg-zinc-50 text-zinc-900 antialiased">
        <div x-data="{ mobileOpen: false, scrolled: false }" x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 20)" class="relative">

            {{-- Navigation --}}
            <nav x-bind:class="scrolled ? 'bg-white/80 backdrop-blur-lg shadow-sm border-b border-zinc-200' : 'bg-transparent'" class="fixed top-0 inset-x-0 z-50 transition-all duration-300">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16 lg:h-20">
                        {{-- Logo --}}
                        <a href="#" class="flex items-center gap-2.5">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-lime-400">
                                <x-app-logo-icon class="size-5 fill-current text-zinc-900" />
                            </span>
                            <span class="text-lg font-bold tracking-tight text-zinc-900">Microfin</span>
                        </a>

                        {{-- Desktop Nav --}}
                        <div class="hidden lg:flex items-center gap-8">
                            <a href="#hero" class="text-sm font-medium text-zinc-600 hover:text-zinc-900 transition-colors">Home</a>
                            <a href="#services" class="text-sm font-medium text-zinc-600 hover:text-zinc-900 transition-colors">Services</a>
                            <a href="#testimonials" class="text-sm font-medium text-zinc-600 hover:text-zinc-900 transition-colors">Testimonials</a>
                            <a href="#pricing" class="text-sm font-medium text-zinc-600 hover:text-zinc-900 transition-colors">Pricing</a>
                            <a href="#faq" class="text-sm font-medium text-zinc-600 hover:text-zinc-900 transition-colors">FAQ</a>
                            <a href="#contact" class="text-sm font-medium text-zinc-600 hover:text-zinc-900 transition-colors">Contact</a>
                        </div>

                        {{-- Desktop Auth --}}
                        <div class="hidden lg:flex items-center gap-3">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-900 transition-colors">Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-900 transition-colors">Log in</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="inline-flex items-center rounded-xl bg-lime-400 px-5 py-2.5 text-sm font-semibold text-zinc-900 hover:bg-lime-500 transition-all shadow-sm hover:shadow-md">Open Account</a>
                                    @endif
                                @endauth
                            @endif
                        </div>

                        {{-- Mobile Toggle --}}
                        <button @click="mobileOpen = !mobileOpen" class="lg:hidden p-2 rounded-lg text-zinc-600 hover:bg-zinc-100 transition-colors">
                            <svg x-show="!mobileOpen" class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                            <svg x-show="mobileOpen" class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Mobile Menu --}}
                <div x-show="mobileOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="lg:hidden border-t border-zinc-200 bg-white">
                    <div class="px-4 py-4 space-y-3">
                        <a href="#hero" @click="mobileOpen = false" class="block text-sm font-medium text-zinc-600 hover:text-zinc-900">Home</a>
                        <a href="#services" @click="mobileOpen = false" class="block text-sm font-medium text-zinc-600 hover:text-zinc-900">Services</a>
                        <a href="#testimonials" @click="mobileOpen = false" class="block text-sm font-medium text-zinc-600 hover:text-zinc-900">Testimonials</a>
                        <a href="#pricing" @click="mobileOpen = false" class="block text-sm font-medium text-zinc-600 hover:text-zinc-900">Pricing</a>
                        <a href="#faq" @click="mobileOpen = false" class="block text-sm font-medium text-zinc-600 hover:text-zinc-900">FAQ</a>
                        <a href="#contact" @click="mobileOpen = false" class="block text-sm font-medium text-zinc-600 hover:text-zinc-900">Contact</a>
                        <hr class="border-zinc-200">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ route('dashboard') }}" class="block text-sm font-medium text-lime-600">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="block text-sm font-medium text-zinc-600">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="block text-center rounded-xl bg-lime-400 px-5 py-2.5 text-sm font-semibold text-zinc-900 hover:bg-lime-500 transition-all">Open Account</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </nav>

            {{-- Hero --}}
            <section id="hero" class="relative pt-32 pb-20 lg:pt-40 lg:pb-28 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-lime-50/50 via-transparent to-transparent pointer-events-none"></div>
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="lg:grid lg:grid-cols-2 lg:gap-16 items-center">
                        {{-- Left Column --}}
                        <div>
                            <div class="inline-flex items-center gap-2 rounded-full bg-lime-100 px-4 py-1.5 text-sm font-medium text-lime-700 mb-6">
                                <span class="h-2 w-2 rounded-full bg-lime-400 animate-pulse"></span>
                                Trusted by 50+ cooperatives nationwide
                            </div>
                            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold leading-[1.1] tracking-tight text-zinc-900">
                                Empowering Communities Through<br>
                                <span class="text-lime-500">Accessible Microfinance</span>
                            </h1>
                            <p class="mt-6 text-lg text-zinc-600 leading-relaxed max-w-lg">
                                Fast loan applications, secure savings, digital payments, and member management — all in one modern platform.
                            </p>
                            <div class="mt-8 flex flex-wrap gap-4">
                                <a href="{{ route('register') }}" class="inline-flex items-center rounded-xl bg-lime-400 px-6 py-3 text-base font-semibold text-zinc-900 hover:bg-lime-500 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                                    Apply for Loan
                                    <svg class="ml-2 size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                                <a href="#" class="inline-flex items-center rounded-xl border border-zinc-200 bg-white px-6 py-3 text-base font-semibold text-zinc-700 hover:bg-zinc-50 hover:border-zinc-300 transition-all shadow-sm">
                                    <svg class="mr-2 size-5 text-lime-500" fill="currentColor" viewBox="0 0 20 20"><path d="M6.5 3.5a1.5 1.5 0 012.5-1.06l7.22 7.22a1.5 1.5 0 010 2.12l-7.22 7.22a1.5 1.5 0 01-2.56-1.06V14.5H2.5A1.5 1.5 0 011 13V7a1.5 1.5 0 011.5-1.5H6.5V3.5z"/></svg>
                                    Watch Demo
                                </a>
                            </div>
                            {{-- Trust Badges --}}
                            <div class="mt-10 flex flex-wrap gap-6">
                                <div class="flex items-center gap-2 text-sm text-zinc-500">
                                    <svg class="size-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    Secure
                                </div>
                                <div class="flex items-center gap-2 text-sm text-zinc-500">
                                    <svg class="size-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    Fast Approval
                                </div>
                                <div class="flex items-center gap-2 text-sm text-zinc-500">
                                    <svg class="size-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    Trusted by Cooperatives
                                </div>
                                <div class="flex items-center gap-2 text-sm text-zinc-500">
                                    <svg class="size-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    Mobile Ready
                                </div>
                            </div>
                        </div>

                        {{-- Right Column -- Dashboard Mockup --}}
                        <div class="mt-12 lg:mt-0 relative">
                            <div class="relative">
                                {{-- Main Dashboard Card --}}
                                <div class="bg-white border border-zinc-200 rounded-3xl shadow-xl p-6 space-y-5">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <div class="h-3 w-3 rounded-full bg-red-400"></div>
                                            <div class="h-3 w-3 rounded-full bg-amber-400"></div>
                                            <div class="h-3 w-3 rounded-full bg-emerald-400"></div>
                                        </div>
                                        <span class="text-xs font-medium text-zinc-400">Dashboard</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="rounded-2xl bg-zinc-50 border border-zinc-100 p-4">
                                            <div class="text-xs text-zinc-500">Total Members</div>
                                            <div class="text-2xl font-bold text-zinc-900 mt-1">10,000+</div>
                                            <div class="text-xs text-emerald-600 mt-1">↑ 12% this month</div>
                                        </div>
                                        <div class="rounded-2xl bg-zinc-50 border border-zinc-100 p-4">
                                            <div class="text-xs text-zinc-500">Active Loans</div>
                                            <div class="text-2xl font-bold text-zinc-900 mt-1">2,845</div>
                                            <div class="text-xs text-emerald-600 mt-1">↑ 8% this month</div>
                                        </div>
                                        <div class="rounded-2xl bg-zinc-50 border border-zinc-100 p-4">
                                            <div class="text-xs text-zinc-500">Collections</div>
                                            <div class="text-2xl font-bold text-zinc-900 mt-1">₱12.4M</div>
                                            <div class="text-xs text-emerald-600 mt-1">↑ 15% this month</div>
                                        </div>
                                        <div class="rounded-2xl bg-zinc-50 border border-zinc-100 p-4">
                                            <div class="text-xs text-zinc-500">Monthly Growth</div>
                                            <div class="text-2xl font-bold text-zinc-900 mt-1">+₱2.1M</div>
                                            <div class="text-xs text-emerald-600 mt-1">↑ 5% this month</div>
                                        </div>
                                    </div>
                                    <div class="h-2 rounded-full bg-zinc-100 overflow-hidden">
                                        <div class="h-full w-3/4 rounded-full bg-lime-400"></div>
                                    </div>
                                    <div class="flex justify-between text-xs text-zinc-400">
                                        <span>Portfolio Growth</span>
                                        <span>75%</span>
                                    </div>
                                </div>

                                {{-- Floating Card: Loan Approved --}}
                                <div class="absolute -top-4 -right-4 bg-white border border-zinc-200 rounded-2xl shadow-lg p-4 flex items-center gap-3 animate-bounce/2" style="animation: float 3s ease-in-out infinite;">
                                    <div class="h-10 w-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                                        <svg class="size-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-zinc-900">Loan Approved</div>
                                        <div class="text-xs text-zinc-500">₱50,000</div>
                                    </div>
                                </div>

                                {{-- Floating Card: Collection Rate --}}
                                <div class="absolute -bottom-4 -left-4 bg-white border border-zinc-200 rounded-2xl shadow-lg p-4 flex items-center gap-3" style="animation: float 3.5s ease-in-out infinite 0.5s;">
                                    <div class="h-10 w-10 rounded-xl bg-lime-100 flex items-center justify-center">
                                        <svg class="size-5 text-lime-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-zinc-900">98% Collection Rate</div>
                                        <div class="text-xs text-zinc-500">Industry leading</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Statistics --}}
            <section class="border-y border-zinc-200 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                        <div class="text-center">
                            <div class="text-4xl lg:text-5xl font-bold text-lime-500">10,000+</div>
                            <div class="mt-2 text-sm text-zinc-500">Members</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl lg:text-5xl font-bold text-lime-500">₱500M+</div>
                            <div class="mt-2 text-sm text-zinc-500">Loans Released</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl lg:text-5xl font-bold text-lime-500">98%</div>
                            <div class="mt-2 text-sm text-zinc-500">Repayment Rate</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl lg:text-5xl font-bold text-lime-500">50+</div>
                            <div class="mt-2 text-sm text-zinc-500">Branches</div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Services --}}
            <section id="services" class="py-20 lg:py-28">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-2xl mx-auto">
                        <div class="inline-flex items-center rounded-full bg-lime-100 px-4 py-1.5 text-sm font-medium text-lime-700 mb-4">Our Services</div>
                        <h2 class="text-4xl lg:text-5xl font-bold tracking-tight text-zinc-900">Everything You Need</h2>
                        <p class="mt-4 text-lg text-zinc-600">Comprehensive financial tools designed for cooperatives and communities.</p>
                    </div>
                    <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="group bg-white border border-zinc-200 rounded-2xl p-6 hover:shadow-xl hover:border-lime-200 transition-all duration-300">
                            <div class="h-12 w-12 rounded-xl bg-lime-100 flex items-center justify-center group-hover:bg-lime-200 transition-colors">
                                <svg class="size-6 text-lime-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
                            </div>
                            <h3 class="mt-4 text-xl font-semibold text-zinc-900">Loan Management</h3>
                            <p class="mt-2 text-base text-zinc-600 leading-relaxed">End-to-end loan processing from application to disbursement and collection tracking.</p>
                        </div>
                        <div class="group bg-white border border-zinc-200 rounded-2xl p-6 hover:shadow-xl hover:border-lime-200 transition-all duration-300">
                            <div class="h-12 w-12 rounded-xl bg-lime-100 flex items-center justify-center group-hover:bg-lime-200 transition-colors">
                                <svg class="size-6 text-lime-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M8 8a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2h-4a2 2 0 01-2-2V8zm2-4a4 4 0 00-4 4v4a4 4 0 004 4h4a4 4 0 004-4V8a4 4 0 00-4-4h-4z" clip-rule="evenodd"/></svg>
                            </div>
                            <h3 class="mt-4 text-xl font-semibold text-zinc-900">Savings Accounts</h3>
                            <p class="mt-2 text-base text-zinc-600 leading-relaxed">Member savings management with competitive interest rates and flexible withdrawal options.</p>
                        </div>
                        <div class="group bg-white border border-zinc-200 rounded-2xl p-6 hover:shadow-xl hover:border-lime-200 transition-all duration-300">
                            <div class="h-12 w-12 rounded-xl bg-lime-100 flex items-center justify-center group-hover:bg-lime-200 transition-colors">
                                <svg class="size-6 text-lime-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/></svg>
                            </div>
                            <h3 class="mt-4 text-xl font-semibold text-zinc-900">Payment Collection</h3>
                            <p class="mt-2 text-base text-zinc-600 leading-relaxed">Automated collection schedules with multiple payment channels and real-time tracking.</p>
                        </div>
                        <div class="group bg-white border border-zinc-200 rounded-2xl p-6 hover:shadow-xl hover:border-lime-200 transition-all duration-300">
                            <div class="h-12 w-12 rounded-xl bg-lime-100 flex items-center justify-center group-hover:bg-lime-200 transition-colors">
                                <svg class="size-6 text-lime-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                            </div>
                            <h3 class="mt-4 text-xl font-semibold text-zinc-900">Insurance Services</h3>
                            <p class="mt-2 text-base text-zinc-600 leading-relaxed">Integrated microinsurance products for member protection and risk management.</p>
                        </div>
                        <div class="group bg-white border border-zinc-200 rounded-2xl p-6 hover:shadow-xl hover:border-lime-200 transition-all duration-300">
                            <div class="h-12 w-12 rounded-xl bg-lime-100 flex items-center justify-center group-hover:bg-lime-200 transition-colors">
                                <svg class="size-6 text-lime-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                            </div>
                            <h3 class="mt-4 text-xl font-semibold text-zinc-900">Accounting</h3>
                            <p class="mt-2 text-base text-zinc-600 leading-relaxed">Double-entry bookkeeping, chart of accounts, journal entries, and financial reporting.</p>
                        </div>
                        <div class="group bg-white border border-zinc-200 rounded-2xl p-6 hover:shadow-xl hover:border-lime-200 transition-all duration-300">
                            <div class="h-12 w-12 rounded-xl bg-lime-100 flex items-center justify-center group-hover:bg-lime-200 transition-colors">
                                <svg class="size-6 text-lime-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                            </div>
                            <h3 class="mt-4 text-xl font-semibold text-zinc-900">Mobile Banking</h3>
                            <p class="mt-2 text-base text-zinc-600 leading-relaxed">Member mobile app for balance inquiries, loan applications, and payment notifications.</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Why Choose Us --}}
            <section class="py-20 lg:py-28 bg-zinc-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-2xl mx-auto">
                        <div class="inline-flex items-center rounded-full bg-lime-100 px-4 py-1.5 text-sm font-medium text-lime-700 mb-4">Why Choose Us</div>
                        <h2 class="text-4xl lg:text-5xl font-bold tracking-tight text-zinc-900">Built for Modern Microfinance</h2>
                        <p class="mt-4 text-lg text-zinc-600">Purpose-built features that make a difference for your cooperative.</p>
                    </div>
                    <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="flex items-start gap-4">
                            <div class="h-10 w-10 rounded-xl bg-lime-100 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="size-5 text-lime-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-zinc-900">Fast Approval</h3>
                                <p class="mt-1 text-base text-zinc-600">Get loan decisions within 24 hours with our streamlined application process.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="h-10 w-10 rounded-xl bg-lime-100 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="size-5 text-lime-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-zinc-900">Low Interest Rates</h3>
                                <p class="mt-1 text-base text-zinc-600">Competitive rates designed to make credit accessible to all members.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="h-10 w-10 rounded-xl bg-lime-100 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="size-5 text-lime-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-zinc-900">Secure Transactions</h3>
                                <p class="mt-1 text-base text-zinc-600">Bank-grade security with encrypted data and multi-factor authentication.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="h-10 w-10 rounded-xl bg-lime-100 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="size-5 text-lime-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-zinc-900">Mobile Access</h3>
                                <p class="mt-1 text-base text-zinc-600">Full platform access via mobile app and responsive web interface.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="h-10 w-10 rounded-xl bg-lime-100 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="size-5 text-lime-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-zinc-900">Real-time Reporting</h3>
                                <p class="mt-1 text-base text-zinc-600">Live dashboards and reports for data-driven decision making.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="h-10 w-10 rounded-xl bg-lime-100 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="size-5 text-lime-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-zinc-900">Multi-Branch Support</h3>
                                <p class="mt-1 text-base text-zinc-600">Centralized management for multiple branches and remote operations.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Process --}}
            <section class="py-20 lg:py-28 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-2xl mx-auto">
                        <div class="inline-flex items-center rounded-full bg-lime-100 px-4 py-1.5 text-sm font-medium text-lime-700 mb-4">How It Works</div>
                        <h2 class="text-4xl lg:text-5xl font-bold tracking-tight text-zinc-900">Get Started in 5 Easy Steps</h2>
                        <p class="mt-4 text-lg text-zinc-600">From registration to receiving funds, we make it simple.</p>
                    </div>
                    <div class="mt-12 grid sm:grid-cols-5 gap-6 lg:gap-8">
                        <div class="text-center">
                            <div class="mx-auto h-14 w-14 rounded-2xl bg-lime-100 flex items-center justify-center">
                                <span class="text-2xl font-bold text-lime-600">1</span>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-zinc-900">Register</h3>
                            <p class="mt-1 text-sm text-zinc-500">Create your member account</p>
                        </div>
                        <div class="text-center">
                            <div class="mx-auto h-14 w-14 rounded-2xl bg-lime-100 flex items-center justify-center">
                                <span class="text-2xl font-bold text-lime-600">2</span>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-zinc-900">Apply</h3>
                            <p class="mt-1 text-sm text-zinc-500">Submit your loan application</p>
                        </div>
                        <div class="text-center">
                            <div class="mx-auto h-14 w-14 rounded-2xl bg-lime-100 flex items-center justify-center">
                                <span class="text-2xl font-bold text-lime-600">3</span>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-zinc-900">Verification</h3>
                            <p class="mt-1 text-sm text-zinc-500">We verify your details</p>
                        </div>
                        <div class="text-center">
                            <div class="mx-auto h-14 w-14 rounded-2xl bg-lime-100 flex items-center justify-center">
                                <span class="text-2xl font-bold text-lime-600">4</span>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-zinc-900">Approval</h3>
                            <p class="mt-1 text-sm text-zinc-500">Get notified of approval</p>
                        </div>
                        <div class="text-center">
                            <div class="mx-auto h-14 w-14 rounded-2xl bg-emerald-100 flex items-center justify-center">
                                <span class="text-2xl font-bold text-emerald-600">5</span>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-zinc-900">Receive Funds</h3>
                            <p class="mt-1 text-sm text-zinc-500">Funds disbursed to your account</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Testimonials --}}
            <section id="testimonials" class="py-20 lg:py-28 bg-zinc-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-2xl mx-auto">
                        <div class="inline-flex items-center rounded-full bg-lime-100 px-4 py-1.5 text-sm font-medium text-lime-700 mb-4">Testimonials</div>
                        <h2 class="text-4xl lg:text-5xl font-bold tracking-tight text-zinc-900">Trusted by Cooperative Leaders</h2>
                        <p class="mt-4 text-lg text-zinc-600">Hear from the people who use Microfin every day.</p>
                    </div>
                    <div class="mt-12 grid md:grid-cols-3 gap-6">
                        <div class="bg-white border border-zinc-200 rounded-2xl p-6 hover:shadow-xl transition-all duration-300">
                            <div class="flex gap-1 text-lime-400">
                                <svg class="size-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="size-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="size-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="size-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="size-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                            <p class="mt-4 text-base text-zinc-600 leading-relaxed">"Microfin transformed how we manage our cooperative's loan portfolio. The automated collection tracking alone saved us countless hours."</p>
                            <div class="mt-6 flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-lime-100 flex items-center justify-center text-sm font-semibold text-lime-700">MR</div>
                                <div>
                                    <div class="text-sm font-semibold text-zinc-900">Maria Reyes</div>
                                    <div class="text-xs text-zinc-500">General Manager, Samahan Coop</div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-zinc-200 rounded-2xl p-6 hover:shadow-xl transition-all duration-300">
                            <div class="flex gap-1 text-lime-400">
                                <svg class="size-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="size-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="size-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="size-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="size-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                            <p class="mt-4 text-base text-zinc-600 leading-relaxed">"The mobile app made it easy for our members in remote areas to access their accounts. Member satisfaction has never been higher."</p>
                            <div class="mt-6 flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-lime-100 flex items-center justify-center text-sm font-semibold text-lime-700">JS</div>
                                <div>
                                    <div class="text-sm font-semibold text-zinc-900">Juan Santos</div>
                                    <div class="text-xs text-zinc-500">Board Director, Buklod Coop</div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-zinc-200 rounded-2xl p-6 hover:shadow-xl transition-all duration-300">
                            <div class="flex gap-1 text-lime-400">
                                <svg class="size-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="size-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="size-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="size-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="size-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                            <p class="mt-4 text-base text-zinc-600 leading-relaxed">"The real-time reporting and analytics give us insights we never had before. Our portfolio at risk dropped by 40% in six months."</p>
                            <div class="mt-6 flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-lime-100 flex items-center justify-center text-sm font-semibold text-lime-700">AG</div>
                                <div>
                                    <div class="text-sm font-semibold text-zinc-900">Anna Gonzales</div>
                                    <div class="text-xs text-zinc-500">Finance Officer, Lakas Coop</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Pricing --}}
            <section id="pricing" class="py-20 lg:py-28 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-2xl mx-auto">
                        <div class="inline-flex items-center rounded-full bg-lime-100 px-4 py-1.5 text-sm font-medium text-lime-700 mb-4">Pricing</div>
                        <h2 class="text-4xl lg:text-5xl font-bold tracking-tight text-zinc-900">Simple, Transparent Pricing</h2>
                        <p class="mt-4 text-lg text-zinc-600">Choose the plan that fits your cooperative's needs.</p>
                    </div>
                    <div class="mt-12 grid md:grid-cols-3 gap-6 max-w-5xl mx-auto">
                        <div class="bg-white border border-zinc-200 rounded-2xl p-6 hover:shadow-xl transition-all duration-300">
                            <h3 class="text-xl font-semibold text-zinc-900">Starter</h3>
                            <div class="mt-4">
                                <span class="text-4xl font-bold text-zinc-900">Free</span>
                            </div>
                            <p class="mt-2 text-sm text-zinc-500">For small cooperatives getting started</p>
                            <ul class="mt-6 space-y-3">
                                <li class="flex items-center gap-3 text-sm text-zinc-600">
                                    <svg class="size-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Up to 100 members
                                </li>
                                <li class="flex items-center gap-3 text-sm text-zinc-600">
                                    <svg class="size-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Basic loan management
                                </li>
                                <li class="flex items-center gap-3 text-sm text-zinc-600">
                                    <svg class="size-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    1 branch
                                </li>
                                <li class="flex items-center gap-3 text-sm text-zinc-600">
                                    <svg class="size-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Email support
                                </li>
                            </ul>
                            <a href="{{ route('register') }}" class="mt-6 block w-full text-center rounded-xl border border-zinc-200 px-5 py-2.5 text-sm font-semibold text-zinc-700 hover:bg-zinc-50 transition-all">Get Started</a>
                        </div>
                        <div class="bg-white border-2 border-lime-300 rounded-2xl p-6 hover:shadow-xl transition-all duration-300 relative">
                            <div class="absolute -top-3 left-1/2 -translate-x-1/2 inline-flex items-center rounded-full bg-lime-400 px-4 py-1 text-xs font-semibold text-zinc-900">Most Popular</div>
                            <h3 class="text-xl font-semibold text-zinc-900">Professional</h3>
                            <div class="mt-4">
                                <span class="text-4xl font-bold text-zinc-900">₱2,499</span>
                                <span class="text-sm text-zinc-500">/month</span>
                            </div>
                            <p class="mt-2 text-sm text-zinc-500">For growing cooperatives</p>
                            <ul class="mt-6 space-y-3">
                                <li class="flex items-center gap-3 text-sm text-zinc-600">
                                    <svg class="size-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Up to 1,000 members
                                </li>
                                <li class="flex items-center gap-3 text-sm text-zinc-600">
                                    <svg class="size-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Full loan & savings
                                </li>
                                <li class="flex items-center gap-3 text-sm text-zinc-600">
                                    <svg class="size-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Up to 5 branches
                                </li>
                                <li class="flex items-center gap-3 text-sm text-zinc-600">
                                    <svg class="size-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Mobile app access
                                </li>
                                <li class="flex items-center gap-3 text-sm text-zinc-600">
                                    <svg class="size-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Priority support
                                </li>
                            </ul>
                            <a href="{{ route('register') }}" class="mt-6 block w-full text-center rounded-xl bg-lime-400 px-5 py-2.5 text-sm font-semibold text-zinc-900 hover:bg-lime-500 transition-all shadow-sm">Start Free Trial</a>
                        </div>
                        <div class="bg-white border border-zinc-200 rounded-2xl p-6 hover:shadow-xl transition-all duration-300">
                            <h3 class="text-xl font-semibold text-zinc-900">Enterprise</h3>
                            <div class="mt-4">
                                <span class="text-4xl font-bold text-zinc-900">Custom</span>
                            </div>
                            <p class="mt-2 text-sm text-zinc-500">For large multi-branch cooperatives</p>
                            <ul class="mt-6 space-y-3">
                                <li class="flex items-center gap-3 text-sm text-zinc-600">
                                    <svg class="size-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Unlimited members
                                </li>
                                <li class="flex items-center gap-3 text-sm text-zinc-600">
                                    <svg class="size-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    All modules
                                </li>
                                <li class="flex items-center gap-3 text-sm text-zinc-600">
                                    <svg class="size-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Unlimited branches
                                </li>
                                <li class="flex items-center gap-3 text-sm text-zinc-600">
                                    <svg class="size-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    API access
                                </li>
                                <li class="flex items-center gap-3 text-sm text-zinc-600">
                                    <svg class="size-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Dedicated support
                                </li>
                            </ul>
                            <a href="#contact" class="mt-6 block w-full text-center rounded-xl border border-zinc-200 px-5 py-2.5 text-sm font-semibold text-zinc-700 hover:bg-zinc-50 transition-all">Contact Us</a>
                        </div>
                    </div>
                </div>
            </section>

            {{-- FAQ --}}
            <section id="faq" class="py-20 lg:py-28 bg-zinc-50">
                <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-2xl mx-auto">
                        <div class="inline-flex items-center rounded-full bg-lime-100 px-4 py-1.5 text-sm font-medium text-lime-700 mb-4">FAQ</div>
                        <h2 class="text-4xl lg:text-5xl font-bold tracking-tight text-zinc-900">Frequently Asked Questions</h2>
                        <p class="mt-4 text-lg text-zinc-600">Everything you need to know about Microfin.</p>
                    </div>
                    <div class="mt-12 space-y-4" x-data="{ open: null }">
                        <div class="bg-white border border-zinc-200 rounded-2xl">
                            <button @click="open = open === 0 ? null : 0" class="w-full px-6 py-4 flex items-center justify-between text-left">
                                <span class="text-base font-semibold text-zinc-900">How long does loan approval take?</span>
                                <svg x-bind:class="open === 0 ? 'rotate-180' : ''" class="size-5 text-zinc-400 transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open === 0" x-collapse class="px-6 pb-4">
                                <p class="text-base text-zinc-600">Most loan applications are approved within 24 hours. Simple applications may be approved even faster. The process includes document verification and credit assessment.</p>
                            </div>
                        </div>
                        <div class="bg-white border border-zinc-200 rounded-2xl">
                            <button @click="open = open === 1 ? null : 1" class="w-full px-6 py-4 flex items-center justify-between text-left">
                                <span class="text-base font-semibold text-zinc-900">What are the requirements for a loan?</span>
                                <svg x-bind:class="open === 1 ? 'rotate-180' : ''" class="size-5 text-zinc-400 transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open === 1" x-collapse class="px-6 pb-4">
                                <p class="text-base text-zinc-600">Basic requirements include: valid government ID, proof of income, membership registration, and co-signer information for larger loan amounts.</p>
                            </div>
                        </div>
                        <div class="bg-white border border-zinc-200 rounded-2xl">
                            <button @click="open = open === 2 ? null : 2" class="w-full px-6 py-4 flex items-center justify-between text-left">
                                <span class="text-base font-semibold text-zinc-900">Is mobile banking available?</span>
                                <svg x-bind:class="open === 2 ? 'rotate-180' : ''" class="size-5 text-zinc-400 transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open === 2" x-collapse class="px-6 pb-4">
                                <p class="text-base text-zinc-600">Yes! Our mobile app is available for iOS and Android. Members can check balances, apply for loans, make payments, and receive notifications.</p>
                            </div>
                        </div>
                        <div class="bg-white border border-zinc-200 rounded-2xl">
                            <button @click="open = open === 3 ? null : 3" class="w-full px-6 py-4 flex items-center justify-between text-left">
                                <span class="text-base font-semibold text-zinc-900">Can cooperatives use this system?</span>
                                <svg x-bind:class="open === 3 ? 'rotate-180' : ''" class="size-5 text-zinc-400 transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open === 3" x-collapse class="px-6 pb-4">
                                <p class="text-base text-zinc-600">Absolutely. Microfin is built specifically for cooperatives and microfinance institutions. We support multi-branch operations, member management, and cooperative governance structures.</p>
                            </div>
                        </div>
                        <div class="bg-white border border-zinc-200 rounded-2xl">
                            <button @click="open = open === 4 ? null : 4" class="w-full px-6 py-4 flex items-center justify-between text-left">
                                <span class="text-base font-semibold text-zinc-900">How secure is the platform?</span>
                                <svg x-bind:class="open === 4 ? 'rotate-180' : ''" class="size-5 text-zinc-400 transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open === 4" x-collapse class="px-6 pb-4">
                                <p class="text-base text-zinc-600">We use bank-grade encryption, secure data centers, multi-factor authentication, and regular security audits. Your data is always protected.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- CTA --}}
            <section id="contact" class="py-20 lg:py-28 bg-white">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <div class="rounded-3xl bg-gradient-to-br from-lime-400 to-emerald-400 p-8 sm:p-12 lg:p-16 shadow-xl">
                        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-zinc-900">Ready to Modernize Your Microfinance Operations?</h2>
                        <p class="mt-4 text-lg text-zinc-800/80 max-w-2xl mx-auto">Start managing members, loans, savings, and collections today with Microfin.</p>
                        <div class="mt-8 flex flex-wrap justify-center gap-4">
                            <a href="{{ route('register') }}" class="inline-flex items-center rounded-xl bg-zinc-900 px-6 py-3 text-base font-semibold text-white hover:bg-zinc-800 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                                Open Account
                                <svg class="ml-2 size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                            <a href="#" class="inline-flex items-center rounded-xl bg-white/20 backdrop-blur-sm px-6 py-3 text-base font-semibold text-zinc-900 hover:bg-white/30 transition-all border border-white/30">
                                Schedule Demo
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Footer --}}
            <footer class="border-t border-zinc-200 bg-zinc-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
                    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-lime-400">
                                    <x-app-logo-icon class="size-4 fill-current text-zinc-900" />
                                </span>
                                <span class="text-base font-bold text-zinc-900">Microfin</span>
                            </div>
                            <p class="mt-4 text-sm text-zinc-500 leading-relaxed">Empowering communities through inclusive financial services.</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-zinc-900">Company</h4>
                            <ul class="mt-4 space-y-3">
                                <li><a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 transition-colors">About</a></li>
                                <li><a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 transition-colors">Careers</a></li>
                                <li><a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 transition-colors">Contact</a></li>
                                <li><a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 transition-colors">Blog</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-zinc-900">Products</h4>
                            <ul class="mt-4 space-y-3">
                                <li><a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 transition-colors">Loan Management</a></li>
                                <li><a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 transition-colors">Savings</a></li>
                                <li><a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 transition-colors">Collections</a></li>
                                <li><a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 transition-colors">Mobile App</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-zinc-900">Resources</h4>
                            <ul class="mt-4 space-y-3">
                                <li><a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 transition-colors">Documentation</a></li>
                                <li><a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 transition-colors">API Reference</a></li>
                                <li><a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 transition-colors">Support Center</a></li>
                                <li class="flex items-center gap-3 mt-4">
                                    <a href="#" class="text-zinc-400 hover:text-zinc-600 transition-colors">
                                        <svg class="size-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    </a>
                                    <a href="#" class="text-zinc-400 hover:text-zinc-600 transition-colors">
                                        <svg class="size-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.937 4.937 0 004.604 3.417 9.868 9.868 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.054 0 13.999-7.496 13.999-13.986 0-.209 0-.42-.015-.63a9.936 9.936 0 002.46-2.548l-.047-.02z"/></svg>
                                    </a>
                                    <a href="#" class="text-zinc-400 hover:text-zinc-600 transition-colors">
                                        <svg class="size-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-12 pt-8 border-t border-zinc-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <p class="text-sm text-zinc-400">&copy; {{ date('Y') }} Microfin. All rights reserved.</p>
                        <div class="flex gap-6">
                            <a href="#" class="text-sm text-zinc-400 hover:text-zinc-600 transition-colors">Privacy Policy</a>
                            <a href="#" class="text-sm text-zinc-400 hover:text-zinc-600 transition-colors">Terms of Service</a>
                        </div>
                    </div>
                </div>
            </footer>

            <style>
                @keyframes float {
                    0%, 100% { transform: translateY(0px); }
                    50% { transform: translateY(-8px); }
                }
            </style>
        </div>
    </body>
</html>
