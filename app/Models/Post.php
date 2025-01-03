<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class Post extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = Str::slug($model->title);
        });

        static::updating(function ($model) {
            $model->slug = Str::slug($model->title);
            if ($model->isDirty('thumbnail')) {
                Storage::disk('public')->delete($model->getOriginal('thumbnail'));
            }
        });

        static::deleting(function ($model) {
            // Menghapus file thumbnail dari storage jika ada
            if ($model->thumbnail) {
                Storage::disk('public')->delete($model->thumbnail);
            }
        });
    }

    protected $fillable = ['title', 'reading_time', 'thumbnail', 'content', 'slug', 'category_id', 'is_published', 'tanggal'];



    // Definisikan aksesornya
    public function getTanggalAttribute($value)
    {
        return Carbon::parse($value)->format('d M Y');
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getImageUrlAttribute()
    {
        return Storage::url($this->image);
    }
}
