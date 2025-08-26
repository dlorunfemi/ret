@include('partials.head', ['title' => $title ?? null])

<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-3 col-lg-2 p-0 border-end min-vh-100" style="background:#0b1221;">
            @include('admin.partials.nav')
        </div>
        <div class="col-12 col-md-9 col-lg-10 p-0">
            @if (session('status'))
                <div class="container mt-3">
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="container mt-3">
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

@include('partials.footer')
