<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->json('name');                       // translatable
            $table->json('description')->nullable();    // translatable
            $table->json('meta_title')->nullable();     // translatable (SEO)
            $table->json('meta_description')->nullable();
            $table->string('slug')->unique();
            $table->decimal('price', 10, 2)->default(0);
            $table->unsignedInteger('stock')->nullable(); // null = unlimited
            $table->json('sizes')->nullable();            // ["XS","S",...] empty = no size
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
