<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource - Guest View (Blade)
     */
    public function index()
    {
        $blogs = Blog::latest()->paginate(10);

        return view('blog.index', compact('blogs'));
    }

    /**
     * Display a listing of the resource for API (User & Admin)
     */
    public function apiIndex()
    {
        $blogs = Blog::with('comments')->latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => $blogs,
        ]);
    }

    /**
     * Show the form for creating a new resource - Admin View (Blade)
     */
    public function create()
    {
        return view('admin.blog.create');
    }

    /**
     * Store a newly created resource in storage (API - Admin Only)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'isi_blog' => 'required|string|min:10',
        ]);

        try {
            $blog = Blog::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Blog created successfully',
                'blog' => $blog,
            ], 201);
        } catch (Exception $e) {
            Log::error('error creating blog: '.$e->getMessage());

            return response()->json(['message' => 'Failed to create blog'], 500);
        }
    }

    /**
     * Display the specified resource - Guest View (Blade)
     */
    public function show(string $id)
    {
        try {
            $blog = Blog::findOrFail($id);

            return view('blog.show', compact('blog'));
        } catch (ModelNotFoundException) {
            return redirect()->route('blog.index')->with('error', 'Blog not found');
        }
    }

    /**
     * Show the form for editing the resource - Admin View (Blade)
     */
    public function edit(string $id)
    {
        try {
            $blog = Blog::findOrFail($id);

            return view('admin.blog.edit', compact('blog'));
        } catch (ModelNotFoundException) {
            return redirect()->route('admin.blog.index')->with('error', 'Blog not found');
        }
    }

    /**
     * Update the specified resource in storage (API - Admin Only)
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'isi_blog' => 'required|string|min:10',
        ]);

        try {
            $blog = Blog::findOrFail($id);
            $blog->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Blog updated successfully',
                'blog' => $blog,
            ]);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Blog not found'], 404);
        } catch (Exception $e) {
            Log::error('error updating blog: '.$e->getMessage());

            return response()->json(['message' => 'Failed to update blog'], 500);
        }
    }

    /**
     * Remove the specified resource from storage (API - Admin Only)
     */
    public function destroy(string $id)
    {
        try {
            $blog = Blog::findOrFail($id);
            $blog->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Blog deleted successfully',
            ]);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Blog not found'], 404);
        } catch (Exception $e) {
            Log::error('error deleting blog: '.$e->getMessage());

            return response()->json(['message' => 'Failed to delete blog'], 500);
        }
    }

    /**
     * Admin dashboard - show all blogs (Blade)
     */
    public function adminIndex()
    {
        $blogs = Blog::latest()->paginate(15);
        $totalBlog = Blog::count();

        return view('admin.blog.index', compact('blogs', 'totalBlog'));
    }
}