<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('sale_price', 10, 2)->nullable()->after('price');
        });

        Schema::table('order_items', function (Blueprint $table) {
            // Original unit price snapshot when the line was bought on discount.
            $table->decimal('original_unit_price', 10, 2)->nullable()->after('unit_price');
        });
    }

    public function down(): void
    {
        Schema::table('products', fn (Blueprint $table) => $table->dropColumn('sale_price'));
        Schema::table('order_items', fn (Blueprint $table) => $table->dropColumn('original_unit_price'));
    }
};
