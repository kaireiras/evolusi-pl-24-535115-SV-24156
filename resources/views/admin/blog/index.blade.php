@extends('layouts.admin')

@section('sidebar-nav')
<a href="/admin/blog/create" class="nav-item">buat</a>
<a href="/admin/blog" class="nav-item active">list</a>
@endsection

@section('main-title')
list surat
@endsection

@section('styles')
<style>
/* LIST HEADER */
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

/* BUTTON */
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

/* SEARCH */
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

/* TABLE */
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

/* ACTIONS */
.row-actions {
  display: flex;
  gap: 0.75rem;
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
  text-decoration: none;
}

.row-btn:hover {
  color: #000;
}

.row-btn.del:hover {
  color: #c00;
}
</style>
@endsection

@section('content')

<div class="list-header">

  <div class="list-count">
    {{ $totalBlog }} surat
  </div>

  <a
    href="/admin/blog/create"
    class="btn"
    style="font-size:0.72rem;padding:0.3rem 0.9rem"
  >
    + buat baru
  </a>

</div>

<div class="search-row">

  <input
    type="text"
    placeholder="cari surat…"
    oninput="searchBlog(this.value)"
  >

</div>

<table>

  <thead>

    <tr>
      <th style="width:50%">kepada</th>
      <th>tanggal</th>
      <th>status</th>
      <th></th>
    </tr>

  </thead>

  <tbody id="blog-body">

    @foreach ($blogs as $blog)

    <tr>

      <td class="td-title">
        {{ Str::limit($blog->isi_blog, 50) }}
      </td>

      <td class="td-date">
        {{ $blog->created_at->format('d M Y') }}
      </td>

      <td class="td-status pub">
        dipublikasikan
      </td>

      <td>

        <div class="row-actions">

          <a
            href="/admin/blog/{{ $blog->id_blog }}/edit"
            class="row-btn"
          >
            edit
          </a>

          <button
            class="row-btn del"
            onclick="hapus({{ $blog->id_blog }})"
          >
            hapus
          </button>

        </div>

      </td>

    </tr>

    @endforeach

  </tbody>

</table>

@endsection

@section('scripts')
<script>
function hapus(id) {

  if (!confirm('Yakin ingin menghapus?')) {
    return;
  }

  fetch(`/api/blog/${id}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
  })
  .then(res => res.json())
  .then(() => {

    location.reload();

  })
  .catch(() => {

    alert('gagal menghapus');

  });
}

function searchBlog(q) {

  q = q.toLowerCase();

  const rows = document.querySelectorAll('#blog-body tr');

  rows.forEach(row => {

    const text = row.innerText.toLowerCase();

    if (text.includes(q)) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }

  });
}
</script>
@endsection