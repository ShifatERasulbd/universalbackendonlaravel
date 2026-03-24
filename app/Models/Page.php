<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'featured_image',
        'status',
        'published_at',
        'scheduled_at',
        'is_active',
        'order',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'draft' => 'warning',
            'published' => 'success',
            'scheduled' => 'info',
            default => 'secondary',
        };
    }

    public function getStatusLabelAttribute()
    {
        return ucfirst($this->status);
    }
}
