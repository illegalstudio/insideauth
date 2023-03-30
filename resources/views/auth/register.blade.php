<x-linky::layout>
    <form method="POST" action="{{ route(insideauth()->route_register) }}">
        @csrf

        <!-- Name -->
        <div>
            <x-linky::input-label for="name" :value="__('Name')" />
            <x-linky::input-text id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required="" autofocus="" autocomplete="name" />
            <x-linky::input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-linky::input-label for="email" :value="__('Email')" />
            <x-linky::input-text id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required="" autocomplete="username" />
            <x-linky::input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-linky::input-label for="password" :value="__('Password')" />

            <x-linky::input-text id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required="" autocomplete="new-password" />

            <x-linky::input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-linky::input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-linky::input-text id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required="" autocomplete="new-password" />

            <x-linky::input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route(insideauth()->route_login) }}">
                {{ __('Already registered?') }}
            </a>

            <x-linky::button-primary class="ml-4">
                {{ __('Register') }}
            </x-linky::button-primary>
        </div>
    </form>
</x-linky::layout>
