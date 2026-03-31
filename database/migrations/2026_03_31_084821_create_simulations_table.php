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
        Schema::create('simulations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('tariff_id')->nullable()->constrained()->onDelete('set null');
            $table->string('location_name')->nullable();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('roof_length', 8, 2);
            $table->decimal('roof_width', 8, 2);
            $table->decimal('average_monthly_bill', 14, 2);
            $table->decimal('estimated_budget', 14, 2);
            $table->decimal('ghi_value', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simulations');
    }
};
