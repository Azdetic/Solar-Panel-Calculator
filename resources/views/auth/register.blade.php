@extends('layouts.calculator')

@section('content')
<main class="flex-grow flex items-center justify-center pt-16 pb-8 px-6 w-full">
    <div class="bg-surface-container-low p-8 rounded-2xl shadow-sm border border-outline-variant/20 w-full max-w-md">
        <div class="text-center mb-8">
            <span class="material-symbols-outlined text-4xl text-primary font-bold">solar_power</span>
            <h1 class="font-headline text-2xl font-extrabold text-on-surface mt-2 tracking-tight">Create Account</h1>
            <p class="text-outline-variant text-sm mt-1">Start saving simulations to your account</p>
        </div>

        @if($errors->any())
        <div class="mb-4 bg-error-container text-on-error-container p-4 rounded-lg text-sm font-body shadow-sm border border-error">
            <ul class="list-disc pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            
            <div class="space-y-1">
                <label class="font-label text-xs font-bold text-outline uppercase tracking-wider">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus class="w-full bg-surface-container-high border-none rounded-lg p-3 text-on-surface focus:ring-1 focus:ring-primary/40 transition-all font-body text-sm">
            </div>

            <div class="space-y-1">
                <label class="font-label text-xs font-bold text-outline uppercase tracking-wider">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-surface-container-high border-none rounded-lg p-3 text-on-surface focus:ring-1 focus:ring-primary/40 transition-all font-body text-sm">
            </div>

            <div class="space-y-1">
                <label class="font-label text-xs font-bold text-outline uppercase tracking-wider">Password</label>
                <input type="password" name="password" required class="w-full bg-surface-container-high border-none rounded-lg p-3 text-on-surface focus:ring-1 focus:ring-primary/40 transition-all font-body text-sm">
            </div>

            <div class="space-y-1">
                <label class="font-label text-xs font-bold text-outline uppercase tracking-wider">Confirm Password</label>
                <input type="password" name="password_confirmation" required class="w-full bg-surface-container-high border-none rounded-lg p-3 text-on-surface focus:ring-1 focus:ring-primary/40 transition-all font-body text-sm">
            </div>

            <button type="submit" class="w-full solar-gradient text-white py-3.5 rounded-xl font-headline font-extrabold text-sm shadow-lg hover:-translate-y-0.5 active:scale-95 transition-all mt-6">
                Register
            </button>
        </form>

        <p class="text-center text-xs text-outline mt-6">
            Already have an account? <a href="{{ route('login') }}" class="text-primary font-bold hover:underline">Sign in</a>
        </p>
    </div>
</main>
@endsection
