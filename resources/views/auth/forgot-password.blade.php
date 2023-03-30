<x-inside_auth::layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route(insideauth()->route_password_email) }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-inside_auth::input-label for="email" :value="__('Email')" />
            <x-inside_auth::input-text id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required="" autofocus=""/>
            <x-inside_auth::input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-inside_auth::button-primary>
                {{ __('Email Password Reset Link') }}
            </x-inside_auth::button-primary>
        </div>
    </form>
</x-inside_auth::layout>
