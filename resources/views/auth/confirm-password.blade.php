<x-inside_auth::layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route(insideauth()->route_password_confirm) }}">
        @csrf

        <!-- Password -->
        <div>
            <x-inside_auth::input-label for="password" :value="__('Password')" />

            <x-inside_auth::input-text id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required="" autocomplete="current-password"/>

            <x-inside_auth::input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-inside_auth::button-primary>
                {{ __('Confirm') }}
            </x-inside_auth::button-primary>
        </div>
    </form>
</x-inside_auth::layout>
