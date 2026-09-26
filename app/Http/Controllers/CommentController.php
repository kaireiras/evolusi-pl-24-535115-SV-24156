<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('comments')->latest()->paginate(10);

        return view('blog.index', compact('blogs'));
    }

    public function store(Request $request, string $blogId)
    {
        $validated = $request->validate([
            'isi_balasan' => 'required|string|min:3',
            'pengirim' => 'nullable|string',
        ]);

        $blog = Blog::findOrFail($blogId);

        $blog->comments()->create([
            'isi_balasan' => $validated['isi_balasan'],
            'pengirim' => $validated['pengirim'] ?? 'Anonim',
        ]);

        return redirect()->back()->with('success', 'Komentar/Doa berhasil dikirim.');
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'isi_balasan' => 'required|string|min:3',
        ]);

        $comment = Comment::findOrFail($id);
        $comment->update($validated);

        return redirect()->back()->with('success', 'Komentar berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return redirect()->back()->with('success', 'Komentar berhasil dihapus.');
    }
}
