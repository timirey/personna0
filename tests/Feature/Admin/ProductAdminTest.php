<?php

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('creates a product with translatable content through the panel', function () {
    $category = Category::factory()->create();

    Livewire::test(CreateProduct::class)
        ->fillForm([
            'name' => 'Panel Tee',
            'slug' => 'panel-tee',
            'category_id' => $category->id,
            'price' => 199,
            'sizes' => ['S', 'M'],
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $product = Product::where('slug', 'panel-tee')->first();

    expect($product)->not->toBeNull()
        // Create page edits the first supported locale (ro) by default.
        ->and($product->getTranslation('name', 'ro'))->toBe('Panel Tee')
        ->and($product->category_id)->toBe($category->id);
});
