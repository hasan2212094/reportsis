<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>

    @include('layout.style')

    {{-- ICON --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    {{-- GOOGLE FONT --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    {{-- GLOBAL STYLE --}}
    <style>
        * {

            font-family: 'Inter', sans-serif !important;

        }



        body {

            background:
                linear-gradient(135deg,
                    #f8fafc 0%,
                    #eef2ff 40%,
                    #f1f5f9 100%);

            min-height: 100vh;

        }



        /* SIDEBAR */
        #layout-menu {

            background:
                rgba(255, 255, 255, 0.75) !important;

            backdrop-filter:
                blur(18px);

            border-right:
                1px solid rgba(226, 232, 240, 0.7);

            box-shadow:
                0 10px 40px rgba(15, 23, 42, 0.05);

        }



        /* SIDEBAR TITLE */
        .app-brand-text {

            font-weight: 800 !important;

            letter-spacing: -0.5px;

            color: #0f172a !important;

        }



        /* MENU */
        .menu-inner .menu-link {

            border-radius: 18px !important;

            margin: 6px 12px;

            transition:
                all 0.25s ease;

            font-weight: 600;

            color: #334155 !important;

        }



        .menu-inner .menu-link:hover {

            background:
                linear-gradient(135deg,
                    #e0e7ff,
                    #dbeafe);

            transform:
                translateX(4px);

            box-shadow:
                0 10px 25px rgba(59, 130, 246, 0.10);

        }



        /* ACTIVE MENU */
        .menu-item.active>.menu-link {

            background:
                linear-gradient(135deg,
                    #4f46e5,
                    #2563eb) !important;

            color: white !important;

            box-shadow:
                0 12px 30px rgba(79, 70, 229, 0.35);

        }



        .menu-item.active>.menu-link i {

            color: white !important;

        }



        /* NAVBAR */
        .layout-navbar {

            background:
                rgba(255, 255, 255, 0.72) !important;

            backdrop-filter:
                blur(18px);

            border:
                1px solid rgba(255, 255, 255, 0.3);

            border-radius: 24px;

            margin: 16px;

            box-shadow:
                0 10px 40px rgba(15, 23, 42, 0.06);

        }



        /* CONTENT */
        .content-wrapper {

            padding: 24px;

        }



        /* CARD */
        .card,
        .bg-white {

            border-radius: 28px !important;

            border:
                1px solid rgba(226, 232, 240, 0.6);

            box-shadow:
                0 10px 40px rgba(15, 23, 42, 0.05);

        }



        /* BUTTON */
        button,
        .btn {

            border-radius: 18px !important;

            font-weight: 700 !important;

            transition:
                all 0.25s ease !important;

        }



        button:hover,
        .btn:hover {

            transform:
                translateY(-2px);

            box-shadow:
                0 14px 30px rgba(59, 130, 246, 0.18);

        }



        /* INPUT */
        input,
        select,
        textarea {

            border-radius: 18px !important;

            border:
                1px solid #dbe2ea !important;

            padding:
                14px 18px !important;

            transition:
                all 0.2s ease;

            background:
                rgba(255, 255, 255, 0.9);

        }



        input:focus,
        select:focus,
        textarea:focus {

            border-color:
                #6366f1 !important;

            box-shadow:
                0 0 0 4px rgba(99, 102, 241, 0.12) !important;

        }



        /* TABLE */
        table {

            border-radius: 24px;

            overflow: hidden;

        }



        th {

            background:
                #f8fafc !important;

            color:
                #0f172a !important;

            font-weight:
                800 !important;

        }



        td {

            color:
                #334155 !important;

            font-weight:
                500;

        }



        /* SCROLL */
        ::-webkit-scrollbar {

            width: 10px;
            height: 10px;

        }



        ::-webkit-scrollbar-thumb {

            background:
                linear-gradient(180deg,
                    #818cf8,
                    #3b82f6);

            border-radius: 999px;

        }
    </style>

</head>

<body>

    <div class="layout-wrapper layout-content-navbar">

        <div class="layout-container">

            {{-- SIDEBAR --}}
            @include('layout.sidebar')



            <div class="layout-page">

                {{-- NAVBAR --}}
                @include('layout.navbar')



                {{-- CONTENT --}}
                <div class="content-wrapper">

                    @yield('content')

                </div>



                {{-- FOOTER --}}
                @include('layout.footer')

            </div>

        </div>



        <div class="layout-overlay layout-menu-toggle"></div>

    </div>



    {{-- SCRIPT --}}
    @include('layout.script')

</body>

</html>
