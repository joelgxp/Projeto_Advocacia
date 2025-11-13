<div class="modern-header">
    <div class="modern-header-content">
        <div>
            <h1 class="mb-0">@yield('page-title', 'Dashboard')</h1>
        </div>
        @if(auth()->check())
        <div class="dropdown">
            <button class="user-menu-btn dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user-circle"></i>
                <span>{{ auth()->user()->name ?? auth()->user()->email }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="userMenu" style="border-radius: 0.75rem; border: 1px solid var(--border-color);">
                <li><h6 class="dropdown-header">{{ auth()->user()->email }}</h6></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item" style="border-radius: 0;">
                            <i class="fas fa-sign-out-alt me-2"></i> Sair
                        </button>
                    </form>
                </li>
            </ul>
        </div>
        @endif
    </div>
</div>

