@component('mail::message')
    # {{ $title ?? 'Notification' }}

    {{ $message ?? '' }}

    @isset($actionUrl)
        @component('mail::button', ['url' => $actionUrl])
            View Details
        @endcomponent
    @endisset

    Thanks,
    {{ config('app.name') }}
@endcomponent

<x-mail::message>
    # {{ $title ?? 'Notification' }}

    {{ $message ?? 'An action affecting your account occurred.' }}

    @isset($actionUrl)
        <x-mail::button :url="$actionUrl">
            View Details
        </x-mail::button>
    @endisset

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
