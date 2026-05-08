{{-- <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('home') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('sneat/assets/img/logo/logo-sis.png') }}" class="img-fluid" alt="logo sis"
                    width="40" />
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2">SIS</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item active">
            <a href="{{ route('home') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="User Management">User</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('table.index') }}" class="menu-link">
                        <div data-i18n="List Users">List Users</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="/roles" class="menu-link">
                        <div data-i18n="Roles & Permissions">Roles & Permissions</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-box"></i>
                <div data-i18n="User interface">Finance&Accounting</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="/pengajuan" class="menu-link menu-toggle">
                        <div data-i18n="Pengajuan">Pengajuan</div>
                    </a>
                    <ul class="menu-sub" style="padding-left: 2.5rem;">
                        <li class="menu-item">
                            <a href="/directp" class="menu-link">
                                <div data-i18n="direct_cost">Direct Cost</div>
                            </a>
                        </li>
                    </ul>
                    <ul class="menu-sub" style="padding-left: 2.5rem;">
                        <li class="menu-item">
                            <a href="/indirectp" class="menu-link">
                                <div data-i18n="indirect_Cost">Indirect Cost</div>
                            </a>
                        </li>
                    </ul>
                    <ul class="menu-sub" style="padding-left: 2.5rem;">
                        <li class="menu-item">
                            <a href="/ppn" class="menu-link">
                                <div data-i18n="ppn">PPN</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link menu-toggle">
                        <div data-i18n="Accordion">Actual</div>
                    </a>
                    <ul class="menu-sub" style="padding-left: 2.5rem;">
                        <li class="menu-item">
                            <a href="/directa" class="menu-link">
                                <div data-i18n="direct_cost">Direct Cost</div>
                            </a>
                        </li>
                    </ul>
                    <ul class="menu-sub" style="padding-left: 2.5rem;">
                        <li class="menu-item">
                            <a href="/indirecta" class="menu-link">
                                <div data-i18n="indirect_Cost">Indirect Cost</div>
                            </a>
                        </li>
                    </ul>
                    <ul class="menu-sub" style="padding-left: 2.5rem;">
                        <li class="menu-item">
                            <a href="/ppna" class="menu-link">
                                <div data-i18n="ppn">PPN</div>
                            </a>
                        </li>
                    </ul>
                    <ul class="menu-sub" style="padding-left: 2.5rem;">
                        <li class="menu-item">
                            <a href="/luarrab" class="menu-link">
                                <div data-i18n="ppn">Diluar RAB</div>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
</aside> --}}

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('home') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('sneat/assets/img/logo/logo-sis.png') }}" width="40" />
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2">SIS</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        {{-- ================= DASHBOARD ================= --}}
        <li class="menu-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <a href="{{ route('home') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div>Dashboard</div>
            </a>
        </li>

        {{-- ================= USER (ADMIN ONLY) ================= --}}

        @if (auth()->user()->role && strtolower(auth()->user()->role->name) === 'admin')
            <li class="menu-item {{ request()->routeIs('table.*') ? 'open active' : '' }}">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div>User</div>
                </a>

                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('table.index') ? 'active' : '' }}">
                        <a href="{{ route('table.index') }}" class="menu-link">
                            <div>List Users</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endif


        {{-- ================= FINANCE & ACCOUNTING ================= --}}
        <li class="menu-item {{ request()->is('direct*', 'indirect*', 'ppn*', 'luarrab*') ? 'open active' : '' }}">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-box"></i>
                <div>Finance & Accounting</div>
            </a>

            <ul class="menu-sub">

                {{-- Pengajuan --}}
                <li class="menu-item {{ request()->is('directp', 'indirectp', 'ppn') ? 'open active' : '' }}">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <div>Pengajuan</div>
                    </a>
                    <ul class="menu-sub ps-5">
                        <li class="menu-item {{ request()->is('directp') ? 'active' : '' }}">
                            <a href="/directp" class="menu-link">
                                <div>Direct Cost</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->is('indirectp') ? 'active' : '' }}">
                            <a href="/indirectp" class="menu-link">
                                <div>Indirect Cost</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->is('ppn') ? 'active' : '' }}">
                            <a href="/ppn" class="menu-link">
                                <div>PPN</div>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Actual --}}
                <li
                    class="menu-item {{ request()->is('directa', 'indirecta', 'ppna', 'luarrab') ? 'open active' : '' }}">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <div>Actual</div>
                    </a>
                    <ul class="menu-sub ps-5">
                        <li class="menu-item {{ request()->is('directa') ? 'active' : '' }}">
                            <a href="/directa" class="menu-link">
                                <div>Direct Cost</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->is('indirecta') ? 'active' : '' }}">
                            <a href="/indirecta" class="menu-link">
                                <div>Indirect Cost</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->is('ppna') ? 'active' : '' }}">
                            <a href="/ppna" class="menu-link">
                                <div>PPN</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->is('luarrab') ? 'active' : '' }}">
                            <a href="/luarrab" class="menu-link">
                                <div>Diluar RAB</div>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </li>

        {{-- ================= MENU RAB ================= --}}
        <li class="menu-item 
{{ request()->routeIs('page.RAB.*') ? 'open active' : '' }}">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-receipt"></i>
                <div>Pengajuan Dana</div>
            </a>

            <ul class="menu-sub">

                <li class="menu-item {{ request()->routeIs('page.RAB.form.*') ? 'active' : '' }}">
                    <a href="{{ route('page.RAB.form.index') }}" class="menu-link">
                        <i class="bx bx-edit"></i>
                        <div>Form Pengajuan</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('page.RAB.approval.*') ? 'active' : '' }}">
                    <a href="{{ route('page.RAB.approval.index') }}" class="menu-link">
                        <i class="bx bx-check-square"></i>
                        <div>Approval</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('page.RAB.summary.*') ? 'active' : '' }}">
                    <a href="{{ route('page.RAB.summary.index') }}" class="menu-link">
                        <i class="bx bx-bar-chart-alt-2"></i>
                        <div>Summary</div>
                    </a>
                </li>

            </ul>
        </li>

        {{-- ================= WORKORDER ================= --}}
        <li class="menu-item {{ request()->routeIs('page.workorder.*') ? 'active' : '' }}">
            <a href="{{ route('page.workorder.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-briefcase"></i>
                <div>Workorder</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('cnc.*') ? 'active' : '' }}">
            <a href="{{ route('cnc.page') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-briefcase"></i>
                <div>Montoring CNC</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('page.Projectmanager.*') ? 'active' : '' }}">
            <a href="{{ route('page.Projectmanager.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-briefcase"></i>
                <div>Project Manager</div>
            </a>
        </li>
    </ul>
</aside>
