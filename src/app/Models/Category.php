<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
     use HasFactory;

    protected $fillable = ['name', 'slug', 'parent_id', 'path'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    protected static function booted()
    {
        static::saving(function (Category $cat) {
            $cat->slug = $cat->slug ?: Str::slug($cat->name);
            $cat->path = $cat->parent ? rtrim($cat->parent->path, '/') . '/' . $cat->slug : '/' . $cat->slug;
        });
    }

    /** IDs of self + descendants (سريعة لو عدد التصنيفات معقول، وإلا اعمل caching) */
    public function descendantIds(): array
    {
        $all = Category::select('id', 'path')->get();
        $prefix = rtrim($this->path, '/') . '/';
        return $all->filter(fn($c) => $c->id === $this->id || str_starts_with($c->path, $prefix))
            ->pluck('id')->all();
    }
}
