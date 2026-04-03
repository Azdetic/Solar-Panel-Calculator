<nav class="fixed top-0 w-full z-50 bg-surface/80 backdrop-blur-xl border-b border-outline-variant/10">
    <div class="max-w-[1500px] mx-auto px-6 lg:px-12 py-4 flex items-center justify-between">
        
        <!-- Left: Logo -->
        <div class="flex-1">
            <a href="{{ route('home') }}" class="text-2xl font-extrabold tracking-tighter text-on-surface font-headline flex items-center gap-2 group">
                <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center group-hover:rotate-12 transition-transform">
                    <span class="material-symbols-outlined text-white text-base" style="font-variation-settings: 'FILL' 1;">wb_sunny</span>
                </div>
                SolarSmart
            </a>
        </div>

        <!-- Center: Navigation -->
        <div class="hidden md:flex items-center gap-8 font-headline">
            <a href="{{ route('home') }}" 
               class="text-sm font-bold transition-all {{ request()->routeIs('home') ? 'text-primary' : 'text-on-surface-variant hover:text-on-surface' }} relative group">
                Home
                @if(request()->routeIs('home'))
                    <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-primary rounded-full"></span>
                @endif
            </a>
            <a href="{{ route('about') }}" 
               class="text-sm font-bold transition-all {{ request()->routeIs('about') ? 'text-primary' : 'text-on-surface-variant hover:text-on-surface' }} relative group">
                About Us
                @if(request()->routeIs('about'))
                    <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-primary rounded-full"></span>
                @endif
            </a>
            <a href="{{ route('calculator') }}" 
               class="text-sm font-bold transition-all {{ request()->routeIs('calculator') ? 'text-primary' : 'text-on-surface-variant hover:text-on-surface' }} relative group">
                Simulator
                @if(request()->routeIs('calculator'))
                    <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-primary rounded-full"></span>
                @endif
            </a>
        </div>

        <!-- Right: CTA / Mobile Menu -->
        <div class="flex-1 flex justify-end items-center gap-4">
            @auth
                <!-- Chat Icon -->
                <a href="{{ route('messages') }}" class="hidden md:flex text-on-surface-variant hover:text-on-surface transition-all p-2 rounded-full hover:bg-surface-container-highest tooltip" title="Messages">
                    <span class="material-symbols-outlined text-[24px]">chat</span>
                </a>

                <!-- Profile Dropdown -->
                <div x-data="{ open: false }" class="relative hidden md:block">
                    <button @click="open = !open" @click.outside="open = false" 
                            class="flex bg-secondary-container text-on-secondary-container hover:brightness-95 p-2 rounded-full transition-all items-center justify-center tooltip" title="My Profile">
                        <span class="material-symbols-outlined text-[20px]">account_circle</span>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="open" x-transition style="display: none;" 
                         class="absolute right-0 mt-2 w-64 bg-surface-container-low rounded-xl shadow-lg border border-outline-variant/20 overflow-hidden z-50">
                        <div class="p-4 border-b border-outline-variant/20 bg-surface-container">
                            <p class="font-headline font-bold text-on-surface truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-outline-variant capitalize">{{ auth()->user()->role }}</p>
                        </div>
                        <div class="p-2 flex flex-col gap-1">
                            @if(in_array(auth()->user()->role, ['admin', 'vendor']))
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 text-sm text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-[18px]">dashboard</span>
                                Dashboard
                            </a>
                            @endif
                            <a href="{{ route('profile') }}" class="flex items-center gap-3 px-3 py-2 text-sm text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-[18px]">manage_accounts</span>
                                Profile Settings
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-sm text-error hover:bg-error-container hover:text-on-error-container rounded-lg transition-colors text-left cursor-pointer">
                                    <span class="material-symbols-outlined text-[18px]">logout</span>
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}"
                   class="hidden md:flex text-sm font-bold text-on-surface-variant hover:text-on-surface transition-all mr-2">
                    Login
                </a>
                <a href="{{ route('calculator') }}"
                   class="hidden md:flex bg-primary/10 text-primary hover:bg-primary/20 px-5 py-2 rounded-full text-xs font-bold uppercase tracking-widest transition-all">
                    Try Simulator
                </a>
            @endauth

            {{-- Mobile Menu Trigger --}}
            <button class="md:hidden w-10 h-10 rounded-xl bg-surface-container-high flex items-center justify-center text-on-surface">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>
    </div>
</nav>
