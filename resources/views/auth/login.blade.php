<x-linky::layout>
    <form method="POST" action="{{ route(insideauth()->route_login) }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-linky::input-label for="email" :value="__('Email')" />
            <x-linky::input-text id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required="" autofocus="" autocomplete="username" />
            <x-linky::input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-linky::input-label for="password" :value="__('Password')" />

            <x-linky::input-text id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required="" autocomplete="current-password" />

            <x-linky::input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (insideauth()->forgot_password_enabled)
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route(insideauth()->route_password_request) }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-linky::button-primary class="ml-3">
                {{ __('Log in') }}
            </x-linky::button-primary>
        </div>

        <div class="mt-10 flex flex-col items-center sm:flex-col sm:items-start sm:mt-2">
            @if (insideauth()->registration_enabled)
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route(insideauth()->route_register) }}">
                    {{ __('Dont\'t have an account? Register here') }}
                </a>
            @endif
        </div>

    </form>
</x-linky::layout>
