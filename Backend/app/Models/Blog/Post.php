<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class Post extends BaseModel
{
    use HasFactory;


    protected $fillable = ['title', 'description', 'content'];

    protected static function boot()
    {
        static::creating(function (self $post){
            $post->user_id = auth()->id();
        });
        parent::boot(); // TODO: Change the autogenerated stub
    }

    public function scopePaginateAndSearchAndOrder($q)
    {
        return $q
            ->search(request('q'))
            ->order(request('sort_by'), request('order'))
            ->paginate(30);
    }

    public function scopeSearch($q, $keyword)
    {
        return $q->where('title', 'like', "%{$keyword}%");
    }

    public function scopeOrder($q, $sort_by, $order)
    {
        return $q
            ->when($sort_by, function ($q) use ($sort_by, $order) {
                $q->orderby($sort_by, $order == "descending" ? "desc" : "asc");
            });
    }

}
