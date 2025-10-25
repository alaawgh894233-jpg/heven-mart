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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeonDelete();
            $table->foreignId('store_id')->nullable()->constrained('stores')->cascadeonDelete();
            $table->enum('type', ['home', 'work', 'store', 'other'])->default('home');
            $table->text('address_ar');
            $table->text('address_en');
            $table->text('address_details')->nullable();
            $table->string('phone')->nullable();
            $table->decimal('lat', 10, 8);
            $table->decimal('lon', 11, 8);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
