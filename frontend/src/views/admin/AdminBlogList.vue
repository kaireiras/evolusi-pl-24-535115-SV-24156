<template>
  <AdminLayout title="list surat">
    <div class="list-header">
      <div class="list-count">{{ filteredBlogs.length }} surat</div>
      <router-link to="/admin/blog/create" class="btn btn-sm">+ buat baru</router-link>
    </div>

    <div class="search-row">
      <input type="text" v-model="searchQuery" placeholder="cari surat…" />
    </div>

    <div v-if="loading" class="state-msg">Memuat daftar surat...</div>
    <div v-else-if="error" class="state-msg error">{{ error }}</div>

    <table v-else>
      <thead>
        <tr>
          <th style="width: 55%">kepada</th>
          <th>tanggal</th>
          <th>status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="blog in filteredBlogs" :key="blog.id_blog || blog.id">
          <td class="td-title">{{ truncate(blog.isi_blog, 50) }}</td>
          <td class="td-date">{{ formatDate(blog.created_at) }}</td>
          <td class="td-status pub">dipublikasikan</td>
          <td>
            <div class="row-actions">
              <button class="row-btn del" @click="hapus(blog.id_blog || blog.id)">hapus</button>
            </div>
          </td>
        </tr>
        <tr v-if="filteredBlogs.length === 0">
          <td colspan="4" class="empty-td">Tidak ada surat ditemukan.</td>
        </tr>
      </tbody>
    </table>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../../api'
import AdminLayout from '../../components/AdminLayout.vue'

const blogs = ref([])
const searchQuery = ref('')
const loading = ref(true)
const error = ref('')

const fetchBlogs = async () => {
  loading.value = true
  error.value = ''
  try {
    const res = await api.get('/blog')
    blogs.value = res.data.data || res.data || []
  } catch (err) {
    error.value = 'Gagal memuat surat.'
  } finally {
    loading.value = false
  }
}

const filteredBlogs = computed(() => {
  const q = searchQuery.value.toLowerCase().trim()
  if (!q) return blogs.value
  return blogs.value.filter(b => (b.isi_blog || '').toLowerCase().includes(q))
})

const hapus = async (id) => {
  if (!confirm('Yakin ingin menghapus surat ini?')) return

  try {
    await api.delete(`/admin/blog/${id}`)
    blogs.value = blogs.value.filter(b => (b.id_blog || b.id) !== id)
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menghapus surat.')
  }
}

const truncate = (str, len) => {
  if (!str) return ''
  return str.length > len ? str.substring(0, len) + '…' : str
}

const formatDate = (dateStr) => {
  if (!dateStr) return ''
  const d = new Date(dateStr)
  return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })
}

onMounted(fetchBlogs)
</script>

<style scoped>
.list-header {
  display: flex;
  align-items: baseline;
  justify-content: space-between;
  margin-bottom: 1.5rem;
}

.list-count {
  font-size: 0.72rem;
  color: #888;
}

.btn {
  font-family: 'Times New Roman', serif;
  font-size: 0.78rem;
  background: none;
  border: 1px solid #000;
  padding: 0.4rem 1.25rem;
  cursor: pointer;
  transition: all 0.15s;
  color: #000;
  text-decoration: none;
}

.btn:hover {
  background: #000;
  color: #fff;
}

.btn-sm {
  font-size: 0.72rem;
  padding: 0.3rem 0.9rem;
}

.search-row {
  margin-bottom: 1rem;
}

.search-row input {
  width: 200px;
  border: none;
  border-bottom: 1px solid #ccc;
  padding: 0.25rem 0;
  font-family: 'Times New Roman', serif;
  font-size: 0.78rem;
  outline: none;
  background: none;
}

.search-row input:focus {
  border-bottom-color: #000;
}

table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.75rem;
}

thead th {
  text-align: left;
  padding: 0.5rem 0.75rem;
  font-weight: normal;
  color: #888;
  border-bottom: 1px solid #000;
  font-size: 0.68rem;
  letter-spacing: 0.05em;
  text-transform: lowercase;
}

tbody td {
  padding: 0.6rem 0.75rem;
  border-bottom: 1px solid #eee;
  vertical-align: top;
}

tbody tr:hover td {
  background: #fafafa;
}

.td-title {
  font-size: 0.78rem;
  line-height: 1.4;
}

.td-date {
  font-size: 0.7rem;
  color: #888;
  white-space: nowrap;
}

.td-status {
  font-size: 0.68rem;
  color: #888;
}

.td-status.pub {
  color: #000;
}

.row-actions {
  display: flex;
  justify-content: flex-end;
}

.row-btn {
  font-family: 'Times New Roman', serif;
  font-size: 0.68rem;
  color: #bbb;
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
  transition: color 0.15s;
}

.row-btn.del:hover {
  color: #c00;
}

.empty-td {
  text-align: center;
  color: #999;
  padding: 2rem;
}

.state-msg {
  font-size: 0.8rem;
  color: #666;
  padding: 1rem 0;
}
.state-msg.error {
  color: #c00;
}
</style>