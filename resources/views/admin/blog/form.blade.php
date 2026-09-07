@extends('layouts.admin')

@section('page-title', isset($blog) ? '✏️ Edit Surat' : '✍️ Tulis Surat Baru')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body p-5">
                <form action="{{ isset($blog) ? route('admin.blog.update', $blog->id_blog) : route('admin.blog.store') }}" 
                      method="POST">
                    @csrf
                    @if(isset($blog))
                        @method('PUT')
                    @endif

                    <div class="mb-4">
                        <label for="isi_blog" class="form-label">
                            <strong>📝 Konten Surat</strong>
                        </label>
                        <textarea class="form-control @error('isi_blog') is-invalid @enderror" 
                                  id="isi_blog" 
                                  name="isi_blog" 
                                  rows="12" 
                                  placeholder="Tulis pesan Anda di sini..."
                                  required>{{ old('isi_blog', $blog->isi_blog ?? '') }}</textarea>
                        @error('isi_blog')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted d-block mt-2">
                            Minimalkan 10 karakter. <span id="charCount">0</span>/∞ karakter
                        </small>
                    </div>

                    <div class="d-flex gap-2 justify-content-between">
                        <div>
                            <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary">
                                ← Kembali
                            </a>
                        </div>
                        <div>
                            @if(isset($blog))
                                <a href="{{ route('blog.show', $blog->id_blog) }}" class="btn btn-info">
                                    👁️ Lihat Surat
                                </a>
                            @endif
                            <button type="submit" class="btn btn-primary">
                                {{ isset($blog) ? '💾 Simpan Perubahan' : '📤 Kirim Surat' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info -->
        <div class="alert alert-info mt-4" role="alert">
            <strong>💡 Tips:</strong>
            <ul class="mb-0 mt-2">
                <li>Tulis dengan hati dan penuh makna</li>
                <li>Surat akan tersimpan dan dapat diedit kapan saja</li>
                <li>Gunakan paragraph untuk memperjelas pesan Anda</li>
            </ul>
        </div>

        @if(isset($blog))
            <div class="alert alert-secondary mt-3">
                <strong>ℹ️ Informasi Surat</strong>
                <ul class="mb-0 mt-2 small">
                    <li>ID: {{ $blog->id_blog }}</li>
                    <li>Dibuat: {{ $blog->created_at->format('d M Y H:i') }}</li>
                    <li>Diubah: {{ $blog->updated_at->format('d M Y H:i') }}</li>
                </ul>
            </div>
        @endif
    </div>
</div>

@section('scripts')
<script>
    const textarea = document.getElementById('isi_blog');
    const charCount = document.getElementById('charCount');

    if (textarea) {
        textarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
        charCount.textContent = textarea.value.length;
    }
</script>
@endsection
@endsection