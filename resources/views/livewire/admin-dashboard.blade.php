<main class="flex-grow pt-20 pb-8 px-8 lg:px-12 w-full">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <span class="label-md font-bold text-primary uppercase tracking-widest text-[10px]">admin panel</span>
            <h1 class="font-headline text-3xl font-extrabold text-on-surface mt-1 tracking-tight leading-none">dashboard</h1>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="bg-error text-on-error px-4 py-2 flex items-center gap-2 rounded-lg font-bold text-sm hover:brightness-110 transition">
                Logout
                <span class="material-symbols-outlined text-sm">logout</span>
            </button>
        </form>
    </div>

    {{-- Flash message --}}
    @if(session()->has('message'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
         class="mb-6 bg-secondary-container text-on-secondary-container px-5 py-3 rounded-xl text-sm font-bold flex items-center gap-3 shadow-sm animate-fade-in">
        <span class="material-symbols-outlined text-sm">check_circle</span>
        {{ session('message') }}
    </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <div class="bg-surface-container-low p-6 rounded-xl border-l-4 border-primary shadow-sm">
            <h3 class="text-sm font-bold text-outline uppercase tracking-tight">total users</h3>
            <p class="text-3xl font-headline font-extrabold mt-2">{{ \App\Models\User::count() }}</p>
        </div>
        <div class="bg-surface-container-low p-6 rounded-xl border-l-4 border-secondary shadow-sm">
            <h3 class="text-sm font-bold text-outline uppercase tracking-tight">total simulations</h3>
            <p class="text-3xl font-headline font-extrabold mt-2">{{ $simulations->count() }}</p>
        </div>
    </div>

    <!-- Master Data: Tariffs -->
    <div class="bg-surface-container-low rounded-xl shadow-sm border border-outline-variant/20 mb-8 overflow-hidden">
        <div class="px-6 py-4 border-b border-outline-variant/20 bg-surface-container-lowest flex justify-between items-center">
            <h2 class="font-headline text-lg font-bold text-on-surface">PLN tarif</h2>
            <button wire:click="openModal()"
                class="flex items-center gap-2 bg-primary text-on-primary px-4 py-2 rounded-lg font-bold text-sm hover:brightness-110 transition-all active:scale-95">
                <span class="material-symbols-outlined text-sm">add</span>
                Add Tariff
            </button>
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
                        <th class="p-4 text-xs font-bold text-outline uppercase text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10 text-sm">
                    @foreach($tariffs as $t)
                    <tr class="hover:bg-surface-container-highest transition-colors group">
                        <td class="p-4 text-outline-variant">#{{ $t->id }}</td>
                        <td class="p-4 font-bold text-on-surface">{{ $t->name }}</td>
                        <td class="p-4">{{ $t->tariff_code }}</td>
                        <td class="p-4">{{ $t->power_va }}</td>
                        <td class="p-4 font-mono text-right text-tertiary font-bold">Rp {{ number_format($t->price_per_kwh, 2, ',', '.') }}</td>
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button wire:click="openModal({{ $t->id }})"
                                    class="flex items-center gap-1 text-xs font-bold text-primary bg-primary/10 hover:bg-primary/20 px-3 py-1.5 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-sm">edit</span>
                                    Edit
                                </button>
                                <button wire:click="delete({{ $t->id }})"
                                    wire:confirm="Are you sure you want to delete this tariff?"
                                    class="flex items-center gap-1 text-xs font-bold text-error bg-error/10 hover:bg-error/20 px-3 py-1.5 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Transactions: Simulations -->
    <div class="bg-surface-container-low rounded-xl shadow-sm border border-outline-variant/20 overflow-hidden">
        <div class="px-6 py-4 border-b border-outline-variant/20 bg-surface-container-lowest">
            <h2 class="font-headline text-lg font-bold text-on-surface">Saved Simulations</h2>
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
                        <td colspan="4" class="p-8 text-center text-outline-variant italic uppercase text-[10px] tracking-widest">no simulations saved yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- CRUD Modal -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        {{-- Backdrop --}}
        <div wire:click="closeModal()" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

        {{-- Modal Card --}}
        <div class="relative bg-surface rounded-2xl shadow-2xl w-full max-w-lg border border-outline-variant/20 animate-fade-in">
            <div class="px-6 py-5 border-b border-outline-variant/20 flex justify-between items-center">
                <div>
                    <span class="text-[10px] font-bold text-primary uppercase tracking-widest">{{ $editingId ? 'edit tariff' : 'new tariff' }}</span>
                    <h3 class="font-headline text-xl font-extrabold text-on-surface mt-0.5">
                        {{ $editingId ? 'Update PLN Tariff' : 'Add PLN Tariff' }}
                    </h3>
                </div>
                <button wire:click="closeModal()" class="text-outline-variant hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="px-6 py-6 space-y-4">
                {{-- Name --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-outline-variant uppercase tracking-wider">Tariff Name</label>
                    <input wire:model="name" type="text" placeholder="R-1 / 1.300 VA"
                        class="w-full bg-surface-container-high border-none rounded-lg p-3 text-on-surface focus:ring-1 focus:ring-primary/40 transition-all font-body text-sm"/>
                    @error('name') <span class="text-xs text-error">{{ $message }}</span> @enderror
                </div>

                {{-- Code & Power (2-col) --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-outline-variant uppercase tracking-wider">Tariff Code</label>
                        <input wire:model="tariff_code" type="text" placeholder="R-1"
                            class="w-full bg-surface-container-high border-none rounded-lg p-3 text-on-surface focus:ring-1 focus:ring-primary/40 transition-all font-body text-sm"/>
                        @error('tariff_code') <span class="text-xs text-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-outline-variant uppercase tracking-wider">Power (VA)</label>
                        <input wire:model="power_va" type="text" placeholder="1.300"
                            class="w-full bg-surface-container-high border-none rounded-lg p-3 text-on-surface focus:ring-1 focus:ring-primary/40 transition-all font-body text-sm"/>
                        @error('power_va') <span class="text-xs text-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Price/kWh --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-outline-variant uppercase tracking-wider">Price per kWh (Rp)</label>
                    <input wire:model="price_per_kwh" type="number" step="0.01" placeholder="1444.70"
                        class="w-full bg-surface-container-high border-none rounded-lg p-3 text-on-surface focus:ring-1 focus:ring-primary/40 transition-all font-body text-sm"/>
                    @error('price_per_kwh') <span class="text-xs text-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="px-6 py-4 border-t border-outline-variant/10 flex justify-end gap-3">
                <button wire:click="closeModal()" class="px-4 py-2 rounded-lg text-sm font-bold text-on-surface-variant hover:bg-surface-container-high transition-colors">
                    Cancel
                </button>
                <button wire:click="save()"
                    class="px-6 py-2 bg-primary text-on-primary rounded-lg text-sm font-bold hover:brightness-110 transition-all active:scale-95 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">{{ $editingId ? 'save' : 'add' }}</span>
                    {{ $editingId ? 'Save Changes' : 'Add Tariff' }}
                </button>
            </div>
        </div>
    </div>
    @endif

</main>
