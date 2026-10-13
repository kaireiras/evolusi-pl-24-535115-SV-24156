<template>
  <div class="admin-wrapper">
    <aside class="admin-sidebar">
      <div class="sidebar-brand">admin panel</div>
      <nav class="sidebar-nav">
        <router-link to="/admin/blog/create" class="nav-item" active-class="active">buat</router-link>
        <router-link to="/admin/blog" class="nav-item" exact-active-class="active">list</router-link>
        <router-link to="/blog" class="nav-item">lihat publik</router-link>
        <button class="nav-item nav-logout" @click="logout">keluar</button>
      </nav>
    </aside>

    <main class="admin-main">
      <header class="main-header">
        <h2 class="main-title">{{ title }}</h2>
      </header>
      <section class="main-content">
        <slot />
      </section>
    </main>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import axios from 'axios'

defineProps({
  title: {
    type: String,
    default: ''
  }
})

const router = useRouter()
const apiUrl = import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000/api'

const logout = async () => {
  try {
    const token = localStorage.getItem('token')
    await axios.post(`${apiUrl}/logout`, {}, {
      headers: { Authorization: `Bearer ${token}` }
    })
  } catch (e) {
    // Lewati jika error
  } finally {
    localStorage.clear()
    router.push('/login')
  }
}
</script>

<style scoped>
.admin-wrapper {
  display: flex;
  min-height: 100vh;
  background: #fff;
  font-family: 'Times New Roman', serif;
  color: #000;
}

.admin-sidebar {
  width: 220px;
  border-right: 1px solid #eee;
  padding: 3rem 2rem;
  display: flex;
  flex-direction: column;
}

.sidebar-brand {
  font-size: 0.85rem;
  color: #888;
  margin-bottom: 2.5rem;
  letter-spacing: 0.05em;
}

.sidebar-nav {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.nav-item {
  font-size: 0.82rem;
  color: #888;
  text-decoration: none;
  background: none;
  border: none;
  text-align: left;
  padding: 0;
  cursor: pointer;
  transition: color 0.15s;
}

.nav-item:hover,
.nav-item.active {
  color: #000;
  font-weight: bold;
}

.nav-logout {
  margin-top: 2rem;
  color: #c00 !important;
}

.nav-logout:hover {
  text-decoration: underline;
}

.admin-main {
  flex: 1;
  padding: 3rem 4rem;
  max-width: 900px;
}

.main-header {
  margin-bottom: 2rem;
}

.main-title {
  font-size: 1.1rem;
  font-weight: normal;
  margin: 0;
}
</style>