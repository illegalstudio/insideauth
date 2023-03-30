<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form method="post" action="{{ route(insideauth()->route_profile_update) }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-inside_auth::input-label for="name" :value="__('Name')" />
            <x-inside_auth::input-text id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required="" autofocus="" autocomplete="name" />
            <x-inside_auth::input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-inside_auth::input-label for="email" :value="__('Email')" />
            <x-inside_auth::input-text id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required="" autocomplete="username" />
            <x-inside_auth::input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="flex items-center gap-4">
            <x-inside_auth::button-primary>{{ __('Save') }}</x-inside_auth::button-primary>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
