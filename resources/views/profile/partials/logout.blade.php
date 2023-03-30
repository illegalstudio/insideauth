<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Logout') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Logout from your account') }}
        </p>
    </header>

    <form method="POST" action="{{ route(insideauth()->route_logout) }}">
        @csrf
        <x-inside_auth::danger-button>{{ __('Logout') }}</x-inside_auth::danger-button>
    </form>

</section>
