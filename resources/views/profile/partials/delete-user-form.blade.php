<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-inside_auth::danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Delete Account') }}</x-inside_auth::danger-button>

    <x-inside_auth::modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable="">
        <form method="post" action="{{ insideauth()->route_profile_destroy }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-inside_auth::input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-inside_auth::input-text
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Password') }}"
                />

                <x-inside_auth::input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-inside_auth::secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-inside_auth::secondary-button>

                <x-inside_auth::danger-button class="ml-3">
                    {{ __('Delete Account') }}
                </x-inside_auth::danger-button>
            </div>
        </form>
    </x-inside_auth::modal>
</section>
