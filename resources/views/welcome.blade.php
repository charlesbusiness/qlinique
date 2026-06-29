<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ClinicManager') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light" style="font-family: 'Figtree', sans-serif;">

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-md navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a href="{{ url('/') }}" class="navbar-brand fw-bold">
                {{ config('app.name', 'ClinicManager') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                @auth
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        @if (Auth::user()->hasPermission('patients.view'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('patients.index') }}">{{ __('Patients') }}</a>
                        </li>
                        @endif
                        @if (Auth::user()->hasPermission('treatments.view'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('treatments.index') }}">{{ __('Treatments') }}</a>
                        </li>
                        @endif
                        @if (Auth::user()->hasPermission('antenatal.view'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('antenatal.index') }}">{{ __('Antenatal') }}</a>
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
                                <li><a class="dropdown-item" href="{{ route('reports.treatment') }}">Treatment Report</a></li>
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
                                <a class="nav-link" href="{{ route('users.index') }}">{{ __('Users') }}</a>
                            </li>
                        @endif
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a>
                                <hr class="dropdown-divider">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger" style="cursor: pointer;">
                                        {{ __('Log Out') }}
                                    </button>
                                </form>
                            </div>
                        </li>
                    </ul>
                @else
                    <ul class="navbar-nav ms-auto align-items-center gap-2">
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Log in</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a href="{{ route('register') }}" class="btn btn-light btn-sm">Register</a>
                            </li>
                        @endif
                    </ul>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="bg-primary text-white d-flex align-items-center" style="min-height: 100vh; background: linear-gradient(135deg, #0d5e32 0%, #094023 100%);">
        <div class="container text-center py-5">
            <h1 class="fw-bold mb-3" style="font-size: clamp(2rem, 5vw, 3.5rem);">{{ config('app.name', 'ClinicManager') }}</h1>
            <p class="lead mb-4 mx-auto opacity-90" style="max-width: 700px; font-size: clamp(1rem, 2.5vw, 1.25rem);">
                A complete electronic health record system built for clinics in low-resource settings.
                Manage patients, treatment charts, antenatal care, billing, and compliance — mobile friendly and simple to use.
            </p>

            <div class="row justify-content-center g-3 mb-5">
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="border border-light border-opacity-25 rounded-3 p-3">
                        <div class="fw-bold fs-4">500+</div>
                        <div class="small opacity-75">Patients Served</div>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="border border-light border-opacity-25 rounded-3 p-3">
                        <div class="fw-bold fs-4">10k+</div>
                        <div class="small opacity-75">Treatments</div>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="border border-light border-opacity-25 rounded-3 p-3">
                        <div class="fw-bold fs-4">99%</div>
                        <div class="small opacity-75">Uptime</div>
                    </div>
                </div>
            </div>

            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-light btn-lg px-5 fw-semibold text-primary">Go to Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-light btn-lg px-5 fw-semibold text-primary">Get Started</a>
            @endauth
        </div>
    </section>

    {{-- Features --}}
    <section class="py-5">
        <div class="container py-4">
            <h2 class="text-center fw-bold mb-2">Everything You Need</h2>
            <p class="text-center text-muted mb-5">A complete clinic management suite designed for healthcare professionals.</p>
            <div class="row g-4">

                {{-- Patient Management --}}
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary mb-3" style="width: 56px; height: 56px;">
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                            </div>
                            <h5 class="fw-bold">Patient Management</h5>
                            <p class="text-muted small mb-0">Register, search, and manage patient records with file numbers and full audit trails.</p>
                        </div>
                    </div>
                </div>

                {{-- Treatment Charts --}}
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 text-success mb-3" style="width: 56px; height: 56px;">
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                                    <rect x="9" y="3" width="6" height="4" rx="1"/>
                                    <path d="M9 14l2 2 4-4"/>
                                </svg>
                            </div>
                            <h5 class="fw-bold">Treatment Charts</h5>
                            <p class="text-muted small mb-0">Document treatments, track vitals, medications, and lab tests with a step-by-step wizard.</p>
                        </div>
                    </div>
                </div>

                {{-- Antenatal Care --}}
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10 text-warning mb-3" style="width: 56px; height: 56px;">
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 21a9 9 0 0 0 9-9H3a9 9 0 0 0 9 9Z"/>
                                    <path d="M12 3v4"/>
                                    <path d="M10 7h4"/>
                                    <path d="M22 12h-4"/>
                                    <path d="M6 12H2"/>
                                </svg>
                            </div>
                            <h5 class="fw-bold">Antenatal Care</h5>
                            <p class="text-muted small mb-0">Manage antenatal records and partograph charts for comprehensive maternal care.</p>
                        </div>
                    </div>
                </div>

                {{-- Billing & Invoicing --}}
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-info bg-opacity-10 text-info mb-3" style="width: 56px; height: 56px;">
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="1" x2="12" y2="23"/>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                </svg>
                            </div>
                            <h5 class="fw-bold">Billing &amp; Invoicing</h5>
                            <p class="text-muted small mb-0">Generate invoices, record payments, and manage financial records with auto-numbering.</p>
                        </div>
                    </div>
                </div>

                {{-- Compliance Tracking --}}
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10 text-danger mb-3" style="width: 56px; height: 56px;">
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                </svg>
                            </div>
                            <h5 class="fw-bold">Compliance Tracking</h5>
                            <p class="text-muted small mb-0">Monitor treatment compliance, consent logs, and audit trails for regulatory adherence.</p>
                        </div>
                    </div>
                </div>

                {{-- Reports & Analytics --}}
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-secondary bg-opacity-10 text-secondary mb-3" style="width: 56px; height: 56px;">
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="20" x2="18" y2="10"/>
                                    <line x1="12" y1="20" x2="12" y2="4"/>
                                    <line x1="6" y1="20" x2="6" y2="14"/>
                                </svg>
                            </div>
                            <h5 class="fw-bold">Reports &amp; Analytics</h5>
                            <p class="text-muted small mb-0">Generate daily, treatment, and financial reports with real-time compliance overviews.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-white border-top py-4 mt-auto">
        <div class="container text-center text-muted small">
            &copy; {{ date('Y') }} {{ config('app.name', 'ClinicManager') }}. All rights reserved.
        </div>
    </footer>

</body>
</html>
