<main class="flex-grow pt-16 pb-8 px-6 max-w-[1200px] mx-auto w-full">
    <div class="mb-5 flex justify-between items-center">
        <div>
            <span class="label-md font-bold text-primary uppercase tracking-widest text-[10px]">Admin Panel</span>
            <h1 class="font-headline text-3xl font-extrabold text-on-surface mt-1 tracking-tight leading-none">Dashboard</h1>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="bg-error text-on-error px-4 py-2 flex items-center gap-2 rounded-lg font-bold text-sm hover:brightness-110 transition">
                Logout
                <span class="material-symbols-outlined text-sm">logout</span>
            </button>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <div class="bg-surface-container-low p-6 rounded-xl border-l-4 border-primary shadow-sm">
            <h3 class="text-sm font-bold text-outline uppercase">Total Users</h3>
            <p class="text-3xl font-headline font-extrabold mt-2">{{ \App\Models\User::count() }}</p>
        </div>
        <div class="bg-surface-container-low p-6 rounded-xl border-l-4 border-secondary shadow-sm">
            <h3 class="text-sm font-bold text-outline uppercase">Total Simulations</h3>
            <p class="text-3xl font-headline font-extrabold mt-2">{{ $simulations->count() }}</p>
        </div>
    </div>

    <!-- Master Data: Tariffs -->
    <div class="bg-surface-container-low rounded-xl shadow-sm border border-outline-variant/20 mb-8 overflow-hidden">
        <div class="px-6 py-4 border-b border-outline-variant/20 bg-surface-container-lowest">
            <h2 class="font-headline text-lg font-bold text-on-surface">Master Data: PLN Tariffs</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container">
                        <th class="p-4 text-xs font-bold text-outline uppercase">ID</th>
                        <th class="p-4 text-xs font-bold text-outline uppercase">Name</th>
                        <th class="p-4 text-xs font-bold text-outline uppercase">Code</th>
                        <th class="p-4 text-xs font-bold text-outline uppercase">Power (VA)</th>
                        <th class="p-4 text-xs font-bold text-outline uppercase text-right">Price/kWh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10 text-sm">
                    @foreach($tariffs as $t)
                    <tr class="hover:bg-surface-container-highest transition-colors">
                        <td class="p-4 text-outline-variant">#{{ $t->id }}</td>
                        <td class="p-4 font-bold text-on-surface">{{ $t->name }}</td>
                        <td class="p-4">{{ $t->tariff_code }}</td>
                        <td class="p-4">{{ $t->power_va }}</td>
                        <td class="p-4 font-mono text-right text-tertiary font-bold">Rp {{ number_format($t->price_per_kwh, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Transactions: Simulations -->
    <div class="bg-surface-container-low rounded-xl shadow-sm border border-outline-variant/20 overflow-hidden">
        <div class="px-6 py-4 border-b border-outline-variant/20 bg-surface-container-lowest">
            <h2 class="font-headline text-lg font-bold text-on-surface">Transactions: Saved Simulations</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container">
                        <th class="p-4 text-xs font-bold text-outline uppercase">User</th>
                        <th class="p-4 text-xs font-bold text-outline uppercase">Location</th>
                        <th class="p-4 text-xs font-bold text-outline uppercase text-right">System Size</th>
                        <th class="p-4 text-xs font-bold text-outline uppercase text-right">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10 text-sm">
                    @forelse($simulations as $sim)
                    <tr class="hover:bg-surface-container-highest transition-colors">
                        <td class="p-4 font-bold text-on-surface">{{ $sim->user->name ?? 'Guest' }}</td>
                        <td class="p-4 text-outline-variant truncate max-w-[200px]" title="{{ $sim->location_name }}">{{ $sim->location_name }}</td>
                        <td class="p-4 font-mono text-right text-primary font-bold">... kWp</td>
                        <td class="p-4 text-right text-xs text-outline">{{ $sim->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-outline-variant italic">No simulations saved yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>
