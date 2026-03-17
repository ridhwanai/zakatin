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
        Schema::create('zakat_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedInteger('people_count');
            $table->enum('method', ['rice', 'money']);
            $table->decimal('rice_kg', 10, 2)->nullable();
            $table->decimal('fitrah_money', 15, 2)->nullable();
            $table->decimal('wajib_money', 15, 2)->default(0);
            $table->decimal('infaq_money', 15, 2)->default(0);
            $table->decimal('mal_money', 15, 2)->default(0);
            $table->timestamps();

            $table->index('project_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zakat_records');
    }
};
