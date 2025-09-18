<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email - {{ config('app.name') }}</title>
</head>

<body
    style="margin: 0; padding: 20px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; line-height: 1.6; color: #374151; background: ##fbfef9; min-height: 100vh;">
    <div
        style="max-width: 600px; margin: 0 auto; background: ##fbfef9; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); overflow: hidden; border: 1px solid #e5e7eb;">
        <div style="background: ##fbfef9; padding: 32px 30px; text-align: center; color: #374151;">
            <div
                style="width: 100%; height: 100%; margin: 0 auto 16px; background: white; display: flex; align-items: center; justify-content: center;">
                <img src="{{ secure_url('img/logo.png') }}" alt="{{ config('app.name') }}"
                    style="width: 150px; height: auto; object-fit: contain;">
            </div>
            <h1 style="font-size: 24px; font-weight: 700; margin: 0 0 6px 0; letter-spacing: -0.5px; color: #374151;">
                Verify Your Email</h1>
            <p style="font-size: 15px; opacity: 0.9; font-weight: 400; margin: 0; color: #6b7280;">Welcome to
                {{ config('app.name') }}!</p>
        </div>

        <div style="padding: 32px 32px; text-align: center;">
            <div style="font-size: 16px; color: #374151; margin-bottom: 20px; line-height: 1.6;">
                <strong>Hello{{ isset($user) && $user->name ? ' ' . $user->name : '' }}!</strong><br>
                Thanks for creating an account with us. You're almost ready to get started.
            </div>

            <p style="color: #6b7280; margin-bottom: 20px; margin-top: 0;">
                Please click the button below to verify your email address and activate your account.
            </p>

            <a href="{{ $verificationUrl }}"
                style="display: inline-block; background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; text-decoration: none; padding: 14px 28px; border-radius: 8px; font-weight: 600; font-size: 15px; letter-spacing: 0.25px; box-shadow: 0 2px 4px rgba(5, 150, 105, 0.2); margin: 16px 0;">
                ✓ Verify My Email
            </a>

            <div
                style="background: #f9fafb; border: 1px solid #d1d5db; border-radius: 8px; padding: 12px; margin: 16px 0; font-size: 13px; color: #6b7280;">
                <strong>🔒 Security Note:</strong><br>
                This verification link will expire in 60 minutes for your security.
            </div>
        </div>

        <div style="background: #f9fafb; padding: 20px 24px; text-align: center; border-top: 1px solid #e5e7eb;">
            <p style="font-size: 13px; color: #6b7280; margin: 0 0 12px 0;">
                If you didn't create this account, you can safely ignore this email.
            </p>

            <p style="font-size: 13px; color: #6b7280; margin: 0 0 10px 0;">
                <strong>Having trouble with the button?</strong><br>
                Copy and paste this link into your browser:
            </p>

            <div
                style="background: ##fbfef9; border: 1px solid #d1d5db; border-radius: 6px; padding: 12px; font-size: 12px; color: #6b7280; word-break: break-all; margin-top: 12px;">
                {{ $verificationUrl }}
            </div>

            <div style="font-size: 12px; color: #94a3b8; margin-top: 20px;">
                <p style="margin: 0 0 8px 0;">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                <p style="margin: 0;">This email was sent to {{ $user->email ?? 'your email address' }}</p>
            </div>
        </div>
    </div>
</body>

</html>