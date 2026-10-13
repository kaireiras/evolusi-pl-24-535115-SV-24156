<template>
  <div class="login-wrapper">
    <div class="login-card">
      <!-- Kolom Kiri: Branding Teks -->
      <div class="left-pane">
        <h1 class="brand-title">
          <span>surat</span>
          <span>untuk</span>
          <span>surga</span>
        </h1>
      </div>

      <!-- Garis Pemisah Vertikal Tipis -->
      <div class="divider"></div>

      <!-- Kolom Kanan: Form Login -->
      <div class="right-pane">
        <h2 class="form-title">login</h2>

        <form @submit.prevent="handleLogin" class="form-container">
          <div class="input-group">
            <input
              type="text"
              id="username"
              v-model="username"
              placeholder=" "
              required
            />
            <label for="username">username</label>
          </div>

          <div class="input-group">
            <input
              type="password"
              id="password"
              v-model="password"
              placeholder=" "
              required
            />
            <label for="password">password</label>
          </div>

          <p v-if="errorMessage" class="error-text">{{ errorMessage }}</p>

          <div class="button-wrapper">
            <button type="submit" :disabled="loading">
              {{ loading ? '...' : 'log in' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../api'

const router = useRouter()

const username = ref('')
const password = ref('')
const loading = ref(false)
const errorMessage = ref('')

const handleLogin = async () => {
  loading.value = true
  errorMessage.value = ''

  try {
    const res = await api.post('/login', {
      email: username.value,
      password: password.value
    })

    const data = res.data

    localStorage.setItem('token', data.token)
    localStorage.setItem('user', JSON.stringify(data.user))

    if (data.user?.role === 'admin') {
      router.push('/admin/blog')
    } else {
      router.push('/blog')
    }
  } catch (err) {
    errorMessage.value = err.response?.data?.message || 'Login gagal'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Lora:wght@400;500&display=swap');

.login-wrapper {
  min-height: 100vh;
  width: 100vw;
  background-color: #ffffff;
  display: flex;
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  overflow: hidden;
}

.login-card {
  width: 100%;
  height: 100vh;
  background-color: #ffffff;
  display: flex;
  box-shadow: none;
}

/* Kolom Kiri */
.left-pane {
  flex: 1.1;
  display: flex;
  align-items: center;
  justify-content: flex-start;
  padding-left: 12vw;
}

.brand-title {
  font-family: 'Playfair Display', serif;
  font-size: clamp(2.4rem, 4vw, 3.8rem);
  font-weight: 400;
  color: #111111;
  line-height: 1.7;
  display: flex;
  flex-direction: column;
}

/* Garis Pemisah Vertikal Penuh */
.divider {
  width: 1px;
  height: 100vh;
  background-color: #000000;
}

/* Kolom Kanan */
.right-pane {
  flex: 0.9;
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 0 10vw 0 6vw;
  max-width: 480px;
}

.form-title {
  font-family: 'Playfair Display', serif;
  font-size: clamp(1.6rem, 2.2vw, 2.2rem);
  font-weight: 400;
  color: #111111;
  margin: 0 0 3.5rem 0;
}

.form-container {
  display: flex;
  flex-direction: column;
  gap: 2.5rem;
  width: 100%;
}

/* Input Minimalis Bergaris Bawah */
.input-group {
  position: relative;
  width: 100%;
}

.input-group input {
  width: 100%;
  border: none;
  border-bottom: 1px solid #777777;
  padding: 0.45rem 0;
  font-family: 'Lora', serif;
  font-size: 0.95rem;
  outline: none;
  background: transparent;
  color: #111111;
  box-sizing: border-box;
}

.input-group input:focus {
  border-bottom-color: #000000;
}

.input-group label {
  position: absolute;
  top: 0.45rem;
  left: 0;
  font-family: 'Lora', serif;
  font-size: 0.85rem;
  color: #888888;
  pointer-events: none;
  transition: 0.2s ease all;
}

.input-group input:focus ~ label,
.input-group input:not(:placeholder-shown) ~ label {
  top: -1.1rem;
  font-size: 0.75rem;
  color: #444444;
}

.error-text {
  font-family: 'Lora', serif;
  font-size: 0.8rem;
  color: #c92a2a;
  margin: -1rem 0 0 0;
}

/* Tombol Abu-abu */
.button-wrapper {
  display: flex;
  justify-content: flex-end;
  margin-top: 1.5rem;
}

button {
  background-color: #cccccc;
  color: #222222;
  border: none;
  padding: 0.5rem 1.8rem;
  font-family: 'Lora', serif;
  font-size: 0.82rem;
  cursor: pointer;
  letter-spacing: 0.5px;
  transition: background-color 0.2s;
}

button:hover {
  background-color: #bbbbbb;
}

button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>
