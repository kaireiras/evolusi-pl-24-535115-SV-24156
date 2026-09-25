<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['blog_id', 'isi_balasan', 'pengirim'];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}