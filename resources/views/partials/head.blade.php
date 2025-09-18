<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Basic Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'App - Retrixnet' }}</title>
    <meta name="title" content="App - Retrixnet">
    <meta name="description" content="A concise, compelling description of your page's content (150-160 characters).">
    <meta name="keywords" content="keyword1, keyword2, keyword3">
    <meta name="author" content="Your Name">

    <meta property="og:type" content="website">
    <meta property="og:url" content="https://yourwebsite.com/page-url/">
    <meta property="og:title" content="App - Retrixnet">
    <meta property="og:description"
        content="A concise, compelling description of your page's content (150-160 characters).">
    <meta property="og:image" content="https://yourwebsite.com/images/social-preview-image.jpg">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://yourwebsite.com/page-url/">
    <meta property="twitter:title" content="App - Retrixnet">
    <meta property="twitter:description"
        content="A concise, compelling description of your page's content (150-160 characters).">
    <meta property="twitter:image" content="https://yourwebsite.com/images/social-preview-image.jpg">

    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.svg') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />
</head>

<body>
