<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('image_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_value_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('url_image');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image_products');
    }
};
