<?php

namespace App\Models;

use App\Traits\ApiTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, ApiTrait;

    protected $fillable = ['name', 'slug'];

    protected $allowIncluded = [
        'posts', 'posts.user'
    ];

    protected $allowFilter = [
        'id', 'name', 'slug'
    ];

    protected $allowSort = [
        'id', 'name', 'slug'
    ];


    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
