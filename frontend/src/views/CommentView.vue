<template>
  <div>
    <h1>Daftar Komentar / Lilin Doa</h1>
    <router-link to="/">Kembali ke Beranda</router-link>
    <ul>
      <li v-for="item in comments" :key="item.id">
        <strong>{{ item.pengirim || 'Anonim' }}:</strong> {{ item.isi_balasan }}
      </li>
    </ul>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const comments = ref([])
const apiUrl = import.meta.env.VITE_API_URL

onMounted(async () => {
  try {
    const response = await axios.get(`${apiUrl}/komentar`)
    comments.value = response.data.data
  } catch (error) {
    console.error('Gagal mengambil data:', error)
  }
})
</script>