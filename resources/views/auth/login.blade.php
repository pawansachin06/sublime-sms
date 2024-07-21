<x-app-layout>
    <x-authentication-card>
        {{-- <div class="mb-4"><x-button.google /></div> --}}
        <form method="POST" action="{{ route('login') }}" class="block">
            <div class="px-6">
                <h2 class="text-lg text-gray-600 mb-3">Login to your account</h2>
                <x-validation-errors class="mb-4" />
                @session('status')
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ $value }}
                    </div>
                @endsession

                @csrf

                <div>
                    <x-label for="email" value="{{ __('Username or Email') }}" />
                    <x-input id="email" class="block mt-1 w-full" type="text" name="email" :value="old('email')" required autofocus autocomplete="username" />
                </div>

                <div class="mt-4 mb-4">
                    <x-label for="password" value="{{ __('Password') }}" />
                    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                {{--
                <div class="block mt-4">
                    <label for="remember_me" class="flex items-center">
                        <x-checkbox id="remember_me" name="remember" />
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>
                 --}}

            </div>
            <div class="">
                <button type="submit" class="auth-card-login-btn uppercase font-bold border-0 w-full text-white">
                    {{ __('Log in') }}
                </button>
            </div>
        </form>
    </x-authentication-card>
</x-app-layout>
