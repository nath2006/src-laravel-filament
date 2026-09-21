<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail',
        'status',
        'is_featured',
        'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    //Relasi: Post milik satu user
    public function user(){
        return $this->belongsTo(User::class);
    }

    //Relasi: Post milik satu category
    public function category(){
        return $this->belongsTo(Category::class);
    }

    //Scope untuk query published
    public function scopePublished($query){
        return $query->where('status', 'published');
    }

    //Scope untuk query featured
     public function scopeFeatured($query){
        return $query->where('is_featured', true);
    }
}
