<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title') — surat untuk surga</title>
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
  font-family: 'Times New Roman', Times, serif;
  background: #fff;
  color: #000;
  min-height: 100vh;
  display: flex;
}

/* SIDEBAR */
.sidebar {
  width: 180px;
  flex-shrink: 0;
  border-right: 1px solid #000;
  min-height: 100vh;
  padding: 2rem 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 2rem;
  position: fixed;
  top: 0; left: 0; bottom: 0;
}

.sidebar-title {
  font-size: 0.82rem;
  font-weight: normal;
  line-height: 1.4;
}
.sidebar-title a { text-decoration: none; color: #000; }

.sidebar-nav {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.nav-item {
  font-size: 0.78rem;
  cursor: pointer;
  padding: 0.2rem 0;
  color: #888;
  border: none;
  background: none;
  font-family: 'Times New Roman', serif;
  text-align: left;
  transition: color 0.15s;
}
.nav-item:hover { color: #000; }
.nav-item.active { color: #000; border-bottom: 1px solid #000; display: inline-block; }

.sidebar-back {
  margin-top: auto;
  font-size: 0.72rem;
  color: #bbb;
  text-decoration: none;
}
.sidebar-back:hover { color: #000; }

/* MAIN */
.main {
  margin-left: 180px;
  flex: 1;
  padding: 2rem 2.5rem 4rem;
  max-width: 700px;
}

.main-title {
  font-size: 0.82rem;
  font-weight: normal;
  margin-bottom: 2rem;
  color: #888;
}

/* TOAST */
.toast {
  position: fixed;
  bottom: 1.5rem;
  right: 1.5rem;
  background: #000;
  color: #fff;
  font-family: 'Times New Roman', serif;
  font-size: 0.78rem;
  padding: 0.6rem 1rem;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.25s;
}
.toast.show { opacity: 1; }

/* MOBILE */
@media (max-width: 640px) {
  body { flex-direction: column; }
  .sidebar { position: relative; width: 100%; min-height: auto; flex-direction: row; flex-wrap: wrap; gap: 1rem; padding: 1.25rem; border-right: none; border-bottom: 1px solid #000; }
  .sidebar-nav { flex-direction: row; }
  .main { margin-left: 0; padding: 1.25rem; }
}

@yield('styles')
</style>
</head>
<body>

<aside class="sidebar">
  <div class="sidebar-title"><a href="/blog">surat untuk surga</a></div>
  <nav class="sidebar-nav" id="sidebar-nav">
    @yield('sidebar-nav')
  </nav>
  <a href="/blog" class="sidebar-back">← kembali</a>
</aside>

<main class="main">
  <div class="main-title" id="main-title">@yield('main-title')</div>
  @yield('content')
</main>

<div class="toast" id="toast"></div>

<script>
function showToast(msg) {
  const toast = document.getElementById('toast');
  toast.textContent = msg;
  toast.classList.add('show');
  setTimeout(() => toast.classList.remove('show'), 2000);
}
</script>

@yield('scripts')
</body>
</html>