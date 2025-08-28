@include('partials.head', ['title' => $title ?? null])

<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-3 col-lg-2 p-0 border-end min-vh-100" style="background:#5c5d60;">
            @include('admin.partials.nav')
        </div>
        <div class="col-12 col-md-9 col-lg-10 p-0">
            @include('partials.alerts')

            @yield('content')
        </div>
    </div>
</div>

@include('partials.footer')
