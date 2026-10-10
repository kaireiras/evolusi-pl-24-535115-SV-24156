<template>
  <div class="page">
    <h1>surat untuk surga</h1>

    <div v-if="loading" class="state-message">
      <p>Memuat surat...</p>
    </div>

    <div v-else-if="error" class="state-message error">
      <p>{{ error }}</p>
    </div>

    <!-- Grid Amplop -->
    <div v-else class="envelope-grid" id="grid">
      <template v-if="letters.length > 0">
        <div
          v-for="blog in letters"
          :key="blog.id_blog || blog.id"
          class="env-card"
          @click="openLetter(blog)"
        >
          <div class="env-icon">
            <img src="/envelope/envelope1.png" alt="Surat" />
          </div>
          <div class="env-label">
            {{ formatDate(blog.created_at) }}<br />
          </div>
        </div>
      </template>
      <div v-else class="empty-state">
        <p>Belum ada surat</p>
      </div>
    </div>

    <!-- Overlay -->
    <div
      class="overlay"
      :class="{ open: isPopupOpen }"
      @click="closePopup"
    ></div>

    <!-- Popup Surat -->
    <div v-if="isPopupOpen && selectedLetter" class="letter-popup">
      <button class="popup-close" @click="closePopup" aria-label="Tutup">✕</button>
      <div class="popup-date">{{ formatDate(selectedLetter.created_at) }}</div>
      <div class="popup-top-row">
        <div class="popup-env-open">
          <img src="/envelope/envelope_open.png" alt="Surat Buka" />
        </div>
        <div class="popup-to">
          <div>Kepada</div>
          <div>Yth. Seseorang</div>
          <div>Di</div>
          <div>Surga</div>
        </div>
      </div>

      <!-- Isi Utama Surat -->
      <div class="popup-body">{{ selectedLetter.isi_blog }}</div>

      <!-- Garis Pembatas -->
      <hr class="popup-divider" />

      <!-- Section Komentar & Balasan -->
      <div class="comments-section">
        <h4 class="comments-title">Lilin Doa & Balasan</h4>

        <!-- List Komentar -->
        <div class="comments-list">
          <template v-if="selectedLetter.comments && selectedLetter.comments.length > 0">
            <div
              v-for="comment in selectedLetter.comments"
              :key="comment.id"
              class="comment-item"
            >
              <div class="comment-header">
                <span>💬 {{ comment.pengirim || 'Anonim' }}</span>
                <span>{{ formatDate(comment.created_at) }}</span>
              </div>
              <div class="comment-text">{{ comment.isi_balasan }}</div>
            </div>
          </template>
          <div v-else class="no-comments">
            Belum ada doa atau balasan untuk surat ini. Sebar kebaikan pertama!
          </div>
        </div>

        <!-- Form Tambah Komentar -->
        <form @submit.prevent="submitComment" class="comment-form">
          <div class="form-group">
            <input
              v-model="commentForm.pengirim"
              type="text"
              placeholder="Nama kamu / Anonim (opsional)"
              class="comment-input"
            />
          </div>
          <div class="form-group">
            <textarea
              v-model="commentForm.isi_balasan"
              rows="3"
              placeholder="Tulis doa atau pesan penyemangat..."
              class="comment-textarea"
              required
            ></textarea>
          </div>
          <button type="submit" class="comment-submit-btn" :disabled="submitting">
            {{ submitting ? 'Mengirim...' : 'Kirim Doa' }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import axios from 'axios'

const apiUrl = import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000/api'

const letters = ref([])
const loading = ref(true)
const error = ref(null)

const isPopupOpen = ref(false)
const selectedLetter = ref(null)
const submitting = ref(false)

const commentForm = ref({
  pengirim: '',
  isi_balasan: ''
})

const formatDate = (dateStr) => {
  if (!dateStr) return ''
  const date = new Date(dateStr)
  return date.toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  })
}

const fetchLetters = async () => {
  loading.value = true
  error.value = null
  try {
    const res = await axios.get(`${apiUrl}/blog`)
    letters.value = res.data.data || res.data || []
  } catch (err) {
    console.error('Gagal mengambil data surat:', err)
    error.value = 'Gagal memuat surat. Pastikan backend aktif.'
  } finally {
    loading.value = false
  }
}

const openLetter = (blog) => {
  selectedLetter.value = blog
  if (!selectedLetter.value.comments) {
    selectedLetter.value.comments = []
  }
  commentForm.value.pengirim = ''
  commentForm.value.isi_balasan = ''
  isPopupOpen.value = true
}

const closePopup = () => {
  isPopupOpen.value = false
  selectedLetter.value = null
}

const handleKeyDown = (e) => {
  if (e.key === 'Escape' && isPopupOpen.value) {
    closePopup()
  }
}

const submitComment = async () => {
  if (!commentForm.value.isi_balasan.trim()) return

  const letterId = selectedLetter.value.id_blog || selectedLetter.value.id
  submitting.value = true

  try {
    const payload = {
      isi_balasan: commentForm.value.isi_balasan.trim(),
      pengirim: commentForm.value.pengirim.trim() || 'Anonim'
    }

    const res = await axios.post(`${apiUrl}/blog/${letterId}/comment`, payload)
    const newComment = res.data?.data || {
      id: Date.now(),
      blog_id: letterId,
      ...payload,
      created_at: new Date().toISOString()
    }

    selectedLetter.value.comments.push(newComment)
    commentForm.value.pengirim = ''
    commentForm.value.isi_balasan = ''
  } catch (err) {
    console.error('Gagal mengirim komentar:', err)
    alert('Gagal mengirim doa/balasan. Coba lagi.')
  } finally {
    submitting.value = false
  }
}

onMounted(() => {
  fetchLetters()
  window.addEventListener('keydown', handleKeyDown)
})

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeyDown)
})
</script>

<style scoped>
.page {
  max-width: 860px;
  margin: 0 auto;
  padding: 2.5rem 2.5rem 4rem;
  font-family: 'Times New Roman', Times, serif;
  color: #000;
}

h1 {
  font-size: 0.95rem;
  font-weight: normal;
  margin-bottom: 2.5rem;
  letter-spacing: 0;
}

.state-message {
  text-align: center;
  padding: 2rem;
  color: #666;
  font-size: 0.85rem;
}

.state-message.error {
  color: #a00;
}

.empty-state {
  grid-column: 1 / -1;
  text-align: center;
  padding: 2rem;
  color: #999;
  font-size: 0.85rem;
}

/* ENVELOPE GRID */
.envelope-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 2rem 1rem;
}

.env-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.45rem;
  cursor: pointer;
  transition: transform 0.2s ease;
}

.env-card:hover {
  transform: scale(1.05);
}

.env-icon {
  width: 72px;
  height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.env-icon img {
  width: 100%;
  height: 100%;
  object-fit: contain;
}

.env-label {
  font-size: 0.68rem;
  text-align: center;
  line-height: 1.4;
  color: #000;
}

/* OVERLAY */
.overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.3);
  z-index: 100;
}

.overlay.open {
  display: block;
}

/* POPUP */
.letter-popup {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: min(700px, 92vw);
  max-height: 85vh;
  overflow-y: auto;
  background: #fff;
  border: 1px solid #bbb;
  padding: 1.75rem 2rem 2rem;
  z-index: 101;
  font-family: 'Times New Roman', Times, serif;
  font-size: 0.85rem;
  line-height: 1.7;
}

.popup-close {
  float: right;
  background: none;
  border: none;
  font-size: 0.9rem;
  cursor: pointer;
  color: #000;
  font-family: 'Times New Roman', Times, serif;
  margin-top: -2px;
}

.popup-date {
  font-size: 0.75rem;
  margin-bottom: 0.75rem;
}

.popup-top-row {
  display: flex;
  gap: 0.85rem;
  align-items: flex-start;
  margin-bottom: 0.85rem;
}

.popup-env-open {
  width: 52px;
  height: 36px;
  flex-shrink: 0;
  margin-top: 2px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.popup-env-open img {
  width: 100%;
  height: 100%;
  object-fit: contain;
}

.popup-to {
  font-size: 0.73rem;
  line-height: 1.5;
}

.popup-body {
  font-size: 0.72rem;
  line-height: 1.65;
  text-align: justify;
  color: #111;
  white-space: pre-wrap;
  word-wrap: break-word;
}

/* SECTION KOMENTAR */
.popup-divider {
  margin: 2rem 0 1.5rem;
  border: 0;
  border-top: 1px dashed #ccc;
}

.comments-title {
  font-size: 0.8rem;
  font-weight: bold;
  margin-bottom: 1rem;
  color: #333;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.comments-list {
  display: flex;
  flex-direction: column;
  gap: 0.85rem;
  margin-bottom: 1.5rem;
}

.comment-item {
  background: #fdfdfd;
  border: 1px solid #eaeaea;
  padding: 0.65rem 0.85rem;
  border-radius: 4px;
}

.comment-header {
  display: flex;
  justify-content: space-between;
  font-size: 0.68rem;
  color: #666;
  margin-bottom: 0.3rem;
  font-weight: bold;
}

.comment-text {
  font-size: 0.72rem;
  color: #222;
  white-space: pre-wrap;
}

.no-comments {
  font-size: 0.7rem;
  color: #888;
  font-style: italic;
}

/* FORM */
.comment-form {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  margin-top: 1rem;
}

.comment-input,
.comment-textarea {
  width: 100%;
  padding: 0.5rem;
  font-family: inherit;
  font-size: 0.72rem;
  border: 1px solid #ccc;
  border-radius: 3px;
  box-sizing: border-box;
}

.comment-input:focus,
.comment-textarea:focus {
  outline: none;
  border-color: #666;
}

.comment-submit-btn {
  align-self: flex-end;
  background: #333;
  color: #fff;
  border: none;
  padding: 0.4rem 0.85rem;
  font-size: 0.7rem;
  font-family: inherit;
  cursor: pointer;
  border-radius: 3px;
  transition: background 0.2s;
}

.comment-submit-btn:hover {
  background: #000;
}

.comment-submit-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* RESPONSIVE */
@media (max-width: 640px) {
  .page {
    padding: 1.5rem 1.25rem 3rem;
  }

  .envelope-grid {
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem 0.75rem;
  }

  .letter-popup {
    left: 50%;
    transform: translateX(-50%);
    top: 60px;
  }
}

@media (max-width: 380px) {
  .envelope-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>
