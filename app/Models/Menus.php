<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    //
    use HasFactory;
    protected $fillable=[
        'title',
        'url',
        'parent_id',
        'order',
        'icon',
        'is_active'
    ];

    // Self-referencing for nested menus
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }

      public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

}
