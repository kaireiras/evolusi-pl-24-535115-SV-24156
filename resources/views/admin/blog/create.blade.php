@extends('layouts.admin')

@section('sidebar-nav')
<a href="/admin/blog/create" class="nav-item active">buat</a>
<a href="/admin/blog" class="nav-item">list</a>
@endsection

@section('main-title')
buat surat
@endsection

@section('styles')
<style>
/* FORM */
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
}

.field textarea:focus {
  border-bottom-color: #000;
}

/* BUTTON */
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
}

.btn:hover {
  background: #000;
  color: #fff;
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
@endsection

@section('content')

<div class="field">

  <label>isi surat</label>

  <textarea
    id="f-body"
    placeholder="Tuliskan suratmu di sini…"
  ></textarea>

</div>

<div class="form-actions">

  <button
    type="button"
    class="btn"
    onclick="simpan()"
  >
    simpan
  </button>

  <button
    type="button"
    class="btn btn-ghost"
    onclick="resetForm()"
  >
    bersihkan
  </button>

</div>

@endsection

@section('scripts')
<script>
async function simpan() {

  const body = document
    .getElementById('f-body')
    .value
    .trim();

  // VALIDASI
  if (!body) {
    alert('isi surat tidak boleh kosong');
    return;
  }

  if (body.length < 10) {
    alert('isi surat minimal 10 karakter');
    return;
  }

  try {

    const response = await fetch('/admin/blog', {

      method: 'POST',

      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },

      body: JSON.stringify({
        isi_blog: body
      })

    });

    const result = await response.json();

    // ERROR RESPONSE
    if (!response.ok) {

      console.log(result);

      if (result.errors?.isi_blog) {

        alert(result.errors.isi_blog[0]);

      } else {

        alert(result.message || 'gagal menyimpan');

      }

      return;
    }

    // SUCCESS
    window.location.href = '/admin/blog';

  } catch (err) {

    console.error(err);

    alert('terjadi kesalahan server');

  }
}

function resetForm() {

  document.getElementById('f-body').value = '';

}
</script>
@endsection