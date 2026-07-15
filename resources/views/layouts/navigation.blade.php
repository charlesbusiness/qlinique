<nav class="navbar navbar-expand-md navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <span>{{ config('app.name', 'Laravel') }}</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="offcanvas offcanvas-start bg-primary text-white" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header border-bottom border-white border-opacity-25">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">{{ config('app.name', 'Klinical') }}</h5>
                <button type="button" class="btn-close btn-close-white text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body">

                <ul class="navbar-nav mx-auto mb-2 mb-md-0">
                    @if (Auth::user()->hasPermission('patients.view'))
                    <li class="nav-item">
                        <x-nav-link :href="route('patients.index')" :active="request()->routeIs('patients.*') && !request()->routeIs('patient-files.*')">
                            {{ __('Patients') }}
                        </x-nav-link>
                    </li>
                    <li class="nav-item">
                        <x-nav-link :href="route('patient-files.index')" :active="request()->routeIs('patient-files.*')">
                            {{ __('Files') }}
                        </x-nav-link>
                    </li>
                    @endif
                    @if (Auth::user()->hasPermission('treatments.view'))
                    <li class="nav-item">
                        <x-nav-link :href="route('treatments.index')" :active="request()->routeIs('treatments.*')">
                            {{ __('Assessments') }}
                        </x-nav-link>
                    </li>
                    @endif
                    @if (Auth::user()->hasPermission('antenatal.view'))
                    <li class="nav-item">
                        <x-nav-link :href="route('antenatal.index')" :active="request()->routeIs('antenatal.*')">
                            {{ __('Antenatal') }}
                        </x-nav-link>
                    </li>
                    @endif
                    @if (Auth::user()->hasAnyPermission('finance.invoices.view', 'finance.invoices.create', 'finance.payments.view', 'finance.payments.create'))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            {{ __('Finance') }}
                        </a>
                        <ul class="dropdown-menu">
                            @if (Auth::user()->hasPermission('finance.invoices.view'))
                            <li><a class="dropdown-item" href="{{ route('finance.invoices') }}">Invoices</a></li>
                            @endif
                            @if (Auth::user()->hasPermission('finance.payments.view'))
                            <li><a class="dropdown-item" href="{{ route('finance.payments') }}">Payments</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if (Auth::user()->hasAnyPermission('reports.daily', 'reports.treatment', 'reports.compliance', 'reports.financial'))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            {{ __('Reports') }}
                        </a>
                        <ul class="dropdown-menu">
                            @if (Auth::user()->hasPermission('reports.daily'))
                            <li><a class="dropdown-item" href="{{ route('reports.daily') }}">Daily Report</a></li>
                            @endif
                            @if (Auth::user()->hasPermission('reports.treatment'))
                            <li><a class="dropdown-item" href="{{ route('reports.treatment') }}">Assessment Report</a></li>
                            @endif
                            @if (Auth::user()->hasPermission('reports.compliance'))
                            <li><a class="dropdown-item" href="{{ route('reports.compliance') }}">Compliance Report</a></li>
                            @endif
                            @if (Auth::user()->hasPermission('reports.financial'))
                            <li><a class="dropdown-item" href="{{ route('reports.financial') }}">Financial Report</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if (Auth::user()->isSuperAdmin())
                        <li class="nav-item">
                            <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                                {{ __('Users') }}
                            </x-nav-link>
                        </li>
                    @endif
                </ul>

                <hr class="d-md-none border-white border-opacity-25">

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <x-dropdown-link :href="route('dashboard')">
                                {{ __('Dashboard') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <hr class="dropdown-divider">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <div class="dropdown-item p-0">
                                    <button type="submit" class="dropdown-item text-danger" style="cursor: pointer;">
                                        {{ __('Log Out') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>