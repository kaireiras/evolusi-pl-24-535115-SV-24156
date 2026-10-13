<template>
  <AdminLayout title="buat surat">
    <div class="field">
      <label>isi surat</label>
      <textarea
        v-model="body"
        placeholder="Tuliskan suratmu di sini…"
      ></textarea>
      <div class="char-info">{{ body.length }} karakter (minimal 10)</div>
    </div>

    <div class="form-actions">
      <button type="button" class="btn" :disabled="submitting" @click="simpan">
        {{ submitting ? 'menyimpan...' : 'simpan' }}
      </button>
      <button type="button" class="btn btn-ghost" @click="resetForm">
        bersihkan
      </button>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../api'
import AdminLayout from '../../components/AdminLayout.vue'

const router = useRouter()

const body = ref('')
const submitting = ref(false)

const simpan = async () => {
  const text = body.value.trim()

  if (!text) {
    alert('isi surat tidak boleh kosong')
    return
  }

  if (text.length < 10) {
    alert('isi surat minimal 10 karakter')
    return
  }

  submitting.value = true
  try {
    await api.post('/admin/blog', { isi_blog: text })
    router.push('/admin/blog')
  } catch (err) {
    const msg = err.response?.data?.errors?.isi_blog?.[0] || err.response?.data?.message || 'Gagal menyimpan surat'
    alert(msg)
  } finally {
    submitting.value = false
  }
}

const resetForm = () => {
  body.value = ''
}
</script>

<style scoped>
.field {
  margin-bottom: 1.25rem;
}

.field label {
  display: block;
  font-size: 0.72rem;
  color: #888;
  margin-bottom: 0.3rem;
  letter-spacing: 0.03em;
}

.field textarea {
  width: 100%;
  border: none;
  border-bottom: 1px solid #ccc;
  padding: 0.3rem 0;
  font-family: 'Times New Roman', serif;
  font-size: 0.82rem;
  color: #000;
  background: none;
  outline: none;
  transition: border-color 0.15s;
  resize: vertical;
  min-height: 220px;
  line-height: 1.65;
  box-sizing: border-box;
}

.field textarea:focus {
  border-bottom-color: #000;
}

.char-info {
  font-size: 0.68rem;
  color: #999;
  margin-top: 0.4rem;
}

.form-actions {
  display: flex;
  gap: 1.5rem;
  margin-top: 2rem;
  padding-top: 1.25rem;
  border-top: 1px solid #e0e0e0;
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
}

.btn:hover {
  background: #000;
  color: #fff;
}

.btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.btn-ghost {
  border-color: #ccc;
  color: #888;
}

.btn-ghost:hover {
  background: none;
  color: #000;
  border-color: #888;
}
</style>