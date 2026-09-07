@extends('layouts.guest')

@section('title', 'Detail Surat')

@section('content')
<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card shadow-0 border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="card-title mb-0">✉️ Surat</h2>
                        <small class="text-muted">
                            Dibuat: {{ $blog->created_at->format('d M Y H:i') }}
                            @if($blog->updated_at != $blog->created_at)
                                | Diubah: {{ $blog->updated_at->format('d M Y H:i') }}
                            @endif
                        </small>
                    </div>
                    <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary">← Kembali</a>
                </div>

                <hr>

                <div class="blog-content py-5">
                    <p class="lead text-dark" style="line-height: 1.8; white-space: pre-wrap;">
                        {{ $blog->isi_blog }}
                    </p>
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">Surat #{{ $blog->id_blog }}</small>
                    <div>
                        @auth
                            <a href="{{ route('admin.blog.edit', $blog->id_blog) }}" class="btn btn-sm btn-warning">
                                ✏️ Edit
                            </a>
                            <form action="{{ route('admin.blog.destroy', $blog->id_blog) }}" method="POST" 
                                  onsubmit="return confirm('Yakin ingin menghapus?')" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">🗑️ Hapus</button>
                            </form>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Blogs -->
        <div class="mt-5">
            <h4 class="mb-4">📮 Surat Lainnya</h4>
            <div class="row g-3">
                @php
                    $related = \App\Models\Blog::where('id_blog', '!=', $blog->id_blog)->limit(3)->get();
                @endphp
                @forelse($related as $item)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <p class="card-text small text-muted">{{ Str::limit($item->isi_blog, 80) }}</p>
                                <a href="{{ route('blog.show', $item->id_blog) }}" class="btn btn-sm btn-link">
                                    Baca →
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Tidak ada surat lain.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@section('styles')
<style>
    .blog-content {
        font-size: 1.1rem;
        color: #333;
        background: #f8f9fa;
        padding: 30px;
        border-radius: 10px;
        border-left: 5px solid #667eea;
    }
    
    @media (max-width: 768px) {
        .blog-content {
            padding: 20px;
        }
        
        h2 {
            font-size: 1.5rem;
        }
    }
</style>
@endsection
@endsection