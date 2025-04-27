<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            {{-- <x-authentication-card-logo /> --}}
            {{-- AGRI market --}}
            {{-- <img src="{{asset('images/agri-market.png')}}" alt="agir" class="bg-red-400 h-[200px]"> --}}
        </x-slot>

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <div class="flex flex-col justify-center items-center mt-4 mb-8">
            <p class="text-3xl text-eucalyptus-700 font-bold">AGRI-MARKET</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email') }}">Email</x-label>
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}">Password</x-label>
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            {{-- <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div> --}}

            <div class="mt-4">
                <x-button class="w-full flex items-center justify-center py-3" type="submit">
                    {{ __('Log in') }}
                </x-button>
            </div>

            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">
                    {{ __("Don't have an account?") }}
                    <a href="{{ route('register') }}" class="text-eucalyptus-700  hover:underline">
                        {{ __('Register here') }}
                    </a>
                </p>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
