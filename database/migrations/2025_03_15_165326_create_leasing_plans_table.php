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
        Schema::create('leasing_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('maximum_trainings');
            $table->date('maximum_date');
            $table->date('next_check_at');
            $table->date('actual_period_start_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leasing_plans');
    }
};
