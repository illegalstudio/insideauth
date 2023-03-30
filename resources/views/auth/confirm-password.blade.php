<x-linky::layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route(insideauth()->route_password_confirm) }}">
        @csrf

        <!-- Password -->
        <div>
            <x-linky::input-label for="password" :value="__('Password')" />

            <x-linky::input-text id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required="" autocomplete="current-password"/>

            <x-linky::input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-linky::button-primary>
                {{ __('Confirm') }}
            </x-linky::button-primary>
        </div>
    </form>
</x-linky::layout>
