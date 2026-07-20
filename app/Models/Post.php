<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;
use App\Models\User;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'content', 'image','user_id', 'is_featured', ];

    public function user()
    {
        return $this->belongsTo(User::class);//1 to 1 relationship with user
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class);//many to many relationship with category
    }
    public function comments()
{
    return $this->hasMany(Comment::class);
}
public function scopeSearch(Builder $query, string $keyword)
{
    return $query->where( 'title','like', "%{$keyword}%");
}
}
