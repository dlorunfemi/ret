    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="https://retrixnet.com">
                <img src="{{ asset('img/logo.png') }}" alt="Logo">
            </a>
            <button class="navbar-toggler border border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-3">
                    <li class="nav-item dropdown">
                        <a class="nav-link " href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Insight<i class="ri-arrow-down-s-line"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="https://retrixnet.com/about-us/">About Us</a></li>
                            <li><a class="dropdown-item" href="https://retrixnet.com/terms-conditions/">Terms & conditions</a></li>
                            <li><a class="dropdown-item" href="https://retrixnet.com/privacy-policy/">Privacy Policy</a></li>
                        </ul>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ route('assets') }}">Portfolio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ route('settings') }}">Settings</a>
                        </li>
                    @endauth
                    <li class="nav-item">
                        <a class="nav-link" href="https://retrixnet.com/roadmap/">Roadmap</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://retrixnet.com/contact-us/">Contact Us</a>
                    </li>

                </ul>
                <div class="d-flex mx-lg-3">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn_blue_outline px-5" type="submit">Login</a>
                        <a href="{{ route('signup') }}" class="btn btn_red ms-3 px-5" type="submit">Signup</a>
                        {{-- <a href="{{ route('password.request') }}" class="btn btn_blue_outline ms-3 px-5"
                            type="submit">Forgot</a> --}}
                    @endguest
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn_red px-5" type="submit">Logout</button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    <!-- Navbar -->
