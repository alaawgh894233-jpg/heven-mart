<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {



    public function up(): void
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('status_en')->unique();
            $table->string('status_ar');
            $table->timestamps();
        });

        DB::table('statuses')->insert([
            ['status_en' => 'pending', 'status_ar' => 'قيد الانتظار'],
            ['status_en' => 'approved', 'status_ar' => 'مقبول'],
            ['status_en' => 'cancelled', 'status_ar' => 'ملغى'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('statuses');
    }
};
