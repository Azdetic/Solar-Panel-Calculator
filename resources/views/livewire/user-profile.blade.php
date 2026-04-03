<main class="flex-grow pt-24 px-6 pb-12 w-full max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="font-headline text-3xl font-extrabold text-on-surface mt-1 tracking-tight leading-none">Profile Settings</h1>
        <p class="text-on-surface-variant text-sm mt-2 font-body max-w-2xl leading-relaxed">
            Tweak your details or change your password right here.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Edit Profile Form -->
        <div class="bg-surface-container-low p-6 rounded-2xl shadow-sm border border-outline-variant/20">
            <h2 class="font-headline text-xl font-bold mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">account_circle</span>
                Edit Info
            </h2>

            @if(session('profile_updated'))
                <div class="mb-4 bg-primary-container text-on-primary-container p-3 rounded-lg text-sm">
                    {{ session('profile_updated') }}
                </div>
            @endif

            <form wire:submit.prevent="updateProfile" class="space-y-4">
                <div class="space-y-1">
                    <label class="font-label text-xs font-bold text-outline uppercase tracking-wider">Full Name</label>
                    <input type="text" wire:model="name" class="w-full bg-surface-container-high border-none rounded-lg p-3 text-on-surface focus:ring-1 focus:ring-primary/40 transition-all text-sm">
                    @error('name') <span class="text-xs text-error">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label class="font-label text-xs font-bold text-outline uppercase tracking-wider">Email Address</label>
                    <input type="email" wire:model="email" class="w-full bg-surface-container-high border-none rounded-lg p-3 text-on-surface focus:ring-1 focus:ring-primary/40 transition-all text-sm">
                    @error('email') <span class="text-xs text-error">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="bg-primary text-on-primary px-5 py-2.5 rounded-xl font-bold text-sm shadow hover:shadow-md transition">
                    Save Changes
                </button>
            </form>
        </div>

        <!-- Change Password Form -->
        <div class="bg-surface-container-low p-6 rounded-2xl shadow-sm border border-outline-variant/20">
            <h2 class="font-headline text-xl font-bold mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">lock</span>
                Change Password
            </h2>

            @if(session('password_updated'))
                <div class="mb-4 bg-primary-container text-on-primary-container p-3 rounded-lg text-sm">
                    {{ session('password_updated') }}
                </div>
            @endif

            <form wire:submit.prevent="updatePassword" class="space-y-4">
                <div class="space-y-1">
                    <label class="font-label text-xs font-bold text-outline uppercase tracking-wider">Current Password</label>
                    <input type="password" wire:model="current_password" class="w-full bg-surface-container-high border-none rounded-lg p-3 text-on-surface focus:ring-1 focus:ring-primary/40 transition-all text-sm">
                    @error('current_password') <span class="text-xs text-error">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label class="font-label text-xs font-bold text-outline uppercase tracking-wider">New Password</label>
                    <input type="password" wire:model="password" class="w-full bg-surface-container-high border-none rounded-lg p-3 text-on-surface focus:ring-1 focus:ring-primary/40 transition-all text-sm">
                    @error('password') <span class="text-xs text-error">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label class="font-label text-xs font-bold text-outline uppercase tracking-wider">Confirm New Password</label>
                    <input type="password" wire:model="password_confirmation" class="w-full bg-surface-container-high border-none rounded-lg p-3 text-on-surface focus:ring-1 focus:ring-primary/40 transition-all text-sm">
                </div>

                <button type="submit" class="bg-tertiary text-on-tertiary px-5 py-2.5 rounded-xl font-bold text-sm shadow hover:shadow-md transition">
                    Update Password
                </button>
            </form>
        </div>
    </div>
</main>