<!-- TopNavBar -->
<nav class="fixed top-0 w-full z-50 bg-slate-50/80 dark:bg-slate-900/80 backdrop-blur-md shadow-sm">
    <div class="flex justify-between items-center w-full px-8 lg:px-12 py-4">
        <a href="{{ route('home') }}" class="text-2xl font-bold tracking-tighter text-slate-900 dark:text-slate-50 font-headline hover:opacity-80 transition-opacity">SolarSmart</a>
        <div class="hidden md:flex items-center gap-8 font-headline font-medium text-sm tracking-tight">
            <a class="text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 transition-colors" href="/">Home</a>
            <a class="text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 transition-colors" href="#">About Us</a>
            <a class="text-amber-700 dark:text-amber-500 font-bold border-b-2 border-amber-500 pb-1" href="{{ route('calculator') }}">Simulator</a>
        </div>
</nav>
