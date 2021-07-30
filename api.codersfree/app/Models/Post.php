<?php

namespace App\Models;

use App\Traits\ApiTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory, ApiTrait;

    const ERASER = 1;
    const PUBLISHED = 2;

    protected $fillable = ['name', 'slug', 'extract', 'body', 'status', 'category_id', 'user_id'];

    // Muchos a uno inversa
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Muchos a uno inversa
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Muchos a muchos
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    // Relación uno a muchos polimórfica
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
