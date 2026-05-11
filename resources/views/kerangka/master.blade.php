{{-- <!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free">
@include('layout.style')

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('layout.sidebar')
            <div class="layout-page">
                @include('layout.navbar')
                <div class="content-wrapper">
                    @yield('content')
                    @include('layout.footer')
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    @include('layout.script')
</body>

</html> --}}
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free">
@include('layout.style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('layout.sidebar')

            <div class="layout-page">
                @include('layout.navbar')

                <div class="content-wrapper">
                    @yield('content')
                </div>

                {{-- Footer dipindahkan ke luar content-wrapper --}}
                @include('layout.footer')
            </div>
        </div>

        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    @include('layout.script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const activeMenu = document.querySelectorAll(".menu-item.active > .menu-link");

            activeMenu.forEach(link => {
                link.style.background = "#eef2ff";
                link.style.borderLeft = "4px solid #696cff";
                link.style.borderRadius = "8px";
                link.style.boxShadow = "0 4px 10px rgba(105,108,255,0.08)";
                link.style.fontWeight = "600";
                link.style.transition = "all 0.3s ease";

                const icon = link.querySelector("i");
                if (icon) {
                    icon.style.color = "#696cff";
                }
            });

        });
    </script>

</body>

</html>
