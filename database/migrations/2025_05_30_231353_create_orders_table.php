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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeonDelete();
            $table->foreignId('store_id')->constrained('stores')->cascadeonDelete();
            $table->foreignId('address_id')->constrained('addresses')->cascadeonDelete();
            $table->string('code');
            $table->string('payment_method');
            $table->date('date');
            $table->decimal('total_price', 10, 2);
            $table->integer('count_items');
            $table->decimal('delivery_charge', 10, 2)->nullable();
            $table->decimal('discount_coupon', 10, 2)->nullable();
            $table->boolean('processed')->default(false);
            $table->timestamp('processed_at')->nullable();

//            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
