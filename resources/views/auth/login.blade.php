@extends('layouts.calculator')

@section('content')
<main class="flex-grow flex items-center justify-center pt-24 pb-8 px-6 w-full">
    <div class="bg-surface-container-low p-8 rounded-2xl shadow-sm border border-outline-variant/20 w-full max-w-md">
        <div class="text-center mb-8">
            <span class="material-symbols-outlined text-4xl text-primary font-bold">login</span>
            <h1 class="font-headline text-2xl font-extrabold text-on-surface mt-2 tracking-tight">Sign In</h1>
            <p class="text-outline-variant text-sm mt-1">Log in to save your sims and ask for quotes.</p>
        </div>

        @if($errors->any())
        <div class="mb-4 bg-error-container text-on-error-container p-4 rounded-lg text-sm font-body shadow-sm border border-error">
            <ul class="list-disc pl-4 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div class="space-y-1">
                <label class="font-label text-xs font-bold text-outline uppercase tracking-wider">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full bg-surface-container-high border-none rounded-lg p-3 text-on-surface focus:ring-1 focus:ring-primary/40 transition-all font-body text-sm">
            </div>

            <div class="space-y-1">
                <label class="font-label text-xs font-bold text-outline uppercase tracking-wider">Password</label>
                <input type="password" name="password" required class="w-full bg-surface-container-high border-none rounded-lg p-3 text-on-surface focus:ring-1 focus:ring-primary/40 transition-all font-body text-sm">
            </div>

            <button type="submit" class="w-full bg-primary text-on-primary py-3.5 rounded-xl font-headline font-extrabold text-sm shadow-md hover:shadow-lg transition-all mt-6 tracking-wide">
                Sign In
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-outline-variant/20 text-center text-sm">
            <p class="text-on-surface-variant">Don't have an account yet? <a href="{{ route('register') }}" class="text-primary font-bold hover:underline transition-colors">Sign up here</a></p>
        </div>
    </div>
</main>
@endsection
