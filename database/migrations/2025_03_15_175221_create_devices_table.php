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
        Schema::create('devices', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['free','leasing','unset','restricted'])->default('unset');
            $table->string('activation_code')->nullable();
            $table->integer('leasing_plan_id')->nullable(); //foreign key
            $table->integer('owner_id')->nullable(); //foreign key
            $table->date('registration_date')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
