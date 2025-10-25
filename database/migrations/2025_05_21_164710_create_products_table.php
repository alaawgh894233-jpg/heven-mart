<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('name_ar');
            $table->string('name_en');
            $table->text('description_ar');
            $table->text('description_en');
            $table->integer('stock')->default(0);
            $table->string('unit_en')->nullable();
            $table->string('unit_ar')->nullable();
            $table->decimal('price', 8, 2);
            $table->decimal('rate',3, 2)->default(0);
            $table->integer('num_of_rates')->default(0);
            $table->integer('num_of_purchase')->default(0);
            $table->enum('status', ['active', 'inactive','requested'])->default('active');
            $table->enum('enum', ['enables', 'disabled'])->default('enables');
            $table->boolean('is_featured')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
