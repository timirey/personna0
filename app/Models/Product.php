<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;

    public array $translatable = ['name', 'description', 'meta_title', 'meta_description'];

    protected $fillable = [
        'category_id', 'name', 'description', 'meta_title', 'meta_description',
        'slug', 'price', 'stock', 'sizes', 'is_active',
    ];

    protected $casts = [
        'sizes' => 'array',
        'price' => 'decimal:2',
        'stock' => 'integer',
        'is_active' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /** True when stock is tracked and depleted. */
    public function isSoldOut(): bool
    {
        return ! is_null($this->stock) && $this->stock <= 0;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery')->useDisk('public');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        // Portrait 4:5 WebP renditions for the storefront srcset. nonQueued() so
        // they generate on upload without needing a queue worker (single-owner shop).
        $this->addMediaConversion('thumb')->fit(Fit::Crop, 400, 500)->format('webp')->nonQueued();
        $this->addMediaConversion('card')->fit(Fit::Crop, 800, 1000)->format('webp')->nonQueued();
        $this->addMediaConversion('full')->fit(Fit::Contain, 1600, 2000)->format('webp')->nonQueued();
    }
}
