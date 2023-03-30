<x-inside_auth::layout>
    <form method="POST" action="{{ route(insideauth()->route_password_store) }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-inside_auth::input-label for="email" :value="__('Email')" />
            <x-inside_auth::input-text id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required="" autofocus="" autocomplete="username" />
            <x-inside_auth::input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-inside_auth::input-label for="password" :value="__('Password')" />
            <x-inside_auth::input-text id="password" class="block mt-1 w-full" type="password" name="password" required="" autocomplete="new-password" />
            <x-inside_auth::input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-inside_auth::input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-inside_auth::input-text id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required="" autocomplete="new-password" />

            <x-inside_auth::input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-inside_auth::button-primary>
                {{ __('Reset Password') }}
            </x-inside_auth::button-primary>
        </div>
    </form>
</x-inside_auth::layout>
