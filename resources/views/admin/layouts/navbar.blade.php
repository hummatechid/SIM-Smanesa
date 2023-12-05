<header>
    <nav class="navbar navbar-expand navbar-light navbar-top">
        <div class="container-fluid">
            <a href="#" class="burger-btn d-xl-none d-block">
                <i class="bi bi-justify fs-3"></i>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-lg-0">

                </ul>
                <div class="dropdown">
                    <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-menu d-flex">
                            <div class="user-name text-end me-3">
                                <h6 class="mb-0 text-gray-600">{{ Auth::user()->email }}</h6>
                                <p class="mb-0 text-sm text-gray-600">{{ Auth::user()->roles[0]->name }}</p>
                            </div>
                            <div class="user-img d-flex align-items-center">
                                <div class="avatar avatar-md">
                                    <img class="rounded-3" src="@if(Auth::user()->teacher && Auth::user()->teacher->photo)
                                        {{ asset(Storage::url(Auth::user()->teacher->photo)) }}
                                        @elseif( Auth::user()->pengguna && Auth::user()->pengguna->photo )
                                        {{ asset(Storage::url(Auth::user()->pengguna->photo)) }}
                                        @else {{ asset('assets/compiled/jpg/0.webp') }}
                                        @endif">
                                </div>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton" style="min-width: 11rem;">
                        @if(Auth::user()->teacher || Auth::user()->pengguna)
                        <li><a class="dropdown-item" href="{{ Auth::user()->hasRole('guru') ? route('teacher.show', Auth::user()->teacher->id) : route('user.show', Auth::user()->pengguna->id) }}"><i class="icon-mid bi bi-person me-2"></i> My
                                Profile</a></li>
                            <hr class="dropdown-divider">
                        @endif
                        <li>
                            <form action="{{ url('logout') }}" method="post">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="icon-mid bi bi-box-arrow-left me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
