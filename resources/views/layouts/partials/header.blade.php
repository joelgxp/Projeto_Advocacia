<header class="d-flex justify-content-between align-items-center py-3 mb-4 border-bottom">
    <h1 class="h3 mb-0">@yield('page-title', 'Dashboard')</h1>
    
    @auth
        <div class="d-flex align-items-center">
            <!-- Notificações -->
            <div class="dropdown me-3">
                <button class="btn btn-link text-dark position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell fa-lg"></i>
                    @if(auth()->user()->notificacoes()->where('lida', false)->count() > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ auth()->user()->notificacoes()->where('lida', false)->count() }}
                        </span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                    <li><h6 class="dropdown-header">Notificações</h6></li>
                    @forelse(auth()->user()->notificacoes()->latest()->limit(5)->get() as $notificacao)
                        <li>
                            <a class="dropdown-item {{ !$notificacao->lida ? 'bg-light' : '' }}" href="{{ $notificacao->link ?? '#' }}">
                                <div class="d-flex w-100 justify-content-between">
                                    <small class="fw-bold">{{ $notificacao->titulo }}</small>
                                    <small class="text-muted">{{ $notificacao->created_at->diffForHumans() }}</small>
                                </div>
                                <small class="text-muted">{{ Str::limit($notificacao->mensagem, 50) }}</small>
                            </a>
                        </li>
                    @empty
                        <li><span class="dropdown-item text-muted">Nenhuma notificação</span></li>
                    @endforelse
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-center" href="#">Ver todas</a></li>
                </ul>
            </div>
            
            <!-- User Menu -->
            <div class="dropdown">
                <button class="btn btn-link text-dark text-decoration-none" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle fa-lg me-2"></i>
                    {{ auth()->user()->name }}
                    <i class="fas fa-chevron-down ms-2 small"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><h6 class="dropdown-header">{{ auth()->user()->roles->first()->name ?? 'Usuário' }}</h6></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Meu Perfil</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Configurações</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i> Sair
                        </a>
                        <form id="logout-form-header" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    @endauth
</header>



