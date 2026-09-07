<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - surat untuk surga</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Times New Roman', Times, serif;
            background: #fff;
            color: #000;
            min-height: 100vh;
        }

        .page {
            max-width: 860px;
            margin: 0 auto;
            padding: 2.5rem 2.5rem 4rem;
        }

        h1 {
            font-size: 0.95rem;
            font-weight: normal;
            margin-bottom: 2.5rem;
            letter-spacing: 0;
        }

        /* OVERLAY */
        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.3);
            z-index: 100;
        }
        .overlay.open { display: block; }

        /* admin */
        .admin-link {
            position: fixed;
            bottom: 1.25rem;
            right: 1.5rem;
            font-family: 'Times New Roman', serif;
            font-size: 0.72rem;
            color: #bbb;
            text-decoration: none;
            border-bottom: 1px solid #ddd;
        }
        .admin-link:hover { color: #000; border-color: #000; }

        @media (max-width: 640px) {
            .page { padding: 1.5rem 1.25rem 3rem; }
        }
    </style>
    @yield('styles')
</head>
<body>

    <div class="page">
        <h1>surat untuk surga</h1>
        @yield('content')
    </div>

    <div class="overlay" id="overlay" onclick="closePopup()"></div>

    @yield('popup')

    @auth
        <a href="/admin/dashboard" class="admin-link">admin</a>
    @endauth

    @yield('scripts')
</body>
</html>