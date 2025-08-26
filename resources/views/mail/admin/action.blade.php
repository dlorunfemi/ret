<x-mail::message>
    # {{ $title ?? 'Admin Notification' }}

    {{ $message ?? 'An administrative action occurred.' }}

    @isset($actionUrl)
        <x-mail::button :url="$actionUrl">
            View Details
        </x-mail::button>
    @endisset

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
