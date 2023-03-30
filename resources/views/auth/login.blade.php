<x-inside_auth::layout>
    <form method="POST" action="{{ route(insideauth()->route_login) }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-inside_auth::input-label for="email" :value="__('Email')" />
            <x-inside_auth::input-text id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required="" autofocus="" autocomplete="username" />
            <x-inside_auth::input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-inside_auth::input-label for="password" :value="__('Password')" />

            <x-inside_auth::input-text id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required="" autocomplete="current-password" />

            <x-inside_auth::input-error :messages="$errors->get('password')" class="mt-2" />
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

            <x-inside_auth::button-primary class="ml-3">
                {{ __('Log in') }}
            </x-inside_auth::button-primary>
        </div>

        <div class="mt-10 flex flex-col items-center sm:flex-col sm:items-start sm:mt-2">
            @if (insideauth()->registration_enabled)
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route(insideauth()->route_register) }}">
                    {{ __('Dont\'t have an account? Register here') }}
                </a>
            @endif
        </div>

    </form>
</x-inside_auth::layout>
