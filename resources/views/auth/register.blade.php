<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            {{-- Uncomment or customize the logo --}}
            {{-- <x-authentication-card-logo /> --}}
            {{-- AGRI market --}}
            {{-- <img src="{{asset('images/agri-market.png')}}" alt="Agri Market" class="bg-red-400 h-[200px]"> --}}
        </x-slot>

        <x-validation-errors class="mb-4" />

        <div class="flex flex-col justify-center items-center mt-4 mb-8">
            <p class="text-3xl text-eucalyptus-700 font-bold">AGRI-MARKET</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-label for="first_name" value="{{ __('First Name') }}">First Name</x-label>
                <x-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="first_name" />
            </div>

            <div class="mt-4">
                <x-label for="middle_name" value="{{ __('Middle Name') }}">Middle Name</x-label>
                <x-input id="middle_name" class="block mt-1 w-full" type="text" name="middle_name" :value="old('middle_name')" required autocomplete="middle_name" />
            </div>

            <div class="mt-4">
                <x-label for="last_name" value="{{ __('Last Name') }}">Last Name</x-label>
                <x-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autocomplete="last_name" />
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}">Email</x-label>
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}">Password</x-label>
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}">Confirm Password</x-label>
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />
                            <div class="ms-2 text-sm text-gray-600">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                    'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-eucalyptus-500">'.__('Terms of Service').'</a>',
                                    'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-eucalyptus-500">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif
          
            <div class="mt-4">
                <x-button class="w-full flex items-center justify-center py-3" type="submit">
                    {{ __('Register') }}
                </x-button>
            </div>
            
          
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">
                    {{ __("Don't have an account?") }}
                    <a href="{{ route('login') }}" class="text-eucalyptus-700 hover:underline">
                        {{ __('Already registered?') }}
                    </a>
                </p>
            </div>
            
            
        </form>
    </x-authentication-card>
</x-guest-layout>
