@include('partials.head', ['title' => $title ?? null])

<!--@include('partials.alerts')-->

@yield('content')

@include('partials.footer')
