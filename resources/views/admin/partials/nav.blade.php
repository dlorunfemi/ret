<div class="d-flex flex-column gap-2 p-3 text-white" style="min-height:100vh;">
    <div class="d-flex align-items-center gap-2 mb-2">
        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-white d-flex align-items-center gap-2">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" style="height:32px;">
            <span class="fw-bold">Admin</span>
        </a>
    </div>

    <hr class="border-secondary">

    <nav class="nav flex-column">
        <a class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'fw-bold' : '' }}"
            href="{{ route('admin.dashboard') }}">
            <i class="ri-dashboard-line me-1"></i> Dashboard
        </a>
        <a class="nav-link text-white {{ request()->routeIs('admin.assets.*') ? 'fw-bold' : '' }}"
            href="{{ route('admin.assets.index') }}">
            <i class="ri-bit-coin-line me-1"></i> Assets
        </a>
        <a class="nav-link text-white {{ request()->routeIs('admin.transactions.*') ? 'fw-bold' : '' }}"
            href="{{ route('admin.transactions.index') }}">
            <i class="ri-exchange-line me-1"></i> Transactions
        </a>
        <a class="nav-link text-white {{ request()->routeIs('admin.users.*') ? 'fw-bold' : '' }}"
            href="{{ route('admin.users.index') }}">
            <i class="ri-user-3-line me-1"></i> Users
        </a>
        <a class="nav-link text-white {{ request()->routeIs('admin.profile.password.*') ? 'fw-bold' : '' }}"
            href="{{ route('admin.profile.password.edit') }}">
            <i class="ri-key-2-line me-1"></i> Change Password
        </a>
    </nav>

    <div class="mt-auto">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-danger w-100"><i class="ri-logout-box-line me-1"></i> Logout</button>
        </form>
    </div>
</div>
