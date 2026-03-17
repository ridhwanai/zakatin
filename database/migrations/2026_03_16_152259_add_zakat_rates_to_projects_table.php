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
        Schema::table('projects', function (Blueprint $table) {
            $table->decimal('rice_rate_per_person', 10, 2)->nullable()->after('status');
            $table->decimal('money_rate_per_person', 15, 2)->nullable()->after('rice_rate_per_person');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'rice_rate_per_person',
                'money_rate_per_person',
            ]);
        });
    }
};
