<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Drink extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image_path',
        'is_featured',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_available' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image_path) {
            if (Str::startsWith($this->image_path, ['http://', 'https://'])) {
                return $this->image_path;
            }

            if (Storage::disk('public')->exists($this->image_path)) {
                return Storage::disk('public')->url($this->image_path);
            }

            if (file_exists(public_path($this->image_path))) {
                return asset($this->image_path);
            }
        }

        return asset($this->defaultImagePath());
    }

    protected function defaultImagePath(): string
    {
        return match ($this->slug ? Str::beforeLast($this->slug, '-') : Str::slug($this->name)) {
            'wintermelon-milk-tea' => 'pics/Wintermelon-Milk-Tea.jpg',
            'okinawa-milk-tea' => 'pics/Okinawa-Milk-Tea.jpg',
            'lychee-green-tea' => 'pics/Lychee-Milk_Tea.jpeg',
            'passionfruit-black-tea' => 'pics/Passionfruit-Black-Tea.jpg',
            'brown-sugar-boba' => 'pics/Brown-Sugar-Milk-Tea.jpeg',
            'matcha-cheesecake' => 'pics/Matcha-CheeseCake-Milk-Tea.jpeg',
            default => 'pics/drinks/signature-house-milk-tea.svg',
        };
    }
}
