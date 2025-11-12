<aside class="col-md-3 col-lg-2 sidebar p-0">
    <div class="position-sticky pt-3">
        <div class="text-center p-3 border-bottom">
            <h4 class="text-white mb-0">
                <i class="fas fa-gavel me-2"></i>
                {{ config('app.name') }}
            </h4>
        </div>
        
        <nav class="nav flex-column p-3">
            @if(auth()->check())
                @if(auth()->user()->hasRole('admin'))
                    <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.processos.*') ? 'active' : '' }}" href="{{ route('admin.processos.index') }}">
                        <i class="fas fa-folder-open me-2"></i> Processos
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.clientes.*') ? 'active' : '' }}" href="{{ route('admin.clientes.index') }}">
                        <i class="fas fa-users me-2"></i> Clientes
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.advogados.*') ? 'active' : '' }}" href="{{ route('admin.advogados.index') }}">
                        <i class="fas fa-user-tie me-2"></i> Advogados
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.funcionarios.*') ? 'active' : '' }}" href="{{ route('admin.funcionarios.index') }}">
                        <i class="fas fa-user-friends me-2"></i> Funcionários
                    </a>
                @elseif(auth()->user()->hasRole('advogado'))
                    <a class="nav-link {{ request()->routeIs('advogado.*') ? 'active' : '' }}" href="{{ route('advogado.dashboard') }}">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                    <a class="nav-link {{ request()->routeIs('advogado.processos.*') ? 'active' : '' }}" href="{{ route('advogado.processos.index') }}">
                        <i class="fas fa-folder-open me-2"></i> Processos
                    </a>
                    <a class="nav-link {{ request()->routeIs('advogado.clientes.*') ? 'active' : '' }}" href="{{ route('advogado.clientes.index') }}">
                        <i class="fas fa-users me-2"></i> Clientes
                    </a>
                    <a class="nav-link {{ request()->routeIs('advogado.audiencias.*') ? 'active' : '' }}" href="{{ route('advogado.audiencias.index') }}">
                        <i class="fas fa-calendar-check me-2"></i> Audiências
                    </a>
                    <a class="nav-link {{ request()->routeIs('advogado.agenda.*') ? 'active' : '' }}" href="{{ route('advogado.agenda.index') }}">
                        <i class="fas fa-calendar me-2"></i> Agenda
                    </a>
                @elseif(auth()->user()->hasAnyRole(['recepcionista', 'tesoureiro']))
                    <a class="nav-link {{ request()->routeIs('recepcao.*') ? 'active' : '' }}" href="{{ route('recepcao.dashboard') }}">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                    <a class="nav-link {{ request()->routeIs('recepcao.processos.*') ? 'active' : '' }}" href="{{ route('recepcao.processos.index') }}">
                        <i class="fas fa-folder-open me-2"></i> Processos
                    </a>
                    <a class="nav-link {{ request()->routeIs('recepcao.clientes.*') ? 'active' : '' }}" href="{{ route('recepcao.clientes.index') }}">
                        <i class="fas fa-users me-2"></i> Clientes
                    </a>
                    @if(auth()->user()->hasRole('tesoureiro'))
                        <a class="nav-link {{ request()->routeIs('recepcao.receber.*') ? 'active' : '' }}" href="{{ route('recepcao.receber.index') }}">
                            <i class="fas fa-money-bill-wave me-2"></i> Contas a Receber
                        </a>
                        <a class="nav-link {{ request()->routeIs('recepcao.pagar.*') ? 'active' : '' }}" href="{{ route('recepcao.pagar.index') }}">
                            <i class="fas fa-credit-card me-2"></i> Contas a Pagar
                        </a>
                    @endif
                @elseif(auth()->user()->hasRole('cliente'))
                    <a class="nav-link {{ request()->routeIs('cliente.*') ? 'active' : '' }}" href="{{ route('cliente.dashboard') }}">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                    <a class="nav-link {{ request()->routeIs('cliente.processos.*') ? 'active' : '' }}" href="{{ route('cliente.processos.index') }}">
                        <i class="fas fa-folder-open me-2"></i> Meus Processos
                    </a>
                    <a class="nav-link {{ request()->routeIs('cliente.documentos.*') ? 'active' : '' }}" href="{{ route('cliente.documentos.index') }}">
                        <i class="fas fa-file me-2"></i> Documentos
                    </a>
                    <a class="nav-link {{ request()->routeIs('cliente.comunicacoes.*') ? 'active' : '' }}" href="{{ route('cliente.comunicacoes.index') }}">
                        <i class="fas fa-comments me-2"></i> Comunicações
                    </a>
                @endif
                
                <hr class="my-3 text-white-50">
                
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i> Sair
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endif
        </nav>
    </div>
</aside>

