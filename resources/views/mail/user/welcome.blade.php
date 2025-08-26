<x-mail::message>
    # Welcome to {{ config('app.name') }}

    Hi {{ $user->name ?? $user->email }},

    Your email has been verified successfully. Your account is now active and you can start using all features.

    <x-mail::button :url="route('assets')">
        Go to Dashboard
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
