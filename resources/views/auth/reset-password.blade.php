<x-linky::layout>
    <form method="POST" action="{{ route(insideauth()->route_password_store) }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-linky::input-label for="email" :value="__('Email')" />
            <x-linky::input-text id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required="" autofocus="" autocomplete="username" />
            <x-linky::input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-linky::input-label for="password" :value="__('Password')" />
            <x-linky::input-text id="password" class="block mt-1 w-full" type="password" name="password" required="" autocomplete="new-password" />
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
            <x-linky::button-primary>
                {{ __('Reset Password') }}
            </x-linky::button-primary>
        </div>
    </form>
</x-linky::layout>
