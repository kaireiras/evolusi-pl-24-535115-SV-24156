@extends('layouts.admin')

@section('page-title', '📊 Dashboard')

@section('content')
<div class="row">
    <div class="col-md-6 col-lg-4">
        <div class="stat-card">
            <h5>📬 Total Surat</h5>
            <div class="number">{{ $totalBlog ?? 0 }}</div>
            <small class="text-muted">Surat yang telah dibuat</small>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-4">
        <div class="stat-card">
            <h5>📅 Bulan Ini</h5>
            <div class="number">
                {{ $blogs->where('created_at', '>=', \Carbon\Carbon::now()->startOfMonth())->count() ?? 0 }}
            </div>
            <small class="text-muted">Surat baru bulan ini</small>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-4">
        <div class="stat-card">
            <h5>⚡ Status</h5>
            <div class="number" style="color: #28a745;">✓</div>
            <small class="text-muted">Sistem berjalan normal</small>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">📮 Surat Terbaru</h5>
            </div>
            <div class="card-body">
                @if($blogs->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Konten</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($blogs->take(5) as $blog)
                                    <tr>
                                        <td><strong>#{{ $blog->id_blog }}</strong></td>
                                        <td>{{ Str::limit($blog->isi_blog, 50) }}</td>
                                        <td>{{ $blog->created_at->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.blog.edit', $blog->id_blog) }}" 
                                               class="btn btn-sm btn-warning">Edit</a>
                                            <a href="{{ route('blog.show', $blog->id_blog) }}" 
                                               class="btn btn-sm btn-info">Lihat</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.blog.index') }}" class="btn btn-primary">
                            Lihat Semua Surat →
                        </a>
                    </div>
                @else
                    <p class="text-muted">Belum ada surat.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection