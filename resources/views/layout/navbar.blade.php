<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center">
                <img src="{{ asset('sneat/assets/img/logo/logo-sis.png') }}" alt="SIS Logo"
                    style="max-height: 40px; margin-right: 10px;">
                <span class="fw-bold text-uppercase" style="font-size: 16px;">
                    PT. SRIBARU INDAH SEJAHTERA
                </span>
            </div>
        </div>

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- User -->
            @php
                $user = Auth::user();
            @endphp

            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        @if ($user && $user->profile_picture)
                            {{-- ✅ Foto profil jika ada --}}
                            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}"
                                class="w-px-40 h-auto rounded-circle" />
                        @elseif ($user)
                            {{-- ✅ Inisial huruf jika tidak ada foto --}}
                            <span
                                class="avatar-initial rounded-circle bg-primary text-white w-px-40 h-px-40 d-flex align-items-center justify-content-center">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        @else
                            {{-- ✅ Guest mode (belum login / session expired) --}}
                            <span
                                class="avatar-initial rounded-circle bg-secondary text-white w-px-40 h-px-40 d-flex align-items-center justify-content-center">
                                G
                            </span>
                        @endif
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online d-flex align-items-center justify-content-center 
                                        rounded-circle bg-primary text-white fw-bold"
                                        style="width:40px; height:40px;">
                                        {{ strtoupper(substr($user?->name ?? 'G', 0, 1)) }}
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">
                                        {{ $user?->name ?? 'Guest' }}
                                    </span>
                                    <small class="text-muted">
                                        {{ ucfirst($user?->role ?? 'Guest') }}
                                    </small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>

                    {{-- ✅ Tampilkan tombol logout hanya jika login --}}
                    @if ($user)
                        <li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item" type="submit">
                                    <i class="bx bx-power-off me-2"></i>
                                    <span class="align-middle">Log Out</span>
                                </button>
                            </form>
                        </li>
                    @endif
                </ul>
            </li>
        </ul>
    </div>
</nav>

<script>
    // Clock digital (biar tampil jam real-time)
    function clock() {
        var time = new Date(),
            hours = time.getHours(),
            minutes = time.getMinutes(),
            seconds = time.getSeconds(),
            ampm = hours >= 12 ? 'PM' : 'AM';

        hours = hours % 12 || 12;
        document.querySelectorAll('.clock')[0].innerHTML =
            String(hours).padStart(2, '0') + ":" +
            String(minutes).padStart(2, '0') + ":" +
            String(seconds).padStart(2, '0') + " " + ampm;
    }
    setInterval(clock, 1000);
</script>
