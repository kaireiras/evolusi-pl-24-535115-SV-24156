@extends('layouts.guest')

@section('title', 'Surat Untuk Surga')

@section('styles')
<style>
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

    @media (max-width: 640px) {
        .envelope-grid { grid-template-columns: repeat(3, 1fr); gap: 1.5rem 0.75rem; }
    }
    @media (max-width: 380px) {
        .envelope-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>
@endsection

@section('content')
<div class="envelope-grid" id="grid">
    @forelse($blogs as $blog)
        <div class="env-card" onclick="openLetter({{ $blog->id_blog }})">
            <div class="env-icon">
                <img src="{{ asset('envelope/envelope1.png') }}" alt="Surat">
            </div>
            <div class="env-label">
                {{ $blog->created_at->format('M d, Y') }}<br>
                About
            </div>
        </div>

        <!-- Hidden data for popup -->
        <div class="letter-data" data-id="{{ $blog->id_blog }}" style="display:none;">
            <div class="letter-date">{{ $blog->created_at->format('M d, Y') }}</div>
            <div class="letter-body">{{ $blog->isi_blog }}</div>
        </div>
    @empty
        <div style="grid-column: 1/-1; text-align: center; padding: 2rem; color: #999;">
            <p style="font-size: 0.85rem;">Belum ada surat</p>
        </div>
    @endforelse
</div>
@endsection

@section('popup')
<div class="letter-popup" id="popup" style="display:none">
    <button class="popup-close" onclick="closePopup()">✕</button>
    <div class="popup-date" id="p-date"></div>
    <div class="popup-top-row">
        <div class="popup-env-open">
            <img src="{{ asset('envelope/envelope_open.png') }}" alt="Surat Buka">
        </div>
        <div class="popup-to">
            <div>Kepada</div>
            <div>Yth. Seseorang</div>
            <div>Di</div>
            <div>Surga</div>
        </div>
    </div>
    <div class="popup-body" id="p-body"></div>
</div>

<style>
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
        font-family: 'Times New Roman', serif;
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
        font-family: 'Times New Roman', serif;
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

    @media (max-width: 640px) {
        .letter-popup { left: 50%; transform: translateX(-50%); top: 60px; }
    }
</style>
@endsection

@section('scripts')
<script>
    function openLetter(id) {
        const data = document.querySelector(`.letter-data[data-id="${id}"]`);
        if (!data) return;

        const popup = document.getElementById('popup');
        document.getElementById('p-date').textContent = data.querySelector('.letter-date').textContent;
        document.getElementById('p-body').textContent = data.querySelector('.letter-body').textContent;
        
        popup.style.display = 'block';
        popup.scrollTop = 0;
        document.getElementById('overlay').classList.add('open');
    }

    function closePopup() {
        document.getElementById('popup').style.display = 'none';
        document.getElementById('overlay').classList.remove('open');
    }
</script>
@endsection