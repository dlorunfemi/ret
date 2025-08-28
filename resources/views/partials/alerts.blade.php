@if (session('status'))
    <div class="container mt-3">
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    </div>
@endif

@if (session('success'))
    <div class="container mt-3">
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    </div>
@endif

@if (session('error'))
    <div class="container mt-3">
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    </div>
@endif

@if (session('warning'))
    <div class="container mt-3">
        <div class="alert alert-warning" role="alert">
            {{ session('warning') }}
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
