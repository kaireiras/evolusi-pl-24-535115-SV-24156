<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blog';

    protected $primaryKey = 'id_blog';

    protected $fillable = [
        'isi_blog',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class, 'blog_id', 'id_blog');
        // Catatan: Jika primary key tabel blog kamu adalah 'id', ubah 'id_blog' di atas menjadi 'id':
        // return $this->hasMany(Comment::class, 'blog_id', 'id');
    }
}
