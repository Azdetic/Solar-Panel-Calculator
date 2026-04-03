<main class="grow pt-20 pb-8 px-8 lg:px-12 w-full">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <span class="label-md font-bold text-primary uppercase tracking-widest text-[10px]">{{ auth()->user()->role }} panel</span>
            <h1 class="font-headline text-3xl font-extrabold text-on-surface mt-1 tracking-tight leading-none">dashboard</h1>
        </div>
        <button wire:click="logout" class="bg-error text-on-error px-4 py-2 flex items-center gap-2 rounded-lg font-bold text-sm hover:brightness-110 transition">
            Logout
        </button>
    </div>

    {{-- Flash message --}}
    @if(session()->has('message'))
    <div class="mb-6 bg-secondary-container text-on-secondary-container px-5 py-3 rounded-xl text-sm font-bold shadow-sm">
        {{ session('message') }}
    </div>
    @endif

    <!-- Tabs -->
    <div class="flex gap-4 mb-6 border-b border-outline-variant/30 pb-2">
        @if(auth()->user()->role === 'admin')
        <button wire:click="setTab('users')" class="px-4 py-2 font-bold text-sm rounded-t-lg transition {{ $activeTab === 'users' ? 'border-b-2 border-primary text-primary' : 'text-on-surface-variant hover:text-on-surface' }}">Users</button>
        <button wire:click="setTab('tariffs')" class="px-4 py-2 font-bold text-sm rounded-t-lg transition {{ $activeTab === 'tariffs' ? 'border-b-2 border-primary text-primary' : 'text-on-surface-variant hover:text-on-surface' }}">Tariffs</button>
        <button wire:click="setTab('simulations')" class="px-4 py-2 font-bold text-sm rounded-t-lg transition {{ $activeTab === 'simulations' ? 'border-b-2 border-primary text-primary' : 'text-on-surface-variant hover:text-on-surface' }}">Simulations</button>
        @endif
        <button wire:click="setTab('quotations')" class="px-4 py-2 font-bold text-sm rounded-t-lg transition {{ $activeTab === 'quotations' ? 'border-b-2 border-primary text-primary' : 'text-on-surface-variant hover:text-on-surface' }}">Quotations (RFQ)</button>
    </div>

    <!-- Content -->
    @if ($activeTab === 'users')
    <div class="animate-fade-in space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="font-headline text-xl font-bold text-on-surface">Manage Users</h2>
            <button wire:click="openUserModal" class="solar-gradient text-white px-4 py-2 rounded-lg font-bold text-sm shadow-md hover:brightness-110 flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">add</span> New User
            </button>
        </div>

        <div class="overflow-x-auto bg-surface-container-lowest rounded-xl shadow-md border border-outline-variant/10">
            <table class="w-full text-left font-body text-sm text-on-surface">
                <thead class="bg-surface-container-high/50 text-xs uppercase tracking-wider text-on-surface-variant border-b border-outline-variant/20 font-bold">
                    <tr>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4 rounded-tr-lg">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @forelse($users as $u)
                        <tr class="hover:bg-surface-container-highest/20 transition-colors">
                            <td class="px-6 py-4 font-bold text-primary">{{ $u->name }}</td>
                            <td class="px-6 py-4">{{ $u->email }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-xs font-bold {{ $u->role === 'admin' ? 'bg-error/10 text-error' : ($u->role === 'vendor' ? 'bg-tertiary/10 text-tertiary' : 'bg-primary/10 text-primary') }}">
                                    {{ ucfirst($u->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 flex gap-2">
                                <button wire:click="openUserModal({{ $u->id }})" class="text-tertiary hover:text-tertiary/80 font-bold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">edit</span> Edit
                                </button>
                                <button wire:click="deleteUser({{ $u->id }})" wire:confirm="Delete this user? All associated quotes might fail." class="text-error hover:text-error/80 font-bold flex items-center gap-1 ml-3">
                                    <span class="material-symbols-outlined text-sm">delete</span> Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-on-surface-variant">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if ($activeTab === 'tariffs')
        <div>
            <button wire:click="openModal" class="mb-4 bg-primary text-on-primary px-4 py-2 rounded-lg font-bold text-sm">Add New Tariff</button>
            <div class="overflow-x-auto bg-surface-container rounded-xl shadow-sm border border-outline-variant/20">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-surface-container-highest text-on-surface text-xs uppercase font-extrabold tracking-wider border-b border-outline-variant/20">
                        <tr>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Code</th>
                            <th class="px-6 py-4">Power (VA)</th>
                            <th class="px-6 py-4">Price/kWh</th>
                            <th class="px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-medium text-on-surface-variant divide-y divide-outline-variant/10">
                        @foreach($tariffs as $tariff)
                        <tr class="hover:bg-surface-container-high transition-colors">
                            <td class="px-6 py-4">{{ $tariff->name }}</td>
                            <td class="px-6 py-4">{{ $tariff->tariff_code }}</td>
                            <td class="px-6 py-4">{{ $tariff->power_va }} VA</td>
                            <td class="px-6 py-4">Rp {{ number_format($tariff->price_per_kwh, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 flex gap-2">
                                <button wire:click="openModal({{ $tariff->id }})" class="text-secondary hover:text-secondary-dark font-bold text-xs uppercase tracking-wide">Edit</button>
                                <button wire:click="delete({{ $tariff->id }})" wire:confirm="Are you sure?" class="text-error hover:text-error-dark font-bold text-xs uppercase tracking-wide">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @elseif ($activeTab === 'simulations')
        <div>
            <div class="overflow-x-auto bg-surface-container rounded-xl shadow-sm border border-outline-variant/20">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-surface-container-highest text-on-surface text-xs uppercase font-extrabold tracking-wider border-b border-outline-variant/20">
                        <tr>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">User</th>
                            <th class="px-6 py-4">Location</th>
                            <th class="px-6 py-4">Bill</th>
                            <th class="px-6 py-4">Budget</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-medium text-on-surface-variant divide-y divide-outline-variant/10">
                        @foreach($simulations as $sim)
                        <tr class="hover:bg-surface-container-high transition-colors">
                            <td class="px-6 py-4">{{ $sim->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4">{{ $sim->user?->name ?? 'Guest' }}</td>
                            <td class="px-6 py-4 truncate max-w-xs">{{ $sim->location_name }}</td>
                            <td class="px-6 py-4">Rp {{ number_format($sim->average_monthly_bill, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">Rp {{ number_format($sim->estimated_budget, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @elseif ($activeTab === 'quotations')
        <div>
            <div class="overflow-x-auto bg-surface-container rounded-xl shadow-sm border border-outline-variant/20">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-surface-container-highest text-on-surface text-xs uppercase font-extrabold tracking-wider border-b border-outline-variant/20">
                        <tr>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">User</th>
                            <th class="px-6 py-4">Selected Vendor</th>
                            <th class="px-6 py-4">Location</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Amount</th>
                            <th class="px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-medium text-on-surface-variant divide-y divide-outline-variant/10">
                        @foreach($quotations as $quote)
                        <tr class="hover:bg-surface-container-high transition-colors">
                            <td class="px-6 py-4">{{ $quote->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4">{{ $quote->user?->name }}<br><span class="text-xs text-on-surface-variant/70">{{ $quote->user?->email }}</span></td>
                            <td class="px-6 py-4">
                                @if($quote->vendor)
                                    <span class="font-bold text-primary block">{{ $quote->vendor->name }}</span>
                                    <span class="text-xs text-on-surface-variant/70">{{ $quote->vendor->email }}</span>
                                @else
                                    <span class="text-xs text-on-surface-variant/70 italic">Not assigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs">{{ $quote->simulation?->location_name }}</td>
                            <td class="px-6 py-4">
                                <span class="uppercase text-[10px] font-bold px-2 py-1 rounded {{
                                    $quote->status === 'requested' ? 'bg-primary/10 text-primary' :
                                    ($quote->status === 'reviewed' ? 'bg-tertiary/10 text-tertiary' :
                                    ($quote->status === 'quotation_sent' ? 'bg-secondary/15 text-secondary' :
                                    ($quote->status === 'accepted' ? 'bg-emerald-100 text-emerald-700' :
                                    ($quote->status === 'rejected' ? 'bg-error/10 text-error' : 'bg-surface-container-highest text-on-surface'))))
                                }}">
                                    {{ str_replace('_', ' ', $quote->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">Rp {{ number_format($quote->total_amount ?? 0, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 flex flex-wrap gap-2 h-full items-center">
                                <button wire:click="openQuoteModal({{ $quote->id }})" class="text-secondary hover:text-secondary-dark font-bold text-xs uppercase tracking-wide flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">edit_document</span>
                                    Review & Update
                                </button>

                                @if(auth()->user()->role === 'vendor')
                                <button wire:click="quickUpdateQuoteStatus({{ $quote->id }}, 'reviewed')" class="text-xs font-bold uppercase tracking-wide px-2 py-1 rounded bg-tertiary/10 text-tertiary hover:bg-tertiary/20 transition">
                                    Mark Reviewed
                                </button>
                                <button wire:click="quickUpdateQuoteStatus({{ $quote->id }}, 'quotation_sent')" class="text-xs font-bold uppercase tracking-wide px-2 py-1 rounded bg-secondary/15 text-secondary hover:bg-secondary/25 transition">
                                    Send Quote
                                </button>
                                <button wire:click="quickUpdateQuoteStatus({{ $quote->id }}, 'completed')" class="text-xs font-bold uppercase tracking-wide px-2 py-1 rounded bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition">
                                    Finish
                                </button>
                                @endif

                                @if($quote->user?->id)
                                <a href="{{ route('messages', ['user' => $quote->user->id]) }}" class="text-tertiary hover:text-tertiary/80 font-bold text-xs uppercase tracking-wide flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">chat</span>
                                    Chat
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- User Modal -->
    @if($isUserModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-surface-container-low rounded-3xl p-8 max-w-md w-full shadow-xl">
            <h2 class="text-2xl font-headline font-bold text-on-surface mb-6">{{ $editingUserId ? 'Edit' : 'Add' }} User</h2>
            <form wire:submit.prevent="saveUser" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface mb-1 uppercase tracking-wide">Name</label>
                    <input type="text" wire:model="userName" class="w-full bg-surface-container rounded-xl border-none outline-none focus:ring-2 focus:ring-primary px-4 py-3 text-sm text-on-surface">
                    @error('userName') <span class="text-error text-xs font-bold">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface mb-1 uppercase tracking-wide">Email</label>
                    <input type="email" wire:model="userEmail" class="w-full bg-surface-container rounded-xl border-none outline-none focus:ring-2 focus:ring-primary px-4 py-3 text-sm text-on-surface">
                    @error('userEmail') <span class="text-error text-xs font-bold">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface mb-1 uppercase tracking-wide">Role</label>
                    <select wire:model="userRole" class="w-full bg-surface-container rounded-xl border-none outline-none focus:ring-2 focus:ring-primary px-4 py-3 text-sm text-on-surface">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                        <option value="vendor">Vendor</option>
                    </select>
                    @error('userRole') <span class="text-error text-xs font-bold">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface mb-1 uppercase tracking-wide cursor-help" title="Leave blank if you don't want to change password">Password {{ $editingUserId ? '(Optional)' : '*' }}</label>
                    <input type="password" wire:model="userPassword" class="w-full bg-surface-container rounded-xl border-none outline-none focus:ring-2 focus:ring-primary px-4 py-3 text-sm text-on-surface" placeholder="{{ $editingUserId ? 'Leave empty to keep current password' : 'Minimum 6 characters' }}">
                    @error('userPassword') <span class="text-error text-xs font-bold">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" wire:click="closeUserModal" class="px-5 py-2 rounded-full font-bold text-sm text-on-surface-variant hover:bg-surface-container-high transition">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-primary text-on-primary rounded-full font-bold text-sm shadow-md hover:shadow-lg transition">Save</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Tariff Modal -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-surface-container-low rounded-3xl p-8 max-w-md w-full shadow-xl">
            <h2 class="text-2xl font-headline font-bold text-on-surface mb-6">{{ $editingId ? 'Edit' : 'Add' }} Tariff</h2>
            <form wire:submit.prevent="save" class="space-y-4">
                <div><label class="block text-xs font-bold text-on-surface mb-1 uppercase tracking-wide">Name</label><input type="text" wire:model="name" class="w-full bg-surface-container rounded-xl border-none outline-none focus:ring-2 focus:ring-primary px-4 py-3 text-sm text-on-surface"></div>
                <div><label class="block text-xs font-bold text-on-surface mb-1 uppercase tracking-wide">Tariff Code</label><input type="text" wire:model="tariff_code" class="w-full bg-surface-container rounded-xl border-none outline-none focus:ring-2 focus:ring-primary px-4 py-3 text-sm text-on-surface"></div>
                <div><label class="block text-xs font-bold text-on-surface mb-1 uppercase tracking-wide">Power (VA)</label><input type="text" wire:model="power_va" class="w-full bg-surface-container rounded-xl border-none outline-none focus:ring-2 focus:ring-primary px-4 py-3 text-sm text-on-surface"></div>
                <div><label class="block text-xs font-bold text-on-surface mb-1 uppercase tracking-wide">Price / kWh</label><input type="number" wire:model="price_per_kwh" class="w-full bg-surface-container rounded-xl border-none outline-none focus:ring-2 focus:ring-primary px-4 py-3 text-sm text-on-surface"></div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" wire:click="closeModal" class="px-5 py-2 rounded-full font-bold text-sm text-on-surface-variant hover:bg-surface-container-high transition">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-primary text-on-primary rounded-full font-bold text-sm shadow-md hover:shadow-lg transition">Save</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Quote Modal -->
    @if($isQuoteModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 text-left">
        <div class="bg-surface-container-low rounded-3xl p-8 max-w-lg w-full shadow-xl">
            <h2 class="text-2xl font-headline font-bold text-on-surface mb-6">Review Quotation Request</h2>
            <form wire:submit.prevent="saveQuote" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface mb-1 uppercase tracking-wide">Update Status</label>
                    <select wire:model="quoteStatus" class="w-full bg-surface-container rounded-xl border-none outline-none focus:ring-2 focus:ring-primary px-4 py-3 text-sm text-on-surface">
                        <option value="requested">Requested</option>
                        <option value="reviewed">Reviewed</option>
                        <option value="quotation_sent">Quotation Sent</option>
                        <option value="accepted">Accepted</option>
                        <option value="rejected">Rejected</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface mb-1 uppercase tracking-wide">Quotation Amount (Rp)</label>
                    <input type="number" wire:model="quoteAmount" class="w-full bg-surface-container rounded-xl border-none outline-none focus:ring-2 focus:ring-primary px-4 py-3 text-sm text-on-surface" placeholder="e.g. 15000000">
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface mb-1 uppercase tracking-wide">Vendor Notes / Quotation Detail</label>
                    <textarea wire:model="quoteNotes" class="w-full bg-surface-container rounded-xl border-none outline-none focus:ring-2 focus:ring-primary px-4 py-3 text-sm text-on-surface" rows="4" placeholder="Write breakdown or notes here..."></textarea>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" wire:click="closeQuoteModal" class="px-5 py-2 rounded-full font-bold text-sm text-on-surface-variant hover:bg-surface-container-high transition">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-primary text-on-primary rounded-full font-bold text-sm shadow-md hover:shadow-lg transition">Save Quotation</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</main>
