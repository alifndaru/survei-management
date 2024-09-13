<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">Stisla</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">St</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('dashboard') }}"><i class="fas fa-home"></i> <span>Dashboard</span></a>
            </li>
            <li class="{{ Request::is('straus', 'straus/create') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('straus') }}"><i class="fas fa-home"></i> <span>Straus</span></a>
            </li>
            <li class="{{ Request::is('acp', 'acp/create') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('acp') }}"><i class="fas fa-home"></i> <span>ACP</span></a>
            </li>
            <li class="{{ Request::is('skala-stress', 'skala-stress/create') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('skala-stress') }}"><i class="fas fa-home"></i> <span>Skala
                        Stress</span></a>
            </li>
            <li class="{{ Request::is('all-answers') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('all-answers') }}"><i class="fas fa-home"></i> <span>All
                        Answers</span></a>
            </li>
            <li>
                <form action="{{ route('users.logout') }}" method="POST" id="logout-form">
                    @csrf
                    <a class="nav-link" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                    </a>
                </form>
            </li>
        </ul>
    </aside>
</div>
