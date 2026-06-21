<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('price', 15, 2);
            $table->integer('stock')->default(0);
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_promo')->default(false);
            $table->decimal('promo_price', 15, 2)->nullable();
            $table->string('barcode')->nullable()->unique();
            $table->boolean('is_best_seller')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
