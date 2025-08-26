{{-- Navbar Toggle untuk Mobile --}}
<nav class="navbar navbar-dark bg-dark d-md-none">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile">
            <span class="navbar-toggler-icon"></span>
        </button>
        <span class="text-white">Admin Panel</span>
    </div>
</nav>

{{-- Sidebar Mobile: Offcanvas --}}
<div class="offcanvas offcanvas-start bg-black text-white" tabindex="-1" id="sidebarMobile">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column justify-content-between">
        {{-- Menu Container yang bisa di-scroll --}}
        <div class="sidebar-menu-container">
            <div class="profile-card text-center mb-4">
                <div class="avatar rounded-circle bg-warning text-black"
                    style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <h5 class="text-white mt-2">{{ Auth::user()->name }}</h5>
                <a href="#" class="text-warning">View Profile</a>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }} text-white"
                        href="{{ route('dashboard') }}">
                        <i class="fa fa-home me-1"></i> Dashboard
                    </a>
                </li>
            </ul>

            {{-- separate --}}
            <hr class="text-warning">
            <h6 class="text-warning mb-4">MENU</h6>
            <ul class="nav flex-column" id="mobileMainMenu">
                {{-- Dropdown: CRM --}}
                <li class="nav-item mb-2">
                    <a class="nav-link text-white dropdown-toggle {{ request()->routeIs('contacts.*') || request()->routeIs('deal.*') || request()->routeIs('pipeline.*') ? 'text-warning' : '' }}"
                        data-bs-toggle="collapse" href="#crmMenuMobile" role="button"
                        aria-expanded="{{ request()->routeIs('contacts.*') || request()->routeIs('deal.*') || request()->routeIs('pipeline.*') ? 'true' : 'false' }}"
                        aria-controls="crmMenuMobile">
                        <i class="fa fa-building me-2"></i> CRM
                    </a>
                    <div class="collapse {{ request()->routeIs('contacts.*') || request()->routeIs('deal.*') || request()->routeIs('pipeline.*') ? 'show' : '' }}"
                        id="crmMenuMobile" data-bs-parent="#mobileMainMenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('contacts.index') ? 'active' : '' }}"
                                    href="{{ route('contacts.index') }}">
                                    <i class="fa fa-address-book me-2"></i> Contacts
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('deal.index') ? 'active' : '' }}"
                                    href="{{ route('deal.index') }}">
                                    <i class="fa fa-handshake me-2"></i> Deals
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('deal.kanban') ? 'active' : '' }}"
                                    href="{{ route('deal.kanban') }}">
                                    <i class="fa fa-columns me-2"></i> Kanban Pipeline
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('pipeline.index') ? 'active' : '' }}"
                                    href="{{ route('pipeline.index') }}">
                                    <i class="fa fa-layer-group me-2"></i> Pipeline Stages
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                {{-- Dropdown: Project Management --}}
                <li class="nav-item mb-2">
                    <a class="nav-link text-white dropdown-toggle {{ request()->routeIs('projects.*') ? 'text-warning' : '' }}"
                        data-bs-toggle="collapse" href="#projectMenuMobile" role="button"
                        aria-expanded="{{ request()->routeIs('projects.*') ? 'true' : 'false' }}"
                        aria-controls="projectMenuMobile">
                        <i class="fa fa-project-diagram me-2"></i> Project Management
                    </a>
                    <div class="collapse {{ request()->routeIs('projects.*') ? 'show' : '' }}" id="projectMenuMobile"
                        data-bs-parent="#mobileMainMenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('projects.index') ? 'active' : '' }}"
                                    href="{{ route('projects.index') }}">
                                    <i class="fa fa-list me-2"></i> Daftar Proyek
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('projects.create') ? 'active' : '' }}"
                                    href="{{ route('projects.create') }}">
                                    <i class="fa fa-plus me-2"></i> Tambah Proyek
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                {{-- Dropdown: HR Management --}}
                <li class="nav-item mb-2">
                    <a class="nav-link text-white dropdown-toggle {{ request()->routeIs('employees.*') || request()->routeIs('divisions.*') || request()->routeIs('payrolls.*') ? 'text-warning' : '' }}"
                        data-bs-toggle="collapse" href="#hrmMenuMobile" role="button"
                        aria-expanded="{{ request()->routeIs('employees.*') || request()->routeIs('divisions.*') || request()->routeIs('payrolls.*') ? 'true' : 'false' }}"
                        aria-controls="hrmMenuMobile">
                        <i class="fa fa-users-cog me-2"></i> HR Management
                    </a>
                    <div class="collapse {{ request()->routeIs('employees.*') || request()->routeIs('divisions.*') || request()->routeIs('payrolls.*') ? 'show' : '' }}"
                        id="hrmMenuMobile" data-bs-parent="#mobileMainMenu">
                        <ul class="nav flex-column ms-3">
                            {{-- Employees --}}
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('employees.index') ? 'active' : '' }}"
                                    href="{{ route('employees.index') }}">
                                    <i class="fa fa-user me-2"></i> Manajemen Karyawan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('employees.create') ? 'active' : '' }}"
                                    href="{{ route('employees.create') }}">
                                    <i class="fa fa-user-plus me-2"></i> Tambah Karyawan
                                </a>
                            </li>

                            {{-- Divisions --}}
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('divisions.index') ? 'active' : '' }}"
                                    href="{{ route('divisions.index') }}">
                                    <i class="fa fa-sitemap me-2"></i> Divisi
                                </a>
                            </li>

                            {{-- Payroll --}}
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('payrolls.index') ? 'active' : '' }}"
                                    href="{{ route('payrolls.index') }}">
                                    <i class="fa fa-money-bill me-2"></i> Penggajian
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('payrolls.create') ? 'active' : '' }}"
                                    href="{{ route('payrolls.create') }}">
                                    <i class="fa fa-plus me-2"></i> Tambah Gaji
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item mb-2">
                    <a class="nav-link text-white dropdown-toggle {{ request()->routeIs('accounting.*') ? 'text-warning' : '' }}"
                        data-bs-toggle="collapse" href="#accountingMenuMobile" role="button"
                        aria-expanded="{{ request()->routeIs('accounting.*') ? 'true' : 'false' }}"
                        aria-controls="accountingMenuMobile">
                        <i class="fa fa-calculator me-2"></i> Accounting
                    </a>
                    <div class="collapse {{ request()->routeIs('accounting.*') ? 'show' : '' }}"
                        id="accountingMenuMobile" data-bs-parent="#mobileMainMenu">
                        <ul class="nav flex-column ms-3">
                            {{-- Incomes --}}
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('accounting.incomes.index') ? 'active' : '' }}"
                                    href="{{ route('accounting.incomes.index') }}">
                                    <i class="fa fa-arrow-up me-2"></i> Manajemen Pendapatan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('accounting.incomes.create') ? 'active' : '' }}"
                                    href="{{ route('accounting.incomes.create') }}">
                                    <i class="fa fa-plus me-2"></i> Tambah Pendapatan
                                </a>
                            </li>

                            {{-- Purchasings --}}
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('accounting.purchasings.index') ? 'active' : '' }}"
                                    href="{{ route('accounting.purchasings.index') }}">
                                    <i class="fa fa-shopping-cart me-2"></i> Pembelian
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('accounting.purchasings.create') ? 'active' : '' }}"
                                    href="{{ route('accounting.purchasings.create') }}">
                                    <i class="fa fa-cart-plus me-2"></i> Tambah Pembelian
                                </a>
                            </li>

                            {{-- Reports --}}
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('accounting.reports.index') ? 'active' : '' }}"
                                    href="{{ route('accounting.reports.index') }}">
                                    <i class="fa fa-chart-bar me-2"></i> Laporan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('accounting.reports.annual') ? 'active' : '' }}"
                                    href="{{ route('accounting.reports.annual') }}">
                                    <i class="fa fa-chart-bar me-2"></i> Laporan
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>

        {{-- Logout button tetap di bawah --}}
        <div class="logout-wrapper mt-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-outline-warning w-100">
                    <i class="fa fa-sign-out-alt me-1"></i> Logout
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Sidebar Desktop --}}
<div class="d-none d-md-flex flex-column justify-content-between position-fixed top-0 start-0 bg-black text-white vh-100 p-3 sidebar-desktop"
    style="width: 280px;">
    {{-- Menu Container yang bisa di-scroll --}}
    <div class="sidebar-menu-container">
        <div class="profile-card text-center mb-4">
            <div class="avatar rounded-circle bg-warning text-black"
                style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; font-size: 32px;">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <h5 class="text-white mt-2">{{ Auth::user()->name }}</h5>
            <a href="#" class="text-warning">View Profile</a>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }} text-white"
                    href="{{ route('dashboard') }}">
                    <i class="fa fa-home me-2"></i> Dashboard
                </a>
            </li>
        </ul>
        {{-- separate --}}
        <hr class="text-warning">
        <h6 class="text-warning mb-4">MENU</h6>
        <ul class="nav flex-column" id="mainMenu">

            {{-- Dropdown: Marketing Strategy --}}
            @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Master Admin') || Auth::user()->hasRole('Marketing'))
                <li class="nav-item mb-2">
                    <a class="nav-link text-white dropdown-toggle {{ request()->is('marketing*') || request()->routeIs('marketing.*') ? 'text-warning' : '' }}"
                        data-bs-toggle="collapse" href="#marketingMenu" role="button"
                        aria-expanded="{{ request()->is('marketing*') || request()->routeIs('marketing.*') ? 'true' : 'false' }}"
                        aria-controls="marketingMenu">
                        <i class="fa fa-bullhorn me-2"></i> Marketing Strategy
                    </a>
                    <div class="collapse {{ request()->is('marketing*') || request()->routeIs('marketing.*') ? 'show' : '' }}"
                        id="marketingMenu" data-bs-parent="#mainMenu">
                        <ul class="nav flex-column ms-3">
                            {{-- Kanban --}}
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('marketing.kanban') ? 'active' : '' }}"
                                    href="{{ route('marketing.kanban') }}">
                                    <i class="fa fa-tasks me-2"></i> Kanban
                                </a>
                            </li>

                            {{-- Mindmap --}}
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('marketing.mindmap') ? 'active' : '' }}"
                                    href="{{ route('marketing.mindmap') }}">
                                    <i class="fa fa-sitemap me-2"></i> Mindmap
                                </a>
                            </li>

                            {{-- Strategy --}}
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('marketing.strategy') ? 'active' : '' }}"
                                    href="{{ route('marketing.strategy') }}">
                                    <i class="fa fa-chess me-2"></i> Strategi
                                </a>
                            </li>

                            {{-- Social --}}
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('marketing.social') ? 'active' : '' }}"
                                    href="{{ route('marketing.social') }}">
                                    <i class="fa fa-share-alt me-2"></i> Sosial
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

            {{-- Dropdown: CRM --}}
            @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Master Admin') || Auth::user()->hasRole('Marketing'))
                <li class="nav-item mb-2">
                    <a class="nav-link text-white dropdown-toggle {{ request()->routeIs('contacts.*') || request()->routeIs('deal.*') || request()->routeIs('pipeline.*') ? 'text-warning' : '' }}"
                        data-bs-toggle="collapse" href="#crmMenu" role="button"
                        aria-expanded="{{ request()->routeIs('contacts.*') || request()->routeIs('deal.*') || request()->routeIs('pipeline.*') ? 'true' : 'false' }}"
                        aria-controls="crmMenu">
                        <i class="fa fa-building me-2"></i> CRM
                    </a>
                    <div class="collapse {{ request()->routeIs('contacts.*') || request()->routeIs('deal.*') || request()->routeIs('pipeline.*') ? 'show' : '' }}"
                        id="crmMenu" data-bs-parent="#mainMenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('contacts.index') ? 'active' : '' }}"
                                    href="{{ route('contacts.index') }}">
                                    <i class="fa fa-address-book me-2"></i> Contacts
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('deal.index') ? 'active' : '' }}"
                                    href="{{ route('deal.index') }}">
                                    <i class="fa fa-handshake me-2"></i> Deals
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('deal.kanban') ? 'active' : '' }}"
                                    href="{{ route('deal.kanban') }}">
                                    <i class="fa fa-columns me-2"></i> Kanban Pipeline
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('pipeline.index') ? 'active' : '' }}"
                                    href="{{ route('pipeline.index') }}">
                                    <i class="fa fa-layer-group me-2"></i> Pipeline Stages
                                </a>
                            </li>

                            {{-- quotations --}}
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('crm.quotations.index') ? 'active' : '' }}"
                                    href="{{ route('crm.quotations.index') }}">
                                    <i class="fa fa-file-invoice me-2"></i> Quotation
                                </a>
                            </li>
                            {{-- upload file  --}}
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('crm.upload.index') ? 'active' : '' }}"
                                    href="{{ route('crm.upload.index') }}">
                                    <i class="fa fa-file-upload me-2"></i> Upload Recommendations
                                </a>
                            </li>

                            {{-- ads --}}
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('crm.ads_plans.index') ? 'active' : '' }}"
                                    href="{{ route('crm.ads_plans.index') }}">
                                    <i class="fa fa-bullhorn me-2"></i> Ads Plans
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

            {{-- Dropdown: Project Management --}}
            @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Master Admin') || Auth::user()->hasRole('Project Manager'))
                <li class="nav-item mb-2">
                    <a class="nav-link text-white dropdown-toggle {{ request()->is('projects*') || request()->routeIs('projects.*') ? 'text-warning' : '' }}"
                        data-bs-toggle="collapse" href="#projectMenu" role="button"
                        aria-expanded="{{ request()->is('projects*') || request()->routeIs('projects.*') ? 'true' : 'false' }}"
                        aria-controls="projectMenu">
                        <i class="fa fa-project-diagram me-2"></i> Project Management
                    </a>
                    <div class="collapse {{ request()->is('projects*') || request()->routeIs('projects.*') ? 'show' : '' }}"
                        id="projectMenu" data-bs-parent="#mainMenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('projects.index') ? 'active' : '' }}"
                                    href="{{ route('projects.index') }}">
                                    <i class="fa fa-list me-2"></i> Daftar Proyek
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('projects.create') ? 'active' : '' }}"
                                    href="{{ route('projects.create') }}">
                                    <i class="fa fa-plus me-2"></i> Tambah Proyek
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

            {{-- Dropdown: HR Management --}}
            @if (Auth::user()->hasRole('Master Admin') || Auth::user()->hasRole('HRM'))
                <li class="nav-item mb-2">
                    <a class="nav-link text-white dropdown-toggle {{ request()->is('employees*') || request()->is('divisions*') || request()->is('payrolls*') || request()->routeIs('employees.*') || request()->routeIs('divisions.*') || request()->routeIs('payrolls.*') ? 'text-warning' : '' }}"
                        data-bs-toggle="collapse" href="#hrmMenu" role="button"
                        aria-expanded="{{ request()->is('employees*') || request()->is('divisions*') || request()->is('payrolls*') || request()->routeIs('employees.*') || request()->routeIs('divisions.*') || request()->routeIs('payrolls.*') ? 'true' : 'false' }}"
                        aria-controls="hrmMenu">
                        <i class="fa fa-users-cog me-2"></i> HR Management
                    </a>
                    <div class="collapse {{ request()->is('employees*') || request()->is('divisions*') || request()->is('payrolls*') || request()->routeIs('employees.*') || request()->routeIs('divisions.*') || request()->routeIs('payrolls.*') ? 'show' : '' }}"
                        id="hrmMenu" data-bs-parent="#mainMenu">
                        <ul class="nav flex-column ms-3">
                            {{-- Employees --}}
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('employees.index') ? 'active' : '' }}"
                                    href="{{ route('employees.index') }}">
                                    <i class="fa fa-user me-2"></i> Manajemen Karyawan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('employees.create') ? 'active' : '' }}"
                                    href="{{ route('employees.create') }}">
                                    <i class="fa fa-user-plus me-2"></i> Tambah Karyawan
                                </a>
                            </li>

                            {{-- Divisions --}}
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('divisions.index') ? 'active' : '' }}"
                                    href="{{ route('divisions.index') }}">
                                    <i class="fa fa-sitemap me-2"></i> Divisi
                                </a>
                            </li>

                            {{-- Payroll --}}
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('payrolls.index') ? 'active' : '' }}"
                                    href="{{ route('payrolls.index') }}">
                                    <i class="fa fa-money-bill me-2"></i> Penggajian
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('payrolls.create') ? 'active' : '' }}"
                                    href="{{ route('payrolls.create') }}">
                                    <i class="fa fa-plus me-2"></i> Tambah Gaji
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

            {{-- Dropdown: Accounting --}}
            @if (Auth::user()->hasRole('Master Admin') || Auth::user()->hasRole('Finance') || Auth::user()->hasRole('Purchasing'))
                <li class="nav-item mb-2">
                    <a class="nav-link text-white dropdown-toggle {{ request()->is('accounting*') || request()->routeIs('accounting.*') ? 'text-warning' : '' }}"
                        data-bs-toggle="collapse" href="#accountingMenu" role="button"
                        aria-expanded="{{ request()->is('accounting*') || request()->routeIs('accounting.*') ? 'true' : 'false' }}"
                        aria-controls="accountingMenu">
                        <i class="fa fa-calculator me-2"></i> Accounting
                    </a>
                    <div class="collapse {{ request()->is('accounting*') || request()->routeIs('accounting.*') ? 'show' : '' }}"
                        id="accountingMenu" data-bs-parent="#mainMenu">
                        <ul class="nav flex-column ms-3">
                            {{-- Incomes --}}
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('accounting.incomes.index') ? 'active' : '' }}"
                                    href="{{ route('accounting.incomes.index') }}">
                                    <i class="fa fa-arrow-up me-2"></i> Manajemen Pendapatan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('accounting.incomes.create') ? 'active' : '' }}"
                                    href="{{ route('accounting.incomes.create') }}">
                                    <i class="fa fa-plus me-2"></i> Tambah Pendapatan
                                </a>
                            </li>

                            {{-- Purchasings --}}
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('accounting.purchasings.index') ? 'active' : '' }}"
                                    href="{{ route('accounting.purchasings.index') }}">
                                    <i class="fa fa-shopping-cart me-2"></i> Pembelian
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('accounting.purchasings.create') ? 'active' : '' }}"
                                    href="{{ route('accounting.purchasings.create') }}">
                                    <i class="fa fa-cart-plus me-2"></i> Tambah Pembelian
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('accounting.loans.index') ? 'active' : '' }}"
                                    href="{{ route('accounting.loans.index') }}">
                                    <i class="fa fa-credit-card me-2"></i> Kredit
                                </a>
                            </li>

                            {{-- Reports --}}
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('accounting.reports.index') ? 'active' : '' }}"
                                    href="{{ route('accounting.reports.index') }}">
                                    <i class="fa fa-chart-bar me-2"></i> Laporan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('accounting.reports.annual') ? 'active' : '' }}"
                                    href="{{ route('accounting.reports.annual') }}">
                                    <i class="fa fa-chart-bar me-2"></i> Annual Report
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('accounting.tax.index') ? 'active' : '' }}"
                                    href="{{ route('accounting.tax.index') }}">
                                    <i class="fa fa-chart-bar me-2"></i> Tax Report
                                </a>
                            </li>
                            {{-- invoices --}}
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('accounting.invoices.index') ? 'active' : '' }}"
                                    href="{{ route('accounting.invoices.index') }}">
                                    <i class="fa fa-file-invoice me-2"></i> Invoices
                                </a>
                            </li>
                            {{-- recepits --}}
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('accounting.receipts.index') ? 'active' : '' }}"
                                    href="{{ route('accounting.receipts.index') }}">
                                    <i class="fa fa-receipt me-2"></i> Receipts
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

            {{-- Dropdown: RBAC --}}
            @if (Auth::user()->hasRole('Master Admin'))
                <li class="nav-item mb-2">
                    <a class="nav-link text-white dropdown-toggle {{ request()->is('rbac*') || request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? 'text-warning' : '' }}"
                        data-bs-toggle="collapse" href="#rbacMenu" role="button"
                        aria-expanded="{{ request()->is('rbac*') || request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? 'true' : 'false' }}"
                        aria-controls="rbacMenu">
                        <i class="fa fa-user-shield me-2"></i> RBAC
                    </a>
                    <div class="collapse {{ request()->is('rbac*') || request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? 'show' : '' }}"
                        id="rbacMenu" data-bs-parent="#mainMenu">
                        <ul class="nav flex-column ms-3">

                            {{-- Roles --}}
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('roles.index') ? 'active' : '' }}"
                                    href="{{ route('roles.index') }}">
                                    <i class="fa fa-user-tag me-2"></i> Roles
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('roles.create') ? 'active' : '' }}"
                                    href="{{ route('roles.index') }}"> {{-- Roles create biasanya masuk halaman index --}}
                                    <i class="fa fa-plus me-2"></i> Tambah Role
                                </a>
                            </li>

                            {{-- Permissions --}}
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('permissions.index') ? 'active' : '' }}"
                                    href="{{ route('permissions.index') }}">
                                    <i class="fa fa-key me-2"></i> Permissions
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('permissions.create') ? 'active' : '' }}"
                                    href="{{ route('permissions.index') }}"> {{-- Permissions create masuk halaman index --}}
                                    <i class="fa fa-plus me-2"></i> Tambah Permission
                                </a>
                            </li>
                            {{-- tambah user --}}
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('users.index') ? 'active' : '' }}"
                                    href="{{ route('users.index') }}">
                                    <i class="fa fa-users me-2"></i> Users
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

        </ul>
    </div>

    {{-- Logout button tetap di bawah --}}
    <div class="logout-wrapper mt-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-sm btn-outline-warning w-100">
                <i class="fa fa-sign-out-alt me-1"></i> Logout
            </button>
        </form>
    </div>
</div>

{{-- CSS untuk Sidebar & Button --}}
<style>
    .nav-link {
        transition: all 0.3s ease;
        padding: 10px 15px;
        margin: 2px 0;
        border-radius: 5px;
        display: flex;
        align-items: center;
        text-decoration: none;
    }

    .nav-link:hover {
        background-color: rgb(255, 96, 0);
        color: white !important;
        text-decoration: none;
    }

    .nav-link.active {
        background-color: rgb(255, 191, 0);
        color: black !important;
    }

    .nav-link.dropdown-toggle {
        position: relative;
    }

    .nav-link.dropdown-toggle::after {
        content: '\f078';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        right: 15px;
        transition: transform 0.3s ease;
    }

    .nav-link.dropdown-toggle[aria-expanded="true"]::after {
        transform: rotate(180deg);
    }

    .nav-link.dropdown-toggle.collapsed::after {
        transform: rotate(0deg);
    }

    /* Indentasi untuk submenu */
    .collapse .nav-link {
        padding-left: 25px;
        font-size: 0.9em;
        border-left: 2px solid rgb(255, 191, 0);
        margin-left: 10px;
        border-radius: 0 5px 5px 0;
    }

    .collapse .nav-link:hover {
        background-color: rgb(255, 96, 0);
        border-left-color: rgb(255, 96, 0);
    }

    .collapse .nav-link.active {
        background-color: rgb(255, 191, 0);
        color: black !important;
        border-left-color: rgb(255, 96, 0);
    }

    /* Smooth collapse animation */
    .collapse {
        transition: height 0.3s ease;
    }

    .nav-item button {
        background-color: rgb(255, 96, 0);
        color: white;
        border-radius: 5px;
        border: none;
        transition: all 0.3s ease;
    }

    .nav-item button:hover {
        background-color: rgb(255, 191, 0);
        color: black;
    }

    .offcanvas-body {
        background-color: rgb(0, 0, 0);
    }

    .offcanvas-title {
        color: rgb(255, 191, 0);
    }

    .profile-card {
        background-color: rgba(255, 255, 255, 0.1);
        padding: 15px;
        border-radius: 10px;
    }

    .avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 80px;
        height: 80px;
        font-size: 32px;
        border-radius: 50%;
        font-weight: bold;
        color: #000;
        background-color: rgb(255, 191, 0);
        margin: 0 auto;
    }

    .profile-card a {
        font-size: 14px;
        text-decoration: none;
    }

    .profile-card a:hover {
        text-decoration: underline;
        color: rgb(255, 96, 0);
    }

    .logout-wrapper {
        padding-bottom: 1rem;
    }

    /* Responsive fixes */
    @media (max-width: 768px) {
        .offcanvas-body {
            padding: 1rem;
        }

        .profile-card .avatar {
            width: 60px;
            height: 60px;
            font-size: 24px;
        }
    }

    /* Icon spacing consistency */
    .nav-link i {
        width: 20px;
        text-align: center;
    }

    /* Highlight dropdown parent when child is active */
    .nav-link.text-warning {
        color: rgb(255, 191, 0) !important;
    }
</style>

<style>
    /* Desktop Sidebar - Scrollable */
    .sidebar-desktop {
        height: 100vh;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: rgb(255, 191, 0) transparent;
    }

    .sidebar-desktop::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-desktop::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
    }

    .sidebar-desktop::-webkit-scrollbar-thumb {
        background: rgb(255, 191, 0);
        border-radius: 3px;
    }

    .sidebar-desktop::-webkit-scrollbar-thumb:hover {
        background: rgb(255, 96, 0);
    }

    /* Mobile Offcanvas - Scrollable */
    .offcanvas-body {
        overflow-y: auto;
        max-height: calc(100vh - 120px);
    }

    /* Ensure the menu container is scrollable */
    .sidebar-menu-container {
        flex: 1;
        overflow-y: auto;
        padding-right: 5px;
    }

    /* Smooth scrolling */
    .sidebar-desktop,
    .offcanvas-body,
    .sidebar-menu-container {
        scroll-behavior: smooth;
    }

    .nav-link {
        transition: all 0.3s ease;
        padding: 10px 15px;
        margin: 2px 0;
        border-radius: 5px;
        display: flex;
        align-items: center;
        text-decoration: none;
    }

    .nav-link:hover {
        background-color: rgb(255, 96, 0);
        color: white !important;
        text-decoration: none;
    }

    .nav-link.active {
        background-color: rgb(255, 191, 0);
        color: black !important;
    }

    .nav-link.dropdown-toggle {
        position: relative;
    }

    .nav-link.dropdown-toggle::after {
        content: '\f078';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        right: 15px;
        transition: transform 0.3s ease;
    }

    .nav-link.dropdown-toggle[aria-expanded="true"]::after {
        transform: rotate(180deg);
    }

    .nav-link.dropdown-toggle.collapsed::after {
        transform: rotate(0deg);
    }

    /* Indentasi untuk submenu */
    .collapse .nav-link {
        padding-left: 25px;
        font-size: 0.9em;
        border-left: 2px solid rgb(255, 191, 0);
        margin-left: 10px;
        border-radius: 0 5px 5px 0;
    }

    .collapse .nav-link:hover {
        background-color: rgb(255, 96, 0);
        border-left-color: rgb(255, 96, 0);
    }

    .collapse .nav-link.active {
        background-color: rgb(255, 191, 0);
        color: black !important;
        border-left-color: rgb(255, 96, 0);
    }

    /* Smooth collapse animation */
    .collapse {
        transition: height 0.3s ease;
    }

    .nav-item button {
        background-color: rgb(255, 96, 0);
        color: white;
        border-radius: 5px;
        border: none;
        transition: all 0.3s ease;
    }

    .nav-item button:hover {
        background-color: rgb(255, 191, 0);
        color: black;
    }

    .offcanvas-body {
        background-color: rgb(0, 0, 0);
    }

    .offcanvas-title {
        color: rgb(255, 191, 0);
    }

    .profile-card {
        background-color: rgba(255, 255, 255, 0.1);
        padding: 15px;
        border-radius: 10px;
    }

    .avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 80px;
        height: 80px;
        font-size: 32px;
        border-radius: 50%;
        font-weight: bold;
        color: #000;
        background-color: rgb(255, 191, 0);
        margin: 0 auto;
    }

    .profile-card a {
        font-size: 14px;
        text-decoration: none;
    }

    .profile-card a:hover {
        text-decoration: underline;
        color: rgb(255, 96, 0);
    }

    .logout-wrapper {
        padding-bottom: 1rem;
        flex-shrink: 0;
        /* Prevent logout button from shrinking */
    }

    /* Responsive fixes */
    @media (max-width: 768px) {
        .offcanvas-body {
            padding: 1rem;
        }

        .profile-card .avatar {
            width: 60px;
            height: 60px;
            font-size: 24px;
        }

        .sidebar-menu-container {
            max-height: calc(100vh - 200px);
            /* Adjust for mobile */
        }
    }

    /* Icon spacing consistency */
    .nav-link i {
        width: 20px;
        text-align: center;
    }

    /* Highlight dropdown parent when child is active */
    .nav-link.text-warning {
        color: rgb(255, 191, 0) !important;
    }

    /* Additional scrollbar styling for mobile */
    .sidebar-menu-container::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar-menu-container::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 2px;
    }

    .sidebar-menu-container::-webkit-scrollbar-thumb {
        background: rgb(255, 191, 0);
        border-radius: 2px;
    }

    .sidebar-menu-container::-webkit-scrollbar-thumb:hover {
        background: rgb(255, 96, 0);
    }
</style>
